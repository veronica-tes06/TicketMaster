<?php
session_start();
require_once __DIR__ . '/../../controllers/EventController.php';

use App\Controllers\EventController;

$eventID = $_POST['eventID'] ?? null;

if (!$eventID) {
    die("Invalid request.");
}

require_once __DIR__ . '/bookEvent.php';
