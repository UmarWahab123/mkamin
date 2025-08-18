<?php

namespace App\Livewire;

use App\Models\Language;
use App\Models\Translation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ViewTranslations extends Component
{
    public Collection $languages;
    public Collection $translationData;
    public ?string $filterGroup = null;
    public string $search = '';
    public $editableTranslations = [];
    public bool $showAddModal = false;
    public bool $isClearing = false;
    public $newTranslation = [
        'key' => '',
        'values' => []
    ];

    public function mount()
    {
        $this->languages = Language::where('is_active', true)->orderBy('name')->get();
        $this->loadTranslations();
    }

    public function loadTranslations()
    {
        // Start building the base query
        $baseQuery = Translation::query();

        if ($this->filterGroup) {
            $baseQuery->where('group', $this->filterGroup);
        }

        // Apply search filter if provided
        if ($this->search && trim($this->search) !== '') {
            $searchTerm = '%' . $this->search . '%';

            // Clone the base query for getting keys matching search criteria
            $keyQuery = clone $baseQuery;

            // Get keys that match the search term directly in the key_name
            $keysByName = $keyQuery->where('key_name', 'like', $searchTerm)
                                   ->distinct()
                                   ->pluck('key_name');

            // Get keys that have translations matching the search term
            $keysByTranslation = Translation::where('translation', 'like', $searchTerm)
                                          ->when($this->filterGroup, function($query) {
                                              $query->where('group', $this->filterGroup);
                                          })
                                          ->distinct()
                                          ->pluck('key_name');

            // Merge both sets of keys
            $allKeys = $keysByName->merge($keysByTranslation)->unique();
        } else {
            // No search term, get all keys based on filter
            $allKeys = $baseQuery->distinct()->pluck('key_name');
        }

        // Initialize translation data
        $this->translationData = collect();
        $this->editableTranslations = []; // Reset to avoid stale data

        // Get all translations for these keys
        $translations = Translation::whereIn('key_name', $allKeys)
            ->with('language')
            ->get();

        // Group by key_name
        $translationsByKey = [];
        foreach ($translations as $translation) {
            if (!isset($translationsByKey[$translation->key_name])) {
                $translationsByKey[$translation->key_name] = [
                    'key' => $translation->key_name,
                    'key_id' => md5($translation->key_name),
                    'group' => $translation->group,
                ];
            }
            $translationsByKey[$translation->key_name][$translation->language->code] = $translation->translation;
        }

        // Create the data collection and setup editable translations
        foreach ($translationsByKey as $key => $data) {
            $this->translationData->push($data);
            $keyId = $data['key_id'];

            // Initialize translations for all languages
            $this->editableTranslations[$keyId] = [];
            foreach ($this->languages as $language) {
                $langCode = $language->code;
                $this->editableTranslations[$keyId][$langCode] = $data[$langCode] ?? null;
            }
        }
    }

    public function addTranslation()
    {
        $keyName = trim($this->newTranslation['key']);

        if (empty($keyName)) {
            $this->dispatch('showNotification', 'error', __('Key name is required!'));
            return;
        }

        // Check for existing translation with the same key
        $exists = Translation::where('key_name', $keyName)->exists();
        if ($exists) {
            $this->dispatch('showNotification', 'error', __('Translation key already exists!'));
            return;
        }

        try {
            // Use a database transaction to ensure all updates succeed or fail together
            DB::beginTransaction();

            $group = $this->filterGroup ?: 'home'; // Use selected group or default to 'home'

            foreach ($this->languages as $language) {
                $translationValue = $this->newTranslation['values'][$language->code] ?? null;

                if ($translationValue !== null && trim($translationValue) !== '') {
                    Translation::create([
                        'language_id' => $language->id,
                        'key_name' => $keyName,
                        'group' => $group,
                        'translation' => $translationValue,
                    ]);
                }
            }

            // Commit the transaction
            DB::commit();

            // Reset form values
            $this->newTranslation = [
                'key' => '',
                'values' => []
            ];

            // Close the modal
            $this->showAddModal = false;

            // Reload translations to reflect changes
            $this->loadTranslations();

            $this->dispatch('showNotification', 'success', __('Translation added successfully!'));

        } catch (\Exception $e) {
            // Roll back the transaction if any operation fails
            DB::rollBack();

            $this->dispatch('showNotification', 'error', __('Error adding translation: ') . $e->getMessage());
        }
    }

    public function saveTranslation($keyId, $originalKey)
    {
        if (!isset($this->editableTranslations[$keyId])) {
            return;
        }

        // Find the group
        $group = '';
        foreach ($this->translationData as $item) {
            if ($item['key_id'] === $keyId) {
                $group = $item['group'];
                break;
            }
        }

        try {
            DB::beginTransaction();

            foreach ($this->languages as $language) {
                $translationValue = $this->editableTranslations[$keyId][$language->code] ?? null;

                $translation = Translation::where('language_id', $language->id)
                    ->where('key_name', $originalKey)
                    ->first();

                if ($translation) {
                    $translation->update([
                        'translation' => $translationValue,
                    ]);
                } else if ($translationValue !== null) {
                    Translation::create([
                        'language_id' => $language->id,
                        'key_name' => $originalKey,
                        'group' => $group,
                        'translation' => $translationValue,
                    ]);
                }
            }

            DB::commit();
            $this->dispatch('showNotification', 'success', __('Translation saved successfully!'));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showNotification', 'error', __('Error saving translation: ') . $e->getMessage());
        }
    }

    public function deleteTranslation($keyId, $originalKey)
    {
        try {
            DB::beginTransaction();

            Translation::where('key_name', $originalKey)->delete();

            DB::commit();

            // Remove from the local data
            $this->translationData = $this->translationData->filter(function($item) use ($keyId) {
                return $item['key_id'] !== $keyId;
            });

            unset($this->editableTranslations[$keyId]);

            $this->dispatch('showNotification', 'success', __('Translation deleted successfully!'));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showNotification', 'error', __('Error deleting translation: ') . $e->getMessage());
        }
    }

    public function updatedFilterGroup()
    {
        $this->loadTranslations();
    }

    public function updatedSearch()
    {
        $this->loadTranslations();
    }

    public function getGroupsProperty()
    {
        return Translation::select('group')->distinct()->pluck('group');
    }

    public function render()
    {
        return view('livewire.view-translations');
    }

    // Add this method to update editableTranslations when a user types
    public function updated($field, $value)
    {
        // Process only updates to editableTranslations
        if (strpos($field, 'editableTranslations.') === 0) {
            // No need to call loadTranslations here, as it would reset the values
        }
    }

    /**
     * Clear the application cache using the optimize:clear command
     */
    public function clearCache()
    {
        $this->isClearing = true;

        try {
            // Check if shell_exec is available
            if (!function_exists('shell_exec')) {
                // If shell_exec is not available, use the fallback directly
                $this->clearCacheFallback();
                $this->loadTranslations();
                $this->dispatch('showNotification', 'success', __('Cache cleared successfully!'));
                $this->isClearing = false;
                return;
            }

            // Get the base path of the Laravel application
            $basePath = base_path();

            // Run more specific cache clearing commands
            $commands = [
                'php artisan cache:clear',
                'php artisan view:clear',
                // Specific translation cache clearing
                'php artisan translator:flush-cache'
            ];

            $output = '';
            $cmdSuccess = false;

            foreach ($commands as $command) {
                // Execute command from the project root with full PHP path
                $result = shell_exec("cd {$basePath} && {$command} 2>&1");
                if ($result) {
                    $output .= $result;
                    $cmdSuccess = true;
                }
            }

            // Fallback to direct cache clearing if shell commands fail
            if (!$cmdSuccess) {
                $this->clearCacheFallback();
            }

            // Reset translations data
            $this->loadTranslations();

            $this->dispatch('showNotification', 'success', __('Cache cleared successfully!'));
        } catch (\Exception $e) {
            // Try fallback method
            try {
                $this->clearCacheFallback();
                $this->loadTranslations();
                $this->dispatch('showNotification', 'success', __('Cache cleared using fallback method!'));
            } catch (\Exception $innerEx) {
                $this->dispatch('showNotification', 'error', __('Error clearing cache: ') . $e->getMessage());
            }
        }

        $this->isClearing = false;
    }

    /**
     * Fallback method to clear cache using Laravel's Facade
     */
    private function clearCacheFallback()
    {
        // Clear Laravel's cache
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');

        // Clear the application cache
        \Illuminate\Support\Facades\Cache::flush();

        // Clear configuration cache
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }
    }
}
