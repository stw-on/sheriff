<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Models\PublicKey;
use App\Models\SigningKey;
use Illuminate\Console\Command;
use SodiumException;

class GenerateKeypairCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'keypair:generate {--sign : Generate a signing keypair instead of an encryption keypair}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new keypair';

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
        if ($this->option('sign')) {
            $keypair = sodium_crypto_sign_keypair();
            $key = sodium_crypto_sign_secretkey($keypair);

            $signingKey = new SigningKey();
            $signingKey->key = $key;
            $signingKey->save();

            $this->line('Generated new signing key with ID ' . $signingKey->id);
        } else {
            $publicKey = new PublicKey();
            $publicKey->name = $this->ask('Key name');

            $keypair = sodium_crypto_box_keypair();

            $publicKeyBytes = sodium_crypto_box_publickey($keypair);

            $publicKey->key = $publicKeyBytes;
            $publicKey->save();

            $this->line('Private: ' . base64_encode(sodium_crypto_box_secretkey($keypair)));
            $this->line('Public:  ' . base64_encode($publicKeyBytes));

            $this->warn('Store the private key in two physically separate, secure locations. You will not be able to see it again!');
        }

        sodium_memzero($keypair);

        return 0;
    }
}
