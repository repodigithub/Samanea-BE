<?php

namespace App\Traits;

trait Upload 
{
    public function uploadImage($request, $path, $inputName)
    {
        $image = null;
        if($request->file($inputName)){
            $image = $request->file($inputName);
            $image->storeAs($path, $image->hashName());
        }
        return $image;
    }
}