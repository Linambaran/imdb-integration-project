<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $host = Request::getHost();
        
        if (str_contains($host, '.trycloudflare.com')) {
            // 1. Paksa HTTPS
            URL::forceScheme('https');

            // 2. Paksa APP_URL agar sesuai dengan domain acak Cloudflare saat ini
            // Jadi aset gambar/CSS akan mengikuti domain baru secara otomatis
            Config::set('app.url', 'https://' . $host);
        }    
    }
}