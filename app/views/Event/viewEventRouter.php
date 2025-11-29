<?php

require_once __DIR__ . '/../../controllers/EventController.php';


$eventID = (int) $_POST['eventID'];

$controller = new EventController();
$controller->showSingleEvent($eventID); // this loads the view with $event
?>