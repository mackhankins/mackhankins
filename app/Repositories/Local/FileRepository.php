<?php


namespace MH\Repositories\Local;

use MH\Repositories\FileRepositoryInterface;
use Storage;
use Image;
use File;

class FileRepository implements FileRepositoryInterface {

    private function uniqueFileNames($path, $name, $ext)
    {
        $output = $name;
        $basename = basename($name, '.' . $ext);
        $i = 2;

        while (File::exists($path . $output))
        {
            $output = $basename . $i . '.' . $ext;
            $i ++;
        }

        return $output;
    }

    public function buildImageJson($path, $file)
    {
        $files = preg_grep('/index\.html$/', glob($path . '*'), PREG_GREP_INVERT);
        foreach ($files as $filename)
        {
            $img['thumb'] = url('images/small/' . basename($filename));
            $img['image'] = url('images/large/' . basename($filename));
            $img['title'] = basename($filename);
            $imag[] = $img;
        }

        Storage::disk('json')->put($file, stripslashes(json_encode($imag)));
    }

    public function uploadImage($file)
    {
        $filename = $this->uniqueFileNames
        (
            storage_path(env('UPLOAD_PATH')),
            $file->getClientOriginalName(),
            $file->getClientOriginalExtension()
        );
        $image = Image::make($file);
        $image->resize('1200', null);
        $image->save(storage_path(env('UPLOAD_PATH')) . $filename);

        return $filename;
    }


}