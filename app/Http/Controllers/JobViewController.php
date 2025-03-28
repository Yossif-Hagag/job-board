<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Category;
use App\Models\Location;
use App\Models\Language;
use App\Models\Attribute;

class JobViewController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::query();
        $query->where('status', 'published');
    
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }
        
        if ($request->filled('is_remote')) {
            $query->where('is_remote', $request->is_remote);
        }
        
        if ($request->filled('job_type')) {
            $query->where('job_type', $request->job_type);
        }
    
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('id', $request->category_id);
            });
        }
    
        if ($request->filled('location_id')) {
            $query->whereHas('locations', function ($q) use ($request) {
                $q->where('id', $request->location_id);
            });
        }
    
        if ($request->filled('language_id')) {
            $query->whereHas('languages', function ($q) use ($request) {
                $q->where('id', $request->language_id);
            });
        }
    
        $attributes = Attribute::with(['values' => function ($query) {
            $query->select('attribute_id', 'value')
                  ->where('value', '!=', 'N/A')
                  ->groupBy('attribute_id', 'value')
                  ->orderBy('value');
        }])->get();
    
        foreach ($attributes as $attribute) {
            $key = 'attr_' . $attribute->id;
            if ($request->filled($key)) {
                $query->whereHas('attributeValues', function ($q) use ($attribute, $request, $key) {
                    $q->where('attribute_id', $attribute->id)
                      ->where('value', $request->$key);
                });
            }
        }
    
        $jobs = $query->latest()->paginate(9)->withQueryString();
    
        $categories = Category::select('id', 'name')
            ->where('name', '!=', 'N/A')
            ->groupBy('id', 'name')
            ->orderBy('name')
            ->get();
    
        $locations = Location::select('id', 'city')
            ->where('city', '!=', 'N/A')
            ->groupBy('id', 'city')
            ->orderBy('city')
            ->get();
    
        $languages = Language::select('id', 'name')
            ->where('name', '!=', 'N/A')
            ->groupBy('id', 'name')
            ->orderBy('name')
            ->get();
    
        return view('home.welcome', compact(
            'jobs',
            'categories',
            'locations',
            'languages',
            'attributes'
        ));
    }
    
    

    public function show(Job $job)
    {
        $job->load(['categories', 'locations', 'languages', 'attributeValues.attribute']);

        return view('home.show', compact('job'));
    }
}