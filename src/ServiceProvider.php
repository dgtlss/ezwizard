<?php
    namespace Dgtlss\EzWizard;

    require_once __DIR__.'/helpers.php';


    class ServiceProvider extends \Illuminate\Support\ServiceProvider {



        public function boot()
        {
            $this->setupConfig(); // Load config
            if ($this->app->runningInConsole()) {
                $this->commands([
                    Commands\Init::class,
                ]);
            }
        }

        public function register()
        {            
            // Not needed for this package but you can use this to register your services

        }

        protected function setupConfig(){

            $configPath = __DIR__ . '/../config/ezwizard.php';
            $this->publishes([$configPath => $this->getConfigPath()], 'config');
    
        }

        protected function getConfigPath()
        {
            return config_path('ezwizard.php');
        }

        protected function publishConfig($configPath)
        {
            $this->publishes([$configPath => config_path('ezwizard.php')], 'config');
        }


    }
