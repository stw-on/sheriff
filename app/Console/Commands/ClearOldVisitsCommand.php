<?php

namespace App\Console\Commands;

use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ClearOldVisitsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visits:clear-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears visits older than 14 days';

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

        return 0;
    }
}
