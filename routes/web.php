<?php
use App\Axys\GenerarRutas;

if (config('app.env') === 'production') {
    \URL::forceScheme('https');
}

Route::group(['prefix' => 'admin','namespace' => 'Admin'], function() {
	GenerarRutas::auth();

	Route::get('/', 'Dashboard@index');

	// imágenes tinymce
	Route::post('subir-tiny', 'Dashboard@subirTiny')->name('subir-tiny');

	/// Administradores
	GenerarRutas::ABM('administradores', 'administrador', 'Administradores', ['visibilidad']);

	// newsletter
	Route::get('newsletter/inscriptos', 'Newsletter@index')->name('inscriptos');
	Route::get('newsletter/inscriptos/exportar', 'Newsletter@exportar')->name('exportar_inscriptos');
	Route::get('newsletter/inscriptos/crear', 'Newsletter@crear')->name('crear_inscripto');
	Route::get('newsletter/inscriptos/{inscripto}/editar', 'Newsletter@editar')->name('editar_inscripto');
	Route::post('newsletter/inscriptos/guardar/{inscripto?}', 'Newsletter@guardar')->name('guardar_inscripto');
	Route::get('newsletter/inscriptos/{inscripto}/eliminar', 'Newsletter@eliminar')->name('eliminar_inscripto');

	// secciones
	Route::get('secciones', 'Secciones@index')->name('secciones');
	Route::get('secciones/crear', 'Secciones@crear')->name('crear_seccion');
	Route::get('secciones/{seccion}/editar', 'Secciones@editar')->name('editar_seccion');
	Route::post('secciones/guardar/{seccion?}', 'Secciones@guardar')->name('guardar_seccion');
	Route::get('secciones/{seccion}/eliminar', 'Secciones@eliminar')->name('eliminar_seccion');
	Route::post('secciones/ordenar', 'Secciones@ordenar')->name('ordenar_secciones');
	Route::get('secciones/{seccion}/visibilidad', 'Secciones@visibilidad')->name('visibilidad_seccion');

	// regiones
	Route::get('regiones', 'Regiones@index')->name('regiones');
	Route::get('regiones/crear', 'Regiones@crear')->name('crear_region');
	Route::get('regiones/{region}/editar', 'Regiones@editar')->name('editar_region');
	Route::post('regiones/guardar/{region?}', 'Regiones@guardar')->name('guardar_region');
	Route::get('regiones/{region}/eliminar', 'Regiones@eliminar')->name('eliminar_region');
	Route::post('regiones/ordenar', 'Regiones@ordenar')->name('ordenar_regiones');

	// banners
	Route::get('banners', 'Banners@index')->name('banners');
	Route::get('banners/crear', 'Banners@crear')->name('crear_banner');
	Route::get('banners/{banner}/editar', 'Banners@editar')->name('editar_banner');
	Route::post('banners/guardar/{banner?}', 'Banners@guardar')->name('guardar_banner');
	Route::get('banners/{banner}/eliminar', 'Banners@eliminar')->name('eliminar_banner');
	Route::get('banners/{banner}/eliminar-archivo/{campo}', 'Banners@eliminarArchivo')->name('eliminar_archivo_banner');
	Route::get('banners/{banner}/visibilidad', 'Banners@visibilidad')->name('visibilidad_banner');
	Route::post('aaaaaaa', 'Banners@ordenar')->name('ordenar_banners');

	// noticias
	Route::get('noticias', 'Noticias@index')->name('noticias');
	Route::get('noticias/crear', 'Noticias@crear')->name('crear_noticia');
	Route::get('noticias/{noticia}/editar', 'Noticias@editar')->name('editar_noticia');
	Route::post('noticias/guardar/{noticia?}', 'Noticias@guardar')->name('guardar_noticia');
	Route::get('noticias/{noticia}/eliminar', 'Noticias@eliminar')->name('eliminar_noticia');
	Route::get('noticias/{noticia}/cambiar-visibilidad', 'Noticias@visibilidad')->name('visibilidad_noticia');
	Route::get('noticias/{noticia}/eliminar-archivo/{campo}', 'Noticias@eliminarArchivo')->name('eliminar_archivo_noticia');

	// contenidos
	Route::get('noticias/{noticia}/multimedia', 'Contenidos@index')->name('contenidos');
	Route::post('noticias/{noticia}/multimedia/subir', 'Contenidos@subirImagen')->name('subir_imagen_noticia');
	Route::post('noticias/{noticia}/multimedia/crear-video', 'Contenidos@crearVideo')->name('crear_video_noticia');
	Route::get('noticias/{noticia}/multimedia/{contenido}/editar', 'Contenidos@editar')->name('editar_contenido_noticia');
	Route::post('noticias/{noticia}/multimedia/{contenido}/guardar', 'Contenidos@guardar')->name('guardar_contenido_noticia');
	Route::get('noticias/{noticia}/multimedia/{contenido}/eliminar', 'Contenidos@eliminar')->name('eliminar_contenido_noticia');
	Route::get('noticias/{noticia}/multimedia/{contenido}/eliminar-imagen', 'Contenidos@eliminarImagen')->name('eliminar_imagen_contenido_noticia');
	Route::post('noticias/{noticia}/multimedia/ordenar', 'Contenidos@ordenar')->name('ordenar_contenidos_noticia');

	// encuestas
	Route::get('encuestas', 'Encuestas@index')->name('encuestas');
	Route::get('encuestas/crear', 'Encuestas@crear')->name('crear_encuesta');
	Route::get('encuestas/{encuesta}/editar', 'Encuestas@editar')->name('editar_encuesta');
	Route::post('encuestas/guardar/{encuesta?}', 'Encuestas@guardar')->name('guardar_encuesta');
	Route::get('encuestas/{encuesta}/eliminar', 'Encuestas@eliminar')->name('eliminar_encuesta');
	Route::get('encuestas/{encuesta}/visibilidad', 'Encuestas@visibilidad')->name('visibilidad_encuesta');

	// opciones
	Route::get('encuestas/{encuesta}/opciones', 'Opciones@index')->name('opciones');
	Route::get('encuestas/{encuesta}/opciones/crear', 'Opciones@crear')->name('crear_opcion');
	Route::get('encuestas/{encuesta}/opciones/{opcion}/editar', 'Opciones@editar')->name('editar_opcion');
	Route::post('encuestas/{encuesta}/opciones/guardar/{opcion?}', 'Opciones@guardar')->name('guardar_opcion');
	Route::get('encuestas/{encuesta}/opciones/{opcion}/eliminar', 'Opciones@eliminar')->name('eliminar_opcion');
	Route::post('encuestas/{encuesta}/opciones/ordenar', 'Opciones@ordenar')->name('ordenar_opciones');
});

Route::get('idioma/{idioma}', 'Localizacion@cambiarIdioma');

Route::post('ajax/newsletter', 'General@newsletter');
Route::get('ajax/clima', 'General@clima');

Route::get('/', 'Noticias@home');
Route::get('secciones/{seccion}-{nombre}', 'Noticias@seccion')->name('seccion');
Route::get('regiones/{region}-{nombre}', 'Noticias@region')->name('region');
Route::get('noticias/{noticia}-{titulo}', 'Noticias@ficha')->name('ficha_noticia');

Route::get('banners/{banner}/link', 'Banners@link')->name('link_banner');

Route::get('encuesta/{pregunta?}', 'Noticias@verEncuesta')->name('ver-encuesta');
Route::post('encuesta/{pregunta?}', 'Noticias@votarEncuesta');
Route::get('encuesta/resultados/{pregunta?}', 'Noticias@verResultadosEncuesta')->name('resultados-encuesta');