<?php

use Filawidget\Http\Controllers\WidgetController;
use Illuminate\Support\Facades\Route;

// Route to update the order of widgets
Route::post('/widgets/update-order', [WidgetController::class, 'updateOrder'])->name('widgets.updateOrder');
