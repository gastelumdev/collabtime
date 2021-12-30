<?php
namespace Fitin\Controllers;
use \Ninja\DatabaseTable;
use \Ninja\Authentication;

class Register {
	private $usersTable;

	public function __construct(DatabaseTable $usersTable, Authentication $authentication) {
		$this->usersTable = $usersTable;
		$this->authentication = $authentication;
	}

	public function registrationForm() {
		return ['template' => 'register.html.php', 
				'title' => 'Register an account'];
	}


	public function success() {
		return ['template' => 'registersuccess.html.php', 
			    'title' => 'Registration Successful'];
	}

	public function registerUser() {
		$user = $_POST['user'];

		// 2021-06-13 OG NEW - Assume the data is valid to begin with 
		$valid = true;
		$errors = [];

		// 2021-06-13 OG NEW - But if any of the fields have been left blank, set $valid to false 
		if (empty($user['firstname'])) {
			$valid = false;
			$errors['firstname'] = 'First name cannot be blank';
		}

		if (empty($user['lastname'])) {
			$valid = false;
			$errors['lastname'] = 'Last name cannot be blank';
		}

		if (empty($user['email'])) {
			$valid = false;
			$errors['email'] = 'Email cannot be blank';
		}
		else if (filter_var($user['email'], FILTER_VALIDATE_EMAIL) == false) {
			$valid = false;
			$errors['email'] = 'Invalid email address';
		}
		else { // if the email is not blank and valid:
			// convert the email to lowercase
			$user['email'] = strtolower($user['email']);

			// search for the lowercase version of `$user['email']`
			if (count($this->usersTable->find('email', $user['email'])) > 0) {
				$valid = false;
				$errors['email'] = 'That email address is already registered';
			}
		}

		// print($user['zipcode']);

		if (empty($user['password'])) {
			$valid = false;
			$errors['password'] = 'Password cannot be blank';
		}

		// 2021-06-13 OG NEW - Create variables for image handling 
		$target_dir = 'images/users/';
		$file_name = $_FILES['userImage']['name'];
		$target_file = $target_dir . basename($file_name);
		
		// 2021-06-13 OG NEW - If an image file is set 
		// if (!empty($file_name)) {
		// 	// 2021-06-13 OG NEW - Check if the file exists 
		// 	if (file_exists($target_file)) {
		// 		$errors['userImage'] = "Sorry, file already exists.";
		// 		$valid = false;
		// 	} else {
		// 		// 2021-06-13 OG NEW - if it does not then upload the image and set name for storage 
		// 		move_uploaded_file($_FILES['userImage']['tmp_name'], $target_file);
		// 		$user['image'] = $file_name;
		// 	}
		// }

		move_uploaded_file($_FILES['userImage']['tmp_name'], $target_file);
		$user['image'] = $file_name;
		

		// 5/22/21 OG NEW 1L - Default value for user permissions
		$user['role'] = 1;

		//If $valid is still true, no fields were blank and the data can be added
		if ($valid == true) {
			//Hash the password before saving it in the database
			$user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);

			//When submitted, the $user variable now contains a lowercase value for email
			//and a hashed password
			$this->usersTable->save($user);

            //header('Location: /user/success'); //5/25/18 JG DEL1L  org
            header('Location: index.php?login'); //5/25/18 JG NEW1L  


		}
		else {
			//If the data is not valid, show the form again
			return ['template' => 'register.html.php', 
				    'title' => 'Register an account',
				    'variables' => [
				    	'errors' => $errors,
				    	'user' => $user
				    ]
				   ]; 
		}
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
				'permissions' => $user['role']
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
						'permissions' => $user['role'] ?? null,
						'loggedIn' => $this->authentication->isLoggedIn()
					]
				];
	}

	public function edit() {
		$activeUser = $this->authentication->getUser();
		$showPasswordField = false;

		if (isset($_GET['id'])) {
			$user = $this->usersTable->findById($_GET['id']);
		}

		if ($activeUser['id'] == $user['id']) {
			$showPasswordField == true;
		}

		$title = 'Edit user';

		return ['template' => 'edituser.html.php',
				'title' => $title,
				'variables' => [
						'user' => $user ?? null,
						'userId' => $activeUser['id'] ?? null,
						'permissions' => $activeUser['permissions'],
						'showPasswordField' => $showPasswordField
					]
				];
	}


	public function saveEdit() {
		$activeUser = $this->authentication->getUser();
		$user = $_POST['user'];

		//Assume the data is valid to begin with
		$valid = true;
		$errors = [];

		//But if any of the fields have been left blank, set $valid to false
		if (empty($user['firstname'])) {
			$valid = false;
			$errors[] = 'First name cannot be blank';
		}

		if (empty($user['lastname'])) {
			$valid = false;
			$errors[] = 'Last name cannot be blank';
		}

		if (empty($user['email'])) {
			$valid = false;
			$errors[] = 'Email cannot be blank';
		}
		else if (filter_var($user['email'], FILTER_VALIDATE_EMAIL) == false) {
			$valid = false;
			$errors[] = 'Invalid email address';
		}
		else { //if the email is not blank and valid:
			//convert the email to lowercase
			$user['email'] = strtolower($user['email']);
		}

		//If $valid is still true, no fields were blank and the data can be added
		if ($valid == true) {

			$updatedUser = [
				'id' => $user['id'],
				'firstname' => $user['firstname'],
				'lastname' => $user['lastname'],
				'email' => $user['email'],
				'zipcode' => $user['zipcode'],
				'permissions' => $user['permissions'] ?? $activeUser['permissions']
			];
			//When submitted, the $user variable now contains a lowercase value for email
			//and a hashed password
			$this->usersTable->save($updatedUser);

            //header('Location: /user/success'); //5/25/18 JG DEL1L  org
			if ($activeUser['permissions'] > 2) {
				header('Location: index.php?admin/users');
			} else {
				header('Location: index.php?admin/profile?id=' . $user['id']); //5/25/18 JG NEW1L  
			}
            


		}
		else {
			//If the data is not valid, show the form again
			return ['template' => 'edituser.html.php', 
				    'title' => 'Edit user',
				    'variables' => [
				    	'errors' => $errors,
				    	'user' => $user,
						'userId' => $activeUser['id'] ?? null,
						'permissions' => $activeUser['permissions'],
						'showPasswordField' => null
				    ]
				   ]; 
		} 
	}


	public function delete() {

		$activeUser = $this->authentication->getUser();
		// 2021-06-14 OG NEW - return if active user does not have permission to edit/delete users
		if ($activeUser['permissions'] < 3) {
			return;
		}
		// print($_POST['id']);
	

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


	public function profile() {
		$activeUser = $this->authentication->getUser();
		$memberships = $this->membershipsTable->find('userid', $activeUser['id']);

		$groups = [];
		foreach ($memberships as $membership) {
			$group = $this->groupsTable->findById($membership['groupid']);

			$category = $this->categoriesTable->findById($group['categoryId']);

			$groups[] = [
				'id' => $group['id'],
				'name' => $group['name'],
				'category' => $category['name']
			];

		}

		$userAchievementsResult = $this->userAchievementsTable->find('userId', $activeUser['id']);
		$userAchievements = [];

		foreach ($userAchievementsResult as $userAchievement) {
			$group = $this->groupsTable->findById($userAchievement['groupid']);
			$achievement = $this->achievementsTable->findById($userAchievement['achievementid']);

			$userAchievements[] = [
				'achievement_name' => $achievement['name'],
				'achievement_icon' => $achievement['icon'],
				'group_name' => $group['name']
			];
		}

		if (isset($_GET['id'])) {
			$user = $this->usersTable->findById($_GET['id']);

			// if (($user['id'] != $activeUser['id']) && $activeUser['permissions'] < 3) {
			// 	return;
			// }
		}

		$date = date_create($user['date']);

		$user['dayOfWeek'] = date_format($date, 'l');
		$user['month'] = date_format($date, 'F');
		$user['day'] = date_format($date, 'd');
		$user['year'] = date_format($date, 'Y');
		$user['hour'] = date_format($date, 'g');
		$user['minutes'] = date_format($date, 'i');
		$user['meridiem'] = date_format($date, 'A');

		$title = 'Profile';

		return ['template' => 'profile.html.php',
				'title' => $title,
				'variables' => [
					'user' => $user ?? null,
					'groups' => $groups ?? null,
					'userAchievements' => $userAchievements ?? null,
					'userId' => $activeUser['id'] ?? null,
					'permissions' => $activeUser['permissions']
				]
		];
	}
}