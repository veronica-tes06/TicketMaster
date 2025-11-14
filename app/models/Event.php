<?php
namespace App\Models;

class Event
{
    private $id;
    private $name;
    private $location;
    private $date;
    private $time;
    private $performer;
    private $maxTickets;
    private $minTickets;

    public function __construct(
        $name = '',
        $location = '',
        $date = '',
        $time = '',
        $performer = '',
        $maxTickets = 0,
        $minTickets = 1
    ) {
        $this->name = $name;
        $this->location = $location;
        $this->date = $date;
        $this->time = $time;
        $this->performer = $performer;
        $this->maxTickets = $maxTickets;
        $this->minTickets = $minTickets;
    }

    //add event function
    public function save()
    {
        require __DIR__ . '/../config/connect.php';

        $stmt = $db->prepare("
            INSERT INTO events 
            (eventName, eventLocation, eventDate, eventTime, performer, eventTicketMaxAMT, eventTicketMinAMT)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $this->name,
            $this->location,
            $this->date,
            $this->time,
            $this->performer,
            $this->maxTickets,
            $this->minTickets
        ]);
    }

    //gett all events, if admin show all, if user show future only
    public static function all($isAdmin)
    {
        require __DIR__ . '/../config/connect.php';

        if ($isAdmin) {
            $sql = "SELECT * FROM events ORDER BY STR_TO_DATE(eventDate, '%d-%m-%Y')";
        } else {
            $sql = "SELECT * FROM events 
                    WHERE STR_TO_DATE(eventDate, '%d-%m-%Y') >= CURDATE()
                    ORDER BY STR_TO_DATE(eventDate, '%d-%m-%Y')";
        }

        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //findby id function
    public static function find($id)
    {
        require __DIR__ . '/../config/connect.php';

        $stmt = $db->prepare("SELECT * FROM events WHERE eventID = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        $event = new Event(
            $row['eventName'] ?? '',
            $row['eventLocation'] ?? '',
            $row['eventDate'] ?? '',
            $row['eventTime'] ?? '',
            $row['performer'] ?? '',
            $row['eventTicketMaxAMT'] ?? 0,
            $row['eventTicketMinAMT'] ?? 1
        );
        $event->id = $row['eventID'] ?? null;
        return $event;
    }

    //find by name function
    public static function findByName($name)
    {
        require __DIR__ . '/../config/connect.php';

        $stmt = $db->prepare("SELECT * FROM events WHERE eventName = ?");
        $stmt->execute([$name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //delete function
    public static function delete($id)
    {
        require __DIR__ . '/../config/connect.php';

        $stmt = $db->prepare("DELETE FROM events WHERE eventID = ?");
        return $stmt->execute([$id]);
    }

    // Getters for properties (used in tests and elsewhere)
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function getPerformer()
    {
        return $this->performer;
    }

    public function getMaxTickets()
    {
        return $this->maxTickets;
    }

    public function getMinTickets()
    {
        return $this->minTickets;
    }
}
