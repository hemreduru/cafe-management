<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DarkModeController extends Controller
{
    public function toggle(Request $request)
    {
        $darkMode = Session::get('darkmode', true);
        Session::put('darkmode', !$darkMode);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }

    public function isEnabled()
    {
        return Session::get('darkmode', true);
    }
}
