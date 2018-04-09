<?php

namespace Typeqast\Models;

class User extends BaseModel
{
	
	/*=== Get all Users ===*/
	public function getAllUsers()
	{
		$query = "SELECT * FROM users";
		$stmt = $this->getConnection()->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	/*=== Get all Users ===*/
	public function getAllFavoritesUsers()
	{
		$query = "SELECT * FROM users WHERE favorite = 1";
		$stmt = $this->getConnection()->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	/*=== GET user by id ===*/
	public function getUserById($id)
	{
		$query = "SELECT * FROM users WHERE id= :id";
		$stmt = $this->getConnection()->prepare($query);
		$stmt->bindParam('id', $id);
		$stmt->execute();
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}
	
	/*=== GET all users based on a search value ===*/
	public function getAllUsersBySearchValue($search_value)
	{
		$query = "SELECT DISTINCT(users.id), first_name, last_name, image_name, email, favorite FROM users 
					INNER JOIN phones ON users.id = phones.user_id WHERE  first_name LIKE ? OR last_name LIKE ?
					OR email LIKE ? OR name LIKE ? OR number LIKE ?";
		$stmt = $this->getConnection()->prepare($query);
		$params= array("%$search_value%", "%$search_value%", "%$search_value%", "%$search_value%", "%$search_value%");
		$stmt->execute($params);
		//echo $stmt->queryString; die;
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	/*=== GET all favorite users based on a search value ===*/
	public function getAllFavoriteUsersBySearchValue($search_value)
	{
		$query = "SELECT DISTINCT(id), first_name, last_name, image_name, email, favorite FROM 
					(SELECT users.id, first_name, last_name, image_name, email, favorite, name, number FROM users 
					INNER JOIN phones ON users.id = phones.user_id WHERE favorite = 1)as tbl1 
					WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR name LIKE ? OR number LIKE ?;";
		$stmt = $this->getConnection()->prepare($query);
		$params= array("%$search_value%", "%$search_value%", "%$search_value%", "%$search_value%", "%$search_value%");
		$stmt->execute($params);
		//echo $stmt->queryString; die;
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	/*=== Get the list of phones for that user ===*/
	public function getPhonesByUser($user_id)
	{
		$query = "SELECT * FROM phones WHERE user_id = :user_id";
		$stmt = $this->getConnection()->prepare($query);
		$stmt->bindParam('user_id', $user_id);
		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		
	}
	
	/*=== Update the user without image ===*/
	public function updateUserWithoutImg($id, $first_name, $last_name, $email, $favorite)
	{
		$query = "UPDATE users SET first_name=:first_name, last_name=:last_name, email=:email, favorite= :favorite WHERE id = :id";
		$stmt = $this->getConnection()->prepare($query);
		$stmt->bindParam('id', $id);
		$stmt->bindParam('first_name', $first_name);
		$stmt->bindParam('last_name', $last_name);
		$stmt->bindParam('email', $email);
		$stmt->bindParam('favorite', $favorite);
		return $stmt->execute();
	}
	
	//Updade user with img
	public function updateUserWithImg($id, $first_name, $last_name, $image_name, $email, $favorite)
	{
		$query = "UPDATE users SET first_name=:first_name, last_name=:last_name, image_name=:image_name, email=:email, favorite=:favorite WHERE id =:id";
		$stmt = $this->getConnection()->prepare($query);
		$stmt->bindParam('id', $id);
		$stmt->bindParam('first_name', $first_name);
		$stmt->bindParam('last_name', $last_name);
		$stmt->bindParam('image_name', $image_name);
		$stmt->bindParam('email', $email);
		$stmt->bindParam('favorite', $favorite);
		return $stmt->execute();
	}
	
	/*=== Insert a new user (contact) into db ===*/
	public function addNewUser($first_name, $last_name, $image_name, $email, $favorites)
	{
		$query = "INSERT INTO users VALUES (NULL, :first_name, :last_name, :image_name, :email, :favorites)";
		$stmt = $this->getConnection()->prepare($query);
		$stmt->bindParam('first_name', $first_name);
		$stmt->bindParam('last_name', $last_name);
		$stmt->bindParam('image_name', $image_name);
		$stmt->bindParam('email', $email);
		$stmt->bindParam('favorites', $favorites);
		$stmt->execute();
		
		//return the id of the inserted row
		return $this->getConnection()->lastInsertId();
		
	}
	
	/*=== Isert phones for that user(contact) into phones tbl ===*/
	public function insertPhone($user_id, $name, $number)
	{
		$query = "INSERT INTO phones VALUES (NULL, :name, :number, :user_id)";
		$stmt = $this->getConnection()->prepare($query);
		$stmt->bindParam('name', $name);
		$stmt->bindParam('number', $number);
		$stmt->bindParam('user_id', $user_id);
		return $stmt->execute();
	}
	
	/*=== Delete user(contact) phones by id ===*/
	public function deleteUserPhones($id)
	{
		$query = "DELETE FROM phones WHERE user_id = :id";
		$stmt = $this->getConnection()->prepare($query);
		$stmt->bindParam('id', $id);
		return $stmt->execute();
	}
	
	/*=== Update user by id favorite ===*/
	public function updateUserById($id, $favorite)
	{
		$query = "UPDATE users SET favorite= :favorite WHERE id = :id";
		$stmt = $this->getConnection()->prepare($query);
		$stmt->bindParam('favorite', $favorite);
		$stmt->bindParam('id', $id);
		return $stmt->execute();
	}
	
	/*=== Delete user(contact) by id ===*/
	public function deleteUser($id)
	{
		$query = "DELETE FROM users WHERE id = :id";
		$stmt = $this->getConnection()->prepare($query);
		$stmt->bindParam('id', $id);
		return $stmt->execute();
	}
}