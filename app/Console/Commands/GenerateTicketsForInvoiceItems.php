<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InvoiceItem;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;

class GenerateTicketsForInvoiceItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:generate-for-invoice-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate tickets for invoice items that do not have tickets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting ticket generation for invoice items...');

        // Get all invoice items that don't have tickets
        $invoiceItems = InvoiceItem::whereDoesntHave('tickets')
            ->with('invoice') // Eager load the invoice relationship
            ->get();

        $count = 0;
        $errors = 0;

        foreach ($invoiceItems as $invoiceItem) {
            try {
                // Check if invoice exists and has point_of_sale_id
                if (!$invoiceItem->invoice) {
                    throw new \Exception("Invoice not found for invoice item");
                }

                if (!$invoiceItem->invoice->point_of_sale_id) {
                    throw new \Exception("Point of sale ID not found in invoice");
                }

                // Get point_of_sale_id from the invoice
                $pointOfSaleId = $invoiceItem->invoice->point_of_sale_id;

                // Create tickets based on quantity
                for ($i = 1; $i <= $invoiceItem->quantity; $i++) {
                    Ticket::create([
                        'code' => $invoiceItem->invoice_number . '' . $i,
                        'invoice_item_id' => $invoiceItem->id,
                        'ticket_status_id' => 1, // Default status
                        'status_updated_at' => now(),
                        'point_of_sale_id' => $pointOfSaleId,
                    ]);
                }

                $count += $invoiceItem->quantity;
                $this->info("Generated {$invoiceItem->quantity} tickets for invoice item {$invoiceItem->invoice_number}");
            } catch (\Exception $e) {
                $errors++;
                $errorMessage = "Error generating tickets for invoice item {$invoiceItem->invoice_number}: " . $e->getMessage();
                Log::error($errorMessage);
                $this->error($errorMessage);
            }
        }

        $this->info("Ticket generation completed. Generated {$count} tickets with {$errors} errors.");
    }
}
