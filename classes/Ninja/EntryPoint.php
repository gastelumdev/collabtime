<?php
namespace Ninja;

class EntryPoint {
	private $route;
	private $method;
	private $routes;

	public function __construct(string $route, string $method, \Ninja\Routes $routes) {
		$this->route = $route;
		$this->routes = $routes;
		$this->method = $method;
		$this->checkUrl();
	}

	private function checkUrl() {
		if ($this->route !== strtolower($this->route)) {
			http_response_code(301);
			header('location: ' . strtolower($this->route));
		}
	}

	private function loadTemplate($templateFileName, $variables = []) {
		extract($variables);

		ob_start();
		include  __DIR__ . '/../../templates/' . $templateFileName;

		return ob_get_clean();
	}

	private function createBreadcrumb($route) {
		$levels = explode('/', $route);
		$breadcrumb = [];
		$path = '';
		$depth = 1;

		foreach ($levels as $level) {
			if ($depth == 1) {
				$path = $path . $level;
			} else {
				$path = $path . '/' . $level;
			}

			$depth++;
			
			$breadcrumb[] = [
				'title' => ucfirst($level),
				'path' => $path
			];
		}

		return $breadcrumb;
	}

	public function run() {

		$routes = $this->routes->getRoutes();	
		$authentication = $this->routes->getAuthentication();
		$breadcrumb = $this->createBreadcrumb($this->route);

		
		// 5/19/2021 OG NEW 1L - Get user for website header when logged in
		
		if (isset($routes[$this->route]['login']) && isset($routes[$this->route]['login']) && !$authentication->isLoggedIn()) {
			//header('location: /login/error');  // 5/25/18 JG DEL1L  org
			header('location: index.php?login/error'); // 5/25/18 JG NEW1L  org
		// 2021-06-14 OG NEW - Check if the user can access the admin area and display error accordingly  
		} else if (isset($routes[$this->route]['admin']) && ($authentication->getUser()['role'] < 1)) {
			header('location: index.php?login/error');
		} else {
			$controller = $routes[$this->route][$this->method]['controller'];
			$action = $routes[$this->route][$this->method]['action'];
			$template = $routes[$this->route]['template'] ?? '';
			$page = $controller->$action();

			$keys = new \Fitin\FitinConfig();
			$google = $keys->getKeys();

			$title = $page['title'] ?? '';

			// 2021-07-01 OG NEW - if template is set, then load the template 
			if ($template != '') {
				if (isset($page['variables'])) {
					$output = $this->loadTemplate($page['template'], $page['variables']);
				}
				else {
					$output = $this->loadTemplate($page['template']);
				}
				
				echo $this->loadTemplate($template, ['loggedIn' => $authentication->isLoggedIn(),
																'output' => $output,
																'breadcrumbs' => $breadcrumb,
																'title' => $title,
																'user' => $authentication->getUser(),
																'keys' => $google,
																]);
			// 2021-07-01 OG NEW - else, echo the page variable that returns json 
			} else {
				echo $page;
			}
			

		}

	}
}