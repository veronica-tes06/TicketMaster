<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/models/Event.php';

class EventTest extends TestCase
{
    private $db;
    private $stmt;

    protected function setUp(): void
    {
        $this->db = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
    }

    // Test 1: Create an event object
    public function testCreateEvent()
    {
        $event = new Event('Concert Night', 'Dublin Hall', '15/12/25', '19:30', 'The Band', 100);
        $this->assertEquals('Concert Night', $event->getName());
        $this->assertEquals('Dublin Hall', $event->getLocation());
        $this->assertEquals('15/12/25', $event->getDate());
        $this->assertEquals('19:30', $event->getTime());
        $this->assertEquals('The Band', $event->getPerformer());
        $this->assertEquals(100, $event->getMaxTickets());
        $this->assertEquals(1, $event->getMinTickets());
    }

    // Test 2: Get all events for regular user
    public function testGetAllEventsForUser()
    {
        $eventsData = [
            [
                'eventID' => 1,
                'eventName' => 'Concert Night',
                'eventLocation' => 'Dublin Hall',
                'eventDate' => '15/12/25',
                'eventTime' => '19:30',
                'performer' => 'The Band',
                'eventTicketMaxAMT' => 100,
                'eventTicketMinAMT' => 1
            ]
        ];

        $this->stmt->method('fetchAll')->willReturn($eventsData);
        $this->stmt->method('execute')->willReturn(true);
        $this->db->method('prepare')->willReturn($this->stmt);
        $GLOBALS['db'] = $this->db;

        $event = new Event();
        $events = $event->all(false);
        $this->assertIsArray($events);
        $this->assertEquals(1, count($events));
        $this->assertEquals('Concert Night', $events[0]['eventName']);
    }

    // Test 3: Get all events for admin
    public function testGetAllEventsForAdmin()
    {
        $eventsData = [
            [
                'eventID' => 1,
                'eventName' => 'Concert Night',
                'eventLocation' => 'Dublin Hall',
                'eventDate' => '15/12/25',
                'eventTime' => '19:30',
                'performer' => 'The Band',
                'eventTicketMaxAMT' => 100,
                'eventTicketMinAMT' => 1
            ]
        ];

        $this->stmt->method('fetchAll')->willReturn($eventsData);
        $this->stmt->method('execute')->willReturn(true);
        $this->db->method('prepare')->willReturn($this->stmt);
        $GLOBALS['db'] = $this->db;

        $event = new Event();
        $events = $event->all(true);
        $this->assertIsArray($events);
        $this->assertEquals(1, count($events));
    }

    // Test 4: Save a new event
    public function testSaveEvent()
    {
        $this->stmt->method('execute')->willReturn(true);
        $this->db->method('prepare')->willReturn($this->stmt);
        $GLOBALS['db'] = $this->db;

        $event = new Event('Festival 2025', 'Phoenix Park', '20/12/25', '18:00', 'Artists', 500);
        $result = $event->save();

        $this->assertTrue($result);
    }

    // Test 5: Find event by ID
    public function testFindEventById()
    {
        $eventData = [
            'eventID' => 1,
            'eventName' => 'Concert Night',
            'eventLocation' => 'Dublin Hall',
            'eventDate' => '15/12/25',
            'eventTime' => '19:30',
            'performer' => 'The Band',
            'eventTicketMaxAMT' => 100,
            'eventTicketMinAMT' => 1
        ];

        $this->stmt->method('fetch')->willReturn($eventData);
        $this->stmt->method('execute')->willReturn(true);
        $this->db->method('prepare')->willReturn($this->stmt);
        $GLOBALS['db'] = $this->db;

        $event = new Event();
        $foundEvent = $event->find(1);

        $this->assertNotNull($foundEvent);
        $this->assertEquals('Concert Night', $foundEvent->getName());
    }

    // Test 6: Find event by name
    public function testFindEventByName()
    {
        $eventData = [
            'eventID' => 1,
            'eventName' => 'Concert Night',
            'eventLocation' => 'Dublin Hall',
            'eventDate' => '15/12/25',
            'eventTime' => '19:30',
            'performer' => 'The Band',
            'eventTicketMaxAMT' => 100,
            'eventTicketMinAMT' => 1
        ];

        $this->stmt->method('fetch')->willReturn($eventData);
        $this->stmt->method('execute')->willReturn(true);
        $this->db->method('prepare')->willReturn($this->stmt);
        $GLOBALS['db'] = $this->db;

        $result = Event::findByName('Concert Night');

        $this->assertNotNull($result);
        $this->assertEquals('Concert Night', $result['eventName']);
    }

    // Test 7: Delete event
    public function testDeleteEvent()
    {
        $this->stmt->method('execute')->willReturn(true);
        $this->db->method('prepare')->willReturn($this->stmt);
        $GLOBALS['db'] = $this->db;

        $event = new Event();
        $result = $event->delete(1);

        $this->assertTrue($result);
    }
}
?>
