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
	
	/*=== Get the list of phones for that user ===*/
	public function getPhonesByUser($user_id)
	{
		$query = "SELECT * FROM phones WHERE user_id = :user_id";
		$stmt = $this->getConnection()->prepare($query);
		$stmt->bindParam('user_id', $user_id);
		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		
	}
	
}