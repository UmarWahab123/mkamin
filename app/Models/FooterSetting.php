<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'social_links',
        'navigation_links',
        'copyright_text',
        'designer_text',
        'designer_url'
    ];

    protected $casts = [
        'social_links' => 'array',
        'navigation_links' => 'array',
    ];

    public static function socialPlatforms()
    {
        return [
            'facebook' => ['icon' => 'fa-facebook', 'name' => 'Facebook'],
            'instagram' => ['icon' => 'fa-instagram', 'name' => 'Instagram'],
            'tiktok' => ['icon' => 'fa-tiktok', 'name' => 'TikTok'],
            'snapchat' => ['icon' => 'fa-snapchat', 'name' => 'Snapchat'],
            'linkedin' => ['icon' => 'fa-linkedin', 'name' => 'LinkedIn'],
            'youtube' => ['icon' => 'fa-youtube', 'name' => 'YouTube'],
            'twitter' => ['icon' => 'fa-twitter', 'name' => 'Twitter'],
            'pinterest' => ['icon' => 'fa-pinterest', 'name' => 'Pinterest'],
            'whatsapp' => ['icon' => 'fa-whatsapp', 'name' => 'WhatsApp'],
        ];
    }
}
