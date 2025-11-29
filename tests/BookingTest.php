<?php
use PHPUnit\Framework\TestCase;
use App\Controllers\AuthController;

require_once __DIR__ . '/../vendor/autoload.php';

class BookingTest extends TestCase
{
    private $db;
    private $stmt;

    protected function setUp(): void
    {
        $this->db = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);

        $GLOBALS['db'] = $this->db;

        $_SESSION = [
            'user' => [
                'accID' => 1,
                'bookings' => [2, 3]
            ]
        ];
    }

    /** ---------- getBookings() ---------- */

    public function testGetBookingsReturnsArray()
    {
        $this->stmt->method('fetchAll')->willReturn([1, 2, 3]);
        $this->stmt->method('execute')->willReturn(true);
        $this->db->method('prepare')->willReturn($this->stmt);

        $auth = new AuthController();
        $result = $auth->getBookings();

        $this->assertIsArray($result);
        $this->assertEquals([1, 2, 3], $result);
    }

    public function testGetBookingsWhenNotLoggedIn()
    {
        $_SESSION = []; // no user logged in
        $auth = new AuthController();
        $result = $auth->getBookings();

        $this->assertEquals([], $result);
    }

    /** ---------- createBooking() ---------- */

    public function testCreateBookingSuccessful()
    {
        // no duplicate booking
        $this->stmt->method('rowCount')->willReturn(0);
        $this->stmt->method('execute')->willReturn(true);

        // DB prepare returns stmt twice (check + insert)
        $this->db->method('prepare')
            ->willReturnOnConsecutiveCalls($this->stmt, $this->stmt);

        $auth = new AuthController();

        // Prevent exit() from killing the test
        $this->expectOutputRegex('/./');

        $auth->createBooking(10, 2);

        $this->assertContains(10, $_SESSION['user']['bookings']);
    }

    public function testCreateBookingFailsForDuplicate()
    {
        $this->stmt->method('rowCount')->willReturn(1); // DUPLICATE FOUND
        $this->db->method('prepare')->willReturn($this->stmt);

        $auth = new AuthController();

        $this->expectExceptionMessageMatches('/already booked/');

        $auth->createBooking(5, 1);
    }

    /** ---------- cancelBooking() ---------- */

    public function testCancelBookingRemovesBooking()
    {
        $this->stmt->method('execute')->willReturn(true);
        $this->db->method('prepare')->willReturn($this->stmt);

        $auth = new AuthController();

        // Simulate redirect blocking
        $this->expectOutputRegex('/./');

        $auth->cancelBooking(2);

        $this->assertNotContains(2, $_SESSION['user']['bookings']);
    }

    public function testCancelBookingFailsIfNotLoggedIn()
    {
        $_SESSION = []; // no user

        $auth = new AuthController();

        $this->expectOutputRegex('/./');

        // Normally this would redirect, but we intercept the output
        $auth->cancelBooking(1);

        // No session bookings should exist
        $this->assertEmpty($_SESSION);
    }
}
