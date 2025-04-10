<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Dil değiştirme metodu
     *
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchLang($locale)
    {
        // Geçerli dil kontrolü (sadece tr ve en destekleniyor)
        if (!in_array($locale, ['en', 'tr'])) {
            $locale = 'en';  // Varsayılan dil
        }
        
        // Session'a dil bilgisini kaydet
        Session::put('locale', $locale);
        App::setLocale($locale);
        
        // Kullanıcıyı geldiği sayfaya geri gönder
        return redirect()->back();
    }
}
