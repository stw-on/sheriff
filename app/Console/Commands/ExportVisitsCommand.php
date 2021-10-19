<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Models\PublicKey;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Console\Command;
use League\Csv\Writer;
use function Functional\map;
use function Functional\reindex;

class ExportVisitsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visits:export {file?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export visits';

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
        $locations = Location::query()->get()->all();
        $keyedLocations = reindex($locations, fn($e, $index) => $locations[$index]->id);
        $keyedLocationNames = map($keyedLocations, fn(Location $l) => $l->name);

        $locationIndex = $this->choice('Location', $keyedLocationNames);

        /** @var Location $location */
        $location = $keyedLocations[$locationIndex];

        $privateKeys = []; // Key ID → keypair

        if ($this->argument('file')) {
            file_put_contents($this->argument('file'), '');
            $csv = Writer::createFromPath($this->argument('file'));
        } else {
            $csv = Writer::createFromStream(STDOUT);
        }

        $csv->insertOne(['entered_at', 'left_at', 'first_name', 'last_name', 'street', 'zip', 'city', 'phone']);

        foreach ($location->visits as $visit) {
            try {
                if (!array_key_exists($visit->public_key_id, $privateKeys)) {
                    $privateKey = base64_decode($this->ask('Enter private key for key ' . $visit->public_key_id));
                    $keypair = sodium_crypto_box_keypair_from_secretkey_and_publickey($privateKey, $visit->publicKey->key);
                    $privateKeys[$visit->public_key_id] = $keypair;
                }

                $data = optional(
                    json_decode(sodium_unpad(
                        sodium_crypto_box_seal_open($visit->contact_details, $privateKeys[$visit->public_key_id]),
                        Visit::PAD_LENGTH
                    ), true, 512, JSON_THROW_ON_ERROR)
                );

                $csv->insertOne([
                    $visit->entered_at->toIso8601String(),
                    optional($visit->left_at)->toIso8601String(),
                    $data['first_name'],
                    $data['last_name'],
                    $data['street'],
                    $data['zip'],
                    $data['city'],
                    $data['phone'],
                ]);
            } catch (\Exception $exception) {
                $this->error('Error exporting visit ' . $visit->id . ': ' . $exception->getMessage());
            }
        }

        return 0;
    }
}
