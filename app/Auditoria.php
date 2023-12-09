<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use App\Jobs\LimpiarAuditoriasViejas;

/// PARA AUDITAR UN MODELO, AGREGAR ESTO EN EL BOOT:

// protected static function boot()
// {
//     parent::boot();

//     static::updating(function ($cosa) { Auditoria::crear($cosa); });
// }

class Auditoria extends Model
{
    protected $table = 'auditorias';

    protected $appends = ['fecha_creacion'];

    const DIAS_A_CONSERVAR = 90;

    public static function crear($modelo)
    {
    	$auditoria = new static;
    	$auditoria->tabla = $modelo->getTable();
    	$auditoria->id_registro = $modelo->getKey();
    	$auditoria->informacion = json_encode($modelo->getOriginal());
    	if($administrador = Auth::user()) {
    		$auditoria->id_administrador = $administrador->id;	
    	}
    	$auditoria->save();

    	LimpiarAuditoriasViejas::dispatch();
    }

    public static function limpiar()
    {
    	static::where('created_at', '<', date('Y-m-d H:i:s', strtotime('-' . static::DIAS_A_CONSERVAR . ' days')))->delete();
    }

    public static function consultar($tabla, $id, $dias = null)
    {
    	if(!$dias) $dias = static::DIAS_A_CONSERVAR;

    	return static::where('tabla', $tabla)
            ->where('id_registro', $id)
            ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-' . static::DIAS_A_CONSERVAR . ' days')))
            ->with('administrador')
            ->orderBy('created_at', 'desc');
    }

    public function administrador()
    {
    	return $this->belongsTo(Administrador::class, 'id_administrador');
    }

    public function getFechaCreacionAttribute()
    {
        return date('d/m/Y - H:i:s', strtotime($this->created_at));
    }
}
