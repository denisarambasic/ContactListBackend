<?php

namespace Typeqast\Controllers;

use Typeqast\Models\User;

class UserController
{
	
	public function getAllUsers()
	{
		
		$user = new User();
		$users = $user->getAllUsers();
		$user_temp = [];
		
		//Get the phones for every user
		foreach($users as $u){
			$user_temp[] = array_merge($u, ['phones' => $user->getPhonesByUser($u['id'])]);
		}
		
		http_response_code(200);
		header('Content-type: application/json');
		echo json_encode($user_temp);

	}
	
}