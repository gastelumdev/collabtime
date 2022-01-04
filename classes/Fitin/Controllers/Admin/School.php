<?php
    namespace Fitin\Controllers\Admin;

use Exception;
use Ninja\DatabaseTable;
    use \Ninja\Authentication;

    class School {
        public function __construct(DatabaseTable $schoolsTable, DatabaseTable $eventsTable, DatabaseTable $eventSchoolsTable, DatabaseTable $usersTable, Authentication $authentication) {
            $this->schoolsTable = $schoolsTable;
            $this->eventsTable = $eventsTable;
            $this->eventSchoolsTable = $eventSchoolsTable;
            $this->usersTable = $usersTable;
            $this->authentication = $authentication;
        }

        public function list() {
            $results = $this->schoolsTable->findAll();
            $activeUser = $this->authentication->getUser();
            $totalSchools = 0;
            $schools = [];

            foreach ($results as $school) {
                if ($school['event_id'] == $_SESSION['event']['id']) {
                    $schools[] = [
                        'id' => $school['id'],
                        'name' => $school['name'],
                        'email' => $school['email'],
                        'status' => $school['status']
                    ];

                    $totalSchools++;
                }
            }

            return [
                'title' => $_SESSION['event']['name'],
                'template' => 'admin_schools.html.php',
                'variables' => [
                    'title' => 'Schools',
                    'schools' => $schools,
                    'totalSchools' => $totalSchools,
                    'userId' => $activeUser['id'],
                    'role' => $activeUser['role'] ?? '',
                    'loggedIn' => $this->authentication->isLoggedIn()
                ]
            ];
        }

        public function saveEdit() {
            $activeUser = $this->authentication->getUser();

            $school = $_POST;

            $userExists = false;
            $existingUser = $this->usersTable->find('email', $school['email']);
            
            if (count($existingUser) > 0) {
                $userExists = true;
            }
            // $randomPassword = strval(rand(100000, 999999));
            $randomPassword = substr($school['name'], 0, 4) . '1234';

            if (!$userExists) {
                $user = [
                    'firstname' => $school['name'],
                    'lastname' => '',
                    'email' => $school['email'],
                    'password' => password_hash($randomPassword, PASSWORD_DEFAULT),
                    'image' => 'avatar.png',
                    'role' => 1
                ];

                $userId = $this->usersTable->save($user);
            } else {
                $userId = $existingUser[0]['id'];
            }

            $school['event_id'] = $_SESSION['event']['id'];
            $school['user_id'] = $userId;
            // $school['user_id'] = 2;
            $school['status'] = 1;

            $schoolId = $this->schoolsTable->save($school);

            $eventSchool = [
                'event_id' => $_SESSION['event']['id'],
                'school_id' => $schoolId
            ];

            $this->eventSchoolsTable->save($eventSchool);

            $results = $this->schoolsTable->findAll();
            $schools = [];

            foreach($results as $school) {
                if ($school['event_id'] == $_SESSION['event']['id']) {
                    $schools[] = [
                        'id' => $school['id'],
                        'name' => $school['name'],
                        'email' => $school['email'],
                        'status' => $school['status']
                    ];
                }
            }

            $output = [
                $schools, $randomPassword
            ];

            $json = json_encode($output);
            return $json; 
        }

        public function createEmail() {
            
            $activeUser = $this->authentication->getUser();

            $to = $_POST['email'];
            $subject = $activeUser['firstname'] . ' ' . $activeUser['lastname'] . ' welcomes you to this year\'s ' . $_SESSION['event']['name'];
            $from = "noreply@collabtime.co";
            
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            
            $headers .= 'From: '.$from."\r\n".
                'Reply-To: '.$from."\r\n" .
                'X-Mailer: PHP/' . phpversion();
            
            $message = '<html><body>';
            $message .= '<h1 style="color:#343a40;">You have been invited to participate in this year\'s '. $_SESSION['event']['name'] .'</h1>';
            $message .= '<p>Go to <a href="https://collabtime.co">Collabtime.co</a> to submit your schools information. Use the credentials below to login.</p>';
            $message .= '<p style="color:#080;font-size:18px;">Username: '. $_POST['email'] .'</p>';
            $message .= '<p style="color:#080;font-size:18px;">Password: '. $_POST['password'] .'</p>';
            $message .= '<p>Thank you, from everyone at Collabtime!</p>';
            $message .= '</body></html>';

        
            if (mail($to,$subject,$message,$headers)) {
                return "Success";
            } else {
                return "Failed";
            }
            
        }

        public function edit() {
            $updated = [
                'id' => $_POST['id'],
                $_POST['field'] => $_POST['value']
            ];
    
            $this->schoolsTable->save($updated);
        }

        public function delete() {
            // 5/23/21 OG NEW - Get active user
            $activeUser = $this->authentication->getUser();
            // 5/23/21 OG NEW - Find the row that needs to be deleted
            $school = $this->schoolsTable->findById($_POST['id']);
            
            // 5/23/21 OG NEW - else delete the event and redirect to admin events page
            $this->schoolsTable->delete($_POST['id']);
            header('location: index.php?events/schools');  // 5/25/18 JG NEW1L  		
        }

        public function showForm() {
            if (isset($_GET['id'])) {
                $school = $this->schoolsTable->findById($_GET['id']);

                return [
                    'title' => 'School Form',
                    'template' => 'school_form.html.php',
                    'variables' => [
                        'school' => $school
                    ]
                ];
            }

            return [
                'title' => 'School Form',
                'template' => 'school_form.html.php'
            ];
        }

        public function saveForm() {
            $result = $_POST;
            $school = $this->schoolsTable->findById($result['id']);
            $activeUser = $this->authentication->getUser();
            $eventManager = $this->usersTable->findById($_SESSION['event']['created_by']);

            if ($school['status'] == 1) {
                $to = $eventManager['email'];
                $subject = $school['name'] . ' has updated their information for '. $_SESSION['event']['name'] .' event.';
                $from = "noreply@collabtime.co";
                // To send HTML mail, the Content-type header must be set
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                
                // Create email headers
                $headers .= 'From: '.$from."\r\n".
                    'Reply-To: '.$from."\r\n" .
                    'X-Mailer: PHP/' . phpversion();
                
                // Compose a simple HTML email message
                $message = '<html><body>';
                $message .= '<h1 style="color:#343a40;">' . $school['name'] . ' has updated their information for '. $_SESSION['event']['name'] .' event.</h1>';
                $message .= '<p>Go to <a href="https://collabtime.co">Collabtime.co</a> to verify and validate their status.</p>';
                $message .= '<p>Thank you, from everyone at Collabtime!</p>';
                $message .= '</body></html>';

                mail($to,$subject,$message,$headers);
            }

            $updatedSchool = [
                'id' => $result['id'],
                'name' => $result['name'],
                'group_name' => $result['group_name'],
                'street' => $result['street'],
                'city' => $result['city'],
                'zipcode' => $result['zipcode'],
                'band_director_name' => $result['band_director_name'],
                'band_director_phone' => $result['band_director_phone'],
                'band_director_email' => $result['band_director_email'],
                'booster_parent_name' => $result['booster_parent_name'],
                'booster_parent_phone' => $result['booster_parent_phone'],
                'booster_parent_email' => $result['booster_parent_email'],
                'parade_march_title' => $result['parade_march_title'],
                'parade_march_composer' => $result['parade_march_composer'],
                'additional_band_staff_names' => $result['additional_band_staff_names'],
                'drum_major' => $result['drum_major'],
                'drum_major_name' => $result['drum_major_name'],
                'color_guard_advisor' => $result['color_guard_advisor'],
                'color_guard_captains' => $result['color_guard_captains'],
                'drill_team' => $result['drill_team'],
                'drill_team_advisor' => $result['drill_team_advisor'],
                'drill_team_captains' => $result['drill_team_captains'],
                'school_enrollment' => $result['school_enrollment'],
                'number_of_students_in_band' => $result['number_of_students_in_band'],
                'number_of_students_in_color_guard' => $result['number_of_students_in_color_guard'],
                'number_of_students_in_drill_team' => $result['number_of_students_in_drill_team'],
                'number_of_buses' => $result['number_of_buses'],
                'number_of_box_trucks' => $result['number_of_box_trucks'],
                'number_of_trailers' => $result['number_of_trailers'],
                'number_of_tractor_trailer_rigs' => $result['number_of_tractor_trailer_rigs'],
                'special_instructions' => $result['special_instructions'],
                'status' => 2
            ];

            $this->schoolsTable->save($updatedSchool);

            if ($activeUser['role'] > 3 && $activeUser['id'] != $school['user_id']) {
                header('location: index.php?events/schools/submittal?id='. $result['id']);
            } else {
                header('location: index.php?events/submittal?id='. $result['id']);
            }

            
        }

        // public function saveForm() {
        //     $result = $_POST;
        //     $activeUser = $this->authentication->getUser();

        //     $schools = $this->schoolsTable->find('user_id', $activeUser['id']);

        //     $updatedSchool = Array();

        //     foreach ($schools as $school) {
        //         if ($school['event_id'] == $_SESSION['event']['id']) {
        //             $updatedSchool['id'] = $school['id'];
        //             $updatedSchool['city'] = $result['city'];
        //             $updatedSchool['zipcode'] = $result['zipcode'];
        //             $updatedSchool['status'] = 2;
        //         }
        //     }

        //     $this->schoolsTable->save($updatedSchool);

        //     header('location: index.php?events/submittal');
        // }

        private function getCurrentSchool() {
            $activeUser = $this->authentication->getUser();

            $schools = $this->schoolsTable->find('user_id', $activeUser['id']);

            foreach ($schools as $school) {
                if ($school['event_id'] == $_SESSION['event']['id']) {
                    return $school;
                }
            }
        }

        public function showInfo() {
            if (isset($_GET['id'])) {
                $school = $this->schoolsTable->findById($_GET['id']);
            } else {
                $school = $this->getCurrentSchool();
            }

            $activeUser = $this->authentication->getUser();

            return [
                'title' => $_SESSION['event']['name'],
                'template' => 'school_info.html.php',
                'variables' => [
                    'school' => $school,
                    'role' => $activeUser['role'],
                    'status' => $school['status']
                ]
            ];
        }

        public function validate() {
            if (isset($_GET['id'])) {
                $school = $this->schoolsTable->findById($_GET['id']);
                $activeUser = $this->authentication->getUser();

                $to = $school['email'];
                $subject = 'The information provided for ' . $_SESSION['event']['name'] . ' has been approved by ' . $activeUser['firstname'] . ' ' . $activeUser['lastname'];
                $from = "noreply@collabtime.co";
                // To send HTML mail, the Content-type header must be set
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                
                // Create email headers
                $headers .= 'From: '.$from."\r\n".
                    'Reply-To: '.$from."\r\n" .
                    'X-Mailer: PHP/' . phpversion();
                
                // Compose a simple HTML email message
                $message = '<html><body>';
                $message .= '<h1 style="color:#343a40;">The information provided for ' . $_SESSION['event']['name'] . ' has been approved by ' . $activeUser['firstname'] . ' ' . $activeUser['lastname'] . '</h1>';
                $message .= '<p>Go to <a href="https://collabtime.co">Collabtime.co</a> if changes need to be made.</p>';
                $message .= '<p>Thank you, from everyone at Collabtime!</p>';
                $message .= '</body></html>';

                mail($to,$subject,$message,$headers);

                $updatedSchool = [
                    'id' => $_GET['id'],
                    'status' => 3
                ];

                $this->schoolsTable->save($updatedSchool);

                header('location: index.php?events/schools');
            }
        }

        public function devalidate() {
            if (isset($_GET['id'])) {
                $school = $this->schoolsTable->findById($_GET['id']);

                $updatedSchool = [
                    'id' => $_GET['id'],
                    'status' => 2
                ];

                $this->schoolsTable->save($updatedSchool);

                header('location: index.php?events/schools');
            }
        }
    }