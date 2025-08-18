<?php

namespace App\Livewire;

use Livewire\Component;

class HeaderPreview extends Component
{

    public $mobile_logo = '';
    public $desktop_logo = '';
    public $header_color  = '';
    public $is_show_language_switcher = true;
    public $navigation_links = [];
    public $record = null;

    protected $listeners = [
        'updatePreview' => 'updatePreview',
        'formDataChanged' => 'handleFormDataChange'
    ];

    public function mount($record = null)
    {
        $this->record = $record;
        $this->initializeWithDatabaseData();
    }

    public function initializeWithDatabaseData()
    {
        if ($this->record) {
            // Edit mode - load existing data
            $this->mobile_logo = $this->record->mobile_logo ?? '';
            $this->desktop_logo = $this->record->desktop_logo ?? '';
            $this->header_color = $this->record->header_color ?? '';
            $this->is_show_language_switcher = $this->record->is_show_language_switcher ?? true;
            $this->navigation_links = $this->record->navigation_links ?? [];
        } else {
            // Create mode - use defaults
            $this->mobile_logo = '';
            $this->desktop_logo = '';
            $this->header_color = '';
            $this->is_show_language_switcher = true;
            $this->navigation_links = [];
        }
    }

    public function updatePreview($data)
    {
        if (isset($data['mobile_logo'])) {
            $this->mobile_logo = $data['mobile_logo'] ?: '';
        }
        if (isset($data['desktop_logo'])) {
            $this->desktop_logo = $data['desktop_logo'] ?: '';
        }
        if (isset($data['header_color'])) {
            $this->header_color = $data['header_color'] ?: '';
        }
        if (isset($data['is_show_language_switcher'])) {
            $this->is_show_language_switcher = $data['is_show_language_switcher'] ?? true;
        }
        if (isset($data['navigation_links'])) {
            $this->navigation_links = is_array($data['navigation_links']) ? $data['navigation_links'] : [];
        }
    }

    public function handleFormDataChange($formData)
    {
        // Handle complete form data updates
        $this->mobile_logo = $formData['mobile_logo'] ?? '';
        $this->desktop_logo = $formData['desktop_logo'] ?? '';
        $this->header_color = $formData['header_color'] ?? '';
        $this->is_show_language_switcher = $formData['is_show_language_switcher'] ?? true;
        $this->navigation_links = $formData['navigation_links'] ?? [];
    }

    public function render()
    {
        return view('livewire.header-preview');
    }
}
