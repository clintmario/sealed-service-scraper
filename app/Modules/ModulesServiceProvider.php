<?php
namespace App\Modules;
/**
 * Created by PhpStorm.
 * User: ClintMario
 * Date: 9/5/2016
 * Time: 1:31 PM
 */

use \Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // For each of the registered modules, include their routes and Views
        $modules = \Config::get("module.modules");
        $theme = \Config::get("theme.active");

        foreach ($modules as $module) {
            // Load the routes for each of the modules
            if (file_exists(__DIR__ . '/' . $module . '/routes.php')) {
                include __DIR__ . '/' . $module . '/routes.php';
            }

            // Load the views
            if (is_dir(__DIR__ . '/' . $module . '/Views')) {
                $this->loadViewsFrom(__DIR__ .'/' . $module . '/Views', $module);
            }

            if (is_dir(__DIR__ . '/' . $module . '/Views/' . $theme)) {
                $this->loadViewsFrom(__DIR__ . '/' . $module . '/Views/' . $theme, $theme);

                $dir = new \DirectoryIterator(__DIR__ . '/' . $module . '/Views/' . $theme);
                foreach ($dir as $fileInfo) {
                    if ($fileInfo->isDir() && !$fileInfo->isDot()) {
                        $subFolder = $fileInfo->getFilename();
                        $this->loadViewsFrom(__DIR__ . '/' . $module . '/Views/' . $theme, $theme . "." . $module);
                    }
                }
            }
        }
    }

    public function register() {}
}