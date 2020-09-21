<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Models\PublicKey;
use App\Models\User;
use Base64Url\Base64Url;
use http\QueryString;
use http\Url;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use SodiumException;
use function Functional\map;
use function Functional\reindex;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

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
        $user = new User();

        $user->account = $this->ask('Account');
        $user->display_name = $this->ask('Display name');
        $user->password_hash = Hash::make($this->secret('Password'));
        $user->can_manage_locations = $this->confirm('User can create locations?');

        $user->save();

        $this->info('User created.');

        return 0;
    }
}
