<?php
namespace Fitin\Controllers;
use \Ninja\DatabaseTable;
use \Ninja\Authentication;

class Group {
    private $usersTable;
	private $groupsTable;

	// 5/23/21 OG MOD - Added a memberships table to manage the many-to-many relationship between users and groups
	public function __construct(DatabaseTable $groupsTable, DatabaseTable $usersTable, DatabaseTable $membershipTable, DatabaseTable $categoriesTable, DatabaseTable $eventsTable, DatabaseTable $groupCommentsTable, Authentication $authentication) {
		$this->groupsTable = $groupsTable;
		$this->usersTable = $usersTable;
		$this->membershipTable = $membershipTable;
		$this->categoriesTable = $categoriesTable;
		$this->eventsTable = $eventsTable;
		$this->groupCommentsTable = $groupCommentsTable;
		$this->authentication = $authentication;
	}

	// 5/23/21 OG MOD - Lists all groups. 
	public function list() {
		// 5/23/21 OG NEW - Get user logged-in user to display an add-group button in the groups page
		$activeUser = $this->authentication->getUser();

		// 2021-06-25 OG NEW - Set the zipcode from the search, else set the user's zip code 
		$zipcode = null;

		if (isset($_POST['zipcode'])) {
			$zipcode = $_POST['zipcode'];
		}

		$result = $this->groupsTable->find('zipcode', $zipcode);

		// 2021-07-01 OG NEW - Go through each row returned 
		$groups = [];
		foreach ($result as $group) {
			$user = $this->usersTable->findById($group['userId']);
			$category = $this->categoriesTable->findById($group['categoryId']);
			// 5/23/21 OG NEW - Goes through each group and get the the user ids based on the current group id
			$memberships = $this->membershipTable->find('groupid', $group['id']);
			$membersCount = 0;

			// 5/23/21 OG NEW - for each membership if the membership userid is the same as that of the current user
			//					then it sets to the member value to true for template displaying purposes
			$member = null;
			foreach ($memberships as $membership) {
				if ($activeUser) {
					if ( $membership['userid'] == $activeUser['id'] ) {
						$member = true;
					}
				}
				$membersCount++;
			}

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
				'userId' => $group['userId'],
				'firstname' => $user['firstname'],
                'lastname' => $user['lastname'],
				'email' => $user['email'],
				'userId' => $user['id'],
				'member' => $member, // Here each group states if the current user is a memeber
				'membersCount' => $membersCount
			];

			// print $group['zipcode'];

		}

		$title = 'Groups';

		$totalGroups = $this->groupsTable->total();

		$user = $this->authentication->getUser();

