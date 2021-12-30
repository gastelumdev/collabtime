<?php
    namespace Fitin\Controllers\Admin;

    use \Ninja\DatabaseTable;
    use \Ninja\Authentication;

    class Category {
        public function __construct(DatabaseTable $categoriesTable, Authentication $authentication) {
            $this->categoriesTable = $categoriesTable;
            $this->authentication = $authentication;
        }

        public function list() {
            $result = $this->categoriesTable->findAll();

            $categories = [];
            foreach ($result as $category) {
                $categories[] = [
                    'id' => $category['id'],
                    'name' => $category['name']
                ];
            }

            $title = 'Categories';

            $totalCategories = $this->categoriesTable->total();

            $user = $this->authentication->getUser();

            return [
                'template' => 'admin_categories.html.php',
                'title' => $title,
                'variables' => [
                    'totalCategories' => $totalCategories,
                    'categories' => $categories,
                    'userId' => $user['id'] ?? null,
                    'permissions' => $user['permissions'] ?? null,
                    'loggedIn' => $this->authentication->isLoggedIn()
                ]
            ];
        }

        public function saveEdit() {
            $category = $_POST;
            $this->categoriesTable->save($category);

            $json = json_encode($this->categoriesTable->findAll());
            return $json;
        }

        public function edit() {
            $updatedCategory = [
                'id' => $_POST['id'],
                $_POST['field'] => $_POST['value']
            ];

            $this->categoriesTable->save($updatedCategory);
        }

        public function delete() {
            $activeUser = $this->authentication->getUser();
            // 2021-06-14 OG NEW - return if active user does not have permission to edit/delete users
            if ($activeUser['permissions'] < 3) {
                return;
		    }
            
            $this->categoriesTable->delete($_POST['id']);
        }
    }