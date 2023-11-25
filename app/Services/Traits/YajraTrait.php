<?php

namespace App\Services\Traits;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

trait YajraTrait
{

    /**
     * Convert the query result to Yajra JSON format.
     *
     * @return void
     */
    public function toYajra(): void
    {
        $yajra = DataTables::of($this->builder)
        ->addIndexColumn();

        $this->yajra = $yajra;
        $this->result = $yajra->toJson()->original;
    }

}
