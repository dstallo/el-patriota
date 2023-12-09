<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class Dashboard extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.dashboard');
    }


    public function subirTiny(Request $request)
    {
        $reglas = [
            'imagen' => 'required|file|mimes:jpg,jpeg,png|max:512',
        ];
        if(($validator = Validator::make($request->all(), $reglas))->fails()) {
            return response('', 500);
        }

        $archivo = $request->file('imagen');

        $nombre=str_slug(pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME)); //simplifico el nombre
        $nombre=$nombre.'-'.str_random(5); //lo vuelvo único
        $nombre=$nombre.'.'.$archivo->getClientOriginalExtension();//le agrego la extensión
        $ruta = Storage::disk(config('filesystems.contenido'))->putFileAs('public/tinymce', $archivo, $nombre, 'public');
        $url = url(preg_replace("/^public/", 'storage', $ruta));
        
        return response(['location' => $url], 200);
    }
}