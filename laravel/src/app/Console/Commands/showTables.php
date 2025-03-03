<?php

namespace App\Console\Commands;

use ClickHouseDB\Client;
use Illuminate\Console\Command;

class showTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clickhouse:showTables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $clickhouse = new Client([
            'host' => env('CLICKHOUSE_HOST', '127.0.0.1'),
            'port' => env('CLICKHOUSE_PORT', '8123'),
            'username' => env('CLICKHOUSE_USERNAME', 'mydesole'),
            'password' => env('CLICKHOUSE_PASSWORD', '1008asdt'),
        ]);

        $clickhouse->database('default');
        $this->table(['name'], $clickhouse->showTables());
    }
}
