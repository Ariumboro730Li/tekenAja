<?php

namespace App\Services\Traits\Datatable;

trait ToYajraTableColumnTrait
{
    /**
     * Convert the query result to Yajra JSON format.
     * ? if $this->request->type = table_column
     * @return $this
     */
    public function toYajraTableColumn(): void
    {
        $this->toYajra();
    }

}
