<?php
class TripModel
{
    //modelo de datos de la tabla "trips"

    private $db;

    function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;' . 'dbname=db_tickets;' . 'charset=utf8', 'root', '');
    }
    function existeColumna()
    {
        try {
            $query = $this->db->prepare("SHOW COLUMS FROM trips");
            $query->execute();
            $resultado = $query->fetchAll(PDO::FETCH_OBJ);
        } catch (\Throwable $th) {
            $resultado = [];
        }
        return $resultado;
    }



    function getAll($sort = null, $order = null, $page = null, $limit = null, $filter = null,)
    {
        if (isset($sort) &&  isset($order) && isset($page) && isset($limit) && isset($filter)) {
            $offset = (($page - 1) * $limit);

            $query = $this->db->prepare("SELECT * FROM trips WHERE 	placeOfDeparture LIKE '%$filter%' OR placeOfDestination LIKE '%$filter%' OR airline LIKE '$filter' ORDER BY $sort $order LIMIT $offset , $limit");
            $query->execute();
            $trips = $query->fetchAll(PDO::FETCH_OBJ);
            return $trips;
        } else if (isset($sort) && isset($order) && isset($filter)) {
            $query = $this->db->prepare("SELECT * FROM trips WHERE placeOfDeparture LIKE '%$filter%' OR placeOfDestination LIKE '%$filter%' OR airline LIKE '$filter' ORDER BY $sort $order");
            $query->execute();
            $trips = $query->fetchAll(PDO::FETCH_OBJ);
            return $trips;
        } else if (isset($sort) && isset($order) && isset($page) && isset($limit)) {
            $offset = (($page - 1) * $limit);

            $query = $this->db->prepare("SELECT * FROM trips ORDER BY $sort $order LIMIT $offset , $limit");
            $query->execute();
            $trips = $query->fetchAll(PDO::FETCH_OBJ);
            return $trips;
        } else if (isset($sort) && isset($order)) {
            $query = $this->db->prepare("SELECT * FROM trips ORDER BY $sort $order");
            $query->execute();
            $trips = $query->fetchAll(PDO::FETCH_OBJ);
            return $trips;
        } else if (isset($filter)) {
            $query = $this->db->prepare("SELECT * FROM trips WHERE placeOfDeparture LIKE '%$filter%' OR placeOfDestination LIKE '%$filter%' OR airline LIKE '$filter'");
            $query->execute();
            $trips = $query->fetchAll(PDO::FETCH_OBJ);
            return $trips;
        } else {
            $query = $this->db->prepare("SELECT * FROM trips");
            $query->execute();
            $trips = $query->fetchAll(PDO::FETCH_OBJ);
            return $trips;
        }
    }
    function filterByField($field, $filter)
    {
        if (is_numeric($filter))
            $query = $this->db->prepare("SELECT * FROM trips WHERE $field  = $filter");
        else
            $query = $this->db->prepare("SELECT * FROM trips WHERE $field LIKE '%$filter%'");
        $query->execute();
        $trips = $query->fetchAll(PDO::FETCH_OBJ);
        return $trips;
    }
    function filterPages($filter, $page, $limit)
    {
        $offset = ($page - 1 * $limit);
        if (is_numeric($filter))
            $query = $this->db->prepare("SELECT * FROM trips WHERE placeOfDeparture = $filter OR placeOfDestination = $filter OR airline = $filter LIMIT $offset , $limit ");
        else
            $query = $this->db->prepare("SELECT * FROM trips WHERE placeOfDeparture LIKE '%$filter%' OR placeOfDestination LIKE '%$filter%' OR airline LIKE '$filter' LIMIT $offset , $limit");

        $query->execute();
        $trips = $query->fetchAll(PDO::FETCH_OBJ);
        return $trips;
    }

    // function filterTrips($id)
    // {
    //     $query = $this->db->prepare("SELECT * FROM trips INNER JOIN airlines  WHERE trips.airline = ? AND airlines.airline = ?");
    //     $query->execute([$id, $id]);
    //     $trips = $query->fetchAll(PDO::FETCH_OBJ);
    //     return $trips;
    // }
    function get($id)
    {
        $query = $this->db->prepare("SELECT * FROM trips WHERE id = ?");
        $query->execute([$id]);
        $trip= $query->fetch(PDO::FETCH_OBJ);
        return $trip;
    }
    function delete($id)
    {
        $query = $this->db->prepare("DELETE FROM trips WHERE id = ?");
        $query->execute([$id]);
    }
    function insert($date, $passengers, $placeOfDepature, $placeOfDestination, $price, $airline )
    {
        $query = $this->db->prepare("INSERT INTO trips( date , passengers , placeOfDeparture , placeOfDestination, price, airline) VALUES (? , ? , ? , ? , ? , ?)");
        $query->execute(array($date, $passengers, $placeOfDepature, $placeOfDestination, $price, $airline ));
        return $this->db->lastInsertId();
    }
    function update($date, $passengers, $price, $id)
    {
        $query = $this->db->prepare("UPDATE trips SET date = ? , passengers = ? , price = ? WHERE id = ? ");
        $query->execute(array($date, $passengers, $price, $id));
    }
    function pagination($page, $limit)
    {
        
        $page = $_GET['page'];
        $limit = $_GET['limit'];
        $offset = (($page - 1) * $limit);

        $query = $this->db->prepare("SELECT * FROM trips  LIMIT $offset , $limit");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
}
