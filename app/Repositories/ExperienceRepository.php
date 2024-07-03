<?php

namespace App\Repositories;

use App\Models\Experience;
use App\RepositoryInterfaces\ExperienceRepositoryInterface;

class ExperienceRepository extends BaseRepository implements ExperienceRepositoryInterface
{
    protected Experience $experience;
    public function __construct(Experience $experience)
    {
        parent::__construct($experience);
    }
}
