<?php

class MySQL_DataMapper
{

    private $userstable = 'usertable';

    /** @var PDO pdo */
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function fetchUserById($id)
    {
        $query = "INSERT INTO `{$this->userstable}` (username)";
        try {
            $stmt = $this->pdo->prepare($query);

            $stmt->execute(array(
                ':id' => $id
            ));
        } catch (PDOException $e) {
            if (DEBUG) echo 'Fetch failed: ' . $e->getMessage();
            return NULL;
        }

        return $stmt->fetch();
    }
}