<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class WidgetHelper
{
    /**
     * Get a specific field value from a widget by field name
     */
    public static function getFieldValue($widget, $fieldName, $default = '')
    {
        if (!$widget || empty($widget->values)) {
            return $default;
        }

        foreach ($widget->values as $fieldValue) {
            $currentFieldName = $fieldValue->field->name ?? '';
            if ($currentFieldName == $fieldName) {
                return $fieldValue->value;
            }
        }

        return $default;
    }

    /**
     * Get image URL from a JSON or string value
     */
    public static function getImageUrl($imageValue, $default = '')
    {
        if (empty($imageValue)) {
            return $default;
        }

        $imgPath = $imageValue;
        if (is_string($imageValue) && (str_starts_with($imageValue, '{') || str_starts_with($imageValue, '['))) {
            $decoded = json_decode($imageValue, true);
            if (json_last_error() === JSON_ERROR_NONE && !empty($decoded)) {
                if (is_array($decoded)) {
                    $imgPath = is_string(array_values($decoded)[0]) ? array_values($decoded)[0] : '';
                }
            }
        }

        return !empty($imgPath) ? Storage::url($imgPath) : $default;
    }
}
