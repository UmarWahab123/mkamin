<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \Filawidget\Models\Page::class => \App\Policies\PagePolicy::class,
        \Filawidget\Models\Widget::class => \App\Policies\WidgetPolicy::class,
        \Filawidget\Models\WidgetType::class => \App\Policies\WidgetTypePolicy::class,
        \Filawidget\Models\WidgetArea::class => \App\Policies\WidgetAreaPolicy::class,
        \Filawidget\Models\Field::class => \App\Policies\FieldPolicy::class,
        \App\Models\StaffPosition::class => \App\Policies\StaffPositionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
