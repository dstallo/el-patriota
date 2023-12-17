<?php

namespace App\Http\Controllers\Admin;

use App\Axys\AxysFlasher as Flasher;
use App\Axys\AxysListado as Listado;
use App\Http\Controllers\Controller;
use App\Popup;
// use App\Axys\Traits\TieneVisibilidad;

use Illuminate\Http\Request;

class Popups extends Controller
{
    // use TieneVisibilidad;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Popup::orderBy('id', 'desc');

        $listado = new Listado(
            'popups',
            $query,
            $request,
            [],
            [
                'buscando' => [
                    ['campo' => 'nombre', 'comparacion' => 'like'],
                ],
                'buscando_id' => [
                    ['campo' => 'id', 'comparacion' => 'igual'],
                ],
            ]
        );

        $popups = $listado->paginar(50);
        // $popups=$listado->get();

        return view('admin.popups.index', compact('popups', 'listado'));
    }

    public function eliminar(Popup $popup)
    {
        try {
            $popup->delete();
            $flasher = Flasher::set('El popup fue eliminado.', 'Popup Eliminado', 'success');
        } catch (\Exception $e) {
            $flasher = Flasher::set('No se pudo borrar el popup, ya tiene contenido asociado.', 'Error', 'error');
        }
        $flasher->flashear();

        return redirect()->back();
    }

    public function eliminarArchivo(Popup $popup, $campo)
    {
        $popup->eliminarArchivo($campo)->save();
        Flasher::set("Se eliminó el archivo $campo", 'Archivo Eliminado', 'success')->flashear();

        return back();
    }

    public function crear(Request $request)
    {
        $popup = new Popup();

        return view('admin.popups.crear', compact('popup'));
    }

    public function editar(Popup $popup, Request $request)
    {
        return view('admin.popups.editar', compact('popup'));
    }

    public function guardar(Request $request, $id = null)
    {
        $this->validate($request, [
            'nombre' => 'required',
            'imagen' => 'file|mimes:jpeg,png,jpg|max:1024',
            'imagen_vertical' => 'file|mimes:jpeg,png,jpg|max:1024',
        ]);

        if ($id) {
            $popup = Popup::findOrFail($id);
        } else {
            $popup = new Popup();
            $popup->visible = false;
        }

        $popup->fill($request->all())
            ->subir($request->file('imagen'), 'imagen')
            ->subir($request->file('imagen_vertical'), 'imagen_vertical')
            ->save();

        if ($id) {
            Flasher::set('El popup fue modificado exitosamente.', 'Popup Editado', 'success')->flashear();

            return back();
        } else {
            Flasher::set('El popup fue creado exitosamente.', 'Popup Creado', 'success')->flashear();

            return redirect()->route('popups');
        }
    }

    public function visibilidad(Popup $popup)
    {
        if ($popup->visible) {
            $popup->visible = false;
        } else {
            Popup::where('visible', true)->update(['visible' => false]);
            $popup->visible = true;
        }

        $popup->save();

        return back();
    }
}
