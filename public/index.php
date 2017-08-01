<?php

/**
 * Trip Builder API
 * A web service (API) that serves as the engine for
 * front-end websites to manage trips for their customers.
 *
 * @author Marius Iordache
 * @version 1.0
 */

/**
 * Add autoloader, PSR-4
 */
require '../vendor/autoload.php';

/**
 * Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
$app = new Slim\App();

/**
 * Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`
 * is an anonymous function.
 */
$app->get('/', function ($request, $response, $args) {
    $response->write("Skyx API v1.0 (Trip Builder)");
    return $response;
});

/**
 * Route for getting list of airports
 */
$app->get('/api/v1/airports/get', function ($request, $response, $args) {

  $get = $request->getQueryParams(); // get query string params

  // check if client is allowed to use the api
  if (isset($get['clientKey'])) {
    $isClientAllowed = (new \App\Api())->isClientAllowed($get['clientKey']);

    // get data
    $airports = (new \App\Models\Airports(
        new \App\Repositories\Repository(
          \App\Database\MariaDbDatabase::getInstance()
          , 'airports'
    )))->get();

    $results = array();

    if ($airports) {
        foreach($airports as $result) {
          $results[] = $result;
        }
    }

    // output results as json
    return $response
            ->withHeader("access-control-allow-origin", "*")
            ->withJson($results);
  }

});

/**
 * Route for getting list of airlines
 */
$app->get('/api/v1/airlines/get', function ($request, $response, $args) {

  $get = $request->getQueryParams(); // get query string params

  // check if client is allowed to use the api
  if (isset($get['clientKey'])) {
    $isClientAllowed = (new \App\Api())->isClientAllowed($get['clientKey']);

    // get data
    $airlines = (new \App\Models\Airlines(
        new \App\Repositories\Repository(
          \App\Database\MariaDbDatabase::getInstance()
          , 'airlines'
    )))->get();

    $results = array();

    if ($airlines) {
        foreach($airlines as $result) {
          $results[] = $result;
        }
    }

    // output results as json
    return $response
            ->withHeader("access-control-allow-origin", "*")
            ->withJson($results);
  }

});

/**
 * Route fo getting flights data
 */
$app->get('/api/v1/flights/get', function ($request, $response, $args) {

  $get = $request->getQueryParams(); // get query string params

  // check if client is allowed to use the api
  if (isset($get['clientKey'])) {
    $isClientAllowed = (new \App\Api())->isClientAllowed($get['clientKey']);

    // get flights data
    $flights = (new \App\Models\Flights(
        new \App\Repositories\Repository(
          \App\Database\MariaDbDatabase::getInstance()
          , 'flights'
    )))->get();

    $results = array();

    if ($flights) {
        foreach($flights as $result) {
          $results[] = $result;
        }
    }

    // output results as json
    return $response
            ->withHeader("access-control-allow-origin", "*")
            ->withJson($results);
  }

});

/**
 * Route for getting airport data by id
 */
$app->get('/api/v1/airports/get/{id}', function ($request, $response, $args) {

  $get = $request->getQueryParams(); // get query string params

  // check if client is allowed to use the api
  if (isset($get['clientKey'])) {
    $isClientAllowed = (new \App\Api())->isClientAllowed($get['clientKey']);

    $airports = (new \App\Models\Airports(
        new \App\Repositories\Repository(
          \App\Database\MariaDbDatabase::getInstance()
          , 'airports'
    )))->getById($args['id']);

    $results = array();

    if ($airports) {
        foreach($airports as $result) {
          $results[] = $result;
        }
    }

    // output results in json
    return $response
            ->withHeader("access-control-allow-origin", "*")
            ->withJson($results);
  }

});

/**
 * Route for getting trip data by name
 */
