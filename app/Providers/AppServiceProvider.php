<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Services\PortalNotificacoesService;

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
        // Notificações do portal para todas as views portal.*
        View::composer(['portal.*', 'portal.layouts.*'], function ($view) {
            if (session()->has('portal_aluno_id')) {
                $view->with('portalNotificacoes', PortalNotificacoesService::get());
            } else {
                $view->with('portalNotificacoes', []);
            }
        });
    }
}
