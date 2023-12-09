<?php
/**
 * Se encarga de flashear cosas, está inicialmente
 * pensada para usar sweetalert en el front, pero puede andar con
 * cualquier cosa que tenga mensaje, título y tipo.
 * Ojo, depende de la vista parcial 'flasher' donde se implementa el
 * HTML / JS correspondiente.
 */

namespace App\Axys;

//eee, alto nombre
class AxysFlasher
{

	protected $mensaje;
	protected $titulo;
	protected $tipo;

	public function __construct()
	{
		$flasheado=session()->get('axys.flasher');
		if(empty($flasheado)) {
			return null;
		}
		$this->mensaje=$flasheado['mensaje'];
		$this->titulo=$flasheado['titulo'];
		$this->tipo=$flasheado['tipo'];
		return $this;
	}

	public static function set($mensaje=null, $titulo='Ey!', $tipo='info')
	{
		$flasher=new static;
		$flasher->mensaje=$mensaje;
		$flasher->titulo=$titulo;
		$flasher->tipo=$tipo;
		return $flasher;
	}

	public function flashear()
	{
		session()->put('axys.flasher', [
			'mensaje'=>$this->mensaje,
			'titulo'=>$this->titulo,
			'tipo'=>$this->tipo,
		]);
	}

	public function get()
	{
		if(empty($this->mensaje)) {
			return null;
		}
		session()->forget('axys.flasher');
		return [
			'mensaje'=>$this->mensaje,
			'titulo'=>$this->titulo,
			'tipo'=>$this->tipo,
		];
	}
}