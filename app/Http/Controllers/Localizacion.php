<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class Localizacion extends Controller
{
    public function cambiarIdioma($idioma)
    {   
        App::setLocale($idioma);
        session()->put('idioma', $idioma);
        return back();
    }
}
