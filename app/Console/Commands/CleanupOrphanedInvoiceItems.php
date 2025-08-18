<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;

class CleanupOrphanedInvoiceItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice-items:cleanup-orphaned';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete invoice items that do not have associated invoices';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of orphaned invoice items...');

        // Get all invoice items that don't have an associated invoice
        $orphanedItems = InvoiceItem::whereDoesntHave('invoice')->get();

        if ($orphanedItems->isEmpty()) {
            $this->info('No orphaned invoice items found.');
            return;
        }

        $this->info("Found {$orphanedItems->count()} orphaned invoice items.");

        if ($this->confirm('Do you want to delete these orphaned invoice items?', true)) {
            DB::beginTransaction();
            try {
                foreach ($orphanedItems as $item) {
                    $this->info("Deleting invoice item: {$item->invoice_number}");
                    $item->delete();
                }
                DB::commit();
                $this->info('Successfully deleted all orphaned invoice items.');
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Error deleting orphaned invoice items: " . $e->getMessage());
            }
        } else {
            $this->info('Operation cancelled.');
        }
    }
}
