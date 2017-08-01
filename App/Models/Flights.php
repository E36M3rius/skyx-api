<?php

namespace App\Models;

use App\Repositories\Repository as Repository;

class Flights extends Model
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
     * @return array/generator
     */
    public function get($where = array(), $order = array(), $join = array(), $fields = array())
    {
      return $this->repository->get($where, $order, $join, $fields);
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

    /**
     * Ads a flight. Consist of Airport from and to destinations as well
     * as airlines. At this point, the flight is not linked to any trip
     *
     * @param int $airportFromId
     * @param int $airportToId
     * @param int $airlinesId
     */
    public function add($airportFromId, $airportToId, $airlinesId)
    {
      $data = array(
        'airport_from_id' => $airportFromId,
        'airport_to_id' => $airportToId,
        'airlines_id' => $airlinesId
      );

      return $this->repository->add($data);
    }

}
