<?php

namespace App\Axys\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\File as FileHttp;
use \Image;

/**
 * Agrega funcionalidad correspondiente a modelos que tienen fotos
 */

trait TieneArchivos
{
    /**
     * Recibe la instancia de UploadedFile y el nombre del campo
     * lo va guardar en el directorio definido en $this->dir[$campo].
     * Asigna el atributo, pero no guarda a la base de datos.
     */
    public function subir($archivo, $campo='foto')
    {
        if(!$archivo) {
            return $this;
        }
        $nombre=str_slug(pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME)); //simplifico el nombre
        $nombre=$nombre.'-'.str_random(5); //lo vuelvo único
        $nombre=$nombre.'.'.$archivo->getClientOriginalExtension();//le agrego la extensión
        //DO STORAGE - directamente al storage
        //$archivo->move(public_path($this->getDir($campo)), $nombre);
        Storage::disk(config('filesystems.contenido'))->putFileAs($this->getDir($campo), $archivo, $nombre, 'public');
        $this->$campo=$nombre;
        return $this;
    }

    /**
     * Recibe el texto en base64 de la imagen y el nombre del campo
     * lo va guardar en el directorio definido en $this->dir[$campo].
     * Asigna el atributo, pero no guarda a la base de datos.
     */
    public function guardarImagenBase64($base64, $campo='foto')
    {
        $nombre=str_random(10); //invento un nombre
        $nombre=$nombre.'-'.str_random(5); //lo vuelvo único (ya era único :-P)
        $nombre=$nombre.'.png'; //le agrego la extensión

        //$archivo->move(public_path($this->getDir($campo)), $nombre);
        Image::make($base64)->save($this->path_temporal($nombre));

        //DO STORAGE - subir y borrar la local
        Storage::disk(config('filesystems.contenido'))->putFileAs($this->getDir($campo), new FileHttp($this->path_temporal($nombre)), $nombre, 'public');
        File::delete($this->path_temporal($nombre));

        $this->$campo=$nombre;
        return $this;
    }

    /**
     * Resizea la imagen manteniendo proporción y recortando sobrantes
     */
    public function fit($ancho, $alto, $campo='foto')
    {
        if(config('filesystems.contenido')=='local') {
            $archivo_original = $this->pathAbsoluto($campo);
        } else {
            $archivo_original = $this->url($campo);
        }

        $nombre = $this->$campo;

        Image::make($archivo_original)
            ->fit($ancho, $alto)
            ->save($this->path_temporal($nombre));
        

        //DO STORAGE - subir y borrar la local
        Storage::disk(config('filesystems.contenido'))->putFileAs($this->getDir($campo), new FileHttp($this->path_temporal($nombre)), $nombre, 'public');
        File::delete($this->path_temporal($nombre));

        return $this;
    }

    /**
     * Resizea la imagen manteniendo proporción y evitando upsize, no cropea
     */
    public function resize($ancho, $alto, $campo='foto')
    {
        if(config('filesystems.contenido')=='local') {
            $archivo_original = $this->pathAbsoluto($campo);
        } else {
            $archivo_original = $this->url($campo);
        }

        $nombre = $this->$campo;

        Image::make($archivo_original)
            ->resize($ancho, $alto, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
            ->save($this->path_temporal($nombre));
        

        //DO STORAGE - subir y borrar la local
        Storage::disk(config('filesystems.contenido'))->putFileAs($this->getDir($campo), new FileHttp($this->path_temporal($nombre)), $nombre, 'public');
        File::delete($this->path_temporal($nombre));

        return $this;
    }

    /**
     * Crea todos los thumbnails asociados al campo en $this->thumbnails.
     * Asigna los atributos, pero no guarda a la base de datos.
     */
    public function crearThumbnails()
    {
        if (! property_exists(get_class($this), 'thumbnails')) {
            return $this;
        }
        foreach ($this->thumbnails as $campo=>$thumbnails) {
            //DO SPACES
            //if (!File::isFile($this->path($campo))) {
            if (!$this->tiene($campo)) {
                continue;
            }
            foreach ($thumbnails as $campoThumbnail => $tamano) {
                $nombre=pathinfo($this->$campo, PATHINFO_FILENAME)."-tn{$tamano[0]}x{$tamano[1]}.".pathinfo($this->$campo, PATHINFO_EXTENSION);
                
                //DO STORAGE
                if(config('filesystems.contenido')=='local') {
                    $archivo_original = $this->pathAbsoluto($campo);
                } else {
                    $archivo_original = $this->url($campo);
                }

                Image::make($archivo_original)
                    ->fit($tamano[0], $tamano[1])
                    //->save(public_path($this->getDir($campo).'/'.$nombre));
                    ->save($this->path_temporal($nombre));
                
                Storage::disk(config('filesystems.contenido'))->putFileAs($this->getDir($campo), new FileHttp($this->path_temporal($nombre)), $nombre, 'public');

                File::delete($this->path_temporal($nombre));

                $this->$campoThumbnail=$nombre;
            }
        }

        return $this;
    }

    /**
     * Elimina un archivo, si tiene thumbnails asociados, también los elimina.
     * Si se le pasa el nombre de un thumbnail, lo resuelve bien (elimina sólo el tn).
     */
    public function eliminarArchivo($campo)
    {
        //DO SPACES
        //File::delete($this->path($campo));
        $archivos = [$this->getDir($campo) . '/' . $this->$campo];

        $this->$campo = '';

        if (
                property_exists(get_class($this), 'thumbnails') &&
                is_array($this->thumbnails) &&
                array_key_exists($campo, $this->thumbnails)
            ) {
            foreach($this->thumbnails[$campo] as $campoThumbnail => $tamano) {
                //File::delete($this->path($campoThumbnail, $campo));
                $archivos[] = $this->getDir($campo) . '/' . $this->$campoThumbnail;
                $this->$campoThumbnail = '';
            }
        }

        //dd($archivos);

        Storage::disk(config('filesystems.contenido'))->delete($archivos);

        return $this;
    }

    /**
     * Elimina TODOS los archivos y thumbnails asociados.
     */
    public function eliminarTodosLosArchivos()
    {
        foreach ($this->eliminarConArchivos as $campo) {
            $this->eliminarArchivo($campo);
        }

        return $this;
    }

    /**
     * Resuelve la URL de un archivo, para usar en las vistas.
     */
    public function url($campo='foto')
    {
        //DO STORAGE
        //return url($this->getDir($campo).'/'.$this->$campo);
        if(!$this->tiene($campo)) return '';
        return url(Storage::disk(config('filesystems.contenido'))->url($this->getDir($campo).'/'.$this->$campo));
    }

    /**
     * Resuelve el path absoluto de un archivo, puede tener alguna utilidad
     * en un controlador (por eso es pública). También se usa internamente.
     */
    public function pathAbsoluto($campo='foto')
    {
        if(config('filesystems.contenido')=='local') {
            return storage_path('app/' . $this->getDir($campo).'/'.$this->$campo);
        }

        return $this->getDir($campo).'/'.$this->$campo;
    }

    /**
     * Resuelve el path relativo al storage de un archivo, puede tener alguna utilidad
     * en un controlador (por eso es pública). También se usa internamente.
     */
    public function path($campo='foto')
    {
        return $this->getDir($campo).'/'.$this->$campo;
    }

    /**
     * Resuelve el path absoluto de la carpeta temporal para un archivo,
     * es para hacerle cosas antes de subirlo al storage.
     */
    protected function path_temporal($nombre)
    {
        if(!File::exists(storage_path('app/temporal'))) {
            File::makeDirectory(storage_path('app/temporal'));
        }

        return storage_path('app/temporal/'.$nombre);
    }

    /**
     * Verifica si existe el archivo (en la base y en el sistema de archivos).
     */
    public function tiene($campo)
    {
        //DO STORAGE
        // if(File::isFile($this->path($campo))) {
        //     return true;
        // }

        //workaround autogenerar thumbnails si no existen
        if(($madre = $this->campoEsThumbnail($campo)) && !$this->$campo) {
            if($this->$madre) {
                $this->crearThumbnails()->save();
            }
        }

        if(!$this->$campo) return false;
        if(Storage::disk(config('filesystems.contenido'))->exists($this->getDir($campo).'/'.$this->$campo)) {
            return true;
        }
        return false;
    }

    //// FIN DE LA API, AHORA SON FUNCIONES INTERNAS


    /**
     * Inyecta la funcionalidad de eliminarTodosLosArchivos al llamar a 'delete'
     */
    protected static function bootTieneArchivos() {
        static::deleting(function($modelo) { //se llama antes del delete en cuestión
            $modelo->eliminarTodosLosArchivos();
        });
    }

    /**
     * Obtiene el directorio en el que corresponde guardar el campo. Resuelve
     * tanto campos 'padre' como campos 'thumbnail'.
     */
    public function getDir($campo) {
        if ($campoThumbnail=$this->campoEsThumbnail($campo)) {
            $dir=$campoThumbnail;
        } else {
            $dir=$campo;
        }

        if(config('filesystems.contenido')=='local') {
            $directorio = 'public/' . $this->dir[$dir];
        } else {
            $directorio = $this->dir[$dir];    
        }

        if(method_exists($this, 'subdirectorio') && $this->subdirectorio()) {
            $directorio .= '/' . $this->subdirectorio();
        }

        return $directorio;
    }

    /**
     * Resuelve si el nombre de un campo que se pasó es 'padre' (devuelve false)
     * o es un thumbnail de otro campo (devuelve el nombre del campo padre)
     */
    protected function campoEsThumbnail($campo)
    {
        if (
                property_exists(get_class($this), 'thumbnails') &&
                is_array($this->thumbnails)
            )
        {
            foreach($this->thumbnails as $campoPadre=>$camposThumbnail) {
                foreach($camposThumbnail as $campoThumbnail => $tamanos) {
                    if($campoThumbnail==$campo) {
                        return $campoPadre;
                    }
                }
            }
        }

        return false;
    }
}
