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
	
	/*=== GET user by id ===*/
	public function getUserById($id)
	{
		$query = "SELECT * FROM users WHERE id= :id";
		$stmt = $this->getConnection()->prepare($query);
		$stmt->bindParam('id', $id);
		$stmt->execute();
		return $stmt->fetch(\PDO::FETCH_ASSOC);
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