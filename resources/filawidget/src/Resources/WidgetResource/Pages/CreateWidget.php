<?php

namespace Filawidget\Resources\WidgetResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Filawidget\Resources\WidgetResource;
use Filawidget\Models\WidgetField;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CreateWidget extends CreateRecord
{
    protected static string $resource = WidgetResource::class;

    // Add method to manipulate form data before creating
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate a slug from the name field
        $data['slug'] = Str::slug($data['name']);

        // Remove fieldsIds from data as it's not a database column
        if (isset($data['fieldsIds'])) {
            unset($data['fieldsIds']);
        }

        // Remove values as they will be handled separately
        if (isset($data['values'])) {
            unset($data['values']);
        }

        return $data;
    }

    // This is a hook method that will be called by the parent class
    protected function afterCreate(): void
    {
        $widgetId = $this->record->id;
        $data = $this->form->getRawState();
        $fieldsIds = $data['fieldsIds'] ?? [];

        // Get field types
        $fieldTypes = [];
        if (!empty($fieldsIds)) {
            $fields = \Filawidget\Models\Field::whereIn('id', $fieldsIds)
                ->get(['id', 'type'])
                ->keyBy('id');

            foreach ($fields as $field) {
                $fieldTypes[$field->id] = $field->type;
            }
        }

        // Extract values from repeater
        $values = [];
        if (isset($data['values']) && is_array($data['values'])) {
            $repeaterItem = reset($data['values']);
            if (is_array($repeaterItem)) {
                foreach ($repeaterItem as $key => $value) {
                    if (preg_match('/^field_(\d+)$/', $key, $matches)) {
                        $fieldId = (int)$matches[1];
                        $values[$fieldId] = $value;
                    }
                }
            }
        }

        // Save field values
        if (!empty($values)) {
            foreach ($values as $fieldId => $value) {
                $fieldType = $fieldTypes[$fieldId] ?? 'text';

                // Process value based on field type
                if (in_array($fieldType, ['file', 'image']) && is_array($value)) {
                    // Safer handling of file upload arrays
                    if (!empty($value)) {
                        // Check if it's an associative array (like what Filament returns)
                        if (isset($value['name']) || isset($value['path'])) {
                            // It's a single file in associative array format
                            $value = json_encode($value);
                        }
                        // Check if it's a numerically indexed array
                        else if (isset($value[0])) {
                            // If there's just one file, use it directly
                            if (count($value) === 1) {
                                // Check if the first item is a string (path) or an array (file data)
                                if (is_string($value[0])) {
                                    $value = $value[0];
                                } else {
                                    $value = json_encode($value);
                                }
                            } else {
                                // Multiple files
                                $value = json_encode($value);
                            }
                        } else {
                            // Some other type of array, just encode it
                            $value = json_encode($value);
                        }
                    } else {
                        // Empty array, set to empty string
                        $value = '';
                    }
                } elseif (is_array($value)) {
                    $value = json_encode($value);
                }

                // Create the widget field
                WidgetField::create([
                    'widget_id' => $widgetId,
                    'widget_field_id' => $fieldId,
                    'value' => $value,
                ]);
            }
        }
    }
}
