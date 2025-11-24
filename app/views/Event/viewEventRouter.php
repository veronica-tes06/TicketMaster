<?php

require_once __DIR__ . '/../../controllers/EventController.php';

use App\Controllers\EventController;

$eventID = (int) $_POST['eventID'];

$controller = new EventController();
$controller->showSingleEvent($eventID); // this loads the view with $event
?>