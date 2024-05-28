<?php

try {
    // подключаемся к серверу
    $conn = new PDO("mysql:host=localhost;dbname=cms;", "root", "feycizho");
}
catch (PDOException $e) {
    PDOError($e);
}

if(isset($_POST)) {
    $data = file_get_contents("php://input");
    $user = json_decode($data, true);  

    if(isset($user)) {
        if(isset($user['name'])) {
            try {
                $result = $conn->query("DELETE FROM `students` WHERE `name`='{$user['name']}';");
            } catch (PDOException $e) {
                PDOError($e);
            }
            echo json_encode(
                array(
                    "type" => true,
                    "msg" => "Student was deleted successful!"
            ));
            return true; 
        }

    }
}