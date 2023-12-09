<?php

namespace App\Axys;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

//cachear los thumbnails!!!
//https://vimeo.com/api/v2/video/391277390.json

class AxysVideo
{
	protected $url;
	protected $tipo;
	protected $id;

	protected $dir = 'storage/contenido/thumbs-videos';

	/**
	 * Crea un objeto Video.
	 */
	function __construct($url)
	{
		if( ! $valida = self::validarVideo($url) ) {
			return $this;
		}

		$this->url = $url;
		$this->tipo = $valida['tipo'];
		$this->id = $valida['id'];
	}

	/**
	 * Agrega 'video' como validación a Validator.
	 * Estaría bueno que se llamara desde un ServiceProvider.
	 */
	public static function agregarValidacion()
	{
		Validator::extend('video', function ($attribute, $value, $parameters, $validator) {
            return static::validarVideo($value)!==false;
        });
	}

	/**
	 * Valida que una URL sea un video válido.
	 */
	public static function validarVideo($url)
	{
		$valido=false;

		if(!$valido) $valido = static::validarVideoYoutube($url);
		if(!$valido) $valido = static::validarVideoVimeo($url);
		//OTROS FORMATOS
		//if(!$valido) $valido = static::validarVideo...($url);

		return $valido;
	}

	/**
	 * Devuelve si el video actual es válido.
	 */
	public function ok()
	{
		return ($this->id != false);
	}

	/**
	 * Devuelve la URL del video actual.
	 */
	public function url()
	{
		return $this->url;
	}

	/**
	 * Devuelve la URL del embed, para poner en un iframe por ejemplo.
	 */
	public function embedUrl($ancho = '100%')
	{
		$opts=''; //tal vez le de una utilidad más adelante
		if($this->tipo == 'youtube') {
			return 'https://www.youtube.com/embed/'.$this->id.'?wmode=opaque'.$opts;
		} elseif($this->tipo == 'vimeo') {
			return 'https://player.vimeo.com/video/' . $this->id;
		}
		//OTROS FORMATOS
		//elseif(...) ...
		return '';
	}

	/**
	 * Devuelve el HTML del player embebido.
	 * Recibe el ancho (tal como iria en el atributo html) y la relación del alto (% del ancho).
	 * La relación del ancho la maneja una bibliotequita jQuery (mantener-relacion-alto.js).
	 */
	public function embed($ancho = '100%', $relacionDelAlto = '56')
	{
		//data-mantener-relacion-alto="56" sirve para mantener la relación de aspecto 16:9
		if($this->tipo == 'youtube') {
			return '<iframe width="'.$ancho.'" height="320" data-mantener-relacion-alto="'.$relacionDelAlto.'" src="' . $this->embedUrl($ancho) . '" frameborder="0" allowfullscreen></iframe>';
		} elseif($this->tipo == 'vimeo') {
			return '<iframe src="' . $this->embedUrl() . '" width="' . $ancho . '" height="360" data-mantener-relacion-alto="'.$relacionDelAlto.'" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>';
		}
		//OTROS FORMATOS
		//elseif(...) ...
		return '';
	}

	/**
	 * Devuelve la URL del thumbnail del video actual.
	 */
	public function thumb($tamano=null)
	{
		$dir = $this->dir . '/' . $this->tipo;

		if(is_array($tamano)) {
			$dir .= '/'.$tamano[0].'x';
			if(count($tamano)>1) { $dir .= $tamano[1]; }
		}
		$path = $dir . '/' . $this->id . '.jpg';

		if($this->tipo == 'youtube') {
			$url = 'https://img.youtube.com/vi/'.$this->id.'/hqdefault.jpg';
		} elseif($this->tipo == 'vimeo') {
			$info = file_get_contents("https://vimeo.com/api/v2/video/{$this->id}.json");
    		$info = json_decode($info, true);
    		if(!$info || !isset($info[0]) || !isset($info[0]['thumbnail_large'])) {
    			return '';
    		}
    		$url = $info[0]['thumbnail_large'];
		}
			
		if(File::isFile(public_path($path))) {
			return url($path);
		} else {
			$imagen = Image::make($url);
			if(is_array($tamano)) {
				if(count($tamano) == 1) {
					$imagen->resize($tamano[0], null, function ($constraint) {
					    $constraint->aspectRatio();
					});
				} else {
					$imagen->fit($tamano[0], $tamano[1]);
				}
			}
			if(!File::exists(public_path($dir))) {
				File::makeDirectory(public_path($dir), 0755, true);
			}
			$imagen->save(public_path($path));
			return url($path);
		}
		

		return '';
	}


	/**
	 * Valida que una URL sea un video de Youtube válido.
	 */
	protected static function validarVideoYoutube($url)
	{
		$matches = array();
		$ret = preg_match('/^(?:https?\:\/\/)?(?:www\.)?(youtube\.com\/watch\?v=|youtu.be\/)([\w\-]{11}).*$/', $url, $matches);
		if(! $ret)
			return false;
		else
			return array('tipo' => 'youtube', 'id' => $matches[2]);
	}

	/**
	 * Valida que una URL sea un video de Vimeo válido.
	 */
	protected static function validarVideoVimeo($url)
	{
		$matches = array();
		$ret = preg_match('/^(?:https?\:\/\/)?(?:www\.)?(vimeo\.com\/)([\d]+)\/?$/', $url, $matches);
		if(! $ret)
			return false;
		else
			return array('tipo' => 'vimeo', 'id' => $matches[2]);
	}
}