<?php

namespace Omnipay\AzeriCard;

use Illuminate\Support\ServiceProvider;
use Omnipay\Omnipay;

class AzeriCardServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('omnipay.azericard', function () {
            return Omnipay::create('AzeriCard');
        });
    }
}
