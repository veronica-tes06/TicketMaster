<?php
namespace App\Models;

use PDO;

//making the user model class
class User {
    private $id;
    private $email;
    private $password;
    private $isAdmin = 0;
    private $bookings = [];

    //constructor for the user class
    public function __construct($email = '', $password = '') {
        $this->email = $email;
        $this->password = $password;
    }

    //making the getters and setters of the class
    public function getId() { return $this->id; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function isAdmin() { return (bool)$this->isAdmin; }
    public function getBookings() { return $this->bookings; }

    public function setEmail($email) { $this->email = $email; }
    public function setPassword($password) { $this->password = $password; }

    //attempt login from the DB which returns true on success
    public function login() {
        require __DIR__ . '/../config/connect.php';

        $stmt = $db->prepare("SELECT * FROM accounts WHERE accEmail = ?");
        $stmt->execute([$this->email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        //verify if password is right and pass in the variables (from the DB) into the user if it works
        if ($row && $row['accPassword'] === $this->password) {
            $this->id = $row['accID'];
            $this->isAdmin = $row['accAdmin'];
            // Load bookings from eventDetails table
            $b = $db->prepare("SELECT eventID FROM eventDetails WHERE accID = ?");
            $b->execute([$this->id]);
            $this->bookings = $b->fetchAll(PDO::FETCH_COLUMN);
            return true;
        }
        return false;
    }

    //create a new account and return the created User object or false
    public function register() {
        require __DIR__ . '/../config/connect.php';

        //the check for if the email already exists and if it does return false
        $check = $db->prepare("SELECT accID FROM accounts WHERE accEmail = ?");
        $check->execute([$this->email]);
        if ($check->rowCount() > 0) return false;

        //insert the new user into the DB with their details if it passes the check
        $insert = $db->prepare("INSERT INTO accounts (accEmail, accPassword, accAdmin) VALUES (?, ?, 0)");
        if ($insert->execute([$this->email, $this->password])) {
            $this->id = $db->lastInsertId();
            $this->bookings = [];
            return true;
        }
        return false;
    }
}
