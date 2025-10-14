<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SqlDevLogProvider extends ServiceProvider
{
    public function boot()
    {
        return;
        if ('production' == app()->environment()) {
            return;
        }

        DB::listen(function ($query) {
            Log::channel('sql_dev')->debug(
                $query->sql,
                ['bindings' => $query->bindings, 'time' => $query->time]
            );
        });
    }
}
