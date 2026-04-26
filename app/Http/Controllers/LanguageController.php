<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Available languages
     */
    private $availableLanguages = [
        'en' => ['name' => 'English', 'native' => 'English', 'flag' => '🇬🇧'],
        'sw' => ['name' => 'Swahili', 'native' => 'Kiswahili', 'flag' => '🇹🇿'],
    ];

    /**
     * Switch application language
     */
    public function switch(Request $request)
    {
        $request->validate([
            'language' => 'required|in:en,sw',
        ]);

        $locale = $request->input('language');

        // Set locale in session
        Session::put('locale', $locale);

        // Set locale for current request
        App::setLocale($locale);

        // If user is logged in, save preference to database
        if (auth()->check()) {
            auth()->user()->update(['preferred_language' => $locale]);
        }

        return redirect()->back()->with('success', __('messages.language_switched'));
    }

    /**
     * Get current language info
     */
    public static function getCurrentLanguage()
    {
        $locale = Session::get('locale', auth()->user()?->preferred_language ?? 'en');
        
        $languages = [
            'en' => ['name' => 'English', 'native' => 'English', 'flag' => '🇬🇧', 'code' => 'EN'],
            'sw' => ['name' => 'Swahili', 'native' => 'Kiswahili', 'flag' => '🇹🇿', 'code' => 'SW'],
        ];

        return $languages[$locale] ?? $languages['en'];
    }

    /**
     * Get all available languages
     */
    public static function getAvailableLanguages()
    {
        return [
            'en' => ['name' => 'English', 'native' => 'English', 'flag' => '🇬🇧', 'code' => 'EN'],
            'sw' => ['name' => 'Swahili', 'native' => 'Kiswahili', 'flag' => '🇹🇿', 'code' => 'SW'],
        ];
    }
}
