<?php

namespace Filawidget\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WidgetField extends Model
{
    public $incrementing = false;

    protected $primaryKey = ['widget_id', 'widget_field_id'];

    public $timestamps = false;

    protected $fillable = [
        'value',
        'widget_id',
        'widget_field_id',
    ];

    protected $casts = [
        'value' => 'string',
    ];

    // Add relationship methods
    public function widget(): BelongsTo
    {
        return $this->belongsTo(Widget::class, 'widget_id');
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class, 'widget_field_id');
    }

    /**
     * Set the keys for a save update query.
     * This is a fix for composite primary keys in Laravel.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery($query)
    {
        return $query->where('widget_id', $this->getAttribute('widget_id'))
                     ->where('widget_field_id', $this->getAttribute('widget_field_id'));
    }
}
