<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Axys\AxysFlasher as Flasher;
use App\Axys\AxysListado as Listado;

use App\Administrador;

class Administradores extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('rol.admin', ['except' => ['editar', 'guardar']]);
    }

    public function index(Request $request)
    {
        $listado=new Listado(
            'listado_administradores',
            Administrador::query(),
            $request,
            //['id','nombre','email','rol'],
            ['id','nombre','email'],
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
        //$roles = Administrador::roles();
        $administrador = new Administrador;
        $mostrarFormPassword = true;
        //return view('administradores.crear', compact('administrador', 'mostrarFormPassword', 'roles'));
        return view('admin.administradores.crear', compact('administrador', 'mostrarFormPassword'));
    }

    public function editar(Administrador $administrador)
    {
        // if(!\Auth::user()->admin() && $administrador->id!=\Auth::id())
        //     return redirect('/');

        $mostrarFormPassword=($administrador->id==\Auth::id());
        //$roles = Administrador::roles();

        //return view('administradores.editar', compact('administrador', 'mostrarFormPassword', 'roles'));
        return view('admin.administradores.editar', compact('administrador', 'mostrarFormPassword'));
    }

    public function guardar(Request $request, $id=null)
    {
        // if(!\Auth::user()->admin() && $id!=\Auth::id())
        //     return redirect('/');

        $reglas=[
            'nombre' => 'required',
            'foto' => 'nullable|image|max:2048',
            //'rol' => 'required|in:' . implode(',',Administrador::roles()),
        ];

        if($id) {
            //ESTOY EDITANDO UN USUARIO

            $reglas['email']='unique:administradores,email,'.$id;

            $administrador=Administrador::findOrFail($id);
            if($id==\Auth::id() && $request->exists('password') && $request->get('password')!='') {
                //ME ESTOY CAMBIANDO MI PROPIO PASSWORD
                $this->validate($request, [
                    'password' => 'min:6|confirmed'
                ]);
                $administrador->password=bcrypt($request->get('password'));
            }
        } else {
            //CREANDO UN USUARIO NUEVO

            $reglas['email'] = 'unique:administradores,email';
            $reglas['password'] = 'required|min:6|confirmed';
            
            $administrador=new Administrador();
        }

        //Ejecutar validaciones /////////
        $validator = \Validator::make($request->all(), $reglas);
        $validator -> setAttributeNames([
            
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }
        /////////////////////////////////

        // if($id==\Auth::id() && $administrador->rol != $request->get('rol')) {
        //     return redirect()->back()->withErrors(['rol'=>'No podés cambiarte tu propio rol'])
        //         ->withInput($request->except('password', 'password_confirmation'));
        // }


        $administrador->subir($request->file('foto'),'foto')->crearThumbnails();

        if($id) {
            //ESTOY EDITANDO UN USUARIO

            // el password es fillable, por seguridad asigno los campos de a uno
            // (lo dejo fillable para conservar el db:seed)
            // $administrador->fill($request->all());
            $administrador->nombre = $request->get('nombre');
            $administrador->email = $request->get('email');
            // if($id!=\Auth::id()) {
            //     $administrador->rol = $request->get('rol');
            // }

            $administrador->save();

            Flasher::set("El administrador #$administrador->id fue modificado exitosamente.", 'Administrador Editado', 'success')->flashear();
        } else {
            //CREANDO UN USUARIO NUEVO

            $administrador->nombre = $request->get('nombre');
            $administrador->email = $request->get('email');
            //$administrador->rol = $request->get('rol');
            $administrador->password = bcrypt($request->get('password'));
            $administrador->remember_token = str_random(10);
            $administrador->api_token = str_random(60);

            $administrador->save();

            Flasher::set("El administrador #$administrador->id fue creado exitosamente.", 'Administrador Creado', 'success')->flashear();
        }
        return redirect()->route('AdministradoresEditar', compact('administrador'));
        //return redirect()->route('AdministradoresIndex');
    }
}