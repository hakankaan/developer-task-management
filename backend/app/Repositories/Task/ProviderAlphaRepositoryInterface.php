<?php

namespace App\Repositories\Task;

use Illuminate\Support\Collection;

interface ProviderAlphaRepositoryInterface
{
    public function fetch(): Collection;
}
