<?php

use App\Axys\GenerarRutas;
use App\Http\Controllers\Admin\Administradores;
use App\Http\Controllers\Admin\Contenidos;
use App\Http\Controllers\Admin\Dashboard;
use App\Http\Controllers\Admin\Newsletter;
use App\Http\Controllers\Admin\Regiones;
use App\Http\Controllers\Admin\Secciones;
use App\Http\Controllers\Admin\Videos;
use App\Http\Controllers\Admin\Banners;
use App\Http\Controllers\Admin\Cotizaciones;
use App\Http\Controllers\Admin\Encuestas;
use App\Http\Controllers\Admin\Noticias;
use App\Http\Controllers\Admin\Opciones;
use App\Http\Controllers\Admin\Popups;
use App\Http\Controllers\Banners as FrontBanners;
use App\Http\Controllers\Noticias as FrontNoticias;
use App\Http\Controllers\Localizacion as FrontLocalizacion;
use App\Http\Controllers\General as FrontGeneral;
use App\Http\Controllers\Entrevistas as FrontEntrevistas;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

if (config('app.env') === 'production') {
    URL::forceScheme('https');
}

Route::group(['prefix' => 'admin'], function () {
    GenerarRutas::auth();

    Route::get('/', [Dashboard::class, 'index'])->name('home');

    // Configuraciones
    Route::post('/configuraciones', [Dashboard::class, 'guardar'])->name('guardar_configuraciones');

    // Cotizaciones
    Route::get('/cotizaciones', [Cotizaciones::class, 'index'])->name('cotizaciones');
    Route::post('/cotizaciones/ordenar', [Cotizaciones::class, "ordenar"])->name('ordenar_cotizaciones');

    // imágenes tinymce
    Route::post('subir-tiny', [Dashboard::class, 'subirTiny'])->name('subir-tiny');

    // / Administradores
    GenerarRutas::ABM(Administradores::class, 'administradores', 'administrador', 'Administradores');

    // newsletter
    Route::get('newsletter/inscriptos', [Newsletter::class, "index"])->name('inscriptos');
    Route::get('newsletter/inscriptos/exportar', [Newsletter::class, "exportar"])->name('exportar_inscriptos');
    Route::get('newsletter/inscriptos/crear', [Newsletter::class, "crear"])->name('crear_inscripto');
    Route::get('newsletter/inscriptos/{inscripto}/editar', [Newsletter::class, "editar"])->name('editar_inscripto');
    Route::post('newsletter/inscriptos/guardar/{inscripto?}', [Newsletter::class, "guardar"])->name('guardar_inscripto');
    Route::get('newsletter/inscriptos/{inscripto}/eliminar', [Newsletter::class, "eliminar"])->name('eliminar_inscripto');

    // secciones
    Route::get('secciones', [Secciones::class, "index"])->name('secciones');
    Route::get('secciones/crear', [Secciones::class, "crear"])->name('crear_seccion');
    Route::get('secciones/{seccion}/editar', [Secciones::class, "editar"])->name('editar_seccion');
    Route::post('secciones/guardar/{seccion?}', [Secciones::class, "guardar"])->name('guardar_seccion');
    Route::get('secciones/{seccion}/eliminar', [Secciones::class, "eliminar"])->name('eliminar_seccion');
    Route::post('secciones/ordenar', [Secciones::class, "ordenar"])->name('ordenar_secciones');
    Route::get('secciones/{seccion}/visibilidad', [Secciones::class, "visibilidad"])->name('visibilidad_seccion');

    // regiones
    Route::get('regiones', [Regiones::class, "index"])->name('regiones');
    Route::get('regiones/crear', [Regiones::class, "crear"])->name('crear_region');
    Route::get('regiones/{region}/editar', [Regiones::class, "editar"])->name('editar_region');
    Route::post('regiones/guardar/{region?}', [Regiones::class, "guardar"])->name('guardar_region');
    Route::get('regiones/{region}/eliminar', [Regiones::class, "eliminar"])->name('eliminar_region');
    Route::post('regiones/ordenar', [Regiones::class, "ordenar"])->name('ordenar_regiones');

    // banners
    Route::get('banners', [Banners::class, "index"])->name('banners');
    Route::get('banners/crear', [Banners::class, "crear"])->name('crear_banner');
    Route::get('banners/{banner}/editar', [Banners::class, "editar"])->name('editar_banner');
    Route::post('banners/guardar/{banner?}', [Banners::class, "guardar"])->name('guardar_banner');
    Route::get('banners/{banner}/eliminar', [Banners::class, "eliminar"])->name('eliminar_banner');
    Route::get('banners/{banner}/eliminar-archivo/{campo}', [Banners::class, "eliminarArchivo"])->name('eliminar_archivo_banner');
    Route::get('banners/{banner}/visibilidad', [Banners::class, "visibilidad"])->name('visibilidad_banner');
    Route::post('banners/ordenar', [Banners::class, "ordenar"])->name('ordenar_banners');

    // noticias
    Route::get('noticias', [Noticias::class, 'index'])->name('noticias');
    Route::get('noticias/crear', [Noticias::class, 'crear'])->name('crear_noticia');
    Route::get('noticias/{noticia}/editar', [Noticias::class, 'editar'])->name('editar_noticia');
    Route::post('noticias/guardar/{noticia?}', [Noticias::class, 'guardar'])->name('guardar_noticia');
    Route::get('noticias/{noticia}/eliminar', [Noticias::class, 'eliminar'])->name('eliminar_noticia');
    Route::get('noticias/{noticia}/cambiar-visibilidad', [Noticias::class, 'visibilidad'])->name('visibilidad_noticia');
    Route::get('noticias/{noticia}/eliminar-archivo/{campo}', [Noticias::class, 'eliminarArchivo'])->name('eliminar_archivo_noticia');

    // contenidos
    Route::get('noticias/{noticia}/multimedia', [Contenidos::class, "index"])->name('contenidos');
    Route::post('noticias/{noticia}/multimedia/subir', [Contenidos::class, "subirImagen"])->name('subir_imagen_noticia');
    Route::post('noticias/{noticia}/multimedia/crear-video', [Contenidos::class, "crearVideo"])->name('crear_video_noticia');
    Route::get('noticias/{noticia}/multimedia/{contenido}/editar', [Contenidos::class, "editar"])->name('editar_contenido_noticia');
    Route::post('noticias/{noticia}/multimedia/{contenido}/guardar', [Contenidos::class, "guardar"])->name('guardar_contenido_noticia');
    Route::get('noticias/{noticia}/multimedia/{contenido}/eliminar', [Contenidos::class, "eliminar"])->name('eliminar_contenido_noticia');
    Route::get('noticias/{noticia}/multimedia/{contenido}/eliminar-imagen', [Contenidos::class, "eliminarImagen"])->name('eliminar_imagen_contenido_noticia');
    Route::post('noticias/{noticia}/multimedia/ordenar', [Contenidos::class, "ordenar"])->name('ordenar_contenidos_noticia');

    // videos
    Route::get('videos', [Videos::class, "index"])->name('videos');
    Route::get('videos/crear', [Videos::class, "crear"])->name('crear_video');
    Route::get('videos/{video}/editar', [Videos::class, "editar"])->name('editar_video');
    Route::post('videos/guardar/{video?}', [Videos::class, "guardar"])->name('guardar_video');
    Route::get('videos/{video}/eliminar', [Videos::class, "eliminar"])->name('eliminar_video');
    Route::get('videos/{video}/cambiar-visibilidad', [Videos::class, "visibilidad"])->name('visibilidad_video');
    Route::post('videos/ordenar', [Videos::class, "ordenar"])->name('ordenar_videos');
    Route::get('videos/{video}/eliminar-archivo/{campo}', [Videos::class, "eliminarArchivo"])->name('eliminar_archivo_video');

    // encuestas
    Route::get('encuestas', [Encuestas::class, "index"])->name('encuestas');
    Route::get('encuestas/crear', [Encuestas::class, "crear"])->name('crear_encuesta');
    Route::get('encuestas/{encuesta}/editar', [Encuestas::class, "editar"])->name('editar_encuesta');
    Route::post('encuestas/guardar/{encuesta?}', [Encuestas::class, "guardar"])->name('guardar_encuesta');
    Route::get('encuestas/{encuesta}/eliminar', [Encuestas::class, "eliminar"])->name('eliminar_encuesta');
    Route::get('encuestas/{encuesta}/visibilidad', [Encuestas::class, "visibilidad"])->name('visibilidad_encuesta');

    // opciones
    Route::get('encuestas/{encuesta}/opciones', [Opciones::class, "index"])->name('opciones');
    Route::get('encuestas/{encuesta}/opciones/crear', [Opciones::class, "crear"])->name('crear_opcion');
    Route::get('encuestas/{encuesta}/opciones/{opcion}/editar', [Opciones::class, "editar"])->name('editar_opcion');
    Route::post('encuestas/{encuesta}/opciones/guardar/{opcion?}', [Opciones::class, "guardar"])->name('guardar_opcion');
    Route::get('encuestas/{encuesta}/opciones/{opcion}/eliminar', [Opciones::class, "eliminar"])->name('eliminar_opcion');
    Route::post('encuestas/{encuesta}/opciones/ordenar', [Opciones::class, "ordenar"])->name('ordenar_opciones');

    // popups
    Route::get('popups', [Popups::class, "index"])->name('popups');
    Route::get('popups/crear', [Popups::class, "crear"])->name('crear_popup');
    Route::get('popups/{popup}/editar', [Popups::class, "editar"])->name('editar_popup');
    Route::post('popups/guardar/{popup?}', [Popups::class, "guardar"])->name('guardar_popup');
    Route::get('popups/{popup}/eliminar', [Popups::class, "eliminar"])->name('eliminar_popup');
    Route::get('popups/{popup}/eliminar-archivo/{campo}', [Popups::class, "eliminarArchivo"])->name('eliminar_archivo_popup');
    Route::get('popups/{popup}/visibilidad', [Popups::class, "visibilidad"])->name('visibilidad_popup');
});

Route::get('idioma/{idioma}', [FrontLocalizacion::class, "cambiarIdioma"]);

Route::post('ajax/newsletter', [FrontGeneral::class, "newsletter"]);
Route::get('ajax/clima', [FrontGeneral::class, "clima"]);

Route::get('/', [FrontNoticias::class, 'home']);
Route::get('secciones/{seccion}-{nombre}', [FrontNoticias::class, 'seccion'])->name('seccion');
Route::get('regiones/{region}-{nombre}', [FrontNoticias::class, 'region'])->name('region');
Route::get('noticias/{noticia}-{titulo}', [FrontNoticias::class, 'ficha'])->name('ficha_noticia');

Route::get('banners/{banner}/link', [FrontBanners::class, "link"])->name('link_banner');

Route::get('encuesta/{pregunta?}', [FrontNoticias::class, 'verEncuesta'])->name('ver-encuesta');
Route::post('encuesta/{pregunta?}', [FrontNoticias::class, 'votarEncuesta'])->name('votar-encuesta');
Route::get('encuesta/resultados/{pregunta?}', [FrontNoticias::class, 'verResultadosEncuesta'])->name('resultados-encuesta');

Route::get('entrevistas', [FrontEntrevistas::class, "index"]);
