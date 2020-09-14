<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Models\PublicKey;
use Base64Url\Base64Url;
use http\QueryString;
use http\Url;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use SodiumException;
use function Functional\map;
use function Functional\reindex;

class CreateLocationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'location:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new location';

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
        $publicKeys = PublicKey::query()->get()->all();
        $keyedPublicKeys = reindex($publicKeys, fn($e, $index) => $publicKeys[$index]->id);
        $keyedPublicKeyNames = map($keyedPublicKeys, fn(PublicKey $p) => $p->name);

        $name = $this->ask('Location name');
        $keyIndex = $this->choice('Key', $keyedPublicKeyNames);

        $location = new Location();
        $location->name = $name;
        $location->publicKey()->associate($keyedPublicKeys[$keyIndex]);
        $location->save();

        $host = config('sheriff.host');
        $url = "https://$host/register/?scan=" . urlencode(Base64Url::encode($location->generateQrString()));

        $this->line('URL: ' . $url);

        return 0;
    }
}
