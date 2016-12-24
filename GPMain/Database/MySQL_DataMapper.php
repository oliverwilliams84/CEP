<?php

class MySQL_DataMapper
{

    //private $userstable = 'usertable';

    /** @var PDO pdo */
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    //Never call directly, simply inserts values
    public function addNewUser($un,$pw,$pic,$email)
    {
        $query = "INSERT INTO usertable (username, password, picture, email) VALUES (:un, :pw, :pic, :email)";
        $result = true;
        try {
            $stmt = $this->pdo->prepare($query);

            $stmt->execute(array(
                ':un' => $un,
                ':pw' => $pw,
                ':pic' => $pic,
                ':email' => $email
            ));
        } catch (PDOException $e) {
            if (DEBUG) echo 'Adding new user failed: ' . $e->getMessage();
            $result = false;
        }
        $stmt = NULL;
        return $result;
    }


}