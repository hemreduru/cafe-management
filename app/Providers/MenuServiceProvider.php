<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class MenuServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['events']->listen(BuildingMenu::class, function (BuildingMenu $event) {
            // Top navigation items
            $event->menu->add([
                'type'         => 'fullscreen-widget',
                'topnav_right' => true,
            ]);

            // Sidebar search
            $event->menu->add([
                'type' => 'sidebar-menu-search',
                'text' => __('locale.search'),
            ]);

            // Main menu items
            $event->menu->add([
                'text' => __('locale.dashboard'),
                'url'  => 'dashboard',
                'icon' => 'fas fa-fw fa-tachometer-alt'
            ]);

                    // Yeni Satış
                    $event->menu->add([
                        'text' => __('locale.new_sale'),
                        'url'  => 'cart',
                        'icon' => 'fas fa-fw fa-cash-register'
                    ]);


            // kategoriler
            $event->menu->add([
                'text' => __('locale.categories'),
                'url'  => 'categories',
                'icon' => 'fas fa-fw fa-list'
            ]);

               // urunler
               $event->menu->add([
                'text' => __('locale.products'),
                'url'  => 'products',
                'icon' => 'fas fa-fw fa-cube'
            ]);



            // Kategori ve Ürün Yönetimi Menüsü
            $event->menu->add([
                'text'    => __('locale.istatistik'),
                'icon'    => 'fas fa-fw fa-coffee',
                'submenu' => [
                    [
                        'text' => __('locale.sales_history'),
                        'url'  => 'sales',
                        'icon' => 'fas fa-fw fa-history'
                    ],
                    [
                        'text' => __('locale.birim'),
                        'url'  => 'sales/statistics',
                        'icon' => 'fas fa-fw fa-chart-bar'
                    ],
                ]
            ]);

            // Kullanıcı Yönetimi Menüsü
            $event->menu->add([
                'text'    => __('locale.user_management'),
                'icon'    => 'fas fa-fw fa-users',
                'can'     => 'admin',
                'submenu' => [
                    [
                        'text' => __('locale.users'),
                        'url'  => 'users',
                        'icon' => 'fas fa-fw fa-user-friends'
                    ],
                ]
            ]);

            // Profile section
            $event->menu->add([
                'text'    => __('locale.account'),
                'icon'    => 'fas fa-fw fa-user',
                'submenu' => [
                    [
                        'text' => __('locale.profile'),
                        'url'  => 'profile',
                        'icon' => 'fas fa-fw fa-user-circle'
                    ],
                ]
            ]);
        });
    }

    public function register()
    {
        //
    }
}
