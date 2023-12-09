<?php

/**
 * Agrega la clase active (o lo q se le pase en el 2do param) si en la URI
 * aparece lo que se le pase en el 1er param (puede ser un array de strings).
 * $embeber: si embeber en class='..' o devolver la clase directamente
 * $nouri: que NO aparezca en la URI (puede ser un array de strings)
 */
function active_si_uri($queSi,$embeber=true,$queNo='',$active=null)
{
	if(!$active) {
		$active='active';
	}
	if(!is_array($queSi)) {
		$queSi=[$queSi];
	}
	if(!is_array($queNo)) {
		$queNo=[$queNo];
	}
	$uriActual=parse_url(url()->current(),PHP_URL_PATH);
	$enUri=true;
	foreach($queSi as $uri) {
		if(!empty($uri)) {
			$enUri = $enUri && strpos($uriActual, $uri)!==false;
		}
	}
	foreach($queNo as $uri) {
		if(!empty($uri)) {
			$enUri = $enUri && strpos($uriActual, $uri)===false;
		}
	}
	
    if ($enUri) {
    	if ($embeber) {
    		return ' class="'.$active.'"';
    	} else {
    		return ' '.$active;
    	}
    }
}

/**
 * Devuelve una instancia de flasher.
 */
function flasher()
{
	return new App\Axys\AxysFlasher();
}

/**
 * Agrega la clase has-error de bootstrap, si el campo está en el array de errores.
 */
function has_error($errors,$campo)
{
	if($errors->has($campo)) return(' has-error');
}

/**
 * Genera el link de visible/invisible
 */
function accion_visibilidad($visible, $url)
{
	if($visible) {
		$html = sprintf('<a href="%s" role="button" class="btn btn-success btn-circle"><i class="glyphicon glyphicon-eye-open"></i></a>',
				$url);
	} else {
		$html = sprintf('<a href="%s" role="button" class="btn btn-danger btn-circle"><i class="glyphicon glyphicon-eye-close"></i></a>',
				$url);
	}

	return $html;
}

/**
 * Devuelve ' selected' si se cumple la condición
 */
function selected($condicion)
{
	return $condicion?' selected':'';
}

/**
 * Devuelve ' checked' si se cumple la condición
 */
function checked($condicion)
{
	return $condicion?' checked':'';
}

/**
 * Valida si es un email válido
 */
function validar_email($email)
{
	$email = trim($email);

	if(empty($email)) {
		return false;
	}

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    if (substr_count($email, '@') != 1) {
        return false;
    }

    return true;
}