<?php


namespace MH\Repositories\Local;

use MH\Repositories\FileRepositoryInterface;
use Storage;
use Image;
use File;

class FileRepository implements FileRepositoryInterface {


    /**
     * Return a unique file name
     *
     * @param $path
     * @param $name
     * @param $ext
     * @return string
     */
    private function uniqueFileNames($path, $name, $ext)
    {
        $output = $name;
        $basename = basename(str_slug($name), '.' . $ext);
        $i = 2;

        while (File::exists($path . $output))
        {
            $output = $basename . $i . '.' . $ext;
            $i ++;
        }

        return $output;
    }


    /**
     * Build a valid json file for Redactor with Laravel
     *
     * @param $path
     * @param $file
     * @return mixed|void
     */
    public function buildImageJson($path, $file)
    {
        $files = preg_grep('/index\.html$/', glob($path . '*'), PREG_GREP_INVERT);
        foreach ($files as $filename)
        {
            $img['thumb'] = url('images/small/' . basename($filename));
            $img['image'] = url('images/blog/' . basename($filename));
            $img['title'] = basename($filename);
            $imag[] = $img;
        }

        Storage::disk('json')->put($file, stripslashes(json_encode($imag)));
    }


    /**
     * Upload a file with Laravel and
     * resize with Intervention Image
     *
     * @param $file
     * @return string
     */
    public function uploadImage($file)
    {
        $filename = $this->uniqueFileNames
        (
            storage_path(env('UPLOAD_PATH')),
            $file->getClientOriginalName(),
            $file->getClientOriginalExtension()
        );
        $image = Image::make($file);
        // resize the image to a width of 300 and constrain aspect ratio (auto height)
        $image->resize(960, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $image->save(storage_path(env('UPLOAD_PATH')) . $filename);

        return $filename;
    }


}