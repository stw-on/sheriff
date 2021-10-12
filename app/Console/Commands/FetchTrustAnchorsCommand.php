<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Models\PublicKey;
use App\Models\TrustAnchor;
use App\Models\Visit;
use Carbon\Carbon;
use GuzzleHttp\RequestOptions;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use JsonException;

class FetchTrustAnchorsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trust-anchors:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch covpass trust anchors';

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
     * @throws JsonException
     */
    public function handle()
    {
        $dctList = \Http::withOptions([
            RequestOptions::VERIFY => resource_path('d-trust.pem'),
        ])
            ->get('https://de.dscg.ubirch.com/trustList/DSC/')
            ->body();

        $dctList = json_decode(explode("\n", $dctList)[1], true, 512, JSON_THROW_ON_ERROR);

        $count = DB::transaction(static function () use ($dctList) {
            TrustAnchor::query()->delete();

            foreach ($dctList['certificates'] as $certificate) {
                $anchor = new TrustAnchor();
                $anchor->certificate_type = $certificate['certificateType'];
                $anchor->country = $certificate['country'];
                $anchor->kid = $certificate['kid'];
                $anchor->certificate = "-----BEGIN CERTIFICATE-----\n" . $certificate['rawData'] . "\n-----END CERTIFICATE-----";
                $anchor->signature = $certificate['signature'];
                $anchor->thumbprint = $certificate['thumbprint'];
                $anchor->timestamp = $certificate['timestamp'];
                $anchor->save();
            }

            return count($dctList['certificates']);
        });

        $this->line($count . ' certificates saved.');

        return 0;
    }
}
