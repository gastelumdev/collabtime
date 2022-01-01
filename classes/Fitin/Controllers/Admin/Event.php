<?php
    namespace Fitin\Controllers\Admin;

    use Ninja\DatabaseTable;
    use \Ninja\Authentication;

    class Event {
        public function __construct(DatabaseTable $eventsTable, DatabaseTable $schoolsTable, DatabaseTable $usersTable, Authentication $authentication) {
            $this->eventsTable = $eventsTable;
            $this->schoolsTable = $schoolsTable;
            $this->usersTable = $usersTable;
            $this->authentication = $authentication;
        }

        public function list() {
            unset($_SESSION['event']);
            $results = $this->eventsTable->findAll();
            $activeUser = $this->authentication->getUser();
            $totalEvents = 0;

            $events = [];

            if ($activeUser['role'] == 1) {
                $schools = $this->schoolsTable->find('user_id', $activeUser['id']);

                foreach($schools as $school) {
                    $eventResult = $this->eventsTable->findById($school['event_id']);

                    $events[] = [
                        'id' => $eventResult['id'],
                        'name' => $eventResult['name'],
                        'description' => $eventResult['description'],
                        'date' => $eventResult['date'],
                        'image' => $eventResult['image'],
                        'userId' => $eventResult['created_by']
                    ];
                }
            } elseif ($activeUser['role'] >= 4) {
                foreach ($results as $event) {


                    $events[] = [
                        'id' => $event['id'],
                        'name' => $event['name'],
                        'description' => $event['description'],
                        'date' => $event['date'],
                        'image' => $event['image'],
                        'userId' => $event['created_by']
                    ];
    
                    // 5/23/21 OG NEW - Count the events that belong to the active user
                    if ($activeUser['id'] == $event['created_by']) {
                        $totalEvents++;
                    }
                }
            }
            

            $title = 'Events';

            if ($activeUser['role'] > 2) {
                $totalEvents = $this->eventsTable->total();
            }

            return ['template' => 'admin_events.html.php', 
                    'title' => $title, 
                    'variables' => [
                            'totalEvents' => $totalEvents,
                            'events' => $events,
                            'userId' => $activeUser['id'] ?? null,
                            'role' => $activeUser['role'] ?? null,
                            'loggedIn' => $this->authentication->isLoggedIn()
                        ]
                    ];
        }

        public function setAndShow() {
            $activeUser = $this->authentication->getUser();
            $status = 0;

            if (isset($_GET['id'])) {
                $event = $this->eventsTable->findById($_GET['id']);

                if ($activeUser['role'] == 1) {
                    $schools = $this->schoolsTable->find('user_id', $activeUser['id']);

                    foreach ($schools as $school) {
                        if ($school['event_id'] == $event['id']) {
                            $id = $school['id'];
                            $status = $school['status'];
                        }
                    }

                    if ($status == 1) {
                        $location = 'school/form?id='. $id;
                    } elseif ($status == 2) {
                        $location = 'events/submittal';
                    }
                    
                } elseif ($activeUser['role'] > 3) {
                    $location = 'events/schools';
                }
            }

            
            $_SESSION['event'] = $event;

            $title = $event['name'];

            header('location: index.php?' . $location);

            // return [
            //     'template' => 'admin_schools.html.php',
            //     'title' => $title,
            //     'variables' => [
            //         'event' => $event,
            //         'userId' => $activeUser['id'] ?? null,
            //         'permissions' => $activeUser['permissions'] ?? null,
            //         'loggedIn' => $this->authentication->isLoggedIn(),
            //         'event_id' => $_SESSION['event_id']
            //     ]
            // ];
        }

        public function showForm() {
            $user = $this->authentication->getUser();

            $title = 'Create Event';
            return ['template' => 'admin_events_form.html.php',
                    'title' => $title,
                    'variables' => [
                        'userId' => $user['id'] ?? null,
                        'permissions' => $user['permissions'] ?? null,
                        'loggedIn' => $this->authentication->isLoggedIn()
                    ]
            ];  
        }

        public function saveEdit() {
            $activeUser = $this->authentication->getUser();
    
            if (isset($_GET['id'])) {
                $event = $this->eventsTable->findById($_GET['id']);
    
                if ($event['userId'] != $activeUser['id']) {
                    return;
                }
            }
    
            $event = $_POST;
            $event['date'] = $event['date'] . ' ' . $event['time'];
            $event['creation_time'] = date("Y-m-d H:i:s");
            unset($event['time']);
            $event['created_by'] = $activeUser['id'];

            $target_dir = 'images/hero/';
            $file_name = $_FILES['image']['name'];
            $target_file = $target_dir . basename($file_name);
            
            // 2021-06-13 OG NEW - If an image file is set 
            if (empty($file_name)) {
            	$event['image'] = 'band.jpg';
            } else {
                move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
                $event['image'] = $file_name;
            }

            
    
            $this->eventsTable->save($event);

            header('location: index.php?events');

            // $result = $this->eventsTable->findAll();
            // $events = [];

            // foreach ($result as $event) {
            //     $group = $this->groupsTable->findById($event['groupId']);
            //     $user = $this->usersTable->findById($group['userId']);

            //     $events[] = [
            //         'id' => $event['id'],
            //         'name' => $event['name'],
            //         'description' => $event['description'],
            //         'date' => $event['date'],
            //         'userId' => $event['userId'],
            //         'group_name' => $group['name'],
            //         'creator' => $user['firstname'] .' '. $user['lastname']
            //     ];
            // }

            // $json = json_encode($events);
            // return $json; 
        }

        public function edit() {
            $updated = [
                'id' => $_POST['id'],
                $_POST['field'] => $_POST['value']
            ];
    
            $this->eventsTable->save($updated);
        }

        public function delete() {
            // 5/23/21 OG NEW - Get active user
            $activeUser = $this->authentication->getUser();
            // 5/23/21 OG NEW - Find the event that needs to be deleted
            $event = $this->eventsTable->findById($_POST['id']);
            // 5/23/21 OG NEW - If the active user is not the creator and does not have rights then do not proceed to delete
            if ($event['userId'] != $activeUser['id'] && $activeUser['permissions'] < 3) {
                return;
            }
            
            // 5/23/21 OG NEW - else delete the event and redirect to admin events page
            $this->eventsTable->delete($_POST['id']);
            header('location: index.php?admin/events');  // 5/25/18 JG NEW1L  		
        }
    }