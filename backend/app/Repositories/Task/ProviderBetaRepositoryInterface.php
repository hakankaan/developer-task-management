<?php

namespace App\Repositories\Task;

use Illuminate\Support\Collection;

interface ProviderBetaRepositoryInterface
{
    public function fetch(): Collection;
}
