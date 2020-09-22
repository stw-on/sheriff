<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Models\PublicKey;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DbChoreCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:chore';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Do database chore';

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
            Log::info("Deleted $deleted visit(s).");
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
                Log::info("Expired visit $visitId.");
            } catch (\Exception $exception) {
                Log::error("Could not update visit $visitId.");
            }
        }

        /** @var Location $deletedLocation */
        foreach (Location::onlyTrashed()->cursor() as $deletedLocation) {
            if (!$deletedLocation->visits()->exists()) {
                $deletedLocation->forceDelete();
                Log::info("Deleted location " . $deletedLocation->id);
            }
        }

        /** @var PublicKey $deletedPublicKey */
        foreach (PublicKey::onlyTrashed()->cursor() as $deletedPublicKey) {
            if (!$deletedPublicKey->visits()->exists()) {
                $deletedPublicKey->forceDelete();
                Log::info("Deleted public key " . $deletedPublicKey->id);
            }
        }

        return 0;
    }
}
