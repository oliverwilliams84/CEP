<?php

class MySQL_DataMapper
{
    //Provides a data wrapper service for database interactions

    /** @var PDO pdo */
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getFoodItemsByUserID($id) // TODO
    {
        $query = "SELECT `expirydate`,`category`,`userid`,`name`,`description`,`latit`,`longit`,`amount`,
                  `weight` ,`image`,`active`,`hidden` 
                    FROM `itemtable`
                    WHERE `foodid` = :id";
        $result = NULL;
        try {
            $stmt = $this->pdo->prepare($query);

            $stmt->execute(array(
                ':id' => $id
            ));

            $result = $stmt->fetchAll();
        } catch (PDOException $e) {
            if (DEBUG) echo 'Getting auth token failed: ' . $e->getMessage();
        }
        $stmt = NULL;
        return $result;
    }

    public function addNewFoodItem($name, $expirDate, $category, $userID,$desc, $lat, $long, $amount, $weight, $image)
    {
        $query = "INSERT INTO itemtable (name, expirydate, category,userid,description,latit,longit,amount,weight,image) 
        VALUES (:name, :expir, :cat, :uid, :desc, :lat, :long, :amount, :weight, :image)";
        $result = true;
        try {
            $stmt = $this->pdo->prepare($query);

            $stmt->execute(array(
                ':name' => $name,
                ':expir' => $expirDate,
                ':cat' => $category,
                ':uid' => $userID,
                ':desc' => $desc,
                ':lat' => $lat,
                ':long' => $long,
                ':amount' => $amount,               
                ':weight' => $weight,
                ':image' => $image
            ));
        } catch (PDOException $e) {
            if (DEBUG) echo 'Adding new user failed: ' . $e->getMessage();
            $result = false;
        }
        $stmt = NULL;
        return $result;
    }

    //Never call directly, simply inserts values. Use request handler
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

    public function addNewUserMessage($message, $sender, $receiver)
    {
        $query = "INSERT INTO messagetable (message, time) VALUES (:msg, NOW());";
        $query .= "INSERT INTO usermessagetable (messageid, sender, receiver) VALUES (LAST_INSERT_ID(), :send, :rec)";
        $result = true;
        try {
            $stmt = $this->pdo->prepare($query);

            $stmt->execute(array(
                ':msg' => $message,
                ':send' => $sender,
                ':rec' => $receiver
            ));
        } catch (PDOException $e) {
            if (DEBUG) echo 'Adding new user failed: ' . $e->getMessage();
            $result = false;
        }
        $stmt = NULL;
        return $result;
    }

    public function getUserMessagesByID($id)
    {
        $query = "SELECT `messagetable`.`message`,`messagetable`.`time`, `usermessagetable`.`sender`, `usermessagetable`.`receiver`
                    FROM `messagetable`
                        INNER JOIN `usermessagetable`
                        ON `messagetable`.`messageid` = `usermessagetable`.`messageid`
                    WHERE (`sender` = :id) OR (`receiver` = :id)";
        $result = NULL;
        try {
            $stmt = $this->pdo->prepare($query);

            $stmt->execute(array(
                ':id' => $id
            ));

            $result = $stmt->fetchAll();
        } catch (PDOException $e) {
            if (DEBUG) echo 'Get user messages failed: ' . $e->getMessage();
        }
        $stmt = NULL;
        return $result;
    }

    public function getAuthTokenByID($id)
    {
        $query = "SELECT `authToken`, IF(`expirDate` < NOW(),TRUE,FALSE) as isExpired
                    FROM `authtable`
                    WHERE `userid` = :id";
        $result = NULL;
        try {
            $stmt = $this->pdo->prepare($query);

            $stmt->execute(array(
                ':id' => $id
            ));

            $result = $stmt->fetchAll();
        } catch (PDOException $e) {
            if (DEBUG) echo 'Getting auth token failed: ' . $e->getMessage();
        }
        $stmt = NULL;
        return $result;
    }

    public function getPasswordByID($id)
    {
        $query = "SELECT `password`
                    FROM `usertable`
                    WHERE `userid` = :id";
        $result = NULL;
        try {
            $stmt = $this->pdo->prepare($query);

            $stmt->execute(array(
                ':id' => $id
            ));

            $result = $stmt->fetchAll();
        } catch (PDOException $e) {
            if (DEBUG) echo 'Getting password failed: ' . $e->getMessage();
        }
        $stmt = NULL;
        return $result;
    }

    public function addNewRequest($requester, $foodid)
    {
        $query = "INSERT INTO requesttable (requester, foodid) VALUES (:req, :food)";
        $result = true;
        try {
            $stmt = $this->pdo->prepare($query);

            $stmt->execute(array(
                ':req' => $requester,
                ':food' => $foodid
            ));
        } catch (PDOException $e) {
            if (DEBUG) echo 'Adding new request failed: ' . $e->getMessage();
            $result = false;
        }
        $stmt = NULL;
        return $result;
    }

    public function addNewRequestMessage($message, $sender, $requestID)
    {
        $query = "INSERT INTO messagetable (message, time) VALUES (:msg, NOW());";
        $query .= "INSERT INTO requestmessagetable (messageid, sender, requestid) VALUES (LAST_INSERT_ID(), :send, :reqid)";
        $result = true;
        try {
            $stmt = $this->pdo->prepare($query);

            $stmt->execute(array(
                ':msg' => $message,
                ':send' => $sender,
                ':reqid' => $requestID
            ));
        } catch (PDOException $e) {
            if (DEBUG) echo 'Adding new request message failed: ' . $e->getMessage();
            $result = false;
        }
        $stmt = NULL;
        return $result;
    }

    //Instate as boolean
    public function setRequestState($requestid, $instate)
    {
        $state = $instate ? 1 : 0;
        $query = "UPDATE `requesttable` SET `accepted` = :state WHERE `requestid` = :reqid";
        $result = true;
        try {
            $stmt = $this->pdo->prepare($query);

            $stmt->execute(array(
                ':reqid' => $requestid,
                ':state' => $state
            ));
        } catch (PDOException $e) {
            if (DEBUG) echo 'Set request state failed: ' . $e->getMessage();
            $result = false;
        }
        $stmt = NULL;
        return $result;
    }

    public function getRequestsByUserID($id)
    {
        $query = "SELECT `requestid`, `foodid`, `accepted`
                    FROM `requesttable`
                    WHERE `requestid` = :id";
        $result = NULL;
        try {
            $stmt = $this->pdo->prepare($query);

            $stmt->execute(array(
                ':id' => $id
            ));

            $result = $stmt->fetchAll();
        } catch (PDOException $e) {
            if (DEBUG) echo 'Getting requests by ID failed: ' . $e->getMessage();
        }
        $stmt = NULL;
        return $result;
    }

}
