<?php
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
require_once "includes.php";

//HANDLE GET
if ($_SERVER["REQUEST_METHOD"] == "GET") {

    //Get input variables
    $toGet = $_GET["topic"];
    $search = isset($_GET['q']) ? $_GET['q'] : "";



    echo json_encode($toEncode);
}

//HANDLE POST
elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $topic = $_POST["topic"];
    $question = $_POST["question"];
    $answer = $_POST["answer"];
    $authToken = $_POST["auth_token"];

    //Check Vars
    if (!is_numeric($topic)){
        die(json_encode(array("error" => "topic incorrectly defined")));
    } elseif (!is_string($question)) {
        die(json_encode(array("error" => "question incorrect defined")));
    } elseif (!is_string($answer)) {
        die(json_encode(array("error" => "answer incorrect defined")));
    } elseif (!is_string($authToken) || !checkAuthToken($authToken)) {
        die(json_encode(array("error" => "not authorised")));
    }

    if (execQuery($sql)) {
        $toEncode = array("success" => "topic added");
    } else {
        $toEncode = array("error" => "failed to add");
    }

    echo json_encode($toEncode);
}

//Otherwise??
else {
    echo "?";
};
?>