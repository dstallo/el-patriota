<?php

namespace App\Http\Controllers\Admin;

use App\Axys\AxysFlasher as Flasher;
use App\Axys\AxysListado as Listado;
use App\Axys\Traits\TieneVisibilidad;
use App\Banner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Banners extends Controller
{
    use TieneVisibilidad;

    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('rol.admin');
    }

    public function index(Request $request)
    {
        $query = Banner::orderBy('orden');

        if (!session()->has('axys.listado.listado_banners.valores.buscando_ubicacion')) {
            session(['axys.listado.listado_banners.valores.buscando_ubicacion' => Banner::ubicaciones()[0]]);
        }

        $listado = new Listado(
            'listado_banners',
            $query,
            $request,
            [],
            [
                'buscando' => [
                    ['campo' => 'nombre', 'comparacion' => 'like'],
                ],
                'buscando_id' => [
                    ['campo' => 'banners.id', 'comparacion' => 'igual'],
                ],
                'buscando_ubicacion' => [
                    ['campo' => 'ubicacion', 'comparacion' => 'igual'],
                ],
            ]
        );

        // $banners=$listado->paginar(50);
        $banners = $listado->get();

        return view('admin.banners.index', compact('banners', 'listado'));
    }

    public function eliminar(Banner $banner)
    {
        try {
            $banner->delete();
            $flasher = Flasher::set('El banner fue eliminado.', 'Banner Eliminado', 'success');
        } catch (\Exception $e) {
            $flasher = Flasher::set('No se pudo borrar el banner, ya tiene contenido asociado.', 'Error', 'error');
        }
        $flasher->flashear();

        return redirect()->back();
    }

    public function crear(Request $request)
    {
        $banner = new Banner();

        return view('admin.banners.crear', compact('banner'));
    }

    public function editar(Banner $banner, Request $request)
    {
        return view('admin.banners.editar', compact('banner'));
    }

    public function guardar(Request $request, $id = null)
    {
        $this->validate($request, [
            'ubicacion' => 'required',
            'nombre' => 'required',
            'imagen' => 'nullable|file|mimes:jpg,png,gif|max:2048',
            'imagen_responsive' => 'nullable|file|mimes:jpg,png,gif|max:2048',
            'link' => 'nullable|url',
        ], [], [
            'ubicacion' => 'ubicación',
        ]);

        if ($id) {
            $banner = Banner::findOrFail($id);
        } else {
            $banner = new Banner();
            $banner->ordenar([['ubicacion', $request->input('ubicacion')]]);
        }

        $banner->fill($request->all());

        $banner->subir($request->file('imagen'), 'imagen')
            ->subir($request->file('imagen_responsive'), 'imagen_responsive')
            ->save();

        // ahora soporta gifs, así q no hay resize, se ajusta por css

        // if($request->file('imagen')) {
        //     $banner->resizeImagen();
        // }

        // if($request->file('imagen_responsive')) {
        //     $banner->resizeImagenResponsive();
        // }

        if ($id) {
            Flasher::set('El banner fue modificado exitosamente.', 'Banner Editado', 'success')->flashear();

            return back();
        } else {
            Flasher::set('El banner fue creado exitosamente.', 'Banner Creado', 'success')->flashear();

            return redirect()->route('editar_banner', $banner);
        }
    }

    public function visibilidad(Banner $banner)
    {
        return $this->cambiarVisibilidad($banner);
    }

    public function eliminarArchivo(Banner $banner, $campo)
    {
        $banner->eliminarArchivo($campo)->save();
        Flasher::set("Se eliminó el archivo $campo", 'Archivo Eliminado', 'success')->flashear();

        return back();
    }

    public function ordenar(Request $request)
    {
        $ids = $request->all()['ids'];
        $orden = 1;
        foreach ($ids as $id) {
            Banner::where('id', $id)->update(['orden' => $orden]);
            ++$orden;
        }

        return ['ok' => true];
    }
}
