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
	
	
	
}