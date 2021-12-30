<?php
    namespace Fitin\Controllers\Admin;

    use Ninja\DatabaseTable;
    use Ninja\Authentication;

    class User_Achievement {
        public function __construct(DatabaseTable $userAchievementsTable, DatabaseTable $achievementsTable, DatabaseTable $groupsTable, DatabaseTable $usersTable, DatabaseTable $membershipsTable, Authentication $authentication) {
            $this->userAchievementsTable = $userAchievementsTable;
            $this->achievementsTable =$achievementsTable;
            $this->groupsTable = $groupsTable;
            $this->usersTable = $usersTable;
            $this->membershipsTable =$membershipsTable;
            $this->authentication = $authentication;
        }

        public function list() {
            $results = $this->userAchievementsTable->findAll();
            $activeUser = $this->authentication->getUser();
            $totalUserAchievements = 0;

            $userAchievements = [];
            foreach ($results as $userAchievement) {
                $achievement = $this->achievementsTable->findById($userAchievement['achievementid']);
                $group = $this->groupsTable->findById($userAchievement['groupid']);
                $user = $this->usersTable->findById($userAchievement['userid']);

                if ($userAchievement['creatorid'] == $activeUser['id']) {
                    $userAchievements[] = [
                        'id' => $userAchievement['id'],
                        'user_name' => $user['firstname'] . ' ' . $user['lastname'],
                        'achievement_name' => $achievement['name'],
                        'group_name' => $group['name']
                    ];
                }

                $totalUserAchievements++;
            }

            $achievements = $this->achievementsTable->find('userId', $activeUser['id']);
            $groups = $this->groupsTable->find('userId', $activeUser['id']);
            $users = [];
            $memberships = [];

            foreach ($groups as $group) {
                $membershipResults = $this->membershipsTable->find('groupid', $group['id']);
                foreach ($membershipResults as $membership) {
                    $memberships[] = [
                        'userid' => $membership['userid']
                    ];
                }
            }

            foreach ($memberships as $membership) {
                $user = $this->usersTable->findById($membership['userid']);
                $users[] = [
                    'id' => $user['id'],
                    'firstname' => $user['firstname'],
                    'lastname' => $user['lastname']
                ];
            }

            $title = 'User Achievements';

            return [
                'template' => 'admin_user_achievements.html.php',
                'title' => $title,
                'variables' => [
                    'totalUserAchievements' => $totalUserAchievements,
                    'userAchievements' => $userAchievements,
                    'users' => array_unique($users, SORT_REGULAR),
                    'groups' => $groups,
                    'achievements' => $achievements,
                    'userId' => $activeUser['id'] ?? null,
                    'permissions' => $activeUser['permissions'] ?? null,
                    'loggedIn' => $this->authentication->isLoggedIn()
                ]
            ];
        }

        public function saveEdit() {
            $activeUser = $this->authentication->getUser();

            $userAchievement = $_POST;
            $userAchievement['creatorid'] = $activeUser['id'];

            $this->userAchievementsTable->save($userAchievement);

            $result = $this->userAchievementsTable->findAll();
            $userAchievements = [];

            foreach ($result as $userAchievement) {
                $achievement = $this->achievementsTable->findById($userAchievement['achievementid']);
                $group = $this->groupsTable->findById($userAchievement['groupid']);
                $user = $this->usersTable->findById($userAchievement['userid']);

                $userAchievements[] = [
                    'id' => $userAchievement['id'],
                    'achievement_name' => $achievement['name'],
                    'group_name' => $group['name'],
                    'user_name' => $user['firstname'] . ' ' . $user['lastname']
                ];
            }

            $json = json_encode($userAchievements);
            return $json;
        }

        public function delete() {
            // 5/23/21 OG NEW - Get active user
            $activeUser = $this->authentication->getUser();
            // 5/23/21 OG NEW - Find the event that needs to be deleted
            $userAchievement = $this->userAchievementsTable->findById($_POST['id']);
            // 5/23/21 OG NEW - If the active user is not the creator and does not have rights then do not proceed to delete
            // if ($userAchievement['creatorid'] != $activeUser['id'] && $activeUser['permissions'] < 3) {
            //     return;
            // }
            // 5/23/21 OG NEW - else delete the event and redirect to admin events page
            $this->userAchievementsTable->delete($_POST['id']);
            // header('location: index.php?admin/user_achievements');  // 5/25/18 JG NEW1L  
            $json = json_encode($_POST['id']);
            return $json;
        }
    }