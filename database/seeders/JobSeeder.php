<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\Language;
use App\Models\Location;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\JobAttributeValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        $languages = Language::all();
        $locations = Location::all();
        $categories = Category::all();
        $attributes = Attribute::all();

        for ($i = 1; $i <= 100; $i++) {
            $job = Job::create([
                'title' => "Job $i",
                'description' => "This is job number $i",
                'company_name' => "Company $i",
                'salary_min' => rand(2000, 5000),
                'salary_max' => rand(6000, 10000),
                'is_remote' => rand(0, 1),
                'job_type' => collect(['full-time', 'part-time', 'contract', 'freelance'])->random(),
                'status' => collect(['draft', 'published', 'archived'])->random(),
                'published_at' => now()->subDays(rand(0, 30)),
            ]);

            $job->languages()->attach($languages->random(rand(1, 3)));
            $job->locations()->attach($locations->random(rand(1, 2)));
            $job->categories()->attach($categories->random(rand(1, 2)));

            foreach ($attributes as $attr) {
                $value = match ($attr->type) {
                    'number' => rand(1, 10),
                    'boolean' => rand(0, 1),
                    'select' => collect($attr->options)->random(),
                    default => 'N/A'
                };

                JobAttributeValue::create([
                    'job_id' => $job->id,
                    'attribute_id' => $attr->id,
                    'value' => $value
                ]);
            }
        }
    }
}