<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class ArticleFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];
    protected $camel_cased_methods = false;

    public function category($category)
    {
        return $this->where('category_id', $category);
    }

    public function name($name)
    {
        return $this->where('name', 'LIKE', "%$name%");
    }

    public function slug($slug)
    {
        return $this->where('slug', 'LIKE', "%$slug%");
    }

    public function is_active($is_active)
    {
        return $this->where('is_active', $is_active);
    }

    public function setup()
    {
        return $this
            ->orderBy('category_id')
            ->orderBy('number_in_category')
            ->with('category');
    }
}
