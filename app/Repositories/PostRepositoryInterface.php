<?php

namespace MH\Repositories;

/**
 * Interface PostRepositoryInterface
 * @package MH\Repositories
 */
interface PostRepositoryInterface
{

    public function getAll();

    /**
     * @param $int
     * @return mixed
     */
    public function limit($int);

    /**
     * @param $int
     * @return mixed
     */
    public function paginate($int);

    /**
     * @param $int
     * @return mixed
     */
    public function paginatePosts($int);

    /**
     * @param $int
     * @return mixed
     */
    public function paginateLinks($int);

    /**
     * @param $int
     * @return mixed
     */
    public function findById($int);

    /**
     * @param $string
     * @return mixed
     */
    public function findBySlug($string);

    /**
     * @param $string
     * @return mixed
     */
    public function searchPosts($string);

    /**
     * @param array $array
     * @return mixed
     */
    public function store(array $array);

    /**
     * @param $int
     * @param array $array
     * @return mixed
     */
    public function update($int, array $array);

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);
}
