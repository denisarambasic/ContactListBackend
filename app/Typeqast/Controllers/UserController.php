<?php

namespace Typeqast\Controllers;

use Typeqast\Models\User;

class UserController
{
	/*=== Get all users ===*/
	public function getAllUsers()
	{
		header('Access-Control-Allow-Origin: *');
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
	
	/*=== Get user by id ===*/
	public function getUserById($id){
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET');
		header('Content-type: application/json');
		//echo json_encode(['id'=>$id]);
	}	
	
	/*=== Update user by id ===*/
	public function updateUserById($id){
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
		header('Access-Control-Allow-Methods: DELETE, PATCH');
		
		$data = json_decode(file_get_contents('php://input'));
		$id = $data->id;
		$favorite = $data->favorite;
		
		$user = new User();
		$user_id = $user->updateUserById($id, $favorite);
		
		echo json_encode(['message' => 'User updated']);
		
	}
	
	public function deleteUser($id)
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
		header('Access-Control-Allow-Methods: DELETE');
		
		//DELETE user(contact) FROM db;
		$user = new User();
		
		//Get User by id that we can take the users image name to delete it;
		$currentUser = $user->getUserById($id);
		
		//delete image for this user(contact)
		unlink(dirname(__DIR__, 3).'/img/'. $currentUser['image_name']);

		//finally delete the user(contact) because a constraint is set on the phones table
		//we can only delete the user(contact) and all entries for that user will be deleted in the phones tbl
		$user_id = $user->deleteUser($id);
		echo json_encode(['message' => 'User deleted']);
	}
	
	// Create user (contact)
	public function createUser()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, POST');
		header('Content-type: application/json');
		
		$first_name 	= filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
		$last_name 		= filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
		$image_name		= time() . $_FILES['image_name']['name'];
		$email 			= filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
		$favorite		= ($_POST['favorite'] == "true") ? 1 : 0;
		$phones			= $_POST['phones'];
		
		//upload file
		$this->upload($image_name);
				
		//*Insert a new user(contact) into users table;
		$user = new User();
		$user_id = $user->addNewUser($first_name, $last_name, $image_name, $email, $favorite);
	
		
		//Insert phones for that user (contact) into phones tbl
		foreach($phones as $phone)
		{
			$data = json_decode($phone);
			$user->insertPhone($user_id, filter_var($data->name, FILTER_SANITIZE_STRING), filter_var($data->number, FILTER_SANITIZE_STRING));
		}
		

	}
	
	/*=== A helper method to upload a file in the img/ folder ===*/
	public function upload($filename){
		
		$upload_dir = dirname(__DIR__, 3).'/img/';
		
	
		if (move_uploaded_file($_FILES['image_name']['tmp_name'], $upload_dir. $filename)) {
			echo json_encode(['mess'=>"Uploaded"]);
		} else {
			echo json_encode(['mess'=>"File was not uploaded"]);
		}
	}
	
}