$app->get('/api/v1/trips/get/{name}', function ($request, $response, $args) {

  $get = $request->getQueryParams(); // get query string params

  // check if client is allowed to use the api
  if (isset($get['clientKey'])) {
    $isClientAllowed = (new \App\Api())->isClientAllowed($get['clientKey']);

    $trips = (new \App\Models\Trips(
        new \App\Repositories\Repository(
          \App\Database\MariaDbDatabase::getInstance()
          , 'trips'
    )))->getByName($args['name']);

    $results = array();

    if ($trips) {
        foreach($trips as $result) {
          $results[] = $result;
        }
    }

    // output results in json
    return $response
            ->withHeader("access-control-allow-origin", "*")
            ->withJson($results);
  }

});

/**
 * Route for getting all trips data
 */
$app->get('/api/v1/trips/get', function ($request, $response, $args) {

  $get = $request->getQueryParams(); // get query string params

  // check if client is allowed to use the api
  if (isset($get['clientKey'])) {
    $isClientAllowed = (new \App\Api())->isClientAllowed($get['clientKey']);

    $trips = (new \App\Models\Trips(
        new \App\Repositories\Repository(
          \App\Database\MariaDbDatabase::getInstance()
          , 'trips'
    )))->get();

    $results = array();

    if ($trips) {
        foreach($trips as $result) {
          if (!empty($result['trip_name'])) {
            $results[] = $result['trip_name'];
          }
        }
    }

    // output results in json
    return $response
            ->withHeader("access-control-allow-origin", "*")
            ->withJson(array_unique($results));
  }

});

/**
 * Route for adding flight data
 * Should use Slim::post or Slim::put
 */
$app->get(
  '/api/v1/flights/add/{airportFromId}/{airportToId}/{airlinesId}',
  function ($request, $response, $args)
  {
    $get = $request->getQueryParams(); // get query string params

    // check if client is allowed to use the api
    if (isset($get['clientKey'])) {
      $isClientAllowed = (new \App\Api())->isClientAllowed($get['clientKey']);

    // get result
    $result = (new \App\Models\Flights(
        new \App\Repositories\Repository(
          \App\Database\MariaDbDatabase::getInstance()
          , 'flights'
    )))->add($args['airportFromId'], $args['airportToId'], $args['airlinesId']);

    $message = "error";

    if ($result) {
        $message = "$result";
    }

    // output add confirmation
    return $response
            ->withHeader("access-control-allow-origin", "*")
            ->write($message);
  }

});

/**
 * Route for adding flights to the trips
 * Should use Slim::post or Slim::put
 */
$app->get(
  '/api/v1/trips/addFlight/{fightId}/{tripName}',
  function ($request, $response, $args)
  {
    $get = $request->getQueryParams(); // get query string params

    // check if client is allowed to use the api
    if (isset($get['clientKey'])) {
      $isClientAllowed = (new \App\Api())->isClientAllowed($get['clientKey']);

    // get result
    $result = (new \App\Models\Trips(
        new \App\Repositories\Repository(
          \App\Database\MariaDbDatabase::getInstance()
          , 'trips'
    )))->addFlight($args['fightId'], $args['tripName']);

    $message = "error";

    if ($result) {
        $message = "$result";
    }

    // output add confirmation
    return $response
            ->withHeader("access-control-allow-origin", "*")
            ->write($message);
  }

});

/**
 * Route for deleting a flight from a trip
 * Should use Slim::delete
 */
$app->get(
  '/api/v1/trips/deleteFlight/{fightId}/{tripName}',
  function ($request, $response, $args)
  {
    $get = $request->getQueryParams(); // get query string params

    // check if client is allowed to use the api
    if (isset($get['clientKey'])) {
      $isClientAllowed = (new \App\Api())->isClientAllowed($get['clientKey']);

      // get result
      $result = (new \App\Models\Trips(
          new \App\Repositories\Repository(
            \App\Database\MariaDbDatabase::getInstance()
            , 'trips'
      )))->deleteFlight($args['fightId'], $args['tripName']);

      $message = "error";

      if ($result) {
          $message = "deleted";
      }

      // output delete confirmation
      return $response
              ->withHeader("access-control-allow-origin", "*")
              ->write($message);
    }

});

/**
 * Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();
