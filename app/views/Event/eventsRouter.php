<?php

require_once __DIR__ . '/../../controllers/EventController.php';

use App\Controllers\EventController;

session_start();

$controller = new EventController();
$controller->showEventsPage($_SESSION['user']['accAdmin']);
