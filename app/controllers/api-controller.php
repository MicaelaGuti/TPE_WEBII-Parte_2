<?php
require_once '../Travello-rest/app/models/trip.model.php';
require_once '../Travello-rest/app/views/api.view.php';
require_once '../Travello-rest/helpers/auth.helper.php';

class ApiController
{
    private $trip_model;
    private $api_view;
    private $data;
    private $helper;
    private $columns;
    private $order;
    function __construct()
    {
        $this->trip_model = new TripModel();
        $this->api_view = new ApiView();
        $this->data = file_get_contents("php://input");
        $this->helper = new AuthHelper();
        $this->columns  = array(
            "idTrip",
            "placeOfDeparture",
            "placeOfDestination",
            "date",
            "passengers",
            "price",
            "airline_fk",
        );
        $this->order = array(
            "asc",
            "desc"
        );
    }
    private function getData()
    {
        return json_decode($this->data);
    }
    function getAllTrips($params = null)
    {
        try {
            $trips = $this->trip_model->getAll();
            if ($trips) {
                //si hay items en la tabla, entonces comenzamos a trabajar
                if (!empty($_GET['sort']) && !empty($_GET['order']) && isset($_GET['page']) &&  !empty($_GET['limit']) && !empty($_GET['filter'])) {
                    //ordena pagina y filtra en caso que el usuario setee todos estos parametros $_GET
                    $sort = $_GET['sort'];
                    $order = $_GET['order'];
                    $page = $_GET['page'];
                    $limit = $_GET['limit'];
                    $filter = $_GET['filter'];
                    //verifica que la columna exista y el orden sea ASC o DESC
                    if (in_array($sort, $this->columns) && in_array($order, $this->order)) {
                        $trips = $this->trip_model->getAll($sort, $order, $page, $limit, $filter);
                        $this->api_view->response($trips, 200, "Se ordenaron, paginaron y filtraron " . count($trips) . "  viajes exitosamente");
                    } else {
                        $this->api_view->response("Columna desconocida u orden distinto de ASC/DESC", 404);
                    }
                } else if (!empty($_GET['sort']) && !empty($_GET['order']) && !empty($_GET['filter'])) {
                    //ordena y filtra en caso que el usuario setee todos estos parametros
                    $sort = $_GET['sort'];
                    $order = $_GET['order'];
                    $filter = $_GET['filter'];

                    //verifica que la columna exista y el orden sea ASC o DESC
                    if (in_array($order, $this->order) && in_array($sort, $this->columns)) {
                        $trips = $this->trip_model->getAll($sort, $order, null, null, $filter);
                        $this->api_view->response($trips, 200, "Se ordenaron y filtraron " . count($trips) . " viajes exitosamente");
                    } else {
                        $this->api_view->response("Columna desconocida u orden distinto de ASC/DESC", 404);
                    }
                } else if (!empty($_GET['sort']) && !empty($_GET['order']) && isset($_GET['page']) && $_GET['limit']) {
                    //ordena ASC o DESC y pagina en caso que el usuario setee todos estos parametros
                    $sort = $_GET['sort'];
                    $order = $_GET['order'];
                    $page = $_GET['page'];
                    $limit = $_GET['limit'];
                    if (in_array($order, $this->order) && in_array($sort, $this->columns)) {
                        $trips = $this->trip_model->getAll($sort, $order, $page, $limit);
                        $this->api_view->response($trips, 200, "Se ordenaron y paginaron " . count($trips) . " viajes exitosamente");
                    } else {
                        $this->api_view->response("Columna desconocida u orden distinto de ASC/DESC", 404);
                    }
                } else if (!empty($_GET['sort']) && !empty($_GET['order'])) {
                    //ordena ASC o DESC en caso que el usuario setee estos parametros
                    $sort = $_GET['sort'];
                    $order = $_GET['order'];
                    if (in_array($order, $this->order) && in_array($sort, $this->columns)) {
                        $trips = $this->trip_model->getAll($sort, $order);
                        $this->api_view->response($trips, 200, "se ordenaron " . count($trips) . " viajes exitosamente");
                    } else {
                        $this->api_view->response("Columna desconocida u orden distinto de ASC/DESC", 404);
                    }
                } else if (isset($_GET['filter']) && isset($_GET['page']) && isset($_GET['limit'])) {
                    $filter = $_GET['filter'];
                    $page = $_GET['page'];
                    $limit = $_GET['limit'];

                    $trips = $this->trip_model->filterPages($filter, $page, $limit);
                    if (!$trips) {
                        $this->api_view->response("No se pudo filtrar y paginar", 404);
                    }
                    $this->api_view->response($trips, 200, "Se filtro y pagino exitosamente");
                    
                } else if (isset($_GET['filter']) && isset($_GET['field'])) {
                    //filtra por todos los campos de la tabla individualmente
                    $filter = $_GET['filter'];
                    $field = $_GET['field'];
                    if (in_array($field, $this->columns)) {
                        $trips = $this->trip_model->filterByField($field, $filter);

                        if ($trips) {
                            $this->api_view->response($trips, 200, "Se filtraron " . count($trips) . " viajes de la columna " . $field .  "  exitosamente");
                        } else {
                            $this->api_view->response("No se encontraron resultados ", 404);
                        }
                    } else {
                        $this->api_view->response("Columna desconocida u orden distinto de ASC/DESC", 404);
                    }
                } else if (isset($_GET['page']) && isset($_GET['limit'])) {
                    $page = $_GET['page'];
                    $limit = $_GET['limit'];
                    $trips = $this->trip_model->pagination($page, $limit);
                    if (!$trips) {
                        $this->api_view->response("No se pudo paginar ningun viaje", 404);
                    }
                    $this->api_view->response($trips, 200, "Mostrando " . count($trips) . " viajes");
                } else if (isset($_GET['filter'])) {
                    //filtra por todos los campos de la tabla en caso que setee ese parametro

                    $filter = $_GET['filter'];
                    $trips = $this->trip_model->getAll(null, null, null, null, $filter, null);
                    if ($trips) {
                        $this->api_view->response($trips, 200, "Se filtraron " . count($trips) . " viajes exitosamente");
                    } else {
                        $this->api_view->response("No se encontraron resultados ", 404);
                    }
                } else {
                    $trips = $this->trip_model->getAll();
                    $this->api_view->response($trips, 200, "Mostrando " . count($trips) . " viajes");
                }
            } else {
                $this->api_view->response("No se encontraron viajes realacionados con el criterio", 404);
            }
        } catch (\Throwable $th) {
            $this->api_view->response("Error no encontrado", 500);
        }
    }


