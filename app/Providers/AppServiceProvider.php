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
            // Hanya query database jika user sudah login (hindari crash di halaman login)
            // dan bungkus dengan try-catch agar tidak menghancurkan seluruh halaman jika DB belum siap
            $resetRequests = collect(); // default: collection kosong

            try {
                if (\Illuminate\Support\Facades\Auth::check()) {
                    $resetRequests = \App\Models\User::where('is_requesting_reset', true)
                        ->orderBy('updated_at', 'desc')
                        ->get(['id', 'username_nik', 'nama_lengkap']);
                }
            } catch (\Exception $e) {
                // Jika database belum siap, gagal secara diam-diam
                // dan biarkan halaman tetap tampil
            }

            $view->with('resetRequests', $resetRequests);
        });
    }
}
