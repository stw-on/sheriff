<?php

namespace App\Console\Commands;

use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessVisitsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visits:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process visits';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $deleted = Visit::query()
            ->whereDate('entered_at', '<', Carbon::today()->subDays(14))
            ->delete();

        if ($deleted > 0) {
            $this->info("Deleted $deleted visit(s).");
        }

        $expiredVisits = Visit::query()
            ->whereDate('entered_at', '<', Carbon::today())
            ->whereNull('left_at')
            ->cursor();

        /** @var Visit $expiredVisit */
        foreach ($expiredVisits as $expiredVisit) {
            $visitId = $expiredVisit->id;

            try {
                $expiredVisit->left_at = $expiredVisit->entered_at->endOfDay();
                $expiredVisit->save();
                $this->info("Expired visit $visitId.");
            } catch (\Exception $exception) {
                $this->error("Could not update visit $visitId.");
            }
        }

        return 0;
    }
}
