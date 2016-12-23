<?php
/** @var PDO $conn */
$conn = NULL;

function connectDB() {
    global $conn;
    if($conn == null){
        $servername = "mysql: host=127.0.0.1;dbname=gpmain;port=3306";
        $username = "gpmain";
        $password = "123";

        // Create connection
        try {
            $conn = new PDO($servername, $username, $password,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_PERSISTENT => true));
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            return false;
        }

    }
    return true;
}

function execQuery($inputStr, $params) {
    global $conn;
    $result = NULL;

    if (($conn != null) || connectDB()) {
        $result = $conn->prepare($inputStr);
        $result->execute($params);
    } else {
        return NULL;
    }

    return $result;
}
