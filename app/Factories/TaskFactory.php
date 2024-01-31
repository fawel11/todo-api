<?php

namespace App\Factories;

use App\Contracts\FactoryInterface;
use App\Repositories\Api\TaskApiRepository;

class TaskFactory implements FactoryInterface
{

    static public function create()
    {
        //
    }

    static public function createApi()
    {
        return new TaskApiRepository();
    }

}
