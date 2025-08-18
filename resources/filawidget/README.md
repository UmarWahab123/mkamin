# Customizable solution for managing dynamic content in Laravel projects using FilamentPHP.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/Filawidget.svg?style=flat-square)](https://packagist.org/packages/Filawidget)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/Filawidget/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/Filawidget/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/Filawidget/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/Filawidget/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/Filawidget.svg?style=flat-square)](https://packagist.org/packages/Filawidget)

**Filawidget** is a dynamic content and widget management package for **FilamentPHP**, providing an easy-to-use drag-and-drop interface to manage widgets, widget areas, and hierarchical pages. The package is designed to enhance the customization of page layouts and widgets in Laravel projects.

## Support us

[!["Buy Me A Coffee"](https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png)](https://buymeacoffee.com/Filawidget)

## Key Features

### Widget Management
- **Create and Customize Widgets**: Users can create widgets with custom fields, types, and configurations. Each widget can have a set of configurable fields and is tied to a specific widget area.
- **Drag-and-Drop Interface**: An easy-to-use drag-and-drop interface allows users to organize widgets within areas dynamically, rearranging widget order effortlessly.
- **Active/Inactive Widgets**: Manage the status of widgets, setting them as active or inactive based on visibility requirements.

### Widget Areas
- **Multiple Widget Areas**: Define different widget areas (e.g., sidebars, footers) to which widgets can be assigned.
- **Drag-and-Drop Layout Customization**: Users can rearrange the order of both widget areas and the widgets within them using drag-and-drop functionality, ensuring customizable page layouts.
- **Active/Inactive Widget Areas**: Control the visibility of widget areas, marking them as active or inactive depending on layout preferences.

### Page and Subpage Management
- **Hierarchical Pages**: Manage pages and subpages in a hierarchical structure with parent-child relationships. Pages can be organized into different levels of hierarchy, facilitating complex website structures.
- **Page Status**: Set pages as active or inactive based on publishing needs, allowing for controlled visibility across the website.
- **Drag-and-Drop Page Reordering**: Reorder pages and subpages easily through a drag-and-drop interface, ensuring flexibility in page hierarchy and content organization.

### Dynamic Field Configuration
- **Custom Fields for Widgets**: Add custom fields to widgets, such as text, images, or other input types, through a dynamic and configurable system.
- **JSON-Based Field Options**: Provide flexible options for fields, enabling validation rules, default values, and other configurations in JSON format.

### Widget Types
- **Custom Widget Types**: Create different types of widgets with specific functionalities, allowing for a wide range of content management options.
- **Field Association with Widget Types**: Assign different sets of fields to widget types, ensuring each widget type has the necessary input fields for customization.

### Order Management
- **Customizable Widget and Page Orders**: Users can update the order of widgets, pages, and widget areas. Each item can be repositioned dynamically, offering complete control over the structure.
- **Automated Order Updates**: Use the built-in functionality to update the order of widgets and pages across the system automatically.

### Note :
Screenshots from the client project.

<br />
<a href="https://www.youtube.com/watch?v=KyedlYpU5i0" target="_blank">Youtube Video</a>

<div align="center">
    <h1>Widgets</h1>
</div>

[<img src="https://raw.githubusercontent.com/Filawidget/main/screenshots/widgets.png" width="100%">](https://www.youtube.com/watch?v=KyedlYpU5i0)

<div align="center">
    <h1>Pages</h1>
</div>

[<img src="https://raw.githubusercontent.com/Filawidget/main/screenshots/pages.png" width="100%">](https://www.youtube.com/watch?v=KyedlYpU5i0)

<div align="center">
    <h1>Preview</h1>
</div>

[<img src="https://raw.githubusercontent.com/Filawidget/main/screenshots/preview.png" width="100%">](https://www.youtube.com/watch?v=KyedlYpU5i0)

<div align="center">
    <h1>Frontend</h1>
</div>

[<img src="https://raw.githubusercontent.com/Filawidget/main/screenshots/frontend.png" width="100%">](https://www.youtube.com/watch?v=KyedlYpU5i0)

## Installation

You can install the package via composer:

```bash
composer require Filawidget
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filawidget-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filawidget-config"
```

This is the contents of the published config file:

```php
return [
    'should_register_navigation_appearance' => true,
    'should_register_navigation_pages' => true,
    'should_register_navigation_widgets' => true,
    'should_register_navigation_widget_areas' => true,
    'should_register_navigation_fields' => true,
    'should_register_navigation_widget_types' => true,
    'show_home_link' => true,
    'show_quick_appearance' => true,
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filawidget-views"
```

Available fields of filament that can use it for create dynamic widget.

```bash
----------------------------------------
| Field Type        | Description      |
|-------------------|------------------|
| Text              | Text Field       |
| Textarea          | Textarea Field   |
| Number            | Number Input     |
| Select            | Select Dropdown  |
| Checkbox          | Checkbox         |
| Radio             | Radio Button     |
| Toggle            | Toggle Switch    |
| Color Picker      | Color Picker     |
| Date Picker       | Date Picker      |
| Date Time Picker  | Date Time Picker |
| Time Picker       | Time Picker      |
| File Upload       | File Upload      |
| Image Upload      | Image Upload     |
| Rich Editor       | Rich Text Editor |
| Markdown Editor   | Markdown Editor  |
| Tags Input        | Tags Input       |
| Password          | Password Input   |
----------------------------------------
```

## Usage

```php
// AdminPanelProvider
use Filawidget\FilaWidgetPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
            ->plugins([
                FilaWidgetPlugin::make(),
            ]);
}
```

```php
// Areas
use Filawidget\Services\AreaService;

$areas = AreaService::getAllAreas();
$areasWithOrderedWidgets = AreaService::getAllAreasWithOrderedWidgets();
$area = AreaService::getWidgetByIdentifier("Sidebar");
```

```php
// Widgets
use Filawidget\Services\WidgetService;

$widgets = WidgetService::getAllWidgets();
$widget = WidgetService::getWidgetBySlug("latest-posts");
```

```php
// Pages
use Filawidget\Services\PageService;

$pages = PageService::getAllPages();
$page = PageService::getPageBySlug("about-us");
$counts = PageService::counts();
```

```php
use Filawidget\Services\AreaService;
use Filawidget\Services\PageService;

// Route
Route::get('/', function(){
    $pages =  PageService::getAllPages();
    $areas =  AreaService::getAllAreas();

    return view('welcome',[
        'pages' => $pages,
        'areas' => $areas,
    ]);
});

// Welcome Blade
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Filament Widgets</title>
    <style>
      .widget-card {
          margin-bottom: 20px;
      }
      .widget-header {
          font-size: 1.25rem;
          font-weight: bold;
          background-color: #f8f9fa;
          padding: 10px;
          border-bottom: 1px solid #dee2e6;
      }
    </style>
  </head>
  <body>
    <div class="container mt-4">

        <div class="row px-2 py-2 mb-3 rounded border">
            <nav class="navbar navbar-expand-lg navbar-light bg-light rounded">
                <div class="container-fluid">
                  <a class="navbar-brand" href="#">Navbar</a>
                  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                  </button>
                  <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                      @foreach ($pages as $key => $page)
                        @if(count($page->children))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown{{ $key }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ $page->title }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown{{ $key }}">
                                    @foreach ($page->children as $key => $sub_page)
                                        <li>
                                            <a class="dropdown-item" href="{{ $sub_page->slug }}">
                                                {{ $sub_page->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ $page->slug }}">
                                    {{ $page->title }}
                                </a>
                            </li>
                        @endif
                      @endforeach
                    </ul>
                    <form class="d-flex">
                      <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                      <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                  </div>
                </div>
            </nav>
        </div>

        @foreach ($areas as $area)
            <div class="row px-2 py-2 mb-3 rounded border">
                @forelse ($area->widgets as $widget)
                    <div class="col-md-4 px-2 py-2">
                        <div class="card widget-card mb-0">
                            <div class="widget-header">
                                {{ $widget->name }}
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    {{ $widget->description }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 px-2 py-2">
                        <div class="card widget-card mb-0">
                            <div class="card-body bg-light">
                                <p class="card-text text-center fw-bold">No Widget Found</p>
                                <p class="card-text text-center fw-bold fs-2">˟</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        @endforeach
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
  </body>
</html>
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Ibrahim Bougaoua](https://github.com/ibrahim bougaoua)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