    function getTrip($params = null)
    { //devuelve un unico item por ID
        try {
            $id = $params[':ID'];
            $trip = $this->trip_model->get($id);
            if ($trip)
                $this->api_view->response($trip);
            else
                $this->api_view->response("El viaje con el id $id no existe", 404);
        } catch (\Throwable $th) {
            $this->api_view->response("Error no encontrado", 500);
        }
    }
    function deleteTrip($params = null)
    {
        //elimina un unico item por ID
        try {
            if (!$this->helper->isLogged()) {
                $this->api_view->response("No estas loggeado", 401);
                return;
            }
            $id = $params[':ID'];
            $trip = $this->trip_model->get($id);
            if ($trip) {
                $this->trip_model->delete($id);
                $this->api_view->response($trip, 200, "Se elimino correctamente el viaje con el id $id");
            } else
                $this->api_view->response("El viaje con el id $id no existe", 404);
        } catch (\Throwable $th) {
            $this->api_view->response("Error no encontrado", 500);
        }
    }
    function insertTrip()
    {
        //inserta un unico item por body (getData()) 
        try {
            if (!$this->helper->isLogged()) {
                $this->api_view->response("No estas loggeado", 401);
                return;
            }
            $trip = $this->getData();
            if (empty($trip->placeOfDeparture) || empty($trip->placeOfDestination) || empty($trip->date) || empty($trip->passengers) || empty($trip->price)|| empty($trip->airline_fk)) {
                $this->api_view->response("Complete todos los datos", 400);
            } else {
                $id = $this->trip_model->insert($trip->placeOfDeparture, $trip->placeOfDestination, $trip->date, $trip->passengers, $trip->price, $trip->airline_fk);
                $trip = $this->trip_model->get($id);
                $this->api_view->response($trip, 201, "Se agrego correctamente el viaje con el id $id");
            }
        } catch (\Throwable) {
            $this->api_view->response("Error no encontrado", 500);
        }
    }

    function updateTrip($params = null)
    {
        //actualiza un unico item por body (getData())
        try {
            if (!$this->helper->isLogged()) {
                $this->api_view->response("No estas loggeado", 401);
                return;
            }
            $id = $params[':ID'];
            $body = $this->getData();
            if (empty($body->date) || empty($body->price)|| empty($body->passengers)) {
                $this->api_view->response("Complete todos los datos", 400);
            } else {
                $this->trip_model->update($body->date, $body->price, $body->passengers, $id);
                $this->api_view->response($body, 201, "Se actualizo correctamente el viaje con el id $id");
            }
        } catch (\Throwable $th) {
            $this->api_view->response("Error no encontrado", 500);
        }
    }
    function filterTrips($params = null)
    {
        //devuelve un arreglo de items dependiendo su aerolinea (id_fk) 
        try {
            $id_fk = $_GET['airline'];

            $trips = $this->trip_model->filterTrips($id_fk);
            if ($trips) {
                $this->api_view->response($trips, 200, "Mostrando " . count($trips) . " viajes a realizarse con la aerolinea $id_fk");
            }
        } catch (\Throwable $th) {
            $this->api_view->response("Error no encontrado", 500);
        }
    }
}
