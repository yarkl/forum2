<?php
/**
 * Created by PhpStorm.
 * User: yar
 * Date: 09.03.18
 * Time: 20:10
 */

namespace App\Filters;


abstract class Filter
{
    protected $request;

    protected $builder;

    protected $filters = [];

    public function __construct(\Illuminate\Http\Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;
        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }
        return $this->builder;
    }
    /**
     * Fetch all relevant filters from the request.
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->request->intersect($this->filters);
    }

}