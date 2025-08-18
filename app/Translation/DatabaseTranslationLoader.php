<?php

namespace App\Translation;

use Illuminate\Translation\FileLoader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Language;
use App\Models\Translation;

class DatabaseTranslationLoader extends FileLoader
{
    public function load($locale, $group, $namespace = null)
    {

        // Load file-based translations
        $translations = parent::load($locale, $group, $namespace);

        // Try fetching from database
        try {

            $languageId = Language::where('code', $locale)->value('id');

            if ($languageId) {

                // Fetch translations from database
                $dbTranslations = Cache::remember("translations.{$locale}", 60, function () use ($languageId) {
                    return Translation::where('language_id', $languageId)
                        ->pluck('translation', 'key_name')
                        ->toArray();
                });


                // Merge DB translations with file translations
                $translations = array_merge($translations, $dbTranslations);
            } else {
                Log::warning("❌ No language ID found for locale: {$locale}");
            }
        } catch (\Exception $e) {
            Log::error("🔥 DB Translation Error: " . $e->getMessage());
        }

        return $translations;
    }
}
