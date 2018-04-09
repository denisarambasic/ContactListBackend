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
	
	/*=== Get all favorite users ===*/
	public function getAllFavoritesUsers()
	{
		header('Access-Control-Allow-Origin: *');
		$user = new User();
		$users = $user->getAllFavoritesUsers();
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
				
		$user = new User();
		$currentUser = $user->getUserById($id);
		
		$currentUser = array_merge($currentUser, ['phones' => $user->getPhonesByUser($currentUser['id'])]);
		http_response_code(200);
		header('Content-type: application/json');
		echo json_encode($currentUser);
		
	}	
	
	/*=== Get all users based on an search value ===*/
	public function getAllUsersOnSearch($search_value){
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
		$user = new User();
		$users = $user->getAllUsersBySearchValue($search_value);
		$user_temp = [];
		
		//Get the phones for every user
		foreach($users as $u){
			$user_temp[] = array_merge($u, ['phones' => $user->getPhonesByUser($u['id'])]);
		}
		
		http_response_code(200);
		header('Content-type: application/json');
		echo json_encode($user_temp);	
			
	}
	
	/*=== Get all users based on an search value ===*/
	public function getAllFavoriteUsersOnSearch($search_value){
		header('Access-Control-Allow-Origin: *');
		$user = new User();
		$users = $user->getAllFavoriteUsersBySearchValue($search_value);
		$user_temp = [];
		
		//Get the phones for every user
		foreach($users as $u){
			$user_temp[] = array_merge($u, ['phones' => $user->getPhonesByUser($u['id'])]);
		}
		
		http_response_code(200);
		header('Content-type: application/json');
		echo json_encode($user_temp);	
			
	}
	
	
	/*=== Update user by id ===*/
	public function updateUserById($id){
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
		header('Access-Control-Allow-Methods: DELETE, PATCH, PUT');

		if($_SERVER['REQUEST_METHOD'] == 'PATCH'){
		
		$data = json_decode(file_get_contents('php://input'));
		$id = $data->id;
		$favorite = $data->favorite;
		
		$user = new User();
		$user_id = $user->updateUserById($id, $favorite);
		
		echo json_encode(['message' => 'User updated']);
		}
		
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
	
	//update user by id all fields
	public function updateUserByIdAll($id)
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
		header('Access-Control-Allow-Methods: DELETE, PATCH, POST');
		
		
		$first_name 	= filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
		$last_name 		= filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
		//if new image give it a name if not set to false
		if($_FILES){
			$image_name		= time() . $_FILES['image_name']['name'];
		}else {
			$image_name		= false;
		}
		
		
		$email 			= filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
		$favorite		= ($_POST['favorite'] == 1) ? 1 : 0;
		
		//check if phones is set
		if(isset($_POST['phones'])){
			$phones	= $_POST['phones'];
		}else{
			$phones	= null;
		}
		
		$user = new User();
		
		//1. if image_name == null update all fields without image_name
		if(!$image_name){
			$user->updateUserWithoutImg($id, $first_name, $last_name, $email, $favorite);
		}else{

			//if image_name exists that means that someone edit his profile image we need to get the old image name
			//to delete it from the img folder
			$thisUser = $user->getUserById($id);
			$image_name_old = $thisUser['image_name'];
			unlink(dirname(__DIR__, 3).'/img/'. $image_name_old);
			
			//upload the new file
			$this->upload($image_name);
					
			//*Update the user with all fields;

			$user->updateUserWithImg($id, $first_name, $last_name, $image_name, $email, $favorite);	
			
		}
		
		//delete all phones for that user and set it again
		$user->deleteUserPhones($id);

		//Insert phones for that user (contact) into phones tbl again.
		if($phones){
			foreach($phones as $phone)
			{
				$data = json_decode($phone);
				$user->insertPhone($id, filter_var($data->name, FILTER_SANITIZE_STRING), filter_var($data->number, FILTER_SANITIZE_STRING));
			}
		}
		echo json_encode(['message'=>'user updated']);
		
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
			//echo json_encode(['mess'=>"Uploaded"]);
		} else {
			//echo json_encode(['mess'=>"File was not uploaded"]);
		}
	}
	
}