<?php

namespace App\Providers;

use Illuminate\Translation\TranslationServiceProvider as BaseTranslationServiceProvider;
use App\Translation\DatabaseTranslationLoader;
use Illuminate\Translation\Translator;

class TranslationServiceProvider extends BaseTranslationServiceProvider
{
    public function register()
    {
        // Override the default translation loader
        $this->app->singleton('translation.loader', function ($app) {
            return new DatabaseTranslationLoader($app['files'], $app['path.lang']);
        });

        // Override the default translator
        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];
            $locale = $app['config']['app.locale'];
            $fallback = $app['config']['app.fallback_locale'];

            $trans = new class($loader, $locale) extends Translator {
                /**
                 * Get the translation for the given key.
                 *
                 * @param  string  $key
                 * @param  array  $replace
                 * @param  string|null  $locale
                 * @param  bool  $fallback
                 * @return string|array
                 */
                public function get($key, array $replace = [], $locale = null, $fallback = true)
                {
                    $translation = parent::get($key, $replace, $locale, $fallback);

                    // If the result is an array, it means the translation wasn't found
                    // So we'll return the key name instead
                    if (is_array($translation)) {
                        return $key;
                    }

                    return $translation;
                }
            };

            $trans->setFallback($fallback);

            return $trans;
        });
    }

    public function boot()
    {
        // Clear any cached translations
        if ($this->app->runningInConsole()) {
            $this->app['cache']->forget('translations');
        }
    }
}
