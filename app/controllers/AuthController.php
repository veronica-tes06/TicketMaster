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
                'accBookings' => $user->getBookings(),
            ];

            //redirect to appropriate page based on admin or not
            //SUPER IMPRTANT MAKE NEWTICKETMASTERREPOSITORY MATCH YOUR REPO FOLDER NAME
            //------------------------------------------------------------
            if ($user->isAdmin()) {
                header('Location: /NEWTICKETMASTERREPOSITORY/TicketMaster/app/views/adminEvents.php');
                exit;
            } else {
                header('Location: /NEWTICKETMASTERREPOSITORY/TicketMaster/app/views/events.php');
                exit;
            }
            //------------------------------------------------------------
            //return null to show it worked (login returns string if failure, nothing (here) if it works)
            return null;
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
                'accBookings' => '',
            ];

            //redirect to appropriate page based on admin or not
            //SUPER IMPRTANT MAKE NEWTICKETMASTERREPOSITORY MATCH YOUR REPO FOLDER NAME
            //------------------------------------------------------------
            if ($user->isAdmin()) {
                header('Location: /NEWTICKETMASTERREPOSITORY/TicketMaster/app/views/adminEvents.php');
                exit;
            } else {
                header('Location: /NEWTICKETMASTERREPOSITORY/TicketMaster/app/views/events.php');
                exit;
            }
            //------------------------------------------------------------
        }

        //assumption kindof 
        return 'Email already associated with an account.';
    }
}
