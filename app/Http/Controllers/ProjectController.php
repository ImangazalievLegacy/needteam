<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\ProjectServiceInterface;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Redirect;
use Auth;

class ProjectController extends Controller
{
	public function __construct(ProjectServiceInterface $projectService, ProjectRepositoryInterface $projectRepository)
    {
        $this->projectService    = $projectService;
        $this->projectRepository = $projectRepository;
    }

    public function getIndex()
    {
        $projects = $this->projectRepository->all();

        return view('post.project.index')->with(['projects' => $projects]);
    }

    public function getCreate()
    {
        return view('post.project.create');
    }

    public function postCreate(Requests\CreateProjectRequest $request)
    {
        $data = $request->only('title', 'description', 'poster', 'active');

        $data['author_id'] = Auth::user()->id;
        $data['active']    = (bool) $data['active'];
        $data['blocked']   = false;

        if ($project = $this->projectService->create($data)) {
            return Redirect::route('project.show', $project->id);
        }

        return Redirect::route('home')->with('global', 'Ошибка при добавлении проекта. Пожалуйста, повторите попытку позже.');
    }
    public function getShow($id, UserRepositoryInterface $userRepository)
    {
        $project = $this->projectRepository->find($id);
        $author  = $userRepository->find($project->author_id);

        return view('post.project.show')->with(['project' => $project, 'author' => $author]);
    }
}
