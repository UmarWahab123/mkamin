import './bootstrap';
// Add this to your resources/js/app.js or create a separate file

document.addEventListener('DOMContentLoaded', function() {
    // Enhanced drag and drop for AboutSection table
    function initializeAboutSectionDragDrop() {
        const table = document.querySelector('[data-filament-table]');
        
        if (!table) return;
        
        // Add visual feedback for drag operations
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const dragHandle = row.querySelector('[class*="cursor-move"]');
            
            if (dragHandle) {
                // Add hover effect
                dragHandle.addEventListener('mouseenter', function() {
                    this.style.color = '#f59e0b';
                    this.style.fontSize = '18px';
                });
                
                dragHandle.addEventListener('mouseleave', function() {
                    this.style.color = '';
                    this.style.fontSize = '';
                });
                
                // Add drag start visual feedback
                row.addEventListener('dragstart', function() {
                    this.style.opacity = '0.5';
                    this.classList.add('dragging');
                });
                
                row.addEventListener('dragend', function() {
                    this.style.opacity = '';
                    this.classList.remove('dragging');
                    
                    // Show success message
                    setTimeout(() => {
                        showSuccessMessage('Section order updated successfully!');
                    }, 500);
                });
            }
        });
    }
    
    // Show success message
    function showSuccessMessage(message) {
        // Create a simple notification
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50';
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
    
    // Initialize on page load
    initializeAboutSectionDragDrop();
    
    // Re-initialize when Livewire updates the table
    document.addEventListener('livewire:navigated', initializeAboutSectionDragDrop);
    
    // For older Livewire versions
    if (window.Livewire) {
        window.Livewire.hook('message.processed', initializeAboutSectionDragDrop);
    }
});

// Add custom CSS for drag and drop
const style = document.createElement('style');
style.textContent = `
    .dragging {
        background-color: #f3f4f6 !important;
        transform: rotate(2deg);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    [data-filament-table] tbody tr:hover {
        background-color: #f9fafb;
    }
    
    .cursor-move:hover {
        transform: scale(1.1);
        transition: all 0.2s ease;
    }
`;
document.head.appendChild(style);