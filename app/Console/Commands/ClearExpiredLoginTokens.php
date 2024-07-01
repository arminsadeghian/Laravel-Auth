<?php

namespace App\Console\Commands;

use App\Models\LoginToken;
use Illuminate\Console\Command;

class ClearExpiredLoginTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:clear-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all expired login tokens in database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        LoginToken::expired()->delete();
    }
}
