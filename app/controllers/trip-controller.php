<?php
require_once '../Travello-rest/app/models/trip.model.php';
require_once '../Travello-rest/app/views/api.view.php';


class ApiController
{
    private $trip_model;
    private $api_view;
    private $data;
    private $columns;
    private $order;
    function __construct()
    {
        $this->trip_model = new TripModel();
        $this->api_view = new ApiView();
        $this->data = file_get_contents("php://input");
        
        $this->columns  = array(
            "id",
            "placeOfDeparture",
            "placeOfDestination",
            "date",
            "passengers",
            "price",
            "airline",
        );
        $this->order = array(
            "asc",
            "desc"
        );
    }
    private function getData()
    {
        //var_dump($this->data); 
        //$a = json_decode($this->data, false);
        //var_dump( $a);
        //var_dump("^ JSON DECODE");
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
                        $this->api_view->response($trips);
                        http_response_code(200);
                    } else {
                         http_response_code(404);
                    }
                } else if (!empty($_GET['sort']) && !empty($_GET['order']) && !empty($_GET['filter'])) {
                    //ordena y filtra en caso que el usuario setee todos estos parametros
                    $sort = $_GET['sort'];
                    $order = $_GET['order'];
                    $filter = $_GET['filter'];

                    //verifica que la columna exista y el orden sea ASC o DESC
                    if (in_array($order, $this->order) && in_array($sort, $this->columns)) {
                        $trips = $this->trip_model->getAll($sort, $order, null, null, $filter);
                        $this->api_view->response($trips);
                        http_response_code(200);
                    } else {
                        http_response_code(404);
                    }
                } else if (!empty($_GET['sort']) && !empty($_GET['order']) && isset($_GET['page']) && $_GET['limit']) {
                    //ordena ASC o DESC y pagina en caso que el usuario setee todos estos parametros
                    $sort = $_GET['sort'];
                    $order = $_GET['order'];
                    $page = $_GET['page'];
                    $limit = $_GET['limit'];
                    if (in_array($order, $this->order) && in_array($sort, $this->columns)) {
                        $trips = $this->trip_model->getAll($sort, $order, $page, $limit);
                        $this->api_view->response($trips);
                        http_response_code(200);
                    } else {
                        http_response_code(404);
                    }
                } else if (!empty($_GET['sort']) && !empty($_GET['order'])) {
                    //ordena ASC o DESC en caso que el usuario setee estos parametros
                    $sort = $_GET['sort'];
                    $order = $_GET['order'];
                    if (in_array($order, $this->order) && in_array($sort, $this->columns)) {
                        $trips = $this->trip_model->getAll($sort, $order);
                        $this->api_view->response($trips);
                        http_response_code(200);
                    } else {
                        http_response_code(404);;
                    }
                } else if (isset($_GET['filter']) && isset($_GET['page']) && isset($_GET['limit'])) {
                    $filter = $_GET['filter'];
                    $page = $_GET['page'];
                    $limit = $_GET['limit'];

                    $trips = $this->trip_model->filterPages($filter, $page, $limit);
                    if (!$trips) {
                        http_response_code(404);;
                    }
                    $this->api_view->response($trips,);
                    http_response_code(200);
                    
                } 
                else if (isset($_GET['filter']) && isset($_GET['field'])) {
                    //filtra por todos los campos de la tabla individualmente
                    $filter = $_GET['filter'];
                    $field = $_GET['field'];
                    if (in_array($field, $this->columns)) {
                        $trips = $this->trip_model->filterByField($field, $filter);

                        if ($trips) {
                            $this->api_view->response($trips,);
                        } else {
                            http_response_code(404);;
                        }
                    } else {
                        http_response_code(404);;
                    }
                } else if (isset($_GET['page']) && isset($_GET['limit'])) {
                    $page = $_GET['page'];
                    $limit = $_GET['limit'];
                    $trips = $this->trip_model->pagination($page, $limit);
                    if (!$trips) {
                        http_response_code(404);;
                    }
                    $this->api_view->response($trips);
                    
                } else if (isset($_GET['filter'])) {
                    //filtra por todos los campos de la tabla en caso que setee ese parametro

                    $filter = $_GET['filter'];
                    $trips = $this->trip_model->getAll(null, null, null, null, $filter, null);
                    if ($trips) {
                        $this->api_view->response($trips);
                    } else {
                        http_response_code(404);
                    }
                } else {
                    $trips = $this->trip_model->getAll();
                    $this->api_view->response($trips);
                    
                }
            } else {
                http_response_code(404);
            }
        } catch (\Throwable $th) {
            http_response_code(500);
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
                http_response_code(404);
        } catch (\Throwable $th) {
            http_response_code(500);
        }
        
    }
    function deleteTrip($params = null)
    {
        //elimina un unico item por ID
        
            $id = $params[':ID'];
            $trip = $this->trip_model->get($id);
            if ($trip) {
                $this->trip_model->delete($id);
                $this->api_view->response($trip);
            } else
                 http_response_code(404);
      
    }
    function insertTrip()
    {
        //inserta un unico item por body (getData()) 
       
            $trip = $this->getData();
            
            if (empty($trip->date) || empty($trip->passengers) || empty($trip->placeOfDeparture) || empty($trip->placeOfDestination) || empty($trip->price)|| empty($trip->airline)) {
                http_response_code(400);
            } else {
                $id = $this->trip_model->insert($trip->date, $trip->passengers, $trip->placeOfDeparture, $trip->placeOfDestination, $trip->price, $trip->airline);
                $trip = $this->trip_model->get($id);
                $this->api_view->response($trip);
                http_response_code(201);
            }
        
    }

    function updateTrip($params = null)
    {
        //actualiza un unico item por body (getData())
       
            $id = $params[':ID'];
            $body = $this->getData();
            // var_dump($id);
            // var_dump($body);
            if (empty($body->date) || empty($body->passengers)|| empty($body->price)) {
                http_response_code(400);
            } else {
                $this->trip_model->update($body->date, $body->passengers, $body->price, $id);
                $this->api_view->response($body);
                http_response_code(201);
            }
        
    }


 }
