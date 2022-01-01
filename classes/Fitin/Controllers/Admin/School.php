<?php
    namespace Fitin\Controllers\Admin;

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
            

            if (!$userExists) {
                // $randomPassword = strval(rand(100000, 999999));
                $randomPassword = substr($school['name'], 0, 4) . '1234';

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

            $json = json_encode($schools);
            return $json; 
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
            header('location: index.php?admin/schools');  // 5/25/18 JG NEW1L  		
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

            $updatedSchool = [
                'id' => $result['id'],
                'city' => $result['city'],
                'zipcode' => $result['zipcode'],
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