<?php

namespace MH\Repositories;


interface FileRepositoryInterface {

    public function buildImageJson($path,$file);
    public function uploadImage($file);

}