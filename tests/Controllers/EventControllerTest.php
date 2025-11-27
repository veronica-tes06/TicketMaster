<?php
use PHPUnit\Framework\TestCase;
use App\Controllers\EventController;

require_once __DIR__ . '/../../vendor/autoload.php';

class EventControllerTest extends TestCase
{
    private const VALID_PLACE = 'Valid Place';
    private const VALID_TIME = '12:30';
    private const VALID_DATE_FIXED = '25/12/25';
    private const DATE_FMT = 'd/m/y';
    private const PLUS_THREE_DAYS = '+3 days';

    private function invokeValidate($name, $location, $date, $time, $performer, $tickets)
    {
        $controller = new EventController();
        $ref = new ReflectionClass(EventController::class);
        $method = $ref->getMethod('validateEvent');
        $method->setAccessible(true);
        return $method->invoke($controller, $name, $location, $date, $time, $performer, $tickets);
    }

    public function testValidateInvalidNamePattern()
    {
        $msg = $this->invokeValidate('Short', self::VALID_PLACE, self::VALID_DATE_FIXED, self::VALID_TIME, 'Singer', 100);
        $this->assertStringContainsString('Event name must be', $msg);
    }

    public function testValidateDuplicateName()
    {
        // Ensure a duplicate row exists in real test DB to trigger duplicate name branch
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $port = getenv('DB_PORT') ?: '3307';
        $dbName = getenv('DB_NAME') ?: 'ticketmaster_test';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') ?: '';
        try {
            $pdoReal = new PDO("mysql:host=$host;port=$port;dbname=$dbName", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            $pdoReal->exec("INSERT INTO events (eventName, eventLocation, eventDate, eventTime, performer, eventTicketMaxAMT, eventTicketMinAMT) VALUES ('DupEventXYZ','Loc','01-01-2030','12:30','Singer',100,1)");
        } catch (\Throwable $e) {
            $this->markTestSkipped('DB unavailable for duplicate name test: ' . $e->getMessage());
        }
        $msg = $this->invokeValidate('DupEventXYZ', self::VALID_PLACE, self::VALID_DATE_FIXED, self::VALID_TIME, 'Singer', 100);
        $this->assertStringContainsString('already exists', $msg);
        // Cleanup inserted row so other suites or future tests are unaffected
        try {
            $host = getenv('DB_HOST') ?: '127.0.0.1';
            $port = getenv('DB_PORT') ?: '3307';
            $dbName = getenv('DB_NAME') ?: 'ticketmaster_test';
            $user = getenv('DB_USER') ?: 'root';
            $pass = getenv('DB_PASS') ?: '';
            $pdoCleanup = new PDO("mysql:host=$host;port=$port;dbname=$dbName", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            $pdoCleanup->prepare("DELETE FROM events WHERE eventName = ?")->execute(['DupEventXYZ']);
        } catch (\Throwable $e) {
            // Silent: cleanup failure should not fail test
        }
    }

    public function testValidateInvalidLocation()
    {
        $msg = $this->invokeValidate('AlphaBeta', 'x', self::VALID_DATE_FIXED, self::VALID_TIME, 'Singer', 100);
        $this->assertStringContainsString('Location must be', $msg);
    }

    public function testValidateInvalidDateFormat()
    {
        $msg = $this->invokeValidate('BetaGamma', self::VALID_PLACE, '2025-12-25', self::VALID_TIME, 'Singer', 100);
        $this->assertStringContainsString('Date must be', $msg);
    }

    public function testValidateDateTooSoon()
    {
        $today = date(self::DATE_FMT);
        $msg = $this->invokeValidate('GammaDelta', self::VALID_PLACE, $today, self::VALID_TIME, 'Singer', 100);
        $this->assertStringContainsString('at least tomorrow', $msg);
    }

    public function testValidateInvalidTime()
    {
        $msg = $this->invokeValidate('DeltaEpsil', self::VALID_PLACE, date(self::DATE_FMT, strtotime(self::PLUS_THREE_DAYS)), '99:99', 'Singer', 100);
        $this->assertStringContainsString('Time must be', $msg);
    }

    public function testValidateInvalidPerformer()
    {
        $msg = $this->invokeValidate('EpsilonTh', self::VALID_PLACE, date(self::DATE_FMT, strtotime(self::PLUS_THREE_DAYS)), self::VALID_TIME, 'Ab', 100);
        $this->assertStringContainsString('Performer must be', $msg);
    }

    public function testValidateInvalidTickets()
    {
        $msg = $this->invokeValidate('ThetaIota', self::VALID_PLACE, date(self::DATE_FMT, strtotime(self::PLUS_THREE_DAYS)), self::VALID_TIME, 'Singer', 5);
        $this->assertStringContainsString('Tickets must be', $msg);
    }

    public function testValidateValidEvent()
    {
        $future = date(self::DATE_FMT, strtotime('+5 days'));
        $msg = $this->invokeValidate('IotaKappa', self::VALID_PLACE, $future, self::VALID_TIME, 'Singer Name', 300);
        $this->assertNull($msg);
    }

    public function testCreateEventValidationError()
    {
        $controller = new EventController();
        $error = $controller->createEvent('Short', 'PlaceName', self::VALID_DATE_FIXED, self::VALID_TIME, 'Singer', 100);
        $this->assertStringContainsString('Event name must be', $error);
    }

    public function testCreateEventSuccess()
    {
        $future = date(self::DATE_FMT, strtotime('+5 days'));
        $controller = new EventController();
        
        // Create a unique event name - must be 8-25 LETTERS ONLY (no numbers per validation regex)
        // Use a random suffix of letters to avoid duplicates
        $suffix = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);
        $uniqueName = 'TestEvnt' . $suffix; // 8 + 8 = 16 chars, all letters
        
        $result = $controller->createEvent($uniqueName, 'TestLocation', $future, self::VALID_TIME, 'TestPerformer', 100);
        
        // Should return null on success (header redirect happens)
        $this->assertNull($result);
    }
}
