<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        $language = Language::where('code', $locale)
            ->where('is_active', true)
            ->first();

        if (!$language) {
            abort(404);
        }

        session()->put('locale', $locale);
        return redirect()->back();
    }
}
