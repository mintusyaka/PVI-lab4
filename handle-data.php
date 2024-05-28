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
            $user["name"] = trim($user["name"]);

            if (!isNameCorrect($user['name'])) {
                return false; 
            }
            if(
                isset($user['birthday']) &&
                isset($user['sex']) &&
                isset($user['group']))
            {
                try {
                    $result = $conn->query("SELECT `name`,`birthday`,`sex`,`group_name` FROM `students`;");
                } catch (PDOException $e) {
                    PDOError($e);
                }

                $students = array();

                $arr_res = array(
                    "type" => false,
                    "msg" => array()
                );

                if (!$result) {
                    $arr_res["msg"][] = "Could not retrieve employee list:";
                } else {
                    $new_student = new Student($user['name'], $user['birthday'], $user['sex'], $user['group']);

                    while ($row = $result->fetch()) {
                        $student = new Student($row[0], $row[1], $row[2], $row[3]);
                        $students[] = $student;
                    }

                    if(in_array($new_student, $students)) {
                        $arr_res["msg"][] = "Student already exist!\n";
                    } else {
                        if($user['mode'] == 'ADD') {
                            addNewStudentIntoDB($new_student, $conn);
                            $arr_res["msg"][] = "{$new_student->name} added successful!";
                        } else if ($user['mode'] == 'EDIT'){
                            editStudentInDB($new_student, $conn, $user['old_name']);
                            $arr_res["msg"][] = "{$new_student->name} edited successful!";
                        }
                        
                        $arr_res["type"] = true;
                        echo json_encode($arr_res);
                        return true;
                    }
                }
                echo json_encode($arr_res);
                return false;
            } else {
                echo json_encode(array("type" => false, "msg" => "Some fields don't set up!"));
                return false;
            }
        }
    }
    echo json_encode(array("type" => false, "msg" => "Some fields don't set up!"));
    return false;

}

function PDOError(PDOException $e) {
    echo json_encode(
        array(
            "type" => false, // temporary echo
            "msg" => $e->getMessage()
        ));
    return false;
}

function editStudentInDB(Student $student, PDO $conn, $name) {
    try {
        $conn->query("
            UPDATE`students`
            SET
            `name`='{$student->name}',
            `birthday`='{$student->birthday}',
            `sex`='{$student->sex}',
            `group_name`='{$student->group_name}'
            WHERE `name`='{$name}';
        ");
    }
    catch (PDOException $e) {
        PDOError($e);
    }
}

function addNewStudentIntoDB(Student $student, PDO $conn) {
    try {
        $conn->query("
        INSERT INTO `students`(`name`, `birthday`, `sex`, `group_name`) 
        VALUES ('{$student->name}','{$student->birthday}','{$student->sex}','{$student->group_name}');");
    }
    catch (PDOException $e) {
        PDOError($e);
    }
}

function isStartWithUppercase($str) {
    for($i = 0; $i < strlen($str); ++$i) {
        if(($i == 0) && !ctype_upper($str[$i])) {
            return false;
        }
        else if($i != 0 && $str[$i] == ' '){
            if($str[$i-1] == ' ') {
                return false;
            }
            continue;
        }
        else if(($i != 0) && ($str[$i-1] == ' ') && !ctype_upper($str[$i])) {
            return false;
        }
    }
    return true;
}

function isNameCorrect($name) {
    if (!preg_match ("/^[a-zA-z ]*$/", $name) ) {  
        echo json_encode(
            array(
                "type" => false,
                "msg" => "Name consist invalid characters!"
            )
        );
        return false;
    }
    else if(!isStartWithUppercase($name)) {
        echo json_encode(
            array(
                "type" => false,
                "msg" => "Name must starts with uppercase letter!"
            )
        );
        return false;
    }
    return true;
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