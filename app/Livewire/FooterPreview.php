<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FooterSetting;

class FooterPreview extends Component
{
    public $social_links = [];
    public $navigation_links = [];
    public $copyright_text = '';
    public $designer_text = '';
    public $designer_url = '';
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
            $this->social_links = $this->record->social_links ?? [];
            $this->navigation_links = $this->record->navigation_links ?? [];
            $this->copyright_text = $this->record->copyright_text ?? '2025 mcs.sa. All Rights Reserved';
            $this->designer_text = $this->record->designer_text ?? 'Designed by SWU';
            $this->designer_url = $this->record->designer_url ?? '#';
        } else {
            // Create mode - use defaults
            $this->social_links = [];
            $this->navigation_links = [];
            $this->copyright_text = '2025 mcs.sa. All Rights Reserved';
            $this->designer_text = 'Designed by SWU';
            $this->designer_url = '#';
        }
    }

    public function updatePreview($data)
    {
        if (isset($data['social_links'])) {
            $this->social_links = is_array($data['social_links']) ? $data['social_links'] : [];
        }
        
        if (isset($data['navigation_links'])) {
            $this->navigation_links = is_array($data['navigation_links']) ? $data['navigation_links'] : [];
        }
        
        if (isset($data['copyright_text'])) {
            $this->copyright_text = $data['copyright_text'] ?: '2025 mcs.sa. All Rights Reserved';
        }
        
        if (isset($data['designer_text'])) {
            $this->designer_text = $data['designer_text'] ?: 'Designed by SWU';
        }
        
        if (isset($data['designer_url'])) {
            $this->designer_url = $data['designer_url'] ?: '#';
        }
    }

    public function handleFormDataChange($formData)
    {
        // Handle complete form data updates
        $this->social_links = $formData['social_links'] ?? [];
        $this->navigation_links = $formData['navigation_links'] ?? [];
        $this->copyright_text = $formData['copyright_text'] ?? '2025 mcs.sa. All Rights Reserved';
        $this->designer_text = $formData['designer_text'] ?? 'Designed by SWU';
        $this->designer_url = $formData['designer_url'] ?? '#';
    }

    public function render()
    {
        return view('livewire.footer-preview');
    }
}
