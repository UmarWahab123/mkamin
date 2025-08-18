<?php

namespace App\Filament\Resources\BookedReservationResource\Pages;

use App\Filament\Resources\BookedReservationResource;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Traits\HasCloseAndRedirect;

class CreateBookedReservation extends CreateRecord
{
    use HasCloseAndRedirect;

    protected static string $resource = BookedReservationResource::class;

    protected function beforeCreate(): void
    {
        // Your beforeSave logic here
        $data = $this->data;

        // If this is a new record, prepare to create an invoice
        $this->data['invoice'] = [
            'invoice_number' => generateUniqueInvoiceNumber($data['booked_from'] ?? 'point_of_sale'),
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
            'booked_from' => $data['booked_from'] ?? 'point_of_sale',
        ];
    }

    protected function afterCreate(): void
    {
        // Create the invoice after the reservation is created
        $record = $this->record;
        if (isset($this->data['invoice'])) {
            $record->invoice()->create($this->data['invoice']);
        }
    }
}
