<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attribute;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = [
            [
                'name' => 'years_experience',
                'type' => 'number',
                'options' => null
            ],
            [
                'name' => 'requires_degree',
                'type' => 'boolean',
                'options' => null
            ],
            [
                'name' => 'start_date',
                'type' => 'date',
                'options' => null
            ],
            [
                'name' => 'employment_level',
                'type' => 'select',
                'options' => ['Junior', 'Mid', 'Senior', 'Lead']
            ],
            [
                'name' => 'certification',
                'type' => 'text',
                'options' => null
            ]
        ];

        foreach ($attributes as $attr) {
            Attribute::firstOrCreate([
                'name' => $attr['name'],
            ], [
                'type' => $attr['type'],
                'options' => $attr['options'],
            ]);
        }
    }
}
