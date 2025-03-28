<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class JobFilterService
{
    protected Builder $query;
    protected ?string $filter;

    public function __construct(Builder $query, ?string $filter)
    {
        $this->query = $query;
        $this->filter = $filter;
    }

    protected function applyCondition(array $cond, string $boolean = 'and', $query = null)
    {
        $query = $query ?: $this->query;
    
        if ($cond['target'] === 'field') {
            $relationMap = [
                'location_id' => 'locations',
                'category_id' => 'categories',
                'language_id' => 'languages',
            ];
    
            if (array_key_exists($cond['key'], $relationMap)) {
                $relation = $relationMap[$cond['key']];
                if ($boolean === 'or') {
                    $query->orWhereHas($relation, function ($q) use ($cond) {
                        $table = $q->getModel()->getTable();
                        $q->where("{$table}.id", $cond['operator'], $cond['value']);
                    });
                } else {
                    $query->whereHas($relation, function ($q) use ($cond) {
                        $table = $q->getModel()->getTable();
                        $q->where("{$table}.id", $cond['operator'], $cond['value']);
                    });
                }
            } else {
                if ($boolean === 'or') {
                    $query->orWhere($cond['key'], $cond['operator'], $cond['value']);
                } else {
                    $query->where($cond['key'], $cond['operator'], $cond['value']);
                }
            }
        }
    
        if ($cond['target'] === 'attribute') {
            if ($boolean === 'or') {
                $query->orWhereHas('attributeValues', function ($q) use ($cond) {
                    $q->whereHas('attribute', function ($q2) use ($cond) {
                        $q2->where('name', $cond['key']);
                    })->where('value', $cond['operator'], $cond['value']);
                });
            } else {
                $query->whereHas('attributeValues', function ($q) use ($cond) {
                    $q->whereHas('attribute', function ($q2) use ($cond) {
                        $q2->where('name', $cond['key']);
                    })->where('value', $cond['operator'], $cond['value']);
                });
            }
        }
        
         
    }
    
    

    public function apply(): Builder
    {
        if (!$this->filter) {
            return $this->query;
        }

        $parsed = $this->parseFilter($this->filter);

        foreach ($parsed as $group) {
            if ($group['type'] === 'and') {
                foreach ($group['conditions'] as $cond) {
                    $this->applyCondition($cond, 'and');
                }
            } elseif ($group['type'] === 'or') {
                $this->query->where(function ($q) use ($group) {
                    foreach ($group['conditions'] as $cond) {
                        $this->applyCondition($cond, 'or', $q);
                    }
                });
            }
        }

        
        return $this->query;
    }

    protected function parseFilter(string $filter): array
    {
        $groups = explode(' AND ', $filter);
        $conditions = [];

        foreach ($groups as $group) {
            if (Str::contains($group, ' OR ')) {
                $subConditions = explode(' OR ', $group);
                $conditions[] = [
                    'type' => 'or',
                    'conditions' => array_map([$this, 'parseCondition'], $subConditions)
                ];
            } else {
                // check if multiple ANDs without OR in a single string
                $andConditions = explode('&&', $group); // optional support for && if needed
                if (count($andConditions) > 1) {
                    $conditions[] = [
                        'type' => 'and',
                        'conditions' => array_map([$this, 'parseCondition'], $andConditions)
                    ];
                } else {
                    $conditions[] = [
                        'type' => 'and',
                        'conditions' => [$this->parseCondition($group)]
                    ];
                }
            }
        }

        return $conditions;
    }

    protected function parseCondition(string $cond): array
    {
        $cond = trim($cond);
        $isAttribute = false;
    
        if (Str::startsWith($cond, 'attribute:')) {
            $isAttribute = true;
            $cond = Str::replaceFirst('attribute:', '', $cond);
        }
    
        preg_match('/(.+?)(=|!=|>=|<=|>|<)(.+)/', $cond, $matches);
    
        if (count($matches) !== 4) {
            return [
                'target' => 'field',
                'key' => '',
                'operator' => '=',
                'value' => '',
            ];
        }
    
        return [
            'target' => $isAttribute ? 'attribute' : 'field',
            'key' => trim($matches[1]),
            'operator' => trim($matches[2]),
            'value' => trim($matches[3]),
        ];
    }
    
}