<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

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
        // Configurar idioma de Carbon para fechas en español
        \Carbon\Carbon::setLocale(config('app.locale'));
        
        Schema::defaultStringLength(191);
        
        // Registrar el namespace 'layouts' para compatibilidad con algunos componentes de Livewire/Flux
        // Apunta a resources/views/components/layouts
        View::addNamespace('layouts', resource_path('views/components/layouts'));
    }
}