<?php
header('Content-Type: application/json');

$response = [
    "status" => "ok",
    "message" => "Le backend fonctionne !"
];

echo json_encode($response);
