<?php

namespace App\Console\Commands;

use App\Models\Staff;
use Illuminate\Console\Command;

class GenerateStaffTimeIntervals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'staff:generate-time-intervals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate time intervals for all staff members for the next 10 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Generating time intervals for the next 10 days");

        // Get all active staff
        $staffCount = Staff::where('is_active', true)->count();
        $this->info("Found {$staffCount} active staff members");

        $bar = $this->output->createProgressBar($staffCount);
        $bar->start();

        Staff::where('is_active', true)->each(function ($staff) use ($bar) {
            $staffName = $staff->name_en ?? 'Unknown';
            $this->line("Processing staff: {$staffName}");

            // Use the helper function to create time intervals
            createStaffTimeIntervals($staff->id);

            $bar->advance();
        });

        $bar->finish();
        $this->newLine();
        $this->info('Time intervals generated successfully');

        return Command::SUCCESS;
    }
}
