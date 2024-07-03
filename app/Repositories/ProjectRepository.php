<?php

namespace App\Repositories;

use App\Models\Project;
use App\RepositoryInterfaces\ProjectRepositoryInterface;

class ProjectRepository extends BaseRepository implements ProjectRepositoryInterface
{
    protected Project $project;
    public function __construct(Project $project)
    {
        parent::__construct($project);
    }
}
