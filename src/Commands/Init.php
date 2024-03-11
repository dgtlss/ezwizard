<?php

namespace Dgtlss\EzWizard\Commands;

use DB;
use Route;
use Cache;
use Artisan;
use Carbon\Carbon;
use function Laravel\Prompts\text;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;
use function Laravel\Prompts\suggest;
use function Laravel\Prompts\info;
use function Laravel\Prompts\table;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Console\Command;


class Init extends Command
{
    protected $signature = 'ezwiz:init';
    protected $description = 'Start the EzWizard setup process.';

    protected $version;

    private $itemsUsed = [];

    private $dbconfig = [];

    public function __construct()
    {
        parent::__construct();
		$this->version = json_decode(file_get_contents(__DIR__.'/../../composer.json'))->version;		
    }

    public function handle()
    {
        // clear the console
        $this->clear();
        
        // Welcome message
        $this->info('🧙‍♂️ Welcome to EzWizard v'.$this->version);
        
        // See what laravel packages the user wants to install
        $this->newLine();
        $this->info('📦 Laravel Packages');
        $this->laravelPackages();
        
        // See what debugging packages the user wants to install
        $this->newLine();
        $this->info('🔍 Monitoring & Debugging');
        $this->debuggingPackages();

        // See what styling packages the user wants to install
        $this->newLine();
        $this->info('🎨 Styling');
        $this->stylingPackages();

        // Check if the user has a database configured
        $this->newLine();
        $this->databaseChecker();

        // Build the documentation array to display to the user
        $this->newLine();
        $this->buildDocumentationArray();

        // All done!
        $this->info('🧙‍♂️ Thank you for using EzWizard!');
        $this->info('🧙‍♂️ You can now safely remove this package with "composer remove dgtlss/ezwizard"');
        $this->info('🧙‍♂️ To request additional features or packages please visit: https://github.com/dgtlss/ezwizard');
    }

    private function artisanCommand($commandArray)
    {
        $command = $commandArray;
        $process = new Process($command);
        $process->setTimeout(300); // Timeout of 5 minutes
        $process->setTty(true); // Enable TTY
        $process->setWorkingDirectory(base_path()); // Ensure we are in the base directory of the Laravel project
        $process->run(); // Run the command
    }

    /* Functions to install the various packages */
    private function laravelPackages()
    {
        // Check if the user wants to install auth
        $auth = confirm('Would you like to install a Laravel Auth package?', true);
        if($auth){
            $this->installAuth();
        }

        // Check if the user wants to install livewire
        $livewire = confirm('Would you like to install Livewire?', true);
        if($livewire){
            $this->installLiveWire();
        }

        // Check if the user wants to install cashier
        $cashier = confirm('Would you like to install Laravel Cashier?', true);
        if($cashier){
            $this->installCashier();
        }

        // Check if the user wants to install telescope
        $telescope = confirm('Would you like to install Laravel Telescope?', true);
        if($telescope){
            $this->installLaravelTelescope();
        }

        // Check if the user wants to install horizon
        $horizon = confirm('Would you like to install Laravel Horizon?', true);
        if($horizon){
            $this->installLaravelHorizon();
        }

        // Check if the user wants to install intervention
        $intervention = confirm('Would you like to install Intervention Image?', true);
        if($intervention){
            $this->installIntervention();
        }

        // Check if the user wants to install socialite
        $socialite = confirm('Would you like to install Laravel Socialite?', true);
        if($socialite){
            $this->installSocialite();
        }

        // Check if the user wants to install DomPDF
        $dompdf = confirm('Would you like to install DomPDF?', true);
        if($dompdf){
            $this->installDomPDF();
        }

    }

    private function debuggingPackages()
    {
        /* Debugging */
        $debugBar = confirm('Would you like to install the Laravel Debugbar?', true);
        if($debugBar){
            $this->installDebugbar();
        }

        $clockwork = confirm('Would you like to install Clockwork?', true);
        if($clockwork){
            $this->installClockwork();
        }
    }

