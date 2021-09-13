<?php

namespace App\Repositories\Task;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class ProviderAlphaRepository implements ProviderAlphaRepositoryInterface
{
    protected $api_url;
    public function __construct()
    {
        $this->api_url = "http://www.mocky.io/v2/5d47f24c330000623fa3ebfa";
    }


    public function fetch(): Collection
    {
        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->get($this->api_url);

        if ($response->successful()) {
            $tasks = $response->collect();
            $tasks = $tasks->map(function ($item, $key) {
                return [
                    'difficulty' => $item['zorluk'],
                    'duration' => $item['sure'],
                    'source_id' => $item['id'],
                ];
            });
            return $tasks;
        }
    }
}
