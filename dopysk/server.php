<?php

session_start();

try {
    // подключаемся к серверу
    $conn = new PDO("mysql:host=localhost;dbname=cms;", "root", "feycizho");
}
catch (PDOException $e) {
    PDOError($e);
    return false;
}

if(isset($_POST)) {
    $_SESSION['msg'] = 'Attempt to connect...';

    $data = file_get_contents("php://input");
    $user = json_decode($data, true);  

    $name = $user['name'];
    $password = $user['password'];    

    try {
        $result = $conn->query("
            SELECT `token`
            FROM `dopysk-login` WHERE
            `name`='{$name}' AND `password`='{$password}'"
        );
    } catch (PDOException $e) {
        PDOError($e);
        return false;
    }

    $arr_res = array(
        'type' => false,
        'msg' => "error!"
    );

    if($row = $result->fetch()) {
        $_SESSION["msg"] = "Hello {$name}! Token = {$row[0]}";
        $arr_res['type'] = true;
        $arr_res['msg'] = 'Success!';
    } else {
        $_SESSION['msg'] = 'Sorry, incorrect name or password!';
    }

    echo json_encode($arr_res);
    return false;
}

function PDOError(PDOException $e) {
    echo json_encode(
        array(
            "type" => false, // temporary echo
            "msg" => $e->getMessage()
        ));
}