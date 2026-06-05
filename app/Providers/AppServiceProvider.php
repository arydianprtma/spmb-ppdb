<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Models\PpdbSiswa;
use App\Observers\PpdbRegistrantObserver;
use App\Models\ContactMessage;
use App\Observers\ContactMessageObserver;

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
        Vite::prefetch(concurrency: 3);
        
        PpdbSiswa::observe(PpdbRegistrantObserver::class);
        ContactMessage::observe(ContactMessageObserver::class);
    }
}
