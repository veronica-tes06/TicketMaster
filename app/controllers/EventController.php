<?php

/**
 * Event Management Controller
 * 
 *Acceptance Criteria:
 *Verify valid event name is unique with min 8 chars max 25 chars and only letters
 *Verify valid event location is between 6 to 30 letters
 *Verify valid event date is in "DD/MM/YY" format and is not before current date +1
 *Verify valid event time is "<0-23>:<0-59>" format
 *Verify performer name is 4 to 15 letters
 *Verify available tickets is between 30 and 1000
 *Verify invalid inputs produce an error message
 *Verify valid unique event ID is created and saved in DB
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Event.php';

class EventController
{
    //validate when creating an event method (not used yet)
    private function validateEvent($name, $location, $date, $time, $performer, $tickets)
    {
        //name
        if (!preg_match('/^[A-Za-z]{8,25}$/', $name)) {
            return "Event name must be 8–25 letters only.";
        }

        //unique name
        if (Event::findByName($name)) {
            return "Event name already exists.";
        }

        //location
        if (!preg_match('/^[A-Za-z ]{6,30}$/', $location)) {
            return "Location must be 6–30 letters only.";
        }

        //date format
        if (!preg_match('/^\d{2}\/\d{2}\/\d{2}$/', $date)) {
            return "Date must be DD/MM/YY.";
        }

        //date >= tomorrow
        $dateObj = DateTime::createFromFormat('d/m/y', $date);
        if (!$dateObj || $dateObj < new DateTime('+1 day')) {
            return "Date must be at least tomorrow.";
        }

        //time
        if (!preg_match('/^(2[0-3]|[0-1]?\d):[0-5]\d$/', $time)) {
            return "Time must be HH:MM.";
        }

        //performer name
        if (!preg_match('/^[A-Za-z ]{4,15}$/', $performer)) {
            return "Performer must be 4–15 letters.";
        }

        //tickets
        if ($tickets < 30 || $tickets > 1000) {
            return "Tickets must be 30–1000.";
        }

        return null;
    }

    //show which page method
    public function showEventsPage($isAdmin)
    {
        $eventModel = new Event();
        $events = $eventModel->all($isAdmin);

        if ($isAdmin) {
            require EVENT_VIEW . '/adminEvents.php';
        } else {
            require EVENT_VIEW . '/events.php';
        }
        exit;
    }

    //make an event method
    public function createEvent($name, $location, $date, $time, $performer, $tickets)
    {
        //validation
        $error = $this->validateEvent($name, $location, $date, $time, $performer, $tickets);
        if ($error) return $error;

        //create model and save
        $event = new Event($name, $location, $date, $time, $performer, $tickets);
        $event->save();

        header("Location: ../Event/adminEvents.php");
        exit;
    }

    //show 1 event method
    public function showSingleEvent($id)
    {
        $event = new Event();
        $event = $event->find($id);

        require EVENT_VIEW . '/viewEvent.php';
        exit;
    }

    //delete an event method
    public function deleteEvent($id)
    {
        $event = new Event();
        $event->delete($id);

        header("Location: " . EVENT_VIEW . "/adminEvents.php");
        exit;
    }
}
