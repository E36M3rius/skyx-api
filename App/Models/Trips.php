<?php

namespace App\Models;

use App\Repositories\Repository as Repository;

class Trips extends Model
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
     * @return array/generator with results
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
     * @return array/generator with results
     */
    public function getById($id)
    {
      return $this->get(array('id' => $id));
    }

    /**
     * Get detailed information about a particular trip, by name. This includes
     * trip, flight, airport, airlines information
     *
     * @param  string $name
     * @return array results
     */
    public function getByName($name)
    {
      // set the join rules
      $join = array(
        'inner:flights' => 'trips.flight_id=flights.id',
        'left:airports t1' => 'flights.airport_from_id=t1.id',
        'left:airports t2' => 'flights.airport_to_id=t2.id',
        'inner:airlines' => 'flights.airlines_id=airlines.id'
      );

      // set the field requirement
      $fields = array(
        't1.name as airportFrom',
        't2.name as airportTo',
        't1.code as codeFrom',
        't2.code as codeTo',
        'airlines_name',
        'trip_name',
        't1.cityName as cityFrom',
        't2.cityName as cityTo',
        'flight_id'
      );

      return $this->get(array('trip_name' => $name), array(), $join, $fields);
    }

    /**
     * Not implemented yet
     */
    public function add()
    {
    }

    /**
     * Links a flight to a trip
     *
     * @param int $flightId
     * @param string $tripName
     * @return last insert id
     */
    public function addFlight($flightId, $tripName)
    {
      $data = array(
        'trip_name' => $tripName,
        'flight_id' => $flightId
      );

      return $this->repository->add($data);
    }

    /**
     * Removes flight link from trips
     *
     * @param  int $flightId [description]
     * @param  string $tripName [description]
     * @return affected rows
     */
    public function deleteFlight($flightId, $tripName)
    {
      $data = array(
        'trip_name' => $tripName,
        'flight_id' => $flightId
      );

      return $this->repository->delete($data);
    }

}
