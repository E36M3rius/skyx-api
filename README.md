# --- Skyx API V1 ---
A web service (API) that serves as the engine for front-end websites to manage trips for their customers.

## Installation (DEV):
_This needs some work_
###### 1. `Git clone`
###### 2. `Vagrant up` (navigate to vagrant directory first)
###### 3. You will get `api.skyx.dev`

#
#
#
## Usage (available api methods):

For demo purposes I just went with GET requests. I omitted post, put, delete
restful requests as a result.
#
...
#
`1. /api/v1/airports/get`
#
_gets a list of all the airports in alphabetical order._
#
`2. /api/v1/flights/get`
#
_gets a list of all the flights_
#
`3. /api/v1/airports/get/{id}`
#
_gets airport data by id_
#
`4. /api/v1/trips/get/{name}`
#
_gets trip data by name. It includes flight, airport, airlines data_
#
`5. /api/v1/trips/get`
#
_gets a list of all the trips_
#
`6. /api/v1/flights/add/{airportFromId}/{airportToId}/{airlinesId}`
#
_inserts a flight_
#
`7. /api/v1/trips/addFlight/{fightId}/{tripName}`
#
_links a flight to a trip_
#
`8. /api/v1/trips/deleteFlight/{fightId}/{tripName}`
#
_removes flight link from a trip_
#
`9. /api/v1/airlines/get`
#
_gets a list of all the airlines in alphabetical order._
#
Production hostname: skyx-api.mariusiordache.me
Client key: ?clientKey=tkiy538lbkqnzcyjflxcikgxz
#
## Technology stack:
`php | slim | nginx | mariadb | docker`

#
#
## Disclaimer:
`In a production environment I would have made more changes such as:`

* Add cache
* Add a messaging queue for processing the inserts/updates/deletes
* Add proper Restful api request methods
* Proper error handling
* Unit testing
* Monitoring and alerting

#
#
## Support:
author: `Marius Iordache`
#
Linkedin: https://www.linkedin.com/in/themarius/
#
Personal Site: http://mariusiordache.me/
#
Twitter: https://twitter.com/onlyonemarius
#
license: `MIT`
