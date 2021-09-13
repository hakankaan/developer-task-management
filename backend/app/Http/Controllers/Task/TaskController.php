<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Services\Task\TaskServiceInterface;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskService;
    public function __construct(TaskServiceInterface $taskService)
    {
        $this->taskService = $taskService;
    }

    public function organize()
    {
        try {
            $tasks = $this->taskService->organize();
            return response()->json($tasks);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getTasks()
    {
        try {
            $developersWithTasks = $this->taskService->getTasks();
            return response()->json($developersWithTasks, 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function truncateDeveloperTasks()
    {
        try {
            $this->taskService->getTasks();
            return response()->json(200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
