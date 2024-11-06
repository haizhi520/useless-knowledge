<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    exit(0);
}

header("Content-Type: application/json");
function myFunction($param1, $param2)
{

    echo "Parameters using print_r:\n";
    print_r([$param1, $param2]);
    error_log($param1);
    var_dump($param1);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    myFunction("Hello", [1, 2, 3]);
    $data = json_decode(file_get_contents("php://input"), true);
    // error_log(print_r($data, true));
    $name = $data['name'] ?? '';
    $email = $data['email'] ?? '';

    if (empty($name) || empty($email)) {
        echo json_encode(["status" => "error", "message" => "All fields are required!"]);
    } else {
        echo json_encode(["status" => "success", "message" => "Hello, $name! Your email is $email."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Only POST requests are allowed!"]);
}