		return ['template' => 'groups.html.php', 
				'title' => $title, 
				'variables' => [
						'totalGroups' => $totalGroups,
						'zipcode' => $zipcode,
						'groups' => $groups,
						'activeUser' => $activeUser,
						'userId' => $user['id'] ?? null,
						'permissions' => $user['permissions'] ?? null,
						'loggedIn' => $this->authentication->isLoggedIn()
					]
				];
	}

	public function jsonlist() {
		return json_encode($this->list()['variables']);
	}

	public function show() {
		$group = $this->groupsTable->findById($_GET['id']);
		$user = $this->usersTable->findById($group['userId']);
		$events_result = $this->eventsTable->find('groupId', $group['id']);
		$groupComments_result = $this->groupCommentsTable->find('groupid', $group['id']);
		$activeUser = $this->authentication->getUser();
		$category = $this->categoriesTable->findById($group['categoryId']);

		$memberships = $this->membershipTable->find('groupid', $group['id']);
		$groupMembers = [];
		$groupMemberCount = 0;
		$member = false;

		foreach ($memberships as $membership) {
			$groupMember = $this->usersTable->findById($membership['userid']);
			$groupMembers[] = $groupMember;
			$groupMemberCount++;

			if (isset($activeUser['id'])) {
				if ($membership['userid'] == $activeUser['id']) {
					$member = true;
				}
			}
			
		}

		// 2021-07-08 OG NEW - Format event date
		$events = [];

		foreach ($events_result as $event) {

			$date = date_create($event['date']);

			$events[] = [
				'id' => $event['id'],
				'name' => $event['name'],
				'description' => $event['description'],
				'dayOfWeek' => date_format($date, 'l'),
				'month' => date_format($date, 'F'),
				'day' => date_format($date, 'd'),
				'hour' => date_format($date, 'g'),
				'minutes' => date_format($date, 'i'),
				'meridiem' => date_format($date, 'A')

			];
		}

		$groupComments = [];

		foreach ($groupComments_result as $groupComment) {
			$user = $this->usersTable->findById($groupComment['userid']);
			$date = date_create($groupComment['date']);

			$groupComments[] = [
				'id' => $groupComment['id'],
				'text' => $groupComment['text'],
				'date' => $date,
				'user_name' => $user['firstname'] . ' ' . $user['lastname'],
				'user_image' => $user['image'],
				'dayOfWeek' => date_format($date, 'l'),
				'month' => date_format($date, 'F'),
				'day' => date_format($date, 'd'),
				'hour' => date_format($date, 'g'),
				'minutes' => date_format($date, 'i'),
				'meridiem' => date_format($date, 'A')
			];
		}

		$title = $group['name'];

		return [
			'template' => 'group.html.php',
			'title' => $title,
			'variables' => [
				'group' => $group,
				'user' => $user,
				'events' => $events,
				'category' => $category['name'],
				'groupMembers' => $groupMembers,
				'groupMemberCount' => $groupMemberCount,
				'groupComments' => array_reverse($groupComments),
				'member' => $member,
				'loggedIn' => $this->authentication->isLoggedIn()
				]
		];
	}

	// 5/23/21 OG NEW - Joins a user to a group
    public function adminGroupList() {
		// 5/23/21 OG NEW - Find all groups
		$result = $this->groupsTable->findAll();

		// 5/23/21 OG NEW - Get active user
		$activeUser = $this->authentication->getUser();
		$totalGroups = 0;

		// 5/23/21 OG NEW - for each group build the groups array that will be displayed
		$groups = [];
		foreach ($result as $group) {
			$user = $this->usersTable->findById($group['userId']);

			$groups[] = [
				'id' => $group['id'],
				'name' => $group['name'],
				'date' => $group['date'],
				'userId' => $group['userId'],
				'firstname' => $user['firstname'],
                'lastname' => $user['lastname'],
				'email' => $user['email'],
				'userId' => $user['id']
			];
			
			// 5/23/21 OG NEW - Count the groups that belong to the active user
			if ($activeUser['id'] == $group['userId']) {
				$totalGroups++;
			}
		}

		// 5/23/21 OG NEW - If active user has admin privilages get the total of groups
		if ($activeUser['permissions'] > 2) {
			$totalGroups = $this->groupsTable->total();
		}

		$title = 'Groups';

		return ['template' => 'admin_groups.html.php', 
				'title' => $title, 
				'variables' => [
						'totalGroups' => $totalGroups,
						'groups' => $groups,
						'userId' => $activeUser['id'] ?? null,
						'permissions' => $activeUser['permissions'] ?? null,
						'loggedIn' => $this->authentication->isLoggedIn()
					]
				];
	}



	
    public function home() {
		$title = 'Home';

		return ['template' => 'home.html.php', 'title' => $title];
	}


	public function about() {
		$title = 'About';

		return ['template' => 'about.html.php', 'title' => $title];
	}


	public function location() {
		$title = 'Location';

		return ['template' => 'location.html.php', 'title' => $title];
	}

	// 5/23/21 OG MOD - Same as CH11. 
	public function saveEdit() {
		$activeUser = $this->authentication->getUser();

		if (isset($_GET['id'])) {
			$group = $this->groupsTable->findById($_GET['id']);

			if ($group['userId'] != $activeUser['id']) {
				return;
			}
		}

		$group = $_POST['group'];
		$group['date'] = new \DateTime();
		$group['userId'] = $activeUser['id'];

		$this->groupsTable->save($group);
		//header('location: /group/list'); 5/25/18 JG DEL1L  org
		header('location: index.php?admin/groups');  //5/25/18 JG NEW1L  
	}


	// 5/23/21 OG MOD - Same as CH11. This method is called on either a POST and GET route.  
	//				  - POST request provides a GET value coming from the action value
	//				  - GET request either comes from the POST request or a link with no group id 
	public function edit() {
		// Get active user
		$activeUser = $this->authentication->getUser();

		// if there is a group id provided then find the group to display in the edit group form
		// else it will display an empty form
		if (isset($_GET['id'])) {
			$group = $this->groupsTable->findById($_GET['id']);
		}

		$title = 'Edit group';

		return ['template' => 'editgroup.html.php',
				'title' => $title,
				'variables' => [
						'group' => $group ?? null,
						'userId' => $activeUser['id'] ?? null,
						'permissions' => $activeUser['permissions']
					]
				];
	}


	// 5/23/21 OG NEW - This is the POST route that creates the many-to-many relationship between
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

	// 5/23/21 OG NEW - Joins a user to a group
	public function join() {
		// Get current active user
		$activeUser = $this->authentication->getUser();
		// Get the group id from the groups page
		$group = $_POST['id'];

		// Set the memberships array that will build the relationship between the group and the user
		$membership['userid'] = $activeUser['id'];
		$membership['groupid'] = $group;

		// save it and redirect to group list
		$this->membershipTable->save($membership);

		

		echo header('location: index.php?group/list');
	}

	// 5/23/21 OG NEW - Removes the retalationship between the user and the group
	public function leave() {
		// Get current active user
		$activeUser = $this->authentication->getUser();

		// Get the group that was clicked on
		$group = $this->groupsTable->findById($_POST['id']);

		// Delete the relationship from the membership table and redirect to group list
		$this->membershipTable->deleteFromLookup('groupid', 'userid', $group['id'], $activeUser['id']);

		echo header('location: index.php?group/list');
	}
}