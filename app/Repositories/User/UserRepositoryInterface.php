<?php
 
namespace App\Repositories\User;
 
interface UserRepositoryInterface
{
	/**
	 * @param  array $data
	 * @return \App\Models\User|bool
	 */
	public function register($data);
}
