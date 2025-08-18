document.addEventListener('livewire:init', () => {
  // Listen for form updates and send to preview component
  Livewire.hook('element.updated', (el, component) => {
    if (component.name === 'filament-forms-form-component') {
      const formData = component.get('data');
      Livewire.dispatch('updatePreviewState', { state: formData });
    }
  });
});
