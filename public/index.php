<?php
try {
	include __DIR__ . '/../includes/autoload.php';
        // Remove message from request
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	
	$route = ltrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');   // JG remove leading / and extract the string till ? from e.g. /ch14_FINAL-Website/public/index.php?joke/list?page=1

	
	//***********************JG  5/24/18 NEW line 8 - 42 ADAPTER to replace URL writing feature b/c .htaccess file is ignored by Apache********************	
// 	print ('index.php 9: REQUEST_URI = ' . $_SERVER['REQUEST_URI'] . '<br>');  // JG test
// 	print ('index.php: 10 ltrim route = ' . $route . '<br>');  // JG test
	
	
	   //5/22/18 JG NEW4l: adapter to the code b/c of the .htaccess is ignored by apache
	if ($route == ltrim($_SERVER['REQUEST_URI'],  '/') ) 
	    $route = '';  //JG  5/21/18 NEW replaces by ''	    
	else
	    $route = $_SERVER['QUERY_STRING']; // 5/22/18 JG NEW1l: give the query = remaining string
      
// 	print ('index.php: 19 route = ' . $route . '<br>');  // JG test
	
	  //5/22/18 JG NEW6l: adapter to the code b/c of the .htaccess is ignored by apache
	if (strlen(strtok($route, '?')) <  strlen($route))  // string has a second ?
	{ 
		if (strpos($route, 'id')){ //6/7/18 JG is there id?
		   $_GET['id'] = substr ($route, strlen(strtok($route, '?')) + 4, strlen($route)); //6/7/18 JG extract id from e.g. joke/edit?id=37
	       //print ('index.php: 26  id = ' . $_GET['id'] . '<br>');  // test   substr($string, $start, $length)
		  }
		if (strpos($route, 'page') && strpos($route, 'category')) { //6/7/18 JG is there e.g. joke/list?page=2&category=3 for display later ?
		   $_GET['page'] =  substr($route, strpos($route, '=') + 1, strpos($route, '&') - strpos($route, '=') - 1 );
        //   print ('index.php: 30 page = ' . $_GET['page']. '<br>');  // test
		
		   $_GET['category'] = substr ($route, strlen(strtok($route, '&')) + 10, strlen($route)); //6/7/18 JG extract category id 
		                                                                                //from e.g. joke/list?page=2&category=3
		  // print ('index.php: 34 category = ' . $_GET['category'] . '<br>');  // test  
		}
		else {
		if (strpos($route, 'category')){
	       $_GET['category'] = substr ($route, strlen(strtok($route, '?')) + 10, strlen($route)); //6/7/18 JG extract category id 
		                                                                                          //from e.g. joke/list?category=6
		  //  print ('index.php: 40 category = ' . $_GET['category'] . '<br>');  // test
		  }
		if (strpos($route, 'page')){
	       $_GET['page'] = substr ($route, strlen(strtok($route, '?')) + 6, strlen($route)); //6/7/18 JG extract page id from e.g. joke/list?page=2
	       //print ('index.php: 44 page = ' . $_GET['page']. '<br>');  // test
		}
		} // end else
	
	$route = strtok($route, '?'); //retrieve the string between ? ? - for e.g. index?joke/edit?id=12
	} // end the 1st if	
	
// 	print ('index.php: 48 route = ' . $route . '<br>');  // JG test
    // print ('index.php: 49 REQUEST_METHOD = ' . $_SERVER['REQUEST_METHOD']. '<br>');  // JG test
	//****************************END OF JG  5/24/18 NEW line 8 - 42//****************************************************************************************

	$entryPoint = new \Ninja\EntryPoint($route, $_SERVER['REQUEST_METHOD'], new \Fitin\FitinRoutes());
	$entryPoint->run();
}
catch (PDOException $e) {
	// Create the title of the the error page
	$title = 'An error has occurred';

	$output = 'Database error: ' . $e->getMessage() . ' in ' .
	$e->getFile() . ':' . $e->getLine();

	include  __DIR__ . '/../templates/layout.html.php';
}
