<?php

namespace App\Services\Task;

use App\Models\Developer;
use App\Models\Task;
use App\Repositories\Task\ProviderAlphaRepositoryInterface;
use App\Repositories\Task\ProviderBetaRepositoryInterface;
use App\Helpers\TaskHelper;
use App\Models\DeveloperTask;

class TaskService implements TaskServiceInterface
{
    protected $providerAlphaRepository;
    protected $providerBetaRepository;
    public function __construct(
        ProviderAlphaRepositoryInterface $providerAlphaRepository,
        ProviderBetaRepositoryInterface $providerBetaRepository
    ) {
        $this->providerAlphaRepository = $providerAlphaRepository;
        $this->providerBetaRepository = $providerBetaRepository;
    }

    public function fetchAndSave()
    {
        $tasks = $this->providerAlphaRepository->fetch();
        $tasks = $tasks->concat($this->providerBetaRepository->fetch());
        Task::insert($tasks->toArray());
    }

    public function getTasks()
    {
        $developersWithTasks = Developer::with('tasks')->get();
        return $developersWithTasks;
    }



    public function organize()
    {
        ini_set('max_execution_time', 20000);
        $tasks = Task::select('id as task_id', 'difficulty', 'duration')->get();
        $developers = Developer::select('id as developer_id', 'difficulty')->orderBy('difficulty', 'desc')->get();
        // Getting difficulties to iterate over them
        $difficulties = $developers->map(function ($item) {
            return $item->difficulty;
        })->uniqueStrict()->values()->all();
        // finalResult will contain developers with tasks 
        $finalResult = collect([]);
        foreach ($difficulties as $key => $value) {
            $developersByDifficulty = $developers->where('difficulty', $value)->values()->all();
            $tasksByDifficulty = $tasks->where('difficulty', $value)->values()->all();
            $totalDurationOfTasks = array_sum(array_column($tasksByDifficulty, 'duration'));
            $currentDifficulty = $value;
            // Check these situations to prevent more calculations
            if (
                $key == 0 && count($developersByDifficulty) == 1
                || count($developersByDifficulty) == 1 && $totalDurationOfTasks <= $finalResult->min('total_duration')
            ) {
                $finalResult->push([
                    'developer_id' => $developersByDifficulty[0]['developer_id'],
                    'difficulty' => $currentDifficulty,
                    'tasks' => $tasksByDifficulty,
                    'total_duration' => $totalDurationOfTasks
                ]);
                continue;
            }

            // find the developers that has tasks with total duration lower then the duration of tasks for current difficulty
            $tmpList = collect($finalResult->filter(function ($developer) use ($totalDurationOfTasks) {
                return $developer['total_duration'] < $totalDurationOfTasks;
            })->values()->all());

            // If lower difficulty has higher totalDuration then we share the tasks with higher difficulty developers
            $currentAndExtraDevelopers = count($developersByDifficulty) + count($tmpList);
            $extraDevelopers = $tmpList->map(function ($extraDeveloper) {
                return ['developer_id' => $extraDeveloper['developer_id'], 'total_duration' => $extraDeveloper['total_duration']];
            })->all();

            // Helper function to assign tasks to developers accurately
            $assigningTasks = TaskHelper::assignTasks($tasksByDifficulty, $currentAndExtraDevelopers, $extraDevelopers);
            foreach ($assigningTasks as $key => $value) {
                // Check if tasks shared with the developers who has higher difficulty
                if (isset($value['developer_id'])) {
                    $taskKeyToRemove = array_search(0, array_column($value['tasks'], 'task_id'));
                    array_splice($value['tasks'], $taskKeyToRemove, 1);
                    $assigningTask = $value['tasks'];
                    $currentDeveloperId = $value['developer_id'];
                    // Update the final result with adding new tasks to developer which has higher difficulty
                    // Some of them didn't assigned with a new task
                    $finalResult = $finalResult->map(function ($developer) use ($currentDeveloperId, $assigningTask) {
                        if ($developer['developer_id'] == $currentDeveloperId) {
                            $mergedTasks = array_merge($developer['tasks'], $assigningTask);
                            return [
                                'developer_id' => $developer['developer_id'],
                                'difficulty' => $developer['difficulty'],
                                'tasks' => $mergedTasks,
                                'total_duration' => array_sum(array_column($mergedTasks, 'duration'))
                            ];
                        } else return $developer;
                    });
                } else {
                    // New developer with new tasks
                    $developerToAssignTask = array_pop($developersByDifficulty);
                    $finalResult->push([
                        'developer_id' => $developerToAssignTask['developer_id'],
                        'difficulty' => $currentDifficulty,
                        'tasks' => $value['tasks'],
                        'total_duration' => $value['total_duration']
                    ]);
                }
            }
        }
        // Updating the pivot table to persist data in the database
        $finalResult->each(function ($developer) {
            $tmpArray = array_map(function ($task) use ($developer) {
                return ['task_id' => $task->task_id, 'developer_id' => $developer['developer_id']];
            }, $developer['tasks']);
            DeveloperTask::insert($tmpArray);
        });

        return $finalResult;
    }
}
