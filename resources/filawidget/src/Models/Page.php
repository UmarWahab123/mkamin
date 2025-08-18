<?php

namespace Filawidget\Models;

use Filawidget\Observers\PageObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'content', 'status', 'order', 'child_order', 'parent_id'];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeFather($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeChild($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeChildOrdered($query)
    {
        return $query->orderBy('child_order');
    }

    public static function updateOrder(array $order)
    {
        foreach ($order as $index => $id) {
            self::where('id', $id)->update(['order' => $index + 1]);
        }
    }

    public function isRoot()
    {
        return is_null($this->parent_id) || $this->parent_id === '';
    }

    public function children()
    {
        return $this->hasMany(Page::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }
}
