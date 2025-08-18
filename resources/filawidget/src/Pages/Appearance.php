<?php

namespace Filawidget\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Filawidget\Models\Page as ModelsPage;
use Filawidget\Models\Widget;
use Filawidget\Models\WidgetArea;
use Filawidget\Services\AreaService;
use Filawidget\Services\PageService;
use Filawidget\Services\WidgetService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Appearance extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static string $view = 'filawidget::pages.appearance';

    public $filter = 'widgets';

    public $pagesOrder = [];

    public $subPagesOrder = [];

    public $widgetsOrder = [];

    public $widgetAreasOrder = [];

    public $pages = [];

    public $widgets = [];

    public $widgetAreas = [];

    public $nbrWidgetAreas = 0;

    public $nbrPages = 0;

    public static function shouldRegisterNavigation(): bool
    {
        return config('filawidget.should_register_navigation_appearance');
    }

    public static function getLabel(): ?string
    {
        return __('filawidget::filawidget.Appearance');
    }

    public static function getPluralLabel(): ?string
    {
        return __('filawidget::filawidget.Appearances');
    }

    public static function getBreadcrumb(): string
    {
        return __('filawidget::filawidget.Appearance');
    }

    public static function getNavigationLabel(): string
    {
        return __('filawidget::filawidget.Appearance');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filawidget::filawidget.Appearance Management');
    }

    public function mount(Request $request)
    {
        $this->filter = $request->query('filter', 'widgets');

        $this->pages = PageService::getAllPages();
        $this->widgets = WidgetService::getAllWidgets();
        $this->widgetAreas = AreaService::getAllAreasWithOrderedWidgets();
        $this->nbrWidgetAreas = $this->widgetAreas ? count($this->widgetAreas) : 0;
        $this->nbrPages = PageService::counts();
    }

    public function updateOrder()
    {
        if (! is_array($this->widgetsOrder) || ! is_array($this->widgetAreasOrder)) {
            return response()->json(['status' => 'error', 'message' => __('filawidget::filawidget.Invalid input.')], 400);
        }

        foreach ($this->widgetAreasOrder as $index => $widgetAreaId) {
            WidgetArea::where('id', $widgetAreaId)->update(['order' => $index + 1]);
        }

        foreach ($this->widgetsOrder as $index => $widgetId) {
            Widget::where('id', $widgetId)->update(['order' => $index + 1]);
        }

        $this->widgetAreas = WidgetArea::ordered()->withOrderedWidgets()->get();

        if ($this->widgetAreasOrder != []) {
            session()->flash('areaStatus', __('filawidget::filawidget.Area order successfully updated.'));
        }

        if ($this->widgetsOrder != []) {
            session()->flash('widgetStatus', __('filawidget::filawidget.Widgets order successfully updated.'));
        }
    }

    public function updatePageOrder()
    {
        if (! is_array($this->pagesOrder) || ! is_array($this->subPagesOrder)) {
            return response()->json(['status' => 'error', 'message' => __('filawidget::filawidget.Invalid input.')], 400);
        }

        foreach ($this->pagesOrder as $index => $pageId) {
            ModelsPage::father()->where('id', $pageId)->update(['order' => $index + 1]);
        }

        foreach ($this->subPagesOrder as $index => $pageId) {
            ModelsPage::child()->where('id', $pageId)->update(['child_order' => $index + 1]);
        }

        $this->pages = PageService::getAllPages();

        session()->flash('pageStatus', __('filawidget::filawidget.Pages order successfully updated.'));
    }

    public function hideAlert()
    {
        session()->flash('pageStatus', null);
        session()->flash('areaStatus', null);
        session()->flash('widgetStatus', null);
    }

    public function handleOrderUpdate()
    {
        if ($this->filter === 'widgets') {
            $this->updateOrder();
        } else {
            $this->updatePageOrder();
        }
    }

    public function getHeader(): ?View
    {
        return view('filawidget::components.header', [
            'filter' => $this->filter,
        ]);
    }

    public function getFooter(): ?View
    {
        return $this->filter !== 'preview' ? view('filawidget::components.footer') : null;
    }
}
