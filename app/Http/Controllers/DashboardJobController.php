<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Location;
use App\Models\Language;
use App\Models\Attribute;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardJobController extends Controller
{
    public function index()
    {
        return view('dashboard.dashboard', [
            'jobCount' => Job::count(),
            'publishedCount' => Job::where('status', 'published')->count(),
            'draftCount' => Job::where('status', 'draft')->count(),
            'archivedCount' => Job::where('status', 'archived')->count(),
            'companyCount' => Job::distinct('company_name')->count(),
            'locationCount' => Location::count(),
            'userCount' => User::count(),
            'latestJobs' => Job::latest()->take(5)->get(),
            'jobsPerMonthLabels' => Job::selectRaw('MONTHNAME(created_at) as month')
                ->groupBy('month')
                ->orderByRaw('MIN(created_at)')
                ->pluck('month'),
            'jobsPerMonthCounts' => Job::selectRaw('COUNT(*) as count')
                ->groupByRaw('MONTH(created_at)')
                ->pluck('count'),
        ]);
    }

    public function allJobs()
    {
        $jobs = Job::latest()->paginate(9);
        return view('dashboard.jobs.index', compact('jobs'));
    }

    public function show(Job $job)
    {
        return view('dashboard.jobs.show', [
            'job' => $job->load('categories', 'locations', 'languages', 'attributeValues.attribute'),
        ]);
    }
    
    public function create()
    {
        return view('dashboard.jobs.create', [
            'categories' => Category::all(),
            'locations' => Location::all(),
            'languages' => Language::all(),
            'attributes' => Attribute::with('values')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required',
            'company_name' => 'required|string',
            'salary_min' => 'required|numeric',
            'salary_max' => 'required|numeric',
            'job_type' => 'required|in:full-time,part-time,contract',
            'is_remote' => 'nullable|boolean',
            'category_ids' => 'required|array',
            'location_ids' => 'array',
            'language_ids' => 'required|array',
            'status' => 'required|in:draft,published,archived',
        ]);
    
        $job = Job::create([
            'title' => $request->title,
            'description' => $request->description,
            'company_name' => $request->company_name,
            'salary_min' => $request->salary_min,
            'salary_max' => $request->salary_max,
            'job_type' => $request->job_type,
            'is_remote' => $request->has('is_remote'),
            'status' => $request->status,
            'published_at' => $request->status=="published" ? now() : null,
        ]);
    
        $job->categories()->attach($request->category_ids);
        $job->locations()->attach($request->location_ids);
        $job->languages()->attach($request->language_ids);
    
        foreach ($request->all() as $key => $value) {
            if (str_starts_with($key, 'attr_') && $value !== null && $value !== '') {
                $attributeId = str_replace('attr_', '', $key);
                $job->attributeValues()->create([
                    'attribute_id' => $attributeId,
                    'value' => $value,
                ]);
            }
        }
    
        return redirect()->route('dashboard.jobs.index')->with('success', 'Job created successfully!');
    }
    
    public function edit(Job $job)
    {
        return view('dashboard.jobs.edit', [
            'job' => $job->load('categories', 'locations', 'languages', 'attributeValues.attribute'),
            'categories' => Category::all(),
            'locations' => Location::all(),
            'languages' => Language::all(),
            'attributes' => Attribute::all()
        ]);
    }

    public function update(Request $request, Job $job)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required',
            'company_name' => 'required|string',
            'salary_min' => 'required|numeric',
            'salary_max' => 'required|numeric',
            'job_type' => 'required|in:full-time,part-time,contract',
            'is_remote' => 'nullable|boolean',
            'category_ids' => 'required|array',
            'location_ids' => 'array',
            'language_ids' => 'required|array',
            'status' => 'required|in:draft,published,archived',
        ]);
    
        $job->update([
            'title' => $request->title,
            'description' => $request->description,
            'company_name' => $request->company_name,
            'salary_min' => $request->salary_min,
            'salary_max' => $request->salary_max,
            'job_type' => $request->job_type,
            'is_remote' => $request->has('is_remote') ? 1 : 0,
            'status' => $request->status,
            'published_at' => $request->status=="published" ? now() : null,
        ]);
    
        $job->categories()->sync($request->category_ids);
        $job->locations()->sync($request->location_ids);
        $job->languages()->sync($request->language_ids);
    
        foreach ($request->all() as $key => $value) {
            if (str_starts_with($key, 'attr_') && $value !== null) {
                $attributeId = str_replace('attr_', '', $key);
                $job->attributeValues()->updateOrCreate(
                    ['attribute_id' => $attributeId],
                    ['value' => $value]
                );
            }
        }
    
        return redirect()->route('dashboard.jobs.index')->with('success', 'Job updated successfully!');
    }
    

    public function destroy(Job $job)
    {
        $job->delete();
        return redirect()->route('dashboard.jobs.index')->with('success', 'Job deleted successfully!');
    }
}