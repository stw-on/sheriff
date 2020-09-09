<?php

namespace App\Console\Commands;

use App\Models\Location;
use Illuminate\Console\Command;
use SodiumException;

class NewLocationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'location:new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new location';

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
     * @throws SodiumException
     */
    public function handle()
    {
        $location = new Location();
        $location->name = $this->ask('Location name:');

        $keypair = sodium_crypto_box_keypair();

        $publicKey = sodium_crypto_box_publickey($keypair);

        $location->public_key = $publicKey;
        $location->save();

        $this->line('Private: ' . base64_encode(sodium_crypto_box_secretkey($keypair)));
        $this->line('Public:  ' . base64_encode($publicKey));

        $sharedData = [
            'location_id' => $location->id,
        ];

        $this->line('QR:      ' . $location->generateQrString());

        $this->warn('Store the private key in two physically separate, secure locations. You will not be able to see it again!');

        sodium_memzero($keypair);

        return 0;
    }
}