    private function stylingPackages()
    {
        // Check if the user wants to install Cooker by Genericmilk
        $cooker = confirm('Would you like to install Cooker by Genericmilk to manage & compile your javascript & CSS?', true);
        if($cooker){
            $this->installCooker();
        }

        // Check if the user wants to install tailwind
        $tailwind = confirm('Would you like to install Tailwind CSS?', true);
        if($tailwind){
            $this->installTailwind();
        }
    }

    /* Database Checker */
    private function databaseChecker()
    {
        // Check the ENV and see if a database is configured
        $this->dbconfig = [
            'DB_CONNECTION' => env('DB_CONNECTION'),
            'DB_HOST' => env('DB_HOST'),
            'DB_PORT' => env('DB_PORT'),
            'DB_DATABASE' => env('DB_DATABASE'),
            'DB_USERNAME' => env('DB_USERNAME'),
            'DB_PASSWORD' => env('DB_PASSWORD'),
        ];

        // If the user has a database configured perform a quick connection check. We need to do this regardless of the DB_CONNECTION type
        $this->info('🔍 Checking database connection');
        try {
            DB::connection()->getPdo();
            $this->info('🔍 Database connection successful');
            // If the user has a database configured run the migrations
            try{
                $this->info('🔍 Running php artisan migrate');
                $this->artisanCommand(['php', 'artisan', 'migrate']);
                $this->info('🔍 Migrations complete');
            } catch (\Exception $e) {
                $this->error('🔍 Migrations failed');
                $this->error('🔍 Please check your database configuration in your .env file.');
            }
        } catch (\Exception $e) {
            $this->error('🔍 Database connection failed');
            $this->error('🔍 Please check your database configuration in your .env file.');
        }
    }

