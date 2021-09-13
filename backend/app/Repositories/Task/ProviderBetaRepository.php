<?php

namespace App\Repositories\Task;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class ProviderBetaRepository implements ProviderBetaRepositoryInterface
{
    protected $api_url;
    public function __construct()
    {
        $this->api_url = "http://www.mocky.io/v2/5d47f235330000623fa3ebf7";
    }


    public function fetch(): Collection
    {
        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->get($this->api_url);

        if ($response->successful()) {
            $tasks = $response->collect();
            $tasks = $tasks->map(function ($item, $key) {
                $firstKey = array_keys($item)[0];
                $itemDetails = $item[$firstKey];
                return [
                    'difficulty' => $itemDetails['level'],
                    'duration' => $itemDetails['estimated_duration'],
                    'source_id' => $firstKey,
                ];
            });

            return $tasks;
        }
    }
}
