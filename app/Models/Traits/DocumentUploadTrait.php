<?php
namespace App\Models\Traits;

use App\Models\Document;

trait DocumentUploadTrait {

    public function saveImage($db_column_name, $file, $urlprefix, $data=[]){
        $name = $file->getClientOriginalName();
        $contents = file_get_contents($file);
        $path = $urlprefix.'/' . $this->id . '/' . rand(111, 999) . '_' . str_replace(' ','_', $name);
        \Storage::put($path, $contents, 'public');
        $this->$db_column_name=$path;
        $this->save();
    }

}
