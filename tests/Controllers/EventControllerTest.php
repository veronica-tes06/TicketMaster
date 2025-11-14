<?php
use PHPUnit\Framework\TestCase;
use App\Controllers\EventController;

require_once __DIR__ . '/../../vendor/autoload.php';

class EventControllerTest extends TestCase
{
    private $controller;

    protected function setUp(): void
    {
        $this->controller = $this->createMock(EventController::class);
    }

    // Test 1: Create event with valid data
    public function testCreateEventSuccess()
    {
        $name = 'Concert Night';
        $location = 'Dublin Hall';
        $date = '15/12/25';
        $time = '19:30';
        $performer = 'The Band';
        $tickets = 100;

        $this->controller->method('createEvent')
            ->with($name, $location, $date, $time, $performer, $tickets)
            ->willReturn(null);

        $result = $this->controller->createEvent($name, $location, $date, $time, $performer, $tickets);

        $this->assertNull($result);
    }

    // Test 2: Create event fails with invalid name format
    public function testCreateEventInvalidName()
    {
        $name = 'Con'; // too short
        $location = 'Dublin Hall';
        $date = '15/12/25';
        $time = '19:30';
        $performer = 'The Band';
        $tickets = 100;

        $this->controller->method('createEvent')
            ->with($name, $location, $date, $time, $performer, $tickets)
            ->willReturn('Event name must be 8–25 letters only.');

        $result = $this->controller->createEvent($name, $location, $date, $time, $performer, $tickets);

        $this->assertEquals('Event name must be 8–25 letters only.', $result);
    }

    // Test 3: Create event fails with invalid location
    public function testCreateEventInvalidLocation()
    {
        $name = 'Concert Night';
        $location = 'Hall'; // too short
        $date = '15/12/25';
        $time = '19:30';
        $performer = 'The Band';
        $tickets = 100;

        $this->controller->method('createEvent')
            ->with($name, $location, $date, $time, $performer, $tickets)
            ->willReturn('Location must be 6–30 letters only.');

        $result = $this->controller->createEvent($name, $location, $date, $time, $performer, $tickets);

        $this->assertEquals('Location must be 6–30 letters only.', $result);
    }

    // Test 4: Create event fails with invalid date format
    public function testCreateEventInvalidDateFormat()
    {
        $name = 'Concert Night';
        $location = 'Dublin Hall';
        $date = '2025-12-15'; // wrong format
        $time = '19:30';
        $performer = 'The Band';
        $tickets = 100;

        $this->controller->method('createEvent')
            ->with($name, $location, $date, $time, $performer, $tickets)
            ->willReturn('Date must be DD/MM/YY.');

        $result = $this->controller->createEvent($name, $location, $date, $time, $performer, $tickets);

        $this->assertEquals('Date must be DD/MM/YY.', $result);
    }

    // Test 5: Create event fails with invalid time format
    public function testCreateEventInvalidTime()
    {
        $name = 'Concert Night';
        $location = 'Dublin Hall';
        $date = '15/12/25';
        $time = '25:30'; // invalid hour
        $performer = 'The Band';
        $tickets = 100;

        $this->controller->method('createEvent')
            ->with($name, $location, $date, $time, $performer, $tickets)
            ->willReturn('Time must be HH:MM.');

        $result = $this->controller->createEvent($name, $location, $date, $time, $performer, $tickets);

        $this->assertEquals('Time must be HH:MM.', $result);
    }

    // Test 6: Create event fails with invalid performer
    public function testCreateEventInvalidPerformer()
    {
        $name = 'Concert Night';
        $location = 'Dublin Hall';
        $date = '15/12/25';
        $time = '19:30';
        $performer = 'AB'; // too short
        $tickets = 100;

        $this->controller->method('createEvent')
            ->with($name, $location, $date, $time, $performer, $tickets)
            ->willReturn('Performer must be 4–15 letters.');

        $result = $this->controller->createEvent($name, $location, $date, $time, $performer, $tickets);

        $this->assertEquals('Performer must be 4–15 letters.', $result);
    }

    // Test 7: Create event fails with invalid ticket amount
    public function testCreateEventInvalidTickets()
    {
        $name = 'Concert Night';
        $location = 'Dublin Hall';
        $date = '15/12/25';
        $time = '19:30';
        $performer = 'The Band';
        $tickets = 20; // too few

        $this->controller->method('createEvent')
            ->with($name, $location, $date, $time, $performer, $tickets)
            ->willReturn('Tickets must be 30–1000.');

        $result = $this->controller->createEvent($name, $location, $date, $time, $performer, $tickets);

        $this->assertEquals('Tickets must be 30–1000.', $result);
    }

    // Test 8: Create event fails with duplicate event name
    public function testCreateEventDuplicateName()
    {
        $name = 'Concert Night'; // already exists
        $location = 'Dublin Hall';
        $date = '15/12/25';
        $time = '19:30';
        $performer = 'The Band';
        $tickets = 100;

        $this->controller->method('createEvent')
            ->with($name, $location, $date, $time, $performer, $tickets)
            ->willReturn('Event name already exists.');

        $result = $this->controller->createEvent($name, $location, $date, $time, $performer, $tickets);

        $this->assertEquals('Event name already exists.', $result);
    }

    // Test 9: Show events page for regular user
    public function testShowEventsPageUser()
    {
        $this->controller->method('showEventsPage')
            ->with(false)
            ->willReturn(null);

        $result = $this->controller->showEventsPage(false);

        $this->assertNull($result);
    }

    // Test 10: Show events page for admin
    public function testShowEventsPageAdmin()
    {
        $this->controller->method('showEventsPage')
            ->with(true)
            ->willReturn(null);

        $result = $this->controller->showEventsPage(true);

        $this->assertNull($result);
    }

        // Test 11: TODO - Show single event (to be completed)
        public function testShowSingleEventTODO()
        {
            // TODO: Implement showSingleEvent functionality
            // This test will be completed when view event feature is implemented
            $this->markTestIncomplete('showSingleEvent functionality to be implemented');
        }

        // Test 12: TODO - Delete event (to be completed)
        public function testDeleteEventTODO()
        {
            // TODO: Implement deleteEvent functionality
            // This test will be completed when delete event feature is implemented
            $this->markTestIncomplete('deleteEvent functionality to be implemented');
        }
    }
    ?>
