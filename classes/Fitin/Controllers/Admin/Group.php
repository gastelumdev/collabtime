<?php
    namespace Fitin\Controllers\Admin;

    use Ninja\DatabaseTable;
    use \Ninja\Authentication;

    class Group {
        public function __construct(DatabaseTable $groupsTable, DatabaseTable $usersTable, Authentication $authentication, DatabaseTable $categoriesTable) {
            $this->groupsTable = $groupsTable;
            $this->usersTable = $usersTable;
            $this->categoriesTable = $categoriesTable;
            $this->authentication = $authentication;
        }

        public function list() {
            $result = $this->groupsTable->findAll();
            $activeUser = $this->authentication->getUser();
            $totalGroups = 0;
            $categories = $this->categoriesTable->findAll();
    
            $groups = [];
            foreach ($result as $group) {
                $user = $this->usersTable->findById($group['userId']);
                $category = $this->categoriesTable->findById($group['categoryId']);
    
                $groups[] = [
                    'id' => $group['id'],
                    'name' => $group['name'],
                    'description' => $group['description'],
                    'category' => $category['name'],
                    'street' => $group['street'],
                    'city' => $group['city'],
                    'state' => $group['state'],
                    'country' => $group['country'],
                    'zipcode' => $group['zipcode'],
                    'date' => $group['date'],
                    'creator' => $user['firstname']. ' ' .$user['lastname'],
                    'userId' => $group['userId']
                ];

                // 5/23/21 OG NEW - Count the groups that belong to the active user
                if ($activeUser['id'] == $group['userId']) {
                    $totalGroups++;
                }
        
            }
    
            $title = 'Groups';
    
            // 5/23/21 OG NEW - If active user has admin privilages get the total of groups
            if ($activeUser['permissions'] > 2) {
                $totalGroups = $this->groupsTable->total();
            }
    
            $user = $this->authentication->getUser();
    
            return ['template' => 'admin_groups.html.php', 
                    'title' => $title, 
                    'variables' => [
                            'totalGroups' => $totalGroups,
                            'groups' => $groups,
                            'categories' => $categories,
                            'userId' => $user['id'] ?? null,
                            'permissions' => $user['permissions'] ?? null,
                            'loggedIn' => $this->authentication->isLoggedIn()
                        ]
                    ];
        }

        public function showForm() {
            $user = $this->authentication->getUser();

            $categories = $this->categoriesTable->findAll();

            $title = 'Create Group';
            return ['template' => 'editgroup.html.php',
                    'title' => $title,
                    'variables' => [
                        'userId' => $user['id'] ?? null,
                        'categories' => $categories,
                        'permissions' => $user['permissions'] ?? null,
                        'loggedIn' => $this->authentication->isLoggedIn()
                    ]
            ];  
        }

        public function saveEdit() {
            $activeUser = $this->authentication->getUser();
    
            if (isset($_GET['id'])) {
                $group = $this->groupsTable->findById($_GET['id']);
    
                if ($group['userId'] != $activeUser['id']) {
                    return;
                }
            }
    
            $group = $_POST;
            $group['date'] = new \DateTime();
            $group['userId'] = $activeUser['id'];
    
            $this->groupsTable->save($group);
            
            $result = $this->groupsTable->findAll();
            $groups = [];

            foreach ($result as $group) {
                $user = $this->usersTable->findById($group['userId']);
                $category = $this->categoriesTable->findById($group['categoryId']);
    
                $groups[] = [
                    'id' => $group['id'],
                    'name' => $group['name'],
                    'description' => $group['description'],
                    'category' => $category['name'],
                    'street' => $group['street'],
                    'city' => $group['city'],
                    'state' => $group['state'],
                    'country' => $group['country'],
                    'zipcode' => $group['zipcode'],
                    'date' => $group['date'],
                    'creator' => $user['firstname']. ' ' .$user['lastname'],
                    'userId' => $group['userId']
                ];
            }

            $json = json_encode($groups);
            return $json; 
        }

        public function edit() {
            $updated = [
                'id' => $_POST['id'],
                $_POST['field'] => $_POST['value']
            ];
    
            $this->groupsTable->save($updated);
        }

        public function delete() {
            // 5/23/21 OG NEW - Get active user
            $activeUser = $this->authentication->getUser();
            // 5/23/21 OG NEW - Find the group that needs to be deleted
            $group = $this->groupsTable->findById($_POST['id']);
            // 5/23/21 OG NEW - If the active user is not the creator and does not have rights then do not proceed to delete
            if ($group['userId'] != $activeUser['id'] && $activeUser['permissions'] < 3) {
                return;
            }
            
            // 5/23/21 OG NEW - else delete the group and redirect to admin groups page
            $this->groupsTable->delete($_POST['id']);
            header('location: index.php?admin/groups');  // 5/25/18 JG NEW1L  		
        }
    }