<?php
 
namespace App\Repositories\User;
 
interface UserRepositoryInterface
{
    /**
     * @param  array $data
     * @return \App\Models\User|bool
     */
    public function register($data);

    /**
     * @param  string $code
     * @return App\Models\User|bool
     */
    public function activate($code);

    /**
     * @param  int $id
     * @return App\Models\User
     */
    public function find($id);
}
