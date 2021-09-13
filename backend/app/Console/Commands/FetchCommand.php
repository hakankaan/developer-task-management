<?php

namespace App\Console\Commands;

use App\Services\Task\TaskServiceInterface;
use Illuminate\Console\Command;

class FetchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch the tasks from api and save them to db';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    private $taskService;
    public function __construct(TaskServiceInterface $taskService)
    {
        parent::__construct();
        $this->taskService = $taskService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->taskService->fetchAndSave();
        $this->info('Tasks are fetched and saved to db');
    }
}
