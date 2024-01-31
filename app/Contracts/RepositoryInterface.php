<?php

namespace App\Contracts;

use App\Http\Requests\CreateTaskRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

interface RepositoryInterface
{


    public function findById($id);

    public function getAll(Request $request);

    public function getAllForUserId($id);


}
