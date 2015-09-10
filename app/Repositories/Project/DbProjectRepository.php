<?php

namespace App\Repositories\Project;

use App\Models\Project;

class DbProjectRepository implements ProjectRepositoryInterface
{
    /**
     * Constructor
     * 
     * @param  \App\Models\Project $projects
     * @return void
     */
    public function __construct(Project $projects)
    {
        return $this->projects = $projects;
    }

    /**
     * Return all projects
     * 
     * @param  array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all($columns = array('*'))
    {
        return $this->projects->all($columns);
    }

    /**
     * @param  array $data
     * @return App\Models\Project|bool
     */
    public function create($data)
    {
        $project = $this->projects->create($data);

        return $project->save() ? $project : false;
    }

    /**
     * @param  int $id
     * @return App\Models\Project
     */
    public function find($id)
    {
        return $this->projects->find($id);
    }
}
