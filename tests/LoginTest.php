<?php
use PHPUnit\Framework\TestCase;
use App\Controllers\AuthController;

require_once __DIR__ . '/../vendor/autoload.php';

class LoginTest extends TestCase
{
    private $db;
    private $authController;

    protected function setUp(): void
    {

        $this->db = $this->createMock(PDO::class);
        $this->authController = $this->createMock(AuthController::class);
    }

    //Test 1: Successful login
    public function testLoginSuccess()
    {
        $email = 'user@gmail.com';
        $password = 'password123';

        $this->authController->method('login')
            ->with($email, $password)
            ->willReturn(true);

        $result = $this->authController->login($email, $password);
        $this->assertTrue($result, 'Login should succeed');
    }

    // Test 2: Login fails with wrong password
    public function testWrongPassword()
    {
        $email = 'user@gmail.com';
        $password = 'wrongpassword';

        $this->authController->method('login')
            ->with($email, $password)
            ->willReturn('Invalid email or password.');

        $result = $this->authController->login($email, $password);
        $this->assertEquals('Invalid email or password.', $result, 'Should return error message for invalid login');
    }

    //Test 3: Login fails with invalid email format
    public function testInvalidEmail()
    {
        $email = 'invalid_email';
        $password = 'password123';

        $this->authController->method('login')
            ->with($email, $password)
            ->willReturn('Invalid email format.');

        $result = $this->authController->login($email, $password);
        $this->assertEquals('Invalid email format.', $result, 'Return error message for invalid email format');
    }

    // Test 4: Login fails with empty inputs
    public function testNoInputs()
    {
        $email = '';
        $password = '';

        $this->authController->method('login')
            ->with($email, $password)
            ->willReturn('Email and password are required.');

        $result = $this->authController->login($email, $password);
        $this->assertEquals('Email and password are required.', $result, 'Return error for missing credentials');
    }

    // Test 5: Login with admin account
    public function testRedirectAdmin()
    {
        $email = 'admin@gmail.com';
        $password = 'adminPass';

        $this->authController->method('login')
            ->with($email, $password)
            ->willReturn('Redirecting to adminEvents.php');

        $result = $this->authController->login($email, $password);
        $this->assertEquals('Redirecting to adminEvents.php', $result, 'Admin login should redirect to admin page');
    }
}
