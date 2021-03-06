<?php
  class db {
    // To use our database

    // To use this class following:
    // $db = new db();
    // $sql = "SELECT * FROM product WHERE idproduct=:productid";
    // $input = array(
    //   "productid" => 7
    // );
    // $db->readData($sql, $input);
    // This is called prepared statements

    private $conn;
    private $serverIP;
    private $port;
    private $databaseName;
    private $username;
    private $password;
    // Properties for the database

    function __construct() {
      // Starts when the class is called
      $this->serverIP = $GLOBALS['config']['db-ip'];
      $this->port = $GLOBALS['config']['db-port'];
      $this->databaseName = $GLOBALS['config']['db-name'];
      $this->username = $GLOBALS['config']['db-user'];
      $this->password = $GLOBALS['config']['db-password'];

      try {
        $conn = new PDO("mysql:host=$this->serverIP;port=$this->port;dbname=$this->databaseName", $this->username, $this->password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
        // To fix limit issue with prepared statement


        $this->conn = $conn;
        return("Succes");
      }
      catch (Exception $e) {
        die('<h1>NO DB CONNECTION</h1>');
        return ("Connection failed: " . $e->getMessage());
      }
    }
    function __destruct() {
      $this->conn = null;
    }
    // SQL -> sql string prepared
    // input = array contains the input for the prepared statements
    function CreateData($sql, $input) {
      // Create some data

      // Used prepared statements

      try {
        $conn = $this->conn;

        $query = $conn->prepare($sql);
        $query->execute($input);

        $result = $query->rowCount();
        $lastID = $conn->lastInsertId();
        return($lastID);
      } catch (Exception $e) {
        return ("Couldn't create data: " . $e->getMessage());
      }
    }

    // SQL -> sql string prepared
    // input = array contains the input for the prepared statements
    function readData($sql, $input) {
      try {
        $conn = $this->conn;
        $query = $conn->prepare($sql);

        $query->execute($input);

        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        return($row);
      } catch (Exception $e) {
          return ("Couldn't read data: " . $e->getMessage());
      }
    }
    // SQL -> sql string prepared
    // input = array contains the input for the prepared statements
    function UpdateData($sql, $input) {
      try {
        $conn = $this->conn;
        $query = $conn->prepare($sql);

        $query->execute($input);

        $row = $query->rowCount(PDO::FETCH_ASSOC);
        return("Succesfully updated");
      } catch (Exception $e) {
          return ("Couldn't update data: " . $e->getMessage());
      }
    }
    // SQL -> sql string prepared
    // input = array contains the input for the prepared statements
    function DeleteData($sql, $input) {
      try {
        $conn = $this->conn;

        $query = $conn->prepare($sql);
        $query->execute($input);

        $result = $query->rowCount();
        return(1);
      } catch (Exception $e) {
        return ("Couldn't delete data: " . $e->getMessage());
      }
    }
    public function countRows($sql, $input) {
      $conn = $this->conn;

      $query = $conn->prepare($sql);
      $query->execute($input);

      $count = $query->rowCount();
      return($count);

    }
  }
?>
