<?php
require_once '../Travello-rest/libs/Router.php';
require_once '../Travello-rest/app/controllers/trip-controller.php';


//instancia el Router
$router = new Router();

$router->addRoute('trips', 'GET', 'ApiController', 'getAllTrips');
$router->addRoute('trips/:ID', 'GET', 'ApiController', 'getTrip');
$router->addRoute('airlines', 'GET', 'ApiController', 'filterTrips');
$router->addRoute('trips/:ID', 'DELETE', 'ApiController', 'deleteTrip');
$router->addRoute('trips', 'POST', 'ApiController', 'insertTrip');
$router->addRoute('trips/:ID', 'PUT', 'ApiController', 'updateTrip');



$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);
