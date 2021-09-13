<?php

namespace App\Helpers;

class TaskHelper
{
    public static function assignTasks($tasks, $k, $extraDevelopers)
    {
        // Sorting the tasks by duration improved the performance
        array_multisort(array_column($tasks, 'duration'), SORT_ASC, array_column($tasks, 'task_id'), SORT_DESC, $tasks);
        $result = INF;
        $lastTaskIndex = count($tasks) - 1;
        // We will check the best combinations over this array
        $developersArray = array_fill(0, $k, ['total_duration' => 0, 'tasks' => []]);
        // Assigning a symbolic task to higher difficulty developer
        // Symbolic task duration is equal to initial total task duration of the developer with higher difficulty
        for ($i = 0; $i < count($extraDevelopers); $i++) {
            $developersArray[$i]['developer_id'] = $extraDevelopers[$i]['developer_id'];
            $developersArray[$i]['total_duration'] = $extraDevelopers[$i]['total_duration'];
            array_push($developersArray[$i]['tasks'], ['task_id' => 0, 'duration' => $extraDevelopers[$i]['total_duration']]);
        }

        // This function will be used for recursive operation
        $tasksPerWorker = [];
        $checking = function ($lastTaskIndex, $developersArray, &$tasks, &$result, &$tasksPerWorker) use (&$checking) {
            $maxTotalDuration = max(array_map(function ($item) {
                return $item['total_duration'];
            }, $developersArray));
            if ($lastTaskIndex < 0) {
                // Saving the task combinations for each developer 
                if ($maxTotalDuration < $result) {
                    $tasksPerWorker = $developersArray;
                }
                // Storing total duration of best combination
                $result = min($result, $maxTotalDuration);
                return;
            }
            if ($maxTotalDuration >= $result) return;     // Pruning
            for ($currentIndex = 0; $currentIndex < count($developersArray); $currentIndex++) {
                if ($currentIndex > 0 && $developersArray[$currentIndex]['total_duration'] == $developersArray[$currentIndex - 1]['total_duration']) continue;     // Pruning
                // Adding new tasks to developers array to find best combination recursively
                $developersArray[$currentIndex]['total_duration'] += $tasks[$lastTaskIndex]['duration'];
                array_push($developersArray[$currentIndex]['tasks'], $tasks[$lastTaskIndex]);
                $checking($lastTaskIndex - 1, $developersArray, $tasks, $result, $tasksPerWorker);
                // Cleaning
                $taskKey = array_search($tasks[$lastTaskIndex]['task_id'], array_column($developersArray[$currentIndex]['tasks'], 'task_id'));
                array_splice($developersArray[$currentIndex]['tasks'], $taskKey, 1);
                $developersArray[$currentIndex]['total_duration'] -= $tasks[$lastTaskIndex]['duration'];
            }
        };
        $checking($lastTaskIndex, $developersArray, $tasks, $result, $tasksPerWorker);
        unset($checking);
        return $tasksPerWorker;
    }
}
