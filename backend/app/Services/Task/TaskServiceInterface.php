<?php

namespace App\Services\Task;

interface TaskServiceInterface
{
    public function fetchAndSave();
    public function organize();
    public function getTasks();
}
