<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Tüm görünümler için JavaScript çevirilerini ve flash mesajlarını paylaş
        View::composer('*', function ($view) {
            // JavaScript için dil çevirileri
            $jsTranslations = [
                'confirm' => __('locale.confirm'),
                'cancel' => __('locale.cancel'),
                'delete_confirm_title' => __('locale.delete_confirm_title'),
                'delete_confirm_text' => __('locale.delete_confirm_text'),
                'yes_delete' => __('locale.yes_delete'),
                'no_cancel' => __('locale.no_cancel'),
                'deleted' => __('locale.deleted'),
                'delete_error' => __('locale.delete_error'),
                'confirm_action' => __('locale.confirm_action'),
                'confirm_action_text' => __('locale.confirm_action_text'),
                'yes_continue' => __('locale.yes_continue'),
                'operation_successful' => __('locale.operation_successful'),
                'operation_failed' => __('locale.operation_failed'),
            ];
            
            $view->with('jsTranslations', $jsTranslations);
            
            // Flash mesajlarını Javascript değişkeni olarak paylaş
            $flashMessages = [];
            
            foreach (['success', 'error', 'warning', 'info'] as $type) {
                if (Session::has($type)) {
                    $flashMessages[$type] = Session::get($type);
                }
            }
            
            $view->with('flashMessages', $flashMessages);
        });
    }
}
