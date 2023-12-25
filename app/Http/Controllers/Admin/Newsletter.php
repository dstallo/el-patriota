<?php

namespace App\Http\Controllers\Admin;

use App\Axys\AxysFlasher as Flasher;
use App\Axys\AxysListado as Listado;
use App\Axys\Traits\TieneVisibilidad;
use App\Http\Controllers\Controller;
use App\InscriptoNewsletter as Inscripto;
use Illuminate\Http\Request;

class Newsletter extends Controller
{
    use TieneVisibilidad;

    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('rol.admin');
    }

    public function index(Request $request)
    {
        $query = Inscripto::query();

        $listado = new Listado(
            'listado_inscriptos',
            $query,
            $request,
            ['id', 'email', 'nombre'],
            [
                'buscando' => [
                    ['campo' => 'nombre', 'comparacion' => 'like'],
                    ['campo' => 'email', 'comparacion' => 'like'],
                ],
                'buscando_id' => [
                    ['campo' => 'id', 'comparacion' => 'igual'],
                ],
            ]
        );

        $inscriptos = $listado->paginar(50);
        // $inscriptos=$listado->get();

        return view('admin.newsletter.index', compact('inscriptos', 'listado'));
    }

    public function eliminar(Inscripto $inscripto)
    {
        try {
            $inscripto->delete();
            $flasher = Flasher::set('El inscripto fue eliminado.', 'Inscripto Eliminado', 'success');
        } catch (\Exception $e) {
            $flasher = Flasher::set('No se pudo borrar el inscripto, ya tiene contenido asociado.', 'Error', 'error');
        }
        $flasher->flashear();

        return redirect()->back();
    }

    public function crear(Request $request)
    {
        $inscripto = new Inscripto();

        return view('admin.newsletter.crear', compact('inscripto'));
    }

    public function editar(Inscripto $inscripto, Request $request)
    {
        return view('admin.newsletter.editar', compact('inscripto'));
    }

    public function guardar(Request $request, $id = null)
    {
        $this->validate($request, [
            'nombre' => 'required',
            'email' => 'required|email',
        ]);

        if ($id) {
            $inscripto = Inscripto::findOrFail($id);
        } else {
            $inscripto = new Inscripto();
        }

        $inscripto->fill($request->all())
            ->save();

        if ($id) {
            Flasher::set('El inscripto fue modificado exitosamente.', 'Inscripto Editado', 'success')->flashear();

            return back();
        } else {
            Flasher::set('El inscripto fue creado exitosamente.', 'Inscripto Creado', 'success')->flashear();

            return redirect()->route('inscriptos');
        }
    }

    public function exportar()
    {
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=inscriptos_newsletter.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $path = storage_path('app/inscriptos_newsletter.csv');

        $inscriptos = Inscripto::all();
        $columnas = ['ID', 'Fecha', 'Nombre', 'E-Mail'];

        // $callback = function() use ($inscriptos, $columnas)
        // {
        //     $file = fopen('php://output', 'w');
        //     fputcsv($file, $columnas);

        //     foreach($inscriptos as $inscripto) {
        //         fputcsv($file, array($inscripto->id,$inscripto->fecha_inscripcion,$inscripto->email));
        //     }
        //     fclose($file);
        // };
        $file = fopen($path, 'w');
        fputcsv($file, $columnas);

        foreach ($inscriptos as $inscripto) {
            fputcsv($file, [$inscripto->id, $inscripto->fecha_inscripcion, $inscripto->nombre, $inscripto->email]);
        }
        fclose($file);

        return response()->download($path, 'inscriptos_newsletter.csv', $headers);
    }
}
