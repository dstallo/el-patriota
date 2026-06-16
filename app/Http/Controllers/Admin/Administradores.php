<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Axys\AxysFlasher as Flasher;
use App\Axys\AxysListado as Listado;

use App\Administrador;
use App\Axys\Traits\TieneVisibilidad;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class Administradores extends Controller
{
    use TieneVisibilidad;

    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware('rol:admin');
    }

    public function index(Request $request)
    {
        $listado=new Listado(
            'listado_administradores',
            Administrador::query(),
            $request,
            ['id','nombre','email','rol'],
            [
                'buscando'  =>[
                    ['campo'=>'nombre','comparacion'=>'like'],
                    ['campo'=>'email','comparacion'=>'like'],
                ],
                'buscando_id' =>[
                    ['campo'=>'id','comparacion'=>'igual']
                ]
            ]
        );
        
        $administradores=$listado->paginar();

        return view('admin.administradores.index', compact('administradores', 'listado'));
    }

    public function eliminar(Administrador $administrador)
    {
        $id=$administrador->id;
        if($id==\Auth::id()) {
            Flasher::set('No se puede eliminar el administrador logueado.', 'Error', 'error')->flashear();
            return redirect()->back();
        }
        try {
            $administrador->delete();
            $flasher=Flasher::set("El administrador #$id fue eliminado.", 'Administrador Eliminado', 'success');
        } catch (\Exception $e) {
            $administrador=Flasher::set('Ocurrió un error al eliminar el administrador.', 'Error', 'error');
        }
        $flasher->flashear();
        return redirect()->back();
    }

    public function eliminarArchivo(Administrador $administrador, $campo)
    {
        $administrador->eliminarArchivo($campo)->save();
        Flasher::set("Se eliminó el archivo $campo", 'Archivo Eliminado', 'success')->flashear();
        return back();
    }

    public function crear()
    {
        $roles = Administrador::rolesPosibles();
        $administrador = new Administrador;

        return view('admin.administradores.crear', compact('administrador', 'roles'));
    }

    public function editar(Administrador $administrador)
    {
        if(!Auth::user()->admin() && $administrador->id!=Auth::id())
            return redirect('/');

        $roles = Administrador::rolesPosibles();

        return view('admin.administradores.editar', compact('administrador', 'roles'));
    }

    public function guardar(Request $request, $id=null)
    {
        if(!Auth::user()->admin() && $id!=Auth::id())
             return redirect('/');

        $reglas=[
            'nombre'    => ['required', 'max:255'],
            'foto'      => ['nullable', 'file', 'mimes:'.config('app.image_mimes'),'max:'.config('app.image_size')],
            'email'     => ['required', 'email', $id ? Rule::unique('administradores', 'email')->ignore($id) : Rule::unique('administradores', 'email')],
        ];

        // Si no estoy editando mi usuario (creando o editando otro usuario)
        if (Auth::id() != $id) {
            $reglas['rol'] = ['required', 'in:' . implode(',',Administrador::rolesPosibles('values'))];
        }

        // Si estoy editando un usuario
        if($id) {

            $administrador=Administrador::findOrFail($id);

            // Si me estoy cambiando el password
            if($id==Auth::id() && $request->exists('password') && $request->input('password')!='') {
                $this->validate($request, ['password' => 'min:6|confirmed']);
                $administrador->password=bcrypt($request->input('password'));
            }
        } else { // Si estoy creando un usuario
            
            $reglas['password'] = 'required|min:6|confirmed';
            
            $administrador=new Administrador();
        }

        //Ejecutar validaciones /////////
        $validator = Validator::make($request->all(), $reglas);
        $validator->setAttributeNames([]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }
        /////////////////////////////////

        $administrador->subir($request->file('foto'),'foto')->crearThumbnails();

        if ($id) {
            //ESTOY EDITANDO UN USUARIO

            $administrador->nombre = $request->input('nombre');
            $administrador->email = $request->input('email');

            // Solo cambio el rol si no estoy editando mi usuario.
            if ($id != Auth::id()) {
                 $administrador->rol = $request->input('rol');
            }

            $administrador->save();

            Flasher::set("El usuario #$administrador->id fue modificado exitosamente.", 'Usuario Editado', 'success')->flashear();
        } else {
            //CREANDO UN USUARIO NUEVO

            $administrador->nombre = $request->input('nombre');
            $administrador->email = $request->input('email');
            $administrador->rol = $request->input('rol');
            $administrador->password = bcrypt($request->input('password'));
            $administrador->remember_token = Str::random(10);
            $administrador->api_token = Str::random(60);

            $administrador->save();

            Flasher::set("El usuario #$administrador->id fue creado exitosamente.", 'Usuario Creado', 'success')->flashear();
        }

        return redirect()->route('AdministradoresIndex');
    }

    public function visibilidad(Administrador $administrador)
    {
        if ($administrador->logueado()) {
            Flasher::set('No puedes deshabilitar tu propio usuario.', 'Operación no permitida', 'error')->flashear();
            return redirect()->back();
        }
        

        return $this->cambiarVisibilidad($administrador);
    }
}