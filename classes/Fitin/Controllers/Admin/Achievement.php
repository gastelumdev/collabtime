<?php
    namespace Fitin\Controllers\Admin;

    use Ninja\DatabaseTable;
    use Ninja\Authentication;

    class Achievement {
        public function __construct(DatabaseTable $achievementsTable, DatabaseTable $groupsTable, DatabaseTable $usersTable, Authentication $authentication) {
            $this->achievementsTable = $achievementsTable;
            $this->groupsTable = $groupsTable;
            $this->usersTable = $usersTable;
            $this->authentication = $authentication;
        }

        public function list() {
            $result = $this->achievementsTable->findAll();
            $activeUser = $this->authentication->getUser();
            $totalAchievements = 0;

            $achievements = [];
            foreach ($result as $achievement) {
                $group = $this->groupsTable->findById($achievement['groupId']);
                $user = $this->usersTable->findById($group['userId']);

                $achievements[] = [
                    'id' => $achievement['id'],
                    'name' => $achievement['name'],
                    'description' => $achievement['description'],
                    'userId' => $achievement['userId'],
                    'group_name' => $group['name'],
                    'creator' => $user['firstname'] .' '. $user['lastname']
                ];

                if ($activeUser['id'] == $achievement['userId']) {
                    $totalAchievements++;
                }
            }

            $title = 'Achievements';

            if ($activeUser['permissions'] > 2) {
                $totalAchievements = $this->achievementsTable->total();
            }

            $groups = $this->groupsTable->find('userId', $activeUser['id']);

            return ['template' => 'admin_achievements.html.php',
                    'title' => $title,
                    'variables' => [
                            'totalAchievements' => $totalAchievements,
                            'achievements' => $achievements,
                            'groups' => $groups,
                            'userId' => $activeUser['id'] ?? null,
                            'permissions' => $activeUser['permissions'] ?? null,
                            'loggedIn' => $this->authentication->isLoggedIn()
                        ]   
                    ];
        }

        public function saveEdit() {
            $activeUser = $this->authentication->getUser();
            $valid = true;

            $achievement = $_POST;

            $target_dir = 'images/achievement_icons/';
            $file_name = $_FILES['icon']['name'];
            $target_file = $target_dir . basename($file_name);
            
            // 2021-06-13 OG NEW - If an image file is set 
            if (!empty($file_name)) {
                // 2021-06-13 OG NEW - Check if the file exists 
                if (file_exists($target_file)) {
                    // $errors['userImage'] = "Sorry, file already exists.";
                    $valid = false;
                } else {
                    // 2021-06-13 OG NEW - if it does not then upload the image and set name for storage 
                    move_uploaded_file($_FILES['icon']['tmp_name'], $target_file);
                }
                $achievement['icon'] = $file_name;
            }

            $achievement['userId'] = $activeUser['id'];

            // unset($achievement['customFile']);
            $this->achievementsTable->save($achievement);

            $result = $this->achievementsTable->findAll();
            $achievements = [];

            foreach ($result as $achievement) {
                $group = $this->groupsTable->findById($achievement['groupId']);
                $user = $this->usersTable->findById($group['userId']);

                $achievements[] = [
                    'id' => $achievement['id'],
                    'name' => $achievement['name'],
                    'description' => $achievement['description'],
                    'group_name' => $group['name'],
                    'creator' => $user['firstname'] .' '. $user['lastname']
                ];
            }

            $json = json_encode($achievements);
            return $json; 
        }

        public function edit() {
            $updated = [
                'id' => $_POST['id'],
                $_POST['field'] => $_POST['value']
            ];
    
            $this->achievementsTable->save($updated);
        }

        public function delete() {
            // 5/23/21 OG NEW - Get active user
            $activeUser = $this->authentication->getUser();
            // 5/23/21 OG NEW - Find the event that needs to be deleted
            $achievement = $this->achievementsTable->findById($_POST['id']);
            // 5/23/21 OG NEW - If the active user is not the creator and does not have rights then do not proceed to delete
            if ($achievement['userId'] != $activeUser['id'] && $activeUser['permissions'] < 3) {
                return;
            }
            
            // 5/23/21 OG NEW - else delete the event and redirect to admin events page
            $this->achievementsTable->delete($_POST['id']);
            header('location: index.php?admin/achievements');  // 5/25/18 JG NEW1L  		
        }
    }