<?php

namespace App\Http\Controllers\Traits;

trait FileTransfer
{
    public function getImagePath($file, $urlprefix){
        if($file){
            $name = $file->getClientOriginalName();
            $contents = file_get_contents($file);
            $path = $urlprefix.'/' . rand(111, 999) . '_' . str_replace(' ','_', $name);
            \Storage::put($path, $contents, 'public');
            return $path;
        }

        return null;

    }
}
