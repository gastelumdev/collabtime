<?php
    namespace Fitin\Controllers;

    use \Ninja\DatabaseTable;
    use \Ninja\Authentication;

    class Event {
        public function __construct(DatabaseTable $eventsTable, DatabaseTable $usersTable, Authentication $authentication) {
            $this->eventsTable = $eventsTable;
            $this->usersTable = $usersTable;
            $this->authentication = $authentication;
        }

        public function show() {
            $activeUser = $this->authentication->getUser();
            $event = $this->eventsTable->findById($_GET['id']);
            $group = $this->groupsTable->findById($event['groupId']);
            $date = date_create($event['date']);
            $attendees = [];
            $attending = false;

            $event['dayOfWeek'] = date_format($date, 'l');
            $event['month'] = date_format($date, 'F');
            $event['day'] = date_format($date, 'd');
            $event['hour'] = date_format($date, 'g');
            $event['minutes'] = date_format($date, 'i');
            $event['meridiem'] = date_format($date, 'A');

            $eventAttendees = $this->eventUserTable->find('eventid', $event['id']);

            foreach ($eventAttendees as $eventAttendee) {
                $attendee = $this->usersTable->findById($eventAttendee['userid']);
                $attendees[] = $attendee;

                if ($attendee['id'] == $activeUser['id']) {
                    $attending = true;
                }
            }

            return [
                'template' => 'event.html.php',
                'title' => 'event',
                'variables' => [
                    'event' => $event,
                    'group' => $group,
                    'attendees' => $attendees,
                    'attending' => $attending
                ]
            ];
        }

        // 5/23/21 OG NEW - Joins a user to a group
        public function join() {
            // Get current active user
            $activeUser = $this->authentication->getUser();
            // Get the group id from the groups page
            $event = $_POST['id'];

            // Set the memberships array that will build the relationship between the group and the user
            $eventUser['userid'] = $activeUser['id'];
            $eventUser['eventid'] = $event;

            // save it and redirect to group list
            $this->eventUserTable->save($eventUser);

            

            echo header('location: index.php?group/list');
        }

        // 5/23/21 OG NEW - Removes the retalationship between the user and the group
        public function leave() {
            // Get current active user
            $activeUser = $this->authentication->getUser();

            // Get the group that was clicked on
            $event = $this->eventsTable->findById($_POST['id']);

            // Delete the relationship from the membership table and redirect to group list
            $this->eventUserTable->deleteFromLookup('eventid', 'userid', $event['id'], $activeUser['id']);

            echo header('location: index.php?group/list');
        }
    }