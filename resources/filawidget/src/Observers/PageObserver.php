<?php

namespace Filawidget\Observers;

use Filawidget\Models\Page;
use Illuminate\Support\Str;

class PageObserver
{
    public function creating(Page $page)
    {
        $page->slug = Str::slug($page->title, '-');
    }
}
