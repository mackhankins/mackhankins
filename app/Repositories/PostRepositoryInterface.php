<?php

namespace MH\Repositories;


interface PostRepositoryInterface {

    public function getAll();

    public function limit($int);

    public function paginate($int);

    public function findById($int);

    public function findBySlug($string);

    public function searchPosts($string);

    public function datatables();

    public function store(array $array);

    public function update($int, array $array);
}