<?php

namespace App\Console\Commands;

use App\Models\PointOfSale;
use Illuminate\Console\Command;

class GeneratePosTimeIntervals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pos:generate-time-intervals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate time intervals for all points of sale for the next 10 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Generating time intervals for points of sale for the next 10 days");

        // Get all active points of sale
        $posCount = PointOfSale::where('is_active', true)->count();
        $this->info("Found {$posCount} active points of sale");

        $bar = $this->output->createProgressBar($posCount);
        $bar->start();

        PointOfSale::where('is_active', true)->each(function ($pos) use ($bar) {
            $posName = $pos->name_en ?? 'Unknown';
            $this->line("Processing point of sale: {$posName}");

            // Use the helper function to create time intervals
            createPosTimeIntervals($pos->id);

            $bar->advance();
        });

        $bar->finish();
        $this->newLine();
        $this->info('POS time intervals generated successfully');

        return Command::SUCCESS;
    }
}
