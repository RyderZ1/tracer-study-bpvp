<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Membagikan data resetRequests ke layout utama aplikasi agar bisa diakses di header/navbar semua halaman
        \Illuminate\Support\Facades\View::composer('layouts.app', function ($view) {
            $resetRequests = \App\Models\User::where('is_requesting_reset', true)
                ->orderBy('updated_at', 'desc')
                ->get(['id', 'username_nik', 'nama_lengkap']);
            
            $view->with('resetRequests', $resetRequests);
        });
    }
}
