<?php

namespace Postare\FilamentContactLogs\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Postare\FilamentContactLogs\FilamentContactLogs
 */
class FilamentContactLogs extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Postare\FilamentContactLogs\FilamentContactLogs::class;
    }
}
