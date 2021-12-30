<?php
    namespace Fitin\Controllers\Admin;

use Ninja\DatabaseTable;
use \Ninja\Authentication;

class User {
    public function __construct(DatabaseTable $usersTable, Authentication $authentication) {
        $this->usersTable = $usersTable;
        $this->authentication = $authentication;
    }

    public function list() {
		$result = $this->usersTable->findAll();

		$users = [];
		foreach ($result as $user) {

			$users[] = [
				'id' => $user['id'],
				'firstname' => $user['firstname'],
                'lastname' => $user['lastname'],
				'email' => $user['email'],
				'role' => $user['role']
			];

		}

		$title = 'Users';

		$totalUsers = $this->usersTable->total();

		$user = $this->authentication->getUser();

		return ['template' => 'admin_users.html.php', 
				'title' => $title, 
				'variables' => [
						'totalUsers' => $totalUsers,
						'users' => $users,
						'userId' => $user['id'] ?? null,
						'role' => $user['role'] ?? null,
						'loggedIn' => $this->authentication->isLoggedIn()
					]
				];
	}

    public function edit() {
        $updatedUser = [
            'id' => $_POST['id'],
            $_POST['field'] => $_POST['value']
        ];

        $this->usersTable->save($updatedUser);
    }

    public function delete() {

		$activeUser = $this->authentication->getUser();
		// 2021-06-14 OG NEW - return if active user does not have permission to edit/delete users
		if ($activeUser['role'] < 3) {
			return;
		}
		print($_POST['id']);

		$user = $this->usersTable->findById($_POST['id']);
		$groups = $this->groupsTable->find('userId', $user['id']);
		// 2021-06-14 OG NEW - delete each group created by this user
		foreach ($groups as $group) {
			$this->groupsTable->deleteBy('userId', $user['id']);
		}
		// 2021-06-14 OG NEW - Delete user 
		$this->usersTable->delete($_POST['id']);
        //header('location: /group/list'); 5/25/18 JG DEL1L  org
		echo header('location: index.php?admin/users');  // 5/25/18 JG NEW1L  		
	}
}