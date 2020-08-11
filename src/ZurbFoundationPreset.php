<?php

namespace LaravelFrontendPresets\ZurbFoundationPreset;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Laravel\Ui\Presets\Preset;

class ZurbFoundationPreset extends Preset
{
    /**
     * Install the preset.
     *
     * @return void
     */
    public static function install()
    {
        static::updatePackages();
        static::updateSass();
        static::updateBootstrapping();
        static::updateWelcomePage();
        static::removeNodeModules();
    }

    /**
     * Install the preset with auth.
     *
     * @return void
     */
    public static function installAuth()
    {
        static::updateAuthControllers();
        static::updateAuthTemplates();
    }

    /**
     * Update the given package array.
     *
     * @param  array $packages
     * @return array
     */
    protected static function updatePackageArray(array $packages)
    {
        return [
            'foundation-sites' => '^6.6',
            'jquery' => '^3.5',
        ] + Arr::except($packages, [
            'bootstrap',
            'bootstrap-sass',
            'bulma',
            'uikit'
        ]);
    }

    /**
     * Update the SASS files.
     *
     * @return void
     */
    protected static function updateSass()
    {
        tap(new Filesystem, function ($filesystem) {
            $filesystem->deleteDirectory(resource_path('css'));
            $filesystem->delete(public_path('js/app.js'));
            $filesystem->delete(public_path('css/app.css'));

            if (! $filesystem->isDirectory($directory = resource_path('sass'))) {
                $filesystem->makeDirectory($directory, 0755, true);
            }

            $filesystem->copyDirectory(__DIR__.'/foundation-stubs/resources/sass', resource_path('sass'));
        });
    }

    /**
     * Update the bootstrapping JS files.
     *
     * @return void
     */
    protected static function updateBootstrapping()
    {
        tap(new Filesystem, function ($filesystem) {
            if (! $filesystem->isDirectory($directory = resource_path('js'))) {
                $filesystem->makeDirectory($directory, 0755, true);
            }

            $filesystem->copy(__DIR__.'/foundation-stubs/resources/js/bootstrap.js', resource_path('js/bootstrap.js'));
        });
    }

    /**
     * Update the welcome page template file.
     *
     * @return void
     */
    protected static function updateWelcomePage()
    {
        (new Filesystem)->copy(__DIR__.'/foundation-stubs/resources/views/welcome.blade.php', resource_path('views/welcome.blade.php'));
    }

    /**
     * Update the auth controllers and web routes.
     *
     * @return void
     */
    protected static function updateAuthControllers()
    {
        tap(new Filesystem, function ($filesystem) {
            if (! $filesystem->isDirectory($directory = app_path('Http/Controllers/Auth'))) {
                $filesystem->makeDirectory($directory, 0755, true);
            }

            collect($filesystem->allFiles(base_path('vendor/laravel/ui/stubs/Auth')))
                ->each(function (SplFileInfo $file) use ($filesystem) {
                    $filesystem->copy(
                        $file->getPathname(),
                        app_path('Http/Controllers/Auth/'.Str::replaceLast('.stub', '.php', $file->getFilename()))
                    );
                });
        });

        file_put_contents(app_path('Http/Controllers/HomeController.php'), static::compileHomeControllerStub());

        file_put_contents(
            base_path('routes/web.php'),
            "\nAuth::routes();\n\nRoute::get('/home', 'HomeController@index')->name('home');\n",
            FILE_APPEND
        );
    }

    /**
     * Update the auth templates.
     *
     * @return void
     */
    protected static function updateAuthTemplates()
    {
        tap(new Filesystem, function ($filesystem) {
            $filesystem->copyDirectory(__DIR__.'/foundation-stubs/resources/views', resource_path('views'));

            collect($filesystem->allFiles(base_path('vendor/laravel/ui/stubs/migrations')))
                ->each(function (SplFileInfo $file) use ($filesystem) {
                    $filesystem->copy(
                        $file->getPathname(),
                        database_path('migrations/'.$file->getFilename())
                    );
                });
        });
    }

     /**
      * Compile welcome page controller file content.
      *
      * @return string
      */
    private static function compileHomeControllerStub()
    {
        return Str::replaceFirst(
            '{{namespace}}',
            Container::getInstance()->getNamespace(),
            file_get_contents(__DIR__.'/foundation-stubs/controllers/HomeController.stub')
        );
    }
}
