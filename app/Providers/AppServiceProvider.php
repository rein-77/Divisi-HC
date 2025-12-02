<?php

namespace App\Providers;

use App\Models\RiwayatAkses;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        
        // Event listener untuk login
        Event::listen(Login::class, function (Login $event) {
            RiwayatAkses::create([
                'status' => 'login',
                'user_id' => $event->user->user_id,
                'waktu' => now(),
            ]);
        });

        // Event listener untuk logout  
        Event::listen(Logout::class, function (Logout $event) {
            RiwayatAkses::create([
                'status' => 'logout',
                'user_id' => $event->user->user_id,
                'waktu' => now(),
            ]);
        });
    }
}
