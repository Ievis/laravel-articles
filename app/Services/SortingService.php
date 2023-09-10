<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use ReflectionFunction;

class SortingService
{
    private Model $model;
    private Collection $models;
    private string $sorting_attribute;
    private int $number;

    public function __construct(Model $model, string $sorting_attribute)
    {
        $this->model = $model;
        $this->sorting_attribute = $sorting_attribute;
    }

//    public function setParent(string $parent_class, string $foreign_id)
//    {
//        $this->parent = $parent_class::find($this->model->{$foreign_id});
//
//        return $this;
//    }
//
//    public function setRelation(string $relation)
//    {
//        $this->relation = $relation;
//
//        return $this;
//    }

    public function setModels(callable $callback)
    {
        $this->models = $callback($this->model);
        $this->sortModels();

        return $this;
    }

    public function sort()
    {
        $is_models_sortable = $this->model->is_active;

        $this->models = $this->models->map(function ($model) use ($is_models_sortable) {
            if ($is_models_sortable and $model->{$this->sorting_attribute} >= $this->number) {
                $model->{$this->sorting_attribute}++;
            }

            return collect($model)->except(['created_at', 'updated_at']);
        });
        $this->model->id = $this->model->id ?? 0;
        $this->models->push(collect($this->model)->except(['created_at', 'updated_at']));

        return $this->models
            ->sortBy($this->sorting_attribute)
            ->values();
    }

    public function getModels()
    {
        $models = $this->models->map(function ($model) {
            return collect($model)->except(['created_at', 'updated_at']);
        })->toArray();

        return collect($models);
    }

    public function setData(null|array $data)
    {
        $this->setNumber($data);
        foreach ($data as $field => $value) {
            $this->model->{$field} = $value;
        }

        return $this;
    }

    private function setNumber(array $data)
    {
        $this->number = $data[$this->sorting_attribute] ?? $this->model->{$this->sorting_attribute} ?? $this->models->count() + 1;
        $this->model->{$this->sorting_attribute} = $this->number;
    }

    private function sortModels()
    {
        $number = 1;
        $this->models = $this->models->sortBy($this->sorting_attribute)
            ->each(function ($model) use (&$number) {
                $model->{$this->sorting_attribute} = $number++;
            })->values();
    }
}
