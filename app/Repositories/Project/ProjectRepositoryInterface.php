<?php
 
namespace App\Repositories\Project;
 
interface ProjectRepositoryInterface
{
	/**
	 * Return all projects
     * 
     * @param  array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all($columns = array('*'));

    /**
     * @param  array $data
     * @return App\Models\Project|bool
     */
    public function create($data);

    /**
     * @param  int $id
     * @return App\Models\Project
     */
    public function find($id);
}
