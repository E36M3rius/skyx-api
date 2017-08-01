<?php

namespace App\Models;

use App\Repositories\Repository as Repository;

class Airports extends Model
{

    /**
     * @var Repository $repository
     */
    protected $repository;

    /**
     * Constructor. Adds $repository via dependecy injection
     *
     * @param Repository $repository [description]
     */
    public function __construct(Repository $repository)
    {
      $this->repository = $repository;
    }

    /**
     * Get all airports. Extra arguments can be passed in order to
     * be able to filter the results
     *
     * @param  array  $where
     * @param  array  $order
     * @return array/generator
     */
    public function get($where = array(), $order = array('name' => 'ASC'))
    {
      return $this->repository->get($where, $order);
    }

    /**
     * Get airport by id
     *
     * @see $this->get
     * @param  int $id
     * @return array/generator
     */
    public function getById($id)
    {
      return $this->get(array('id' => $id));
    }

}
