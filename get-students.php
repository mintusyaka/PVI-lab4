<?php

try {
    // подключаемся к серверу
    $conn = new PDO("mysql:host=localhost;dbname=cms;", "root", "feycizho");
}
catch (PDOException $e) {
    PDOError($e);
}

$students = &get_students($conn);

if(!$students) {
    echo json_encode(array(
        "type" => false,
        "msg" => "No students!"
    ));
    return false;
} else {

    $arr_res = array(
        "type"=> true,
        "msg" => array()
    );
    foreach( $students as $student ) {
        $arr_res["msg"][] = array(
            "name" => $student->name,
            "birthday" => $student->birthday,
            "sex" => $student->sex,
            "group" => $student->group_name
        );
    }

    echo json_encode($arr_res);
    return true;
}
function &get_students(PDO $conn) {
    try {
        $result = $conn->query("SELECT * FROM `students`");
    } catch (PDOException $e) {
        PDOError($e);
    }

    if(!$result) {
       return null;
    }

    $students = array();
    while ($row = $result->fetch()) {
        $student = new Student($row[0], $row[1], $row[2], $row[3]);
        $students[] = $student;
    }
    return $students;
}

function PDOError(PDOException $e) {
    echo json_encode(
        array(
            "type" => false, // temporary echo
            "msg" => $e->getMessage()
        ));
    return false;
}

class Student {
    
    public $name;
    public $birthday;
    public $sex;
    public $group_name;

    public function __construct($name, $birthday, $sex, $group_name) {
        $this->name = $name;
        $this->birthday = $birthday;
        $this->sex = $sex;
        $this->group_name = $group_name;
    }
}