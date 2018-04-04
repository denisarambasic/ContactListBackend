<?php

namespace Typeqast\Helpers;

class Routing
{
	//We store all our routes that we define in Routes.php into this property
	private $routes = [];
	
	//The root path to your project, if you place it in the public folder use "/",
	//I hold my project on the server in a subfolder "typeqast"
	private $rootPath = '/typeqast';
	
	//Here I store my routes into the routes array
	public function setRoute($httpMethod, $path, $controller, $controllerMethod, $params)
	{
		$this->routes[] = [
			'httpMethod'		=> $httpMethod,
			'path'				=> $path,
			'controller'		=> $controller,
			'controllerMethod'	=> $controllerMethod,
			'params'			=> $params
		];
	}
	
	//Here we call a controller and method if the clients request match any defined route
	public function callController()
	{
		$requestMethod 	= $_SERVER['REQUEST_METHOD'];
		
		//extract the rootPath from the uri
		$requestUri	= substr($_SERVER['REQUEST_URI'], strlen($this->rootPath));
		$temp 		= $requestUri;

		while($temp != '')
		{
			foreach($this->routes as $route)
			{
				//check the path match
				if($temp == $route['path']){
					//check the request Method match
					if($requestMethod == $route['httpMethod']){
						//get the params from the uri
						$params = ltrim(substr($requestUri, strlen($temp)), '/');
						$params = explode('/', $params);
						
						//if the first param is an empty string set params to an empty array
						if($params[0] == ''){
							$params = [];
						}
					
						//check if the params count match
						if(count($params) == count($route['params'])){
							//the last step is to call the defined controller, method and pass the params to it
							$controller = 'Typeqast\Controllers\\' . $route['controller'];
							$controller = new $controller();
							call_user_func_array([$controller, $route['controllerMethod']], $params);
							exit;
						}
											
					}
				}
			}
			
			//If no route match the clients request, extract the last param of the client request and check again
			$tempArray = explode('/', $temp);
			array_pop($tempArray);
			$temp = implode('/', $tempArray);
		}
		
	}
	
}