    /* Laravel Packages */
    private function installAuth()
    {
        $this->info('🔐 Installing Laravel Auth');
        // Which auth package do they want to install?
        $authPackage = select('Which Laravel Auth package would you like to install?', [
            'Laravel Breeze',
            'Laravel Jetstream',
            'Laravel Sanctum',
            'Laravel Fortify',
            'Laravel UI',
            'None'
        ]);
        // If they want to install Breeze
        if($authPackage == 'Laravel Breeze'){
            $this->info('🔐 Installing Laravel Breeze');
            $this->info('🔐 Running composer require laravel/breeze');
            exec('composer require laravel/breeze');
            $this->info('🔐 Running php artisan breeze:install');
            
            $this->artisanCommand(['php', 'artisan', 'breeze:install']);
            $this->info('🔐 Laravel Breeze installation complete');
            $this->itemsUsed[] = 'Laravel Breeze';
        }

        // If they want to install Jetstream
        if($authPackage == 'Laravel Jetstream'){
            $this->info('🔐 Installing Laravel Jetstream');
            $this->info('🔐 Running composer require laravel/jetstream');
            exec('composer require laravel/jetstream');

            // Does the user want to use livewire or inertia?
            $jetstreamStack = select('Which Jetstream stack would you like to use?', [
                'Livewire',
                'Inertia'
            ]);

            // Do they want teams and dark mode?
            $teams = confirm('Would you like to enable teams?', true);
            $darkMode = confirm('Would you like to enable dark mode?', true);

            // Jetstream Livewire Installation
            if($jetstreamStack == 'Livewire'){
                if($teams && darkMode){
                    $this->info('🔐 Running php artisan jetstream:install livewire --teams');
                    $this->artisanCommand(['php', 'artisan', 'jetstream:install', 'livewire', '--teams', '--dark']);
                }elseif($teams){
                    $this->info('🔐 Running php artisan jetstream:install livewire --teams');
                    $this->artisanCommand(['php', 'artisan', 'jetstream:install', 'livewire', '--teams']);
                }elseif($darkMode){
                    $this->info('🔐 Running php artisan jetstream:install livewire --dark');
                    $this->artisanCommand(['php', 'artisan', 'jetstream:install', 'livewire', '--dark']);
                } else {
                    $this->info('🔐 Running php artisan jetstream:install livewire');
                    $this->artisanCommand(['php', 'artisan', 'jetstream:install', 'livewire']);
                }
            }

            // Jetstream Intertia Installation
            if($jetstreamStack == 'Inertia'){
                $ssr = confirm('Would you like to use server-side rendering?', true);
                if($teams && $ssr){
                    $this->info('🔐 Running php artisan jetstream:install inertia --teams');
                    $this->artisanCommand(['php', 'artisan', 'jetstream:install', 'inertia', '--teams', '--ssr']);
                }elseif($teams){
                    $this->info('🔐 Running php artisan jetstream:install inertia --teams');
                    $this->artisanCommand(['php', 'artisan', 'jetstream:install', 'inertia', '--teams']);
                }elseif($ssr){
                    $this->info('🔐 Running php artisan jetstream:install inertia --ssr');
                    $this->artisanCommand(['php', 'artisan', 'jetstream:install', 'inertia', '--ssr']);
                } elseif($darkMode){
                    $this->info('🔐 Running php artisan jetstream:install inertia --dark');
                    $this->artisanCommand(['php', 'artisan', 'jetstream:install', 'inertia', '--dark']);
                } elseif($darkMode && $ssr){
                    $this->info('🔐 Running php artisan jetstream:install inertia --dark --ssr');
                    $this->artisanCommand(['php', 'artisan', 'jetstream:install', 'inertia', '--dark', '--ssr']);
                } elseif($darkMode && $teams){
                    $this->info('🔐 Running php artisan jetstream:install inertia --dark --teams');
                    $this->artisanCommand(['php', 'artisan', 'jetstream:install', 'inertia', '--dark', '--teams']);
                } elseif($darkMode && $teams && $ssr){
                    $this->info('🔐 Running php artisan jetstream:install inertia --dark --teams --ssr');
                    $this->artisanCommand(['php', 'artisan', 'jetstream:install', 'inertia', '--dark', '--teams', '--ssr']);
                } else {
                    $this->info('🔐 Running php artisan jetstream:install inertia');
                    $this->artisanCommand(['php', 'artisan', 'jetstream:install', 'inertia']);
                }
            }

            $this->itemsUsed[] = 'Laravel Jetstream';

        }

        // If they want to install Sanctum
        if($authPackage == 'Laravel Sanctum'){
            $this->info('🔐 Running composer require laravel/sanctum');
            exec('composer require laravel/sanctum');
            $this->info('🔐 Running php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"');
            $this->artisanCommand(['php', 'artisan', 'vendor:publish', '--provider="Laravel\Sanctum\SanctumServiceProvider"']);
            $this->info('🔐 Laravel Sanctum installation complete');
            $this->itemsUsed[] = 'Laravel Sanctum';
        }

        // If they want to install Fortify
        if($authPackage == 'Laravel Fortify'){
            $this->info('🔐 Running composer require laravel/fortify');
            exec('composer require laravel/fortify');
            $this->info('🔐 Running php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"');
            $this->artisanCommand(['php', 'artisan', 'vendor:publish', '--provider="Laravel\Fortify\FortifyServiceProvider"']);
            $this->info('🔐 Laravel Fortify installation complete');
            $this->itemsUsed[] = 'Laravel Fortify';
        }

        // If they want to install Laravel UI
        if($authPackage == 'Laravel UI'){
            $this->info('🔐 Installing Laravel UI');
            $this->info('🔐 Running composer require laravel/ui');
            exec('composer require laravel/ui');
            $scaffoldingType = select('Which laravel UI scaffolding would you like to use?', [
                'Basic',
                'Login & Registration'
            ]);

            $scaffoldingOptions = Select('Which scaffolding style would you like to use?', [
                'Bootstrap',
                'Vue',
                'React',
            ]);

            if($scaffoldingType == 'Basic'){
                $this->info('🔐 installing Basic '.$scaffoldingOptions. ' Scaffolding');
                // Run the command
                $this->artisanCommand(['php', 'artisan', 'ui', strtolower($scaffoldingOptions), '--auth']);

                $this->info('🔐 Laravel Basic Auth installation complete');
            }

            if($scaffoldingType == 'Login & Registration'){
                $this->info('🔐 installing Login & Registration '.$scaffoldingOptions. ' Scaffolding');
                // Run the command
                $this->artisanCommand(['php', 'artisan', 'ui', strtolower($scaffoldingOptions), '--auth']);

                $this->info('🔐 Laravel Login & Registration installation complete');

            }

            $this->itemsUsed[] = 'Laravel UI';
        }
        // If they don't want to install any auth
        if($authPackage == 'None'){
            $this->info('❌ Skipping Laravel Auth installation');
        }   
        $this->info('🔐 Laravel Auth installation complete');
    }

