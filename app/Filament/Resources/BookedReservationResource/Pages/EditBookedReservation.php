<?php

namespace App\Filament\Resources\BookedReservationResource\Pages;

use App\Filament\Resources\BookedReservationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Traits\HasCloseAndRedirect;
use Illuminate\Support\Facades\Log;

class EditBookedReservation extends EditRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = BookedReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->before(function ($record) {
                    // Delete associated invoice items first
                    if ($record->invoice) {
                        // Delete all tickets associated with invoice items
                        foreach ($record->invoice->items as $item) {
                            $item->tickets()->delete();
                        }
                        // Delete invoice items
                        $record->invoice->items()->delete();
                        // Delete the invoice
                        $record->invoice->delete();
                    }
                    // Delete reservation items
                    $record->items()->delete();
                }),
        ];
    }

    protected function beforeSave(): void
    {
        // Get the current data
        $data = $this->data;
        $record = $this->record;

        // Check if status is changing from pending to confirmed/completed
        $oldStatus = $record->status; // Current status in database
        $newStatus = $data['status']; // New status from form

        if ($oldStatus === 'pending' && in_array($newStatus, ['confirmed', 'completed'])) {
            // Update the form data
            $this->data['payment_method'] = 'cash';
            $this->data['booked_from'] = 'point_of_sale';

            // Update the BookedReservation record
            $record->update([
                'payment_method' => 'cash',
                'booked_from' => 'point_of_sale'
            ]);

            if ($record->invoice) {
                // Generate new invoice number
                $newInvoiceNumber = generateUniqueInvoiceNumber('point_of_sale');

                // Update invoice with new number and payment method
                $record->invoice->update([
                    'invoice_number' => $newInvoiceNumber,
                    'booked_from' => 'point_of_sale',
                    'payment_method' => 'cash',
                    // Update other invoice fields
                    'point_of_sale_id' => $data['point_of_sale_id'],
                    'reservation_date' => $data['reservation_date'],
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'subtotal' => $data['subtotal'],
                    'vat_amount' => $data['vat_amount'],
                    'other_taxes_amount' => $data['other_taxes_amount'] ?? 0,
                    'total_price' => $data['total_price'],
                    'discount_amount' => $data['discount_amount'] ?? 0,
                    'discount_code' => $data['discount_code'] ?? null,
                    'discount_id' => $data['discount_id'] ?? null,
                    'other_total_discount_amount' => $data['other_total_discount_amount'] ?? 0,
                    'other_total_discount_value' => $data['other_total_discount_value'] ?? 0,
                    'other_total_discount_type' => $data['other_total_discount_type'] ?? 'fixed',
                    'status' => $data['status'],
                    'notes' => $data['notes'] ?? null,
                    'total_paid_cash' => $data['total_paid_cash'] ?? 0,
                    'total_paid_online' => $data['total_paid_online'] ?? 0,
                    'customer_id' => $data['customer_id'],
                    'customer_detail' => $data['customer_detail'],
                    'total_amount_paid' => $data['total_amount_paid'] ?? 0,
                ]);

                // Update all invoice items and their tickets
                foreach ($record->invoice->items as $item) {
                    $newItemNumber = generateUniqueInvoiceItemNumber($newInvoiceNumber);

                    // Update invoice item number
                    $item->update([
                        'invoice_number' => $newItemNumber
                    ]);

                    // Get the number of tickets that should exist for this item
                    $requiredTickets = $item->quantity;
                    $existingTickets = $item->tickets()->count();

                    if ($existingTickets > 0) {
                        // Update existing tickets
                        $counter = 1;
                        foreach ($item->tickets as $ticket) {
                            $ticket->update([
                                'code' => $newItemNumber . '' . $counter++
                            ]);
                        }
                    }

                    // Create additional tickets if needed
                    if ($existingTickets < $requiredTickets) {
                        $ticketsToCreate = $requiredTickets - $existingTickets;
                        $counter = $existingTickets + 1;

                        for ($i = 0; $i < $ticketsToCreate; $i++) {
                            generateTicketsForInvoiceItem($newItemNumber . '' . $counter++, $data['point_of_sale_id'], $item->id);
                        }
                    }
                }
            }
        } else if ($record->invoice) {
            // If not changing from pending to confirmed/completed, just update invoice with form data
            $record->invoice->update([
                'point_of_sale_id' => $data['point_of_sale_id'],
                'reservation_date' => $data['reservation_date'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'subtotal' => $data['subtotal'],
                'vat_amount' => $data['vat_amount'],
                'other_taxes_amount' => $data['other_taxes_amount'] ?? 0,
                'total_price' => $data['total_price'],
                'discount_amount' => $data['discount_amount'] ?? 0,
                'discount_code' => $data['discount_code'] ?? null,
                'discount_id' => $data['discount_id'] ?? null,
                'other_total_discount_amount' => $data['other_total_discount_amount'] ?? 0,
                'other_total_discount_value' => $data['other_total_discount_value'] ?? 0,
                'other_total_discount_type' => $data['other_total_discount_type'] ?? 'fixed',
                'status' => $data['status'],
                'notes' => $data['notes'] ?? null,
                'total_paid_cash' => $data['total_paid_cash'] ?? 0,
                'total_paid_online' => $data['total_paid_online'] ?? 0,
                'customer_id' => $data['customer_id'],
                'customer_detail' => $data['customer_detail'],
                'payment_method' => $data['payment_method'],
                'total_amount_paid' => $data['total_amount_paid'] ?? 0,
            ]);
        }
    }

    protected function afterSave(): void
    {
        // Remove the status change check from here since it's now in beforeSave
    }
}
