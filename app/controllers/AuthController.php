<?php
/**
 * Register and Login Page
 * 
 *Acceptance Criteria:
 *Verify valid email is unique and is min 1, max 30 chars and only alphanumeric characters 
 *and is associated with account with '@gmail.com' added
 *Verify valid password has min 8 chars max 20 chars and only alphanumeric characters
 *Verify valid account type "buyer" is associated with account in database (admin = 0)
 *Verify taken email already in database is rejected via an error message
 *Verify invalid inputs are rejected via an error message
 *Verify booked events list associated with account in database
 *Verify valid unique account id is created and all details correctly added to database (accounts table)
 *Verify after user creates account they are moved to homescreen "view future events" page (events.php)
 *Admins are moved to adminEvents.php
*/

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../controllers/EventController.php';

use App\Models\User;


class AuthController {
    private function validateCredentials(string $email, string $password): array {
        $error = '';

        //append @gmail.com if missing but dont if its already there
        if (!str_ends_with($email, '@gmail.com')) {
            $email .= '@gmail.com';
        }

        //validate the email format
        if (!preg_match('/^[A-Za-z0-9]+@gmail\.com$/', $email)) {
            $error = 'Email must contain only letters and numbers before @gmail.com.';
        } elseif (strlen($email) < 1 || strlen($email) > 30) {
            $error = 'Email must be between 1 and 30 characters (including @gmail.com).';
        }

        //validate the password format
        elseif (!preg_match('/^[A-Za-z0-9]{8,20}$/', $password)) {
            $error = 'Password must be 8 to 20 characters long and only contain letters or numbers.';
        }
        return [$email, $error];
    }

    //process the login and return an error string on failure or a null on success (since it redirects on success)
    public function login($email, $password) {
        //use the controller validation method above
        [$email, $error] = $this->validateCredentials($email, $password);
        if ($error) return $error; //should be set already in validateCredentials

        //create a user model and save the user data to the session if the login is successful
        $user = new User($email, $password);
        if ($user->login()) {
            if (session_status() !== PHP_SESSION_ACTIVE) session_start();
            $_SESSION['user'] = [
                'accID' => $user->getId(),
                'accEmail' => $user->getEmail(),
                'accAdmin' => (int)$user->isAdmin(),
                'bookings' => $user->getBookings(),
            ];

            //redirect to appropriate page based on admin or not (MVC now as of 13-11-2025)
            //SUPER IMPRTANT MAKE NEWTICKETMASTERREPOSITORY MATCH YOUR REPO FOLDER NAME
            //------------------------------------------------------------
            $eventController = new EventController();
            $eventController->showEventsPage($user->isAdmin());
            exit;
            //------------------------------------------------------------
            //old redirect method below before MVC was added
            //return null to show it worked (login returns string if failure, nothing (here) if it works)
            //return null;
        }

        //only reached if login failed
        return 'Invalid email or password.';
    }


    //process the registration and return an error string on failure or null on success (since it redirects on success)
    public function register($email, $password) {
        [$email, $error] = $this->validateCredentials($email, $password);
        if ($error) return $error;

        $user = new User($email, $password);

        if ($user->register()) {
            if (session_status() !== PHP_SESSION_ACTIVE) session_start();

            //new users are buyers by default
            $_SESSION['user'] = [
                'accID'       => $user->getId(),
                'accEmail'    => $user->getEmail(),
                'accAdmin'    => 0,
                'accBookings' => [],
            ];

            //redirect to appropriate page based on admin or not
            //SUPER IMPRTANT MAKE NEWTICKETMASTERREPOSITORY MATCH YOUR REPO FOLDER NAME (not anymore hopefully)
            //------------------------------------------------------------
            $eventController = new EventController();
            $eventController->showEventsPage($user->isAdmin());
            exit;
            //------------------------------------------------------------
        }
        //assumption kindof 
        return 'Email already associated with an account.';
    }


    // Get all bookings for the logged-in user
    public function getBookings()
    {
        if (!isset($_SESSION['user']['accID'])) {
            return [];
        }

        require __DIR__ . '/../config/connect.php';
        $accID = $_SESSION['user']['accID'];

        // Get list from database
        $stmt = $db->prepare("SELECT eventID FROM eventDetails WHERE accID = ?");
        $stmt->execute([$accID]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }


    // Cancel a booking
    public function cancelBooking($eventID)
    {
        if (!isset($_SESSION['user']['accID'])) {
            header("Location: ../User/login.php");
            exit;
        }

        require __DIR__ . '/../config/connect.php';

        $accID = $_SESSION['user']['accID'];

        // Delete from DB
        $stmt = $db->prepare("DELETE FROM eventDetails WHERE accID = ? AND eventID = ? LIMIT 1");
        $stmt->execute([$accID, $eventID]);

        // Remove from session list
        if (($key = array_search($eventID, $_SESSION['user']['bookings'])) !== false) {
            unset($_SESSION['user']['bookings'][$key]);
            $_SESSION['user']['bookings'] = array_values($_SESSION['user']['bookings']);
        }

        // Redirect back to account
        header("Location: ../User/account.php");
        exit;
    }


    // Create a new booking
    public function createBooking($eventID, $ticketAmount)
    {
        if (!isset($_SESSION['user']['accID'])) {
            header("Location: ../User/login.php");
            exit;
        }

        require __DIR__ . '/../config/connect.php';

        $accID = $_SESSION['user']['accID'];

        // Prevent duplicate booking
        $check = $db->prepare("SELECT * FROM eventDetails WHERE accID = ? AND eventID = ?");
        $check->execute([$accID, $eventID]);

        if ($check->rowCount() > 0) {
            die("You already booked this event.<br><br>
                <a href=\"../Event/eventsRouter.php\">Back to Events</a>");
        }

        // Insert booking
        $stmt = $db->prepare("
            INSERT INTO eventDetails (accID, eventID, eventTicketAMT)
            VALUES (?, ?, ?)
        ");

        if ($stmt->execute([$accID, $eventID, $ticketAmount])) {

            // Add to session
            $_SESSION['user']['bookings'][] = $eventID;

            // Load confirmation page view
            require __DIR__ . '/../views/Booking/bookingPage.php';
            exit;
        }

        echo "<p style='color:red;'>Booking failed.</p>";
        echo '<br><a href="../Event/eventsRouter.php">Back</a>';
    }
}
