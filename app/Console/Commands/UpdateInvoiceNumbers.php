<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;

class UpdateInvoiceNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:update-numbers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update invoice and invoice item numbers using helper functions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting invoice number updates...');

        // Update Invoice numbers
        $invoices = Invoice::get();
        if ($invoices->isNotEmpty()) {
            $counter = 1;
            foreach ($invoices as $invoice) {
                DB::beginTransaction();
                $prefix = $invoice->booked_from == 'point_of_sale' ? 'INV-POS-' : 'INV-WEB-';
                try {
                    $newNumber = $prefix . $counter;
                    $invoice->invoice_number = $newNumber;
                    $invoice->save();
                    DB::commit();
                    $this->info("Updated invoice {$invoice->id} with number: {$newNumber}");
                    $counter++;
                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->error("Error updating invoice {$invoice->id}: " . $e->getMessage());
                    continue;
                }
            }
            $this->info('Completed invoice number updates.');
        }

        // Update Invoice Item numbers
        $invoiceItems = InvoiceItem::get();
        if ($invoiceItems->isNotEmpty()) {
            foreach ($invoiceItems as $item) {
                DB::beginTransaction();
                try {
                    $newNumber = generateUniqueInvoiceItemNumber($item->invoice->invoice_number ?? '');
                    $item->invoice_number = $newNumber;
                    $item->save();
                    DB::commit();
                    $this->info("Updated invoice item {$item->id} with number: {$newNumber}");
                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->error("Error updating invoice item {$item->id}: " . $e->getMessage());
                    continue;
                }
            }
            $this->info('Completed invoice item number updates.');
        }

        $this->info('Invoice number update process completed.');
    }
}
