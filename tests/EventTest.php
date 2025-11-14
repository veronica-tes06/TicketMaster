<?php
use PHPUnit\Framework\TestCase;
use App\Models\Event;

require_once __DIR__ . '/../vendor/autoload.php';

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
                'eventID' => 4,
                'eventName' => 'after',
                'eventLocation' => 'after',
                'eventDate' => '01-01-2026',
                'eventTime' => '20:30',
                'performer' => 'ghost of future',
                'eventTicketMaxAMT' => '30000',
                'eventTicketMinAMT' => '0'
            ],
            [
                'eventID' => 1,
                'eventName' => 'Sham\'s Jam',
                'eventLocation' => 'His Kitchen',
                'eventDate' => '14-11-2025',
                'eventTime' => '19:30',
                'performer' => 'Sham',
                'eventTicketMaxAMT' => '5000',
                'eventTicketMinAMT' => '0'
            ],
            [
                'eventID' => 2,
                'eventName' => 'The Weeknd',
                'eventLocation' => 'Madison Square Garden',
                'eventDate' => '01-01-2026',
                'eventTime' => '20:30',
                'performer' => 'TheWeeknd',
                'eventTicketMaxAMT' => '30000',
                'eventTicketMinAMT' => '0'
            ]
        ];

        $this->stmt->method('fetchAll')->willReturn($eventsData);
        $this->stmt->method('execute')->willReturn(true);
        $this->db->method('query')->willReturn($this->stmt);
        $GLOBALS['db'] = $this->db;

        $event = new Event();
        $events = $event->all(false);
        $this->assertIsArray($events);
            $this->assertEquals(2, count($events));
            $this->assertEquals('The Weeknd', $events[0]['eventName']);
    }

    // Test 3: Get all events for admin
    public function testGetAllEventsForAdmin()
    {
        $eventsData = [
            [
                'eventID' => 4,
                'eventName' => 'after',
                'eventLocation' => 'after',
                'eventDate' => '01-01-2026',
                'eventTime' => '20:30',
                'performer' => 'ghost of future',
                'eventTicketMaxAMT' => '30000',
                'eventTicketMinAMT' => '0'
            ],
            [
                'eventID' => 1,
                'eventName' => 'Sham\'s Jam',
                'eventLocation' => 'His Kitchen',
                'eventDate' => '14-11-2025',
                'eventTime' => '19:30',
                'performer' => 'Sham',
                'eventTicketMaxAMT' => '5000',
                'eventTicketMinAMT' => '0'
            ],
            [
                'eventID' => 2,
                'eventName' => 'The Weeknd',
                'eventLocation' => 'Madison Square Garden',
                'eventDate' => '01-01-2026',
                'eventTime' => '20:30',
                'performer' => 'TheWeeknd',
                'eventTicketMaxAMT' => '30000',
                'eventTicketMinAMT' => '0'
            ],
            [
                'eventID' => 5,
                'eventName' => 'Big Leagues',
                'eventLocation' => 'Croke Park',
                'eventDate' => '19-02-2022',
                'eventTime' => '9:00',
                'performer' => 'Sham',
                'eventTicketMaxAMT' => '1444',
                'eventTicketMinAMT' => '0'
            ],
            [
                'eventID' => 3,
                'eventName' => 'before',
                'eventLocation' => 'before',
                'eventDate' => '01-01-2021',
                'eventTime' => '20:30',
                'performer' => 'ghost of past',
                'eventTicketMaxAMT' => '30000',
                'eventTicketMinAMT' => '0'
            ]
        ];

        $this->stmt->method('fetchAll')->willReturn($eventsData);
        $this->stmt->method('execute')->willReturn(true);
        $this->db->method('query')->willReturn($this->stmt);
        $GLOBALS['db'] = $this->db;

        $event = new Event();
        $events = $event->all(true);
        $this->assertIsArray($events);
        $this->assertGreaterThanOrEqual(5, count($events), 'Expected admin to see at least 5 events');
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
           $this->markTestIncomplete('Find by ID uses require statement, bypassing mocks. Test with real DB integration instead.');
    }

    // Test 6: Find event by name
    public function testFindEventByName()
    {
           $this->markTestIncomplete('Find by name uses require statement, bypassing mocks. Test with real DB integration instead.');
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
