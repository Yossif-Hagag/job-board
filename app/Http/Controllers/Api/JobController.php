<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Services\JobFilterService;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter');
        $query = Job::query()->with(['languages', 'locations', 'categories', 'attributeValues.attribute']);
        $jobs = (new JobFilterService($query, $filter))->apply()->get();
    
        return response()->json($jobs);
    }
    
}