    private function installLivewire()
    {
        $this->info('🪼 Installing Laravel Livewire');
        $this->info('🪼 Running composer require livewire/livewire');
        exec('composer require livewire/livewire');
        $this->info('🪼 Publishing the configuration files');
        $this->artisanCommand(['php', 'artisan', 'livewire:publish', '--config']);
        $this->info('🪼 Laravel Livewire installation complete');
    }

    private function installCashier()
    {
        $this->info('💳 Installing Laravel Cashier');
        $this->info('💳 Running composer require laravel/cashier');
        exec('composer require laravel/cashier');
        $this->info('💳 Running php artisan vendor:publish --tag="cashier-migrations"');
        $this->artisanCommand(['php', 'artisan', 'vendor:publish', '--tag="cashier-migrations"']);
        $this->info('💳 Laravel Cashier installation complete');

        $this->itemsUsed[] = 'Laravel Cashier';
    }

    private function installIntervention()
    {
        $this->info('🖼 Installing Intervention Image');
        $this->info('🖼 Running composer require intervention/image-laravel');
        exec('composer require intervention/image-laravel');
        $this->info('🖼 Running php artisan vendor:publish --provider="Intervention\Image\ImageServiceProviderLaravelRecent"');
        $this->artisanCommand(['php', 'artisan', 'vendor:publish', '--provider="Intervention\Image\Laravel\ServiceProvider"']);
        $this->info('🖼 Intervention installation complete!');

        $this->itemsUsed[] = 'Intervention Image';
    }

    private function installLaravelFolio()
    {
        $this->info('📄 Installing Laravel Folio');
        $this->info('📄 Running composer require laravel/folio');
        exec('composer require laravel/folio');
        $this->info('📄 Running php artisan folio:install');
        $this->artisanCommand(['php', 'artisan', 'folio:install']);
        $this->info('📄 Laravel Folio installation complete');
        $this->itemsUsed[] = 'Laravel Folio';
    }

    private function installSocialite()
    {
        $this->info('🔐 Running composer require laravel/socialite');
        exec('composer require laravel/socialite');
        $this->info('🔐 Laravel Socialite installation complete');
        $this->itemsUsed[] = 'Laravel Socialite';
    }

    private function installDomPDF()
    {
        $this->info('📄 Running composer require dompdf/dompdf');
        exec('composer require dompdf/dompdf');
        $this->info('📄 DomPDF installation complete');
        $this->itemsUsed[] = 'DomPDF';
    }

    /* Debugging & Monitoring */
    private function installLaravelTelescope()
    {
        $this->info('🔍 Installing Laravel Telescope');
        $this->info('🔍 Running composer require laravel/telescope');
        exec('composer require laravel/telescope');
        $this->info('🔍 Running php artisan telescope:install');
        $this->artisanCommand(['php', 'artisan', 'telescope:install']);
        $this->info('🔍 Laravel Telescope installation complete');

        $this->itemsUsed[] = 'Laravel Telescope';
    }

    private function installLaravelHorizon()
    {
        $this->info('🔍 Installing Laravel Horizon');
        $this->info('🔍 Running composer require laravel/horizon');
        exec('composer require laravel/horizon');
        $this->info('🔍 Running php artisan horizon:install');
        $this->artisanCommand(['php', 'artisan', 'horizon:install']);
        $this->info('🔍 Laravel Horizon installation complete');

        $this->itemsUsed[] = 'Laravel Horizon';
    }

    private function installDebugbar()
    {
        $this->info('🐞 Installing Laravel Debugbar');
        $this->info('🐞 Running composer require barryvdh/laravel-debugbar');
        exec('composer require barryvdh/laravel-debugbar');
        $this->info('🐞 Laravel Debugbar installation complete');

        $this->itemsUsed[] = 'Laravel Debugbar';
    }

