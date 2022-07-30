<?php

namespace App\Traits;

trait Upload
{
    public function uploadImage($request, $path, $inputName)
    {
        $image = null;
        if ($request->file($inputName)) {
            $image = $request->file($inputName);
            $name = str_replace(' ', '_', $image->getClientOriginalName());
            $image->move(storage_path('/app/public/' . $path), $name);
            $image = "/storage/${path}/{$name}";
        }
        return $image;
    }
}
