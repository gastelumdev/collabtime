<?php
namespace Ijdb\Controllers;
use \Ninja\DatabaseTable;
use \Ninja\Authentication;

class Joke {
	private $authorsTable;
	private $jokesTable;
	private $categoriesTable;
	private $jokeCategoriesTable; //6/12/18 JG NEW 1L: in order to use $this->jokeCategoriesTable->deleteWhere()
	private $authentication;

	//public function __construct(DatabaseTable $jokesTable, DatabaseTable $authorsTable, DatabaseTable $categoriesTable, 
	//Authentication $authentication) { //6/12/18 JG DEL 1L: need to add $jokecategory table to have all possible manipulation such as delete a child table
	public function __construct(DatabaseTable $jokesTable, DatabaseTable $authorsTable, DatabaseTable $categoriesTable, 
	     DatabaseTable $jokeCategoriesTable, Authentication $authentication) { //6/12/18 JG MOD 1L: added $jokeCategories table to manipulate it e.g. delete a child table
    	$this->jokesTable = $jokesTable;
		$this->authorsTable = $authorsTable;
		$this->categoriesTable = $categoriesTable;
		$this->jokeCategoriesTable = $jokeCategoriesTable; //6/12/18 JG NEW 1L: in order to use $this->jokeCategoriesTable->deleteWhere()
		$this->authentication = $authentication;
	}

	public function list() {

		$page = $_GET['page'] ?? 1;

		$offset = ($page-1)*10;

		if (isset($_GET['category'])) {
			$category = $this->categoriesTable->findById($_GET['category']);
			$jokes = $category->getJokes(10, $offset);
			$totalJokes = $category->getNumJokes();
		}
		else {
			$jokes = $this->jokesTable->findAll('jokedate DESC', 10, $offset);
			$totalJokes = $this->jokesTable->total();
		}		

		$title = 'Joke list';

		

		$author = $this->authentication->getUser();

		return ['template' => 'jokes.html.php', 
				'title' => $title, 
				'variables' => [
						'totalJokes' => $totalJokes,
						'jokes' => $jokes,
						'user' => $author,
						'categories' => $this->categoriesTable->findAll(),
						'currentPage' => $page,
						'categoryId' => $_GET['category'] ?? null
					]
				];
	}

	public function home() {
		$title = 'Internet Joke Database';

		return ['template' => 'home.html.php', 'title' => $title];
	}

	
	public function delete() {

		$author = $this->authentication->getUser();

		$joke = $this->jokesTable->findById($_POST['id']);

		if ($joke->authorId != $author->id && !$author->hasPermission(\Ijdb\Entity\Author::DELETE_JOKES) ) {
			return;
		}
		
        $this->jokeCategoriesTable->deleteWhere('jokeid', $_POST['id']); // 6/13/18 JG NEW 1L:  first DELETE all rows with $_POST['id'] related to a child table
		                                               //otherwise DB problem and joke display problem for [1] 2 3 etc.
		
		$this->jokesTable->delete($_POST['id']);   // 6/13/18 JG delete a row at the parent table
		
        //header('location: /joke/list'); 5/25/18 JG DEL 1L:  org
        header('location: index.php?joke/list');  // 5/25/18 JG NEW 1L  	

	}

	public function saveEdit() {
		$author = $this->authentication->getUser();

		$joke = $_POST['joke'];
		$joke['jokedate'] = new \DateTime();

		$jokeEntity = $author->addJoke($joke);

		$jokeEntity->clearCategories();

		foreach ($_POST['category'] as $categoryId) {
			$jokeEntity->addCategory($categoryId);
		}

		//header('location: /joke/list'); 5/25/18 JG DEL 1L:  org
		header('location: index.php?joke/list');  //5/25/18 JG NEW 1L  	

	}

	public function edit() {
		$author = $this->authentication->getUser();
		$categories = $this->categoriesTable->findAll();

		if (isset($_GET['id'])) {
			$joke = $this->jokesTable->findById($_GET['id']);
		}

		$title = 'Edit joke';

		return ['template' => 'editjoke.html.php',
				'title' => $title,
				'variables' => [
						'joke' => $joke ?? null,
						'user' => $author,
						'categories' => $categories
					]
				];
	}
	
}