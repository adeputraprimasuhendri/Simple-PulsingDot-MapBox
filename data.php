<?php
// Header JSON
header("Content-Type: application/json; charset=UTF-8");

// Inisialisasi array
$location = array();

// Contoh perulangan data
foreach ($data as $data) {
    array_push($location, array(
        "type" => "Feature",
        "properties" => array(
            "title" => $data->title,
            "icon" => "monument",
        ),
        "geometry" => array(
            "coordinates" => array(floatval($data->lng), floatval($data->lat)),
            "type" => "Point",
        ),
    ));
}

$dataset_mapbox = array(
    "features" => $location,
    "type" => "FeatureCollection",
);

echo json_encode($dataset_mapbox);
