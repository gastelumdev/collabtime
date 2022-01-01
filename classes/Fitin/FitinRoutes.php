<?php
namespace Fitin;

class FitinRoutes implements \Ninja\Routes {
    private $usersTable;
    private $groupsTable;
    private $authentication;

    public function __construct() {
        include __DIR__ . '/../../includes/DatabaseConnection.php';

		$this->usersTable = new \Ninja\DatabaseTable($pdo, 'user', 'id');
		$this->eventsTable = new \Ninja\DatabaseTable($pdo, 'event', 'id');
		$this->addressesTable = new \Ninja\DatabaseTable($pdo, 'address', 'id');
		$this->schoolsTable = new \Ninja\DatabaseTable($pdo, 'school', 'id');
		$this->eventSchoolsTable = new \Ninja\DatabaseTable($pdo, 'event_school', 'school_id');
		$this->authentication = new \Ninja\Authentication($this->usersTable, 'email', 'password');
    }

    public function getRoutes(): array {
		$homeController = new \Fitin\Controllers\Home();
		$aboutController = new \Fitin\Controllers\About();
		$locationController = new \Fitin\Controllers\Location();
		$adminHomeController = new \Fitin\Controllers\Admin\Home();
		$adminUserController = new \Fitin\Controllers\Admin\User($this->usersTable, $this->authentication);
		$adminEventController = new \Fitin\Controllers\Admin\Event($this->eventsTable, $this->schoolsTable, $this->usersTable, $this->authentication);
		$adminSchoolController = new \Fitin\Controllers\Admin\School($this->schoolsTable, $this->eventsTable, $this->eventSchoolsTable, $this->usersTable, $this->authentication);
		$userController = new \Fitin\Controllers\Register($this->usersTable, $this->authentication);
		$eventController = new \Fitin\Controllers\Event($this->usersTable, $this->eventsTable, $this->authentication);
		$loginController = new \Fitin\Controllers\Login($this->authentication);

		$routes = [
			// ==========================================================================
			// REGISTER USER
			// ==========================================================================
			'user/register' => [
				'GET' => [
					'controller' => $userController,
					'action' => 'registrationForm'
				],
				'POST' => [
					'controller' => $userController,
					'action' => 'registerUser'
				],
				'template' => 'form_layout.html.php'
			],
			// 2021-06-15 OG NEW - **********NOT USED 
			'user/success' => [
				'GET' => [
					'controller' => $userController,
					'action' => 'success'
				],
				'template' => 'registersuccess.html.php'
			],
			'user/profile' => [
				'GET' => [
					'controller' => $userController,
					'action' => 'profile'
				],
				'template' => 'layout.html.php'
			],

			// ==========================================================================
			// ADMIN
			// ==========================================================================
			'admin' => [
				'GET' => [
					'controller' => $adminHomeController,
					'action' => 'show'
				],
				'template' => 'admin_layout.html.php',
				'login' => true,
				'admin' => true
			],
			// ==========================================================================
			// ADMIN - EVENTS
			// ==========================================================================
			'events' => [
				'GET' => [
					'controller' => $adminEventController,
					'action' => 'list'
				],
				'template' => 'admin_layout.html.php',
				'login' => true,
				'admin' => true
			],
			'admin/event' => [
				'GET' => [
					'controller' => $adminEventController,
					'action' => 'setAndShow'
				],
				'POST' => [
					'controller' => $adminEventController,
					'action' => 'saveEdit'
				],
				'template' => 'admin_layout.html.php',
			],
			'events/create' => [
				'GET' => [
					'controller' => $adminEventController,
					'action' => 'showForm'
				],
				'template' => 'admin_layout.html.php',
				'login' => true
			],
			'event/processCreateForm' => [
				
			],
			// ==========================================================================
			// ADMIN - SCHOOLS
			// ==========================================================================
			'events/schools' => [
				'GET' => [
					'controller' => $adminSchoolController,
					'action' => 'list'
				],
				'template' => 'admin_layout.html.php',
				'login' => true,
				'admin' => true
			],
			'school/create' => [
				'POST' => [
					'controller' => $adminSchoolController,
					'action' => 'saveEdit'
				]
			],
			'school/edit' => [
				'POST' => [
					'controller' => $adminSchoolController,
					'action' => 'edit'
				]
			],
			'school/delete' => [
				'POST' => [
					'controller' => $adminSchoolController,
					'action' => 'delete'
				]
			],
			'school/form' => [
				'GET' => [
					'controller' => $adminSchoolController,
					'action' => 'showForm'
				],
				'template' => 'form_layout.html.php'
			],
			'school/saveForm' => [
				'POST' => [
					'controller' => $adminSchoolController,
					'action' => 'saveForm'
				],
				'template' => 'form_layout.html.php'
			],
			'events/submittal' => [
				'GET' => [
					'controller' => $adminSchoolController,
					'action' => 'showInfo'
				],
				'template' => 'admin_layout.html.php'
			],
			'events/schools/submittal' => [
				'GET' => [
					'controller' => $adminSchoolController,
					'action' => 'showInfo'
				],
				'template' => 'admin_layout.html.php'
			],
			'events/schools/submittal/validate' => [
				'GET' => [
					'controller' => $adminSchoolController,
					'action' => 'validate'
				],
				'template' => 'admin_layout.html.php'
			],
			'events/schools/submittal/devalidate' => [
				'GET' => [
					'controller' => $adminSchoolController,
					'action' => 'devalidate'
				],
				'template' => 'admin_layout.html.php'
			],
			// ==========================================================================
			// ADMIN - USERS
			// ==========================================================================
			'events/users' => [
				'GET' => [
					'controller' => $adminUserController,
					'action' => 'list'
				],
				'template' => 'admin_layout.html.php',
				'login' => true,
				'admin' => true
			],
			'user/save' => [
				'POST' => [
					'controller' => $userController,
					'action' => 'saveEdit'
				],
				'GET' => [
					'controller' => $userController,
					'action' => 'edit'
				],
				'login' => true
				
			],
			'user/edit' => [
				'POST' => [
					'controller' => $adminUserController,
					'action' => 'edit'
				],
				'GET' => [
					'controller' => $adminUserController,
					'action' => 'edit'
				],
				'login' => true
				
			],
			'user/delete' => [
				'POST' => [
					'controller' => $adminUserController,
					'action' => 'delete'
				],
				'login' => true,
				'admin' => true
			],
			
			// ==========================================================================
			// ADMIN - ACTIVITIES
			// ==========================================================================
			// 'admin/events' => [
			// 	'GET' => [
			// 		'controller' => $adminEventController,
			// 		'action' => 'list'
			// 	],
			// 	'template' => 'admin_layout.html.php',
			// 	'login' => true,
			// 	'admin' => true
			// ],
			// 'event/create' => [
			// 	'POST' => [
			// 		'controller' => $adminEventController,
			// 		'action' => 'saveEdit'
			// 	],
			// 	'GET' => [
			// 		'controller' => $adminEventController,
			// 		'action' => 'showForm'
			// 	],
			// 	'template' => '',
			// 	'login' => true,
			// 	'admin' => true
			// ],
			// 'event/edit' => [
			// 	'POST' => [
			// 		'controller' => $adminEventController,
			// 		'action' => 'edit'
			// 	],
			// 	'GET' => [
			// 		'controller' => $adminEventController,
			// 		'action' => 'edit'
			// 	],
			// 	'login' => true,
			// 	'admin' => true
			// ],
			// 'event/delete' => [
			// 	'POST' => [
			// 		'controller' => $adminEventController,
			// 		'action' => 'delete'
			// 	],
			// 	'login' => true,
			// 	'admin' => true
			// ],
			// ==========================================================================
			// LOGIN
			// ==========================================================================
			'login/error' => [
				'GET' => [
					'controller' => $loginController,
					'action' => 'error'
				],
				'template' => 'form_layout.html.php'
			],
			'login/success' => [
				'GET' => [
					'controller' => $loginController,
					'action' => 'success'
				]
			],
			'logout' => [
				'GET' => [
					'controller' => $loginController,
					'action' => 'logout'
				]
			],
			'' => [
				'GET' => [
					'controller' => $loginController,
					'action' => 'loginForm'
				],
				'POST' => [
					'controller' => $loginController,
					'action' => 'processLogin'
				],
				'template' => 'form_layout.html.php'
			],
			'login' => [
				'GET' => [
					'controller' => $loginController,
					'action' => 'loginForm'
				],
				'POST' => [
					'controller' => $loginController,
					'action' => 'processLogin'
				],
				'template' => 'form_layout.html.php'
			],
			
			// ==========================================================================
			// event
			// ==========================================================================
			'event' => [
				'GET' => [
					'controller' => $eventController,
					'action' => 'show'
				],
				'template' => 'layout.html.php'
			],
			// 5/23/21 OG NEW - This is the POST route that creates the many-to-many relationship between
			// 					the user and the group. It calls the join method in the group controller.
			'event/join' => [
				'POST' => [
					'controller' => $eventController,
					'action' => 'join'
				],
				'login' => true
			],
			// 5/23/21 OG NEW - This is the POST route that breaks the many-to-many relationship between
			// 					the user and the group. It calls the leave method in the group controller.
			'event/leave' => [
				'POST' => [
					'controller' => $eventController,
					'action' => 'leave'
				],
				'login' => true
			],
			// ==========================================================================
			// STATIC PAGES
			// ==========================================================================
			// 'about' => [
			// 	'GET' => [
			// 		'controller' => $aboutController,
			// 		'action' => 'show'
			// 	],
			// 	'template' => 'layout.html.php'
			// ],
			// 'location' => [
			// 	'GET' => [
			// 		'controller' => $locationController,
			// 		'action' => 'show'
			// 	],
			// 	'template' => 'layout.html.php'
			// ],
			// '' => [
			// 	'GET' => [
			// 		'controller' => $homeController,
			// 		'action' => 'show'
			// 	],
			// 	'template' => 'layout.html.php'
			// ]
		];

		return $routes;
	}

	public function getAuthentication(): \Ninja\Authentication {
		return $this->authentication;
	}
}