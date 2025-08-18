<?php

namespace App\Providers;

use App\Models\Translation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use Illuminate\Translation\FileLoader;

class DatabaseTranslationServiceProvider extends ServiceProvider
{
    public function register()
    {

        // Override the default translation loader
        $this->app->singleton('translation.loader', function ($app) {
            return new class($app) extends \Illuminate\Translation\FileLoader {
                protected $app;

                public function __construct($app)
                {
                    $this->app = $app;
                    parent::__construct($app['files'], $app['path.lang']);
                }

                public function load($locale, $group, $namespace = null)
                {

                    // Load file-based translations
                    $translations = parent::load($locale, $group, $namespace);

                    // Fetch translations from DB
                    try {

                        $languageId = \App\Models\Language::where('code', $locale)->value('id');

                        if ($languageId) {

                            // Fetch translations from database
                            $dbTranslations = \Illuminate\Support\Facades\Cache::remember("translations.{$locale}", 60, function () use ($languageId) {
                                return \App\Models\Translation::where('language_id', $languageId)
                                    ->pluck('translation', 'key_name')
                                    ->toArray();
                            });


                            // Merge DB translations with file translations
                            $translations = array_merge($translations, $dbTranslations);
                        } else {
                        }
                    } catch (\Exception $e) {
                    }

                    return $translations;
                }
            };
        });

        // Extend the Translator to use our custom loader
        $this->app->extend('translator', function ($service, $app) {
            return new \Illuminate\Translation\Translator($app['translation.loader'], $app->getLocale());
        });

        $this->app->alias('translator', \Illuminate\Translation\Translator::class);
    }



    public function boot() {}
}
