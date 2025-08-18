<div>
    <style>
        .container {
            padding: 1.5rem;
        }

        .header-container {
            margin-bottom: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        @media (min-width: 768px) {
            .header-container {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
        }

        .title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        @media (min-width: 768px) {
            .title {
                margin-bottom: 0;
            }
        }

        .filters-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        @media (min-width: 768px) {
            .filters-container {
                flex-direction: row;
            }
        }

        .form-group {
            width: 100%;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .form-select,
        .form-input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }

        .form-select:focus,
        .form-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
        }

        .table-container {
            overflow-x: auto;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            width: 100%;
        }

        table {
            min-width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        thead {
            background-color: #f9fafb;
        }

        th {
            padding: 0.75rem 1.5rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            color: #6b7280;
            width: calc(100% / 3);
        }

        th.key-header {
            width: calc(100% / 3);
        }

        tbody tr {
            border-top: 1px solid #e5e7eb;
        }

        td {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            color: #374151;
            width: calc(100% / 3);
            word-break: break-word;
            vertical-align: top;
        }

        td.key-cell {
            position: sticky;
            left: 0;
            background-color: white;
            font-weight: 500;
            z-index: 10;
            width: calc(100% / 3);
            padding-top: 1.5rem; /* Align better with textareas */
        }

        .translation-input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            resize: vertical;
            font-family: inherit;
        }

        .translation-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
        }

        .actions-container {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            justify-content: flex-start;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.625rem;
            border: none;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-primary {
            background-color: #4f46e5;
            color: white;
        }

        .btn-primary:hover {
            background-color: #4338ca;
        }

        .btn-danger {
            background-color: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background-color: #dc2626;
        }

        .notification-container {
            position: fixed;
            top: 5rem;
            right: 1rem;
            z-index: 50;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        /* Modal styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 40;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            width: 100%;
            max-width: 32rem;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
        }

        .close-button {
            background: transparent;
            border: none;
            color: #6b7280;
            cursor: pointer;
            font-size: 1.5rem;
            line-height: 1;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            padding: 1rem 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .add-btn {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.5rem 0.75rem;
            background-color: #4f46e5;
            color: white;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
        }

        .add-btn:hover {
            background-color: #4338ca;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }
    </style>

    <!-- Include the notification component -->

    <div class="container">
        <x-notification />
        <div class="header-container">
            <div>
                <h1 class="title">{{ __('Translations Management') }}</h1>
            </div>

            <div class="filters-container">
                <div class="form-group">
                    <label for="group" class="form-label">{{ __('Filter by Group') }}</label>
                    <select id="group" wire:model.live="filterGroup" class="form-select">
                        <option value="">{{ __('All Groups') }}</option>
                        @foreach ($this->groups as $group)
                            <option value="{{ $group }}">{{ $group }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="search" class="form-label">{{ __('Search Keys') }}</label>
                    <input type="text" id="search" class="form-input"
                        placeholder="{{ __('Search...')}}" onkeyup="filterTranslations()">
                </div>

                <button type="button" class="add-btn" wire:click="$set('showAddModal', true)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('Add New Translation') }}
                </button>

                <button type="button" class="add-btn" wire:click="clearCache" wire:loading.attr="disabled" wire:target="clearCache" :disabled="$isClearing">
                    <div wire:loading.remove wire:target="clearCache">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <div wire:loading wire:target="clearCache">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                    stroke-dasharray="42 64"
                                    stroke-linecap="round">
                            </circle>
                        </svg>
                    </div>
                    {{ __('Clear Cache') }}
                </button>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th class="key-header">
                            {{ __('Key') }}
                        </th>
                        @foreach ($languages as $lang)
                            <th>
                                {{ $lang->name }} ({{ $lang->code }})
                            </th>
                        @endforeach
                        <th class="actions-header" style="width: auto; min-width: 120px;">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($translationData as $item)
                        <tr>
                            <td class="key-cell">
                                {{ $item['key'] }}
                            </td>
                            <form wire:submit.prevent="saveTranslation('{{ $item['key_id'] }}', '{{ addslashes($item['key']) }}')">
                            @foreach ($languages as $lang)
                                <td>
                                    <textarea
                                        wire:model="editableTranslations.{{ $item['key_id'] }}.{{ $lang->code }}"
                                        class="translation-input">
                                    </textarea>
                                </td>
                            @endforeach
                            <td>
                                <div class="actions-container">
                                    <button type="submit"
                                        class="btn btn-primary">
                                        {{ __('Save') }}
                                    </button>
                                    <button type="button" wire:click.prevent="deleteTranslation('{{ $item['key_id'] }}', '{{ addslashes($item['key']) }}')"
                                        class="btn btn-danger"
                                        onclick="return confirm('{{ __('Are you sure you want to delete this translation?') }}')">
                                        {{ __('Delete') }}
                                    </button>
                                </div>
                            </td>
                            </form>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($languages) + 2 }}" style="text-align: center; padding: 1rem;">
                                {{ __('No translations found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Add Translation Modal -->
        @if($showAddModal)
        <div class="modal-overlay" wire:click.self="$set('showAddModal', false)">
            <div class="modal">
                <div class="modal-header">
                    <h3 class="modal-title">{{ __('Add New Translation') }}</h3>
                    <button type="button" class="close-button" wire:click="$set('showAddModal', false)">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-4">
                        <label for="new-key" class="form-label">{{ __('Key Name') }}</label>
                        <input type="text" id="new-key" wire:model="newTranslation.key" class="form-input"
                               placeholder="{{ __('Enter translation key') }}" required>
                    </div>

                    @foreach ($languages as $lang)
                        <div class="form-group mb-4">
                            <label for="new-translation-{{ $lang->code }}" class="form-label">
                                {{ $lang->name }} ({{ $lang->code }})
                            </label>
                            <textarea id="new-translation-{{ $lang->code }}"
                                   wire:model="newTranslation.values.{{ $lang->code }}"
                                   class="form-input translation-input"
                                   placeholder="{{ __('Enter translation for :language', ['language' => $lang->name]) }}"></textarea>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" wire:click="$set('showAddModal', false)">
                        {{ __('Cancel') }}
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="addTranslation">
                        {{ __('Add Translation') }}
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>

    @push('scripts')
        <script>
            document.addEventListener('livewire:initialized', () => {
                // Notification handler
                @this.on('showNotification', (data) => {
                    const type = data[0] || 'general';
                    const message = data[1] || '';

                    showNotification(type, message);
                });

                function showNotification(type, message) {
                    const notification = document.createElement('div');
                    notification.className = 'bg-white dark:bg-gray-800 p-4 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700';

                    const icon = type === 'success'
                        ? `<svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                          </svg>`
                        : `<svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                          </svg>`;

                    notification.innerHTML = `
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                ${icon}
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    ${message}
                                </p>
                            </div>
                        </div>
                    `;

                    // Create or get notification container
                    let container = document.querySelector('.notification-container');
                    if (!container) {
                        container = document.createElement('div');
                        container.className = 'notification-container';
                        document.body.appendChild(container);
                    }

                    container.appendChild(notification);
                    setTimeout(() => {
                        notification.remove();
                        // Remove container if it's empty
                        if (container.children.length === 0) {
                            container.remove();
                        }
                    }, 5000);
                }
            });

            // Client-side table filtering
            function filterTranslations() {
                const searchInput = document.getElementById('search');
                const filter = searchInput.value.toLowerCase();
                const table = document.querySelector('.table-container table');
                const rows = table.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    const keyCell = row.querySelector('.key-cell');
                    const translationCells = row.querySelectorAll('textarea');
                    let shouldShow = false;

                    // Check if key contains the search term
                    if (keyCell && keyCell.textContent.toLowerCase().includes(filter)) {
                        shouldShow = true;
                    }

                    // Check if any translation contains the search term
                    if (!shouldShow && translationCells.length > 0) {
                        translationCells.forEach(cell => {
                            if (cell.value.toLowerCase().includes(filter)) {
                                shouldShow = true;
                            }
                        });
                    }

                    // Show or hide the row
                    row.style.display = shouldShow ? '' : 'none';
                });

                // Show "No translations found" message if all rows are hidden
                const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
                const emptyMessage = table.querySelector('tbody tr.empty-message');

                if (visibleRows.length === 0) {
                    if (!emptyMessage) {
                        const colSpan = table.querySelectorAll('thead th').length;
                        const emptyRow = document.createElement('tr');
                        emptyRow.className = 'empty-message';
                        emptyRow.innerHTML = `<td colspan="${colSpan}" style="text-align: center; padding: 1rem;">{{ __('No matches found') }}</td>`;
                        table.querySelector('tbody').appendChild(emptyRow);
                    } else {
                        emptyMessage.style.display = '';
                    }
                } else if (emptyMessage) {
                    emptyMessage.style.display = 'none';
                }
            }

            // Initialize search on page load
            document.addEventListener('DOMContentLoaded', () => {
                // Add input event listener (handles paste events better than keyup)
                const searchInput = document.getElementById('search');
                if (searchInput) {
                    searchInput.addEventListener('input', filterTranslations);
                }
            });
        </script>
    @endpush
</div>
