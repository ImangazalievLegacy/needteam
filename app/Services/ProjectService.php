<?php

namespace App\Services;

use App\Repositories\Project\ProjectRepositoryInterface;
use App\Events\ProjectCreated;
use App\Http\Requests;

class ProjectService implements ProjectServiceInterface
{
    /**
     * Constructor
     * 
     * @param  ProjectRepositoryInterface $projectRepository
     * @return void
     */
    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    /**
     * @param  array $data
     * @return bool
     */
    public function create($data)
    {
        $project = $this->projectRepository->create($data);

        if ($project === false) {
            return false;
        }

        event(new ProjectCreated($project));

        return $project;
    }
}
