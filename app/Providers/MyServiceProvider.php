<?php


namespace App\Providers;


use App\Services\XmlServices\XmlServices;
use App\Services\XmlServices\XmlServicesInterface;
use Illuminate\Support\ServiceProvider;

class MyServiceProvider extends ServiceProvider
{
    /**
     * Bind the interface to an implementation service class
     */
    public function register()
    {
        $this->app->singleton(
            XmlServicesInterface::class,
            XmlServices::class
        );
    }
}
