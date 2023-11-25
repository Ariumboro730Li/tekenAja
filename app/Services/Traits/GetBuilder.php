<?php

namespace App\Services\Traits;

trait GetBuilder
{
    /**
     * fetch the query builder.
     *
     * @return $builder
     */
    public function getBuilder(): object
    {
        return $this->builder;
    }

    public function getResult(): object
    {
        return $this->result;
    }

    public function getSql(): string
    {
        return $this->builder_sql;
    }
}
