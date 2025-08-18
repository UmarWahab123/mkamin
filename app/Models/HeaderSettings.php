<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeaderSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'mobile_logo',
        'desktop_logo',
        'header_color',
        'header_text_color',
        'header_text_hover_color',
        'header_text_dropdown_color',
        'header_text_dropdown_hover_color',
        'is_show_language_switcher',
        'navigation_links'
    ];

    protected $casts = [
        'navigation_links' => 'array',
    ];
}
