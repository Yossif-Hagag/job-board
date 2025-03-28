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
            $query->where($cond['key'], $cond['operator'], $cond['value'], $boolean);
        }

        if ($cond['target'] === 'attribute') {
            $query->whereHas('attributeValues', function ($q) use ($cond) {
                $q->whereHas('attribute', function ($q2) use ($cond) {
                    $q2->where('name', $cond['key']);
                })->where('value', $cond['operator'], $cond['value']);
            }, null, $boolean);
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
                $conditions[] = [
                    'type' => 'and',
                    'conditions' => [$this->parseCondition($group)]
                ];
            }
        }

        return $conditions;
    }

    protected function parseCondition(string $cond): array
    {
        $cond = trim($cond);

        if (Str::startsWith($cond, 'attribute:')) {
            $cond = Str::replaceFirst('attribute:', '', $cond);
            preg_match('/(.+?)(=|!=|>=|<=|>|<)(.+)/', $cond, $matches);

            return [
                'target' => 'attribute',
                'key' => trim($matches[1]),
                'operator' => trim($matches[2]),
                'value' => trim($matches[3]),
            ];
        }

        preg_match('/(.+?)(=|!=|>=|<=|>|<)(.+)/', $cond, $matches);

        return [
            'target' => 'field',
            'key' => trim($matches[1]),
            'operator' => trim($matches[2]),
            'value' => trim($matches[3]),
        ];
    }
}