    private function installClockwork()
    {
        $this->info('🕰 Installing Clockwork');
        $this->info('🕰 Running composer require itsgoingd/clockwork');
        exec('composer require itsgoingd/clockwork');
        $this->info('🕰 Clockwork installation complete');

        $this->itemsUsed[] = 'Clockwork';
    }

    /* Styling Packages */
    private function installCooker()
    {
        $this->info('👨‍🍳 Installing Cooker by Genericmilk');
        exec('composer require genericmilk/cooker');
        $this->info('👨‍🍳 Initialising Cooker!');
        $this->artisanCommand(['php', 'artisan', 'cooker:init']);
        $this->info('👨‍🍳 Cooker installation complete');

        $this->itemsUsed[] = 'Cooker';
    }

    private function installTailwind()
    {
        $this->info('🎨 Installing Tailwind CSS');
        $this->info('🎨 Running npm install tailwindcss postcss autoprefixer');
        exec('npm install -D tailwindcss postcss autoprefixer');
        $this->info('🎨 Running npx tailwindcss init -p');
        exec('npx tailwindcss init -p');
        // Add the Tailwind directives to your CSS. Check for an app.css file. If it doesn't exist, create it.
        if(file_exists('resources/css/app.css')){
            $this->info('🎨 Adding Tailwind directives to resources/css/app.css');
            file_put_contents('resources/css/app.css', "@import 'tailwindcss/base';\n@import 'tailwindcss/components';\n@import 'tailwindcss/utilities';");
        } else {
            $this->info('🎨 Creating resources/css/app.css');
            file_put_contents('resources/css/app.css', "@import 'tailwindcss/base';\n@import 'tailwindcss/components';\n@import 'tailwindcss/utilities';");
        }
        $this->info('🎨 Tailwind CSS installation complete');

        $this->itemsUsed[] = 'Tailwind CSS';
    }

    /* Array of all documentation for the items available */
    private function buildDocumentationArray()
    {
        // Check what items have been used during the installation process
        if($this->itemsUsed != []){
            $this->info('📚 Documentation');
            $this->info('📚 The following items have been installed:');
            $this->info('📚 '.implode(', ', $this->itemsUsed));

            // Put links to the documentation for each item
            $documentation = [
                /* Laravel Packages */
                'Laravel Breeze' => 'https://laravel.com/docs/starter-kits#laravel-breeze',
                'Laravel Jetstream' => 'https://jetstream.laravel.com/introduction.html',
                'Laravel Sanctum' => 'https://laravel.com/docs/sanctum',
                'Laravel Fortify' => 'https://laravel.com/docs/fortify',
                'Laravel UI' => 'https://github.com/laravel/ui',
                'Laravel Livewire' => 'https://livewire.laravel.com/docs/quickstart',
                'Laravel Folio' => 'https://laravel.com/docs/folio',
                'Laravel Cashier' => 'https://laravel.com/docs/billing',
                'Intervention Image' => 'http://image.intervention.io/',
                'Laravel Socialite' => 'https://laravel.com/docs/socialite',
                'DomPDF' => 'https://github.com/dompdf/dompdf',
                /* Monitoring */
                'Laravel Telescope' => 'https://laravel.com/docs/telescope',
                'Laravel Horizon' => 'https://laravel.com/docs/horizon',
                /* Debugging */
                'Laravel Debugbar' => 'https://github.com/barryvdh/laravel-debugbar',
                'Clockwork' => 'https://underground.works/clockwork/#docs-installation',
                /* Styling Packages */
                'Cooker' => 'https://github.com/genericmilk/cooker',
                'Tailwind CSS' => 'https://tailwindcss.com/docs/installation',
            ];

            $this->newLine();
            // Loop through the items used and display the documentation
            foreach($this->itemsUsed as $item){
                $this->info('📚 '.$item.' documentation: '.$documentation[$item]);
            }
            $this->newLine();
        } else {
            $this->info('📚 No items have been installed');
        }
    }
}