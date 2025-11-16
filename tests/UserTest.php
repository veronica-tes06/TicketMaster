<?php
use PHPUnit\Framework\TestCase;
use App\Models\User;

require_once __DIR__ . '/../vendor/autoload.php';

class UserTest extends TestCase
{
    private $db;
    private $stmt;

    protected function setUp(): void
    {
        $this->db = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
    }

    // Test 1 : Create a user object
    public function testCreateUser()
    {
        $user = new User('test@example.com', 'password123');
        $this->assertEquals('test@example.com', $user->getEmail());
        $this->assertEquals('password123', $user->getPassword());
        $this->assertFalse($user->isAdmin());
        $this->assertEmpty($user->getBookings());
    }

    // Test 2: Login with the right credentials
    public function testLoginSuccess()
    {
        $userData = [
            'accID' => 1,
            'accEmail' => 'sham@gmail.com',
            'accPassword' => 'shamjamm',
            'accAdmin' => 0,
            'accBookings' => '[]'
        ];

        $this->stmt->method('fetch')->willReturn($userData);
        $this->stmt->method('execute')->willReturn(true);
        $this->db->method('prepare')->willReturn($this->stmt);
        $GLOBALS['db'] = $this->db;

        $user = new User('sham@gmail.com', 'shamjamm');
        $this->assertTrue($user->login());
        $this->assertEquals(1, $user->getId());
        $this->assertFalse($user->isAdmin());
    }

    //Test 3 Login fails when password is incorrect
    public function testLoginFailsForWrongPassword()
    {
        $userData = ['accEmail' => 'sham@gmail.com', 'accPassword' => 'shamjamm'];
        $this->stmt->method('fetch')->willReturn($userData);
        $this->db->method('prepare')->willReturn($this->stmt);
        $GLOBALS['db'] = $this->db;

        $user = new User('sham@gmail.com', 'wrongpassword');
        $this->assertFalse($user->login());
    }

    // Test 4: Register fails if email already exists
    public function testDuplicateEmail(){
        $this->stmt->method('rowCount')->willReturn(1);
        $this->db->method('prepare')->willReturn($this->stmt);
        $GLOBALS['db'] = $this->db;

        $user = new User('sham@gmail.com', 'shamjamm');
        $this->assertFalse($user->register());
    }

    //  Test 5 : Register works for new email
    public function testRegisterSucceedsForNewEmail()
    {
        $check = $this->createMock(PDOStatement::class);
        $check->method('rowCount')->willReturn(0);

        $insert = $this->createMock(PDOStatement::class);
        $insert->method('execute')->willReturn(true);

        $this->db->method('prepare')
                ->willReturnOnConsecutiveCalls($check, $insert);
        $this->db->method('lastInsertId')->willReturn('5');

        $GLOBALS['db'] = $this->db;

        $user = new User('test_' . uniqid() . '@gmail.com', 'securePass');
        $result = $user->register();

        $this->assertTrue($result, 'Registration should succeed for a unique email');
        $this->assertGreaterThan(0, $user->getId(), 'User ID should be set after successful registration');
    }
}
