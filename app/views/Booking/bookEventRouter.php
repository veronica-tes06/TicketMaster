<?php
session_start();

if (!isset($_POST['eventID'])) {
    die("Invalid request.");
}

$eventID = $_POST['eventID'];

// Load model
require_once __DIR__ . '/../../models/Event.php';

// Import namespaced class
use App\Models\Event;

// Load event from DB
$event = Event::find($eventID);

if (!$event) {
    die("Event not found.");
}

// Load the booking form view
require_once __DIR__ . '/bookEvents.php';
