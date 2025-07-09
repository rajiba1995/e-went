<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Routing\Route;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    
   
/**
     * Return vertical menu data.
     */
    public function getVerticalMenuData()
    {
        return $menuData = [
          'menu' => [
              [
                  'url' => 'admin.dashboard', // Dynamically generated URL from route name
                  'name' => 'Dashboards',
                  'icon' => 'menu-icon tf-icons ri-home-smile-line',
                  'slug' => 'dashboard',
                  "target" => "",
                  'badge' => ['danger', ''],
              ],
              [
                  'url' => 'admin.product.index', // Dynamically generated URL from route name
                  'name' => 'Product Management',
                  'icon' => 'menu-icon tf-icons ri-store-line',
                  'slug' => 'dashboard',
                  "target"=> "_blank",
                  'badge' => ['danger', ''],
                  'submenu' => [
                      [
                          'url' => 'admin.product.categories', // Dynamically generated URL
                          'name' => 'Category',
                          'slug' => 'dashboard-crm',
                      ],
                      [
                          'url' => 'admin.product.sub_categories', // Dynamically generated URL
                          'name' => 'Sub Category',
                          'slug' => 'dashboard-crm',
                      ],
                      [
                          'url' =>'admin.product.index', // Dynamically generated URL
                          'name' => 'Product',
                          'slug' => 'dashboard-crm',
                      ],
                  ],
              ],
              [
                  'url' => 'admin.product.stocks', // Dynamically generated URL
                  'name' => 'Stock Management',
                  'icon' => 'menu-icon tf-icons ri-stock-line',
                  'slug' => 'dashboard',
                  "target" => "_blank",
                  'badge' => ['danger', ''],
                  'submenu' => [
                      [
                          'url' => 'admin.product.stocks', // Dynamically generated URL
                          'name' => 'Stock Report',
                          'slug' => 'dashboard-crm',
                      ],
                  ],
              ],
          ],
      ];
    }
 

             
/**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $menuData = $this->getVerticalMenuData();
        // Share the menu data with all views
        $this->app->make('view')->share('menuData', $menuData);
    }
}
