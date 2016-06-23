<?php

namespace MH\Repositories;

/**
 * Interface FileRepositoryInterface
 * @package MH\Repositories
 */
interface FileRepositoryInterface
{


    /**
     * Build a valid json for Redactor with Laravel
     *
     * @param $path
     * @param $file
     * @return mixed
     */
    public function buildImageJson($path, $file);

    /**
     * Upload an Image with Laravel and
     * resize with Intervention Image
     *
     * @param $file
     * @return mixed
     */
    public function uploadImage($file);
}
