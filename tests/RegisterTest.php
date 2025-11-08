<?php
use PHPUnit\Framework\TestCase;
use App\controllers\AuthController;

require_once __DIR__ . '/../vendor/autoload.php';

class RegisterTest extends TestCase
{
    private $db;
    private $auth;

    protected function setUp(): void
    {
        $this->db = $this->createMock(PDO::class);
        $this->auth = $this->getMockBuilder(AuthController::class)
                           ->setConstructorArgs([$this->db])
                           ->onlyMethods(['register'])
                           ->getMock();
    }

    // Test 1: Successful registration with valid email and password
    public function testSuccessfulRegistration()
    {
        $email = 'newuser@gmail.com';
        $password = 'strongPass1';

        $this->auth->method('register')->willReturn(true);

        $result = $this->auth->register($email, $password);

        $this->assertTrue($result, 'Registration should have been successful.');
    }

    // Test 2: Registration fails when email already exists
    public function testExistingEmail()
    {
        $email = 'existinguser@gmail.com';
        $password = 'password123';

        $this->auth->method('register')->willReturn('Email already exists');

        $result = $this->auth->register($email, $password);

        $this->assertEquals('Email already exists', $result, 'Return error message for duplicate email.');
    }

    // Test 3: Registration fails for invalid email format
    public function testInvalidEmail()
    {
        $email = 'invalidemail'; // missing @gmail.com
        $password = 'password123';

        $this->auth->method('register')->willReturn('Invalid email format');

        $result = $this->auth->register($email, $password);

        $this->assertEquals('Invalid email format', $result, 'Return error for invalid email format.');
    }

    // Test 4: Registration fails bc of short password
    public function testWeakPassword()
    {
        $email = 'user@gmail.com';
        $password = '123'; 

        $this->auth->method('register')->willReturn('Password too short');

        $result = $this->auth->register($email, $password);

        $this->assertEquals('Password too short', $result, 'Return error for a weak password.');
    }

    // Test 5: Shows an error message if register() returns any error
    public function testDisplaysErrorMessage()
    {
        $email = 'user@gmail.com';
        $password = 'badpass';

        $this->auth->method('register')->willReturn('Something went wrong');

        $result = $this->auth->register($email, $password);

        $this->assertEquals('Something went wrong', $result, 'Error message should be displayed if registration fails.');
    }
}
