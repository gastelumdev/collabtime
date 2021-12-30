<?php
namespace Ijdb\Controllers;

class Category {
	private $categoriesTable;
    private $jokeCategoriesTable; //6/9/18 JG NEW 1L: in order to use $this->jokeCategoriesTable->delete()
	
	//public function __construct(\Ninja\DatabaseTable $categoriesTable) {
	    //$this->categoriesTable = $categoriesTable;   //6/9/18 JG DEL 1L:
	public function __construct(\Ninja\DatabaseTable $categoriesTable, \Ninja\DatabaseTable $jokeCategoriesTable) { //6/9/18 JG NEW 1L:
		$this->categoriesTable = $categoriesTable;
		
		$this->jokeCategoriesTable = $jokeCategoriesTable; //6/9/18 JG NEW 1L: in order to use $this->jokeCategoriesTable->delete()
	}

	public function edit() {

		if (isset($_GET['id'])) {
			$category = $this->categoriesTable->findById($_GET['id']);
		}

		$title = 'Edit Category';

		return ['template' => 'editcategory.html.php',
				'title' => $title,
				'variables' => [
					'category' => $category ?? null
				]
		];
	}

	public function saveEdit() {
		$category = $_POST['category'];

		$this->categoriesTable->save($category);

		// header('location: /category/list'); 6/7/18 JG DEL 1L: org
		header('location: index.php?category/list');  // 6/7/18 JG NEW 1L: 
	}

	public function list() {
		$categories = $this->categoriesTable->findAll();

		$title = 'Joke Categories';

		return ['template' => 'categories.html.php', 
			'title' => $title, 
			'variables' => [
			    'categories' => $categories
			  ]
		];
	}

	public function jsonlist() {
		return json_encode($this->list()['variables']);
	}
	
	
	public function delete() {
		$this->jokeCategoriesTable->delete($_POST['id']); // 6/9/18 JG NEW 1L: first DELETE all rows with $_POST['id'] related to a child table
		$this->categoriesTable->delete($_POST['id']);  // 6/9/18 JG Delete a row at the parent table

		// header('location: /category/list'); 6/7/18 JG DEL 1L: org
		header('location: index.php?category/list');  // 6/7/18 JG NEW 1L: 
	}
}