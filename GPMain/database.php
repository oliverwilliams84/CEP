<?php
function getPDO() {
    $servername = "mysql: host=127.0.0.1;dbname=gpmain;port=3306";
    $username = "gptest";
    $password = "123";

    // Create connection
    try {
        $conn = new PDO($servername, $username, $password,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_PERSISTENT => true));
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        return NULL;
    }

    return $conn;
}

class MySQL_DataMapper
{
    private $table = 'gpmain';

    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function fetchUserById($id)
    {
        $query = "SELECT * FROM `{$this->table}` WHERE `id` =:id";
        $stmt = $this->pdo->prepare($query);

        $stmt->execute(array(
            ':id' => $id
        ));

        return $stmt->fetch();
    }
}

//function execQuery($inputStr, $params) {
//    global $conn;
//    $result = NULL;
//
//    if (($conn != null) || connectDB()) {
//        $result = $conn->prepare($inputStr);
//        $result->execute($params);
//    } else {
//        return NULL;
//    }
//
//    return $result;
//}
