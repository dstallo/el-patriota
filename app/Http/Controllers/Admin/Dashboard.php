<?php

namespace App\Http\Controllers\Admin;

use App\Configuracion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Noticia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use App\Axys\AxysFlasher as Flasher;

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
        $grupos = Noticia::obtenerGrupos();
        $configuraciones = Configuracion::obtener();

        return view('admin.dashboard', compact('grupos', 'configuraciones'));
    }

    public function guardar(Request $request, $id = null)
    {
        $form = $this->validate($request, [
            'grupo' => ['nullable', Rule::exists('noticias', 'grupo')],
        ], [], [
            'grupo'  => 'grupo de noticias'
        ]);

        $configuracion = Configuracion::obtener('GRUPO_ACTIVO');
        if (! $configuracion) {
            Flasher::set('No existe la configuración de Grupo Activo.', 'Configuración inexistente', 'error')->flashear();
            return redirect()->route('home');
        }
        
        $configuracion->valor = $form['grupo'] ?? null;
        $configuracion->save();

        Flasher::set('Configuraciones actualizadas exitosamente.', 'Configuraciones actualizadas', 'success')->flashear();

        return redirect()->route('home');
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

        $nombre=Str::random(pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME)); //simplifico el nombre
        $nombre=$nombre.'-'.Str::random(5); //lo vuelvo único
        $nombre=$nombre.'.'.$archivo->getClientOriginalExtension();//le agrego la extensión
        $ruta = Storage::disk(config('filesystems.contenido'))->putFileAs('public/tinymce', $archivo, $nombre, 'public');
        $url = url(preg_replace("/^public/", 'storage', $ruta));
        
        return response(['location' => $url], 200);
    }
}