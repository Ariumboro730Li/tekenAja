<?php

namespace App\Services\Traits\Datatable;

use Illuminate\Support\Facades\DB;

trait ApplyDataGroupTrait
{
    /**
     * Apply the data grouping.
     *
     * @return void
     */
    public function applyDataGroup(): void
    {
        $column =  $this->dataSearchReplace($this->request->column);
        if ($this->builder instanceof \Illuminate\Support\Collection) {
            $this->builder = $this->applyDataGroupCollection($column);
        } else {
            $query2 = DB::table(function ($query) use($column) {
                $query->select(
                    $column, DB::raw("count(*) as total")
                )
                ->from($this->builder)
                ->groupBy($column);
            }, 'query');
            $this->builder = $query2;
        }
    }

    public function applyDataGroupCollection($column): \Illuminate\Support\Collection
    {
        // $collection is an instance of Laravel's Collection
        $columnValues = $this->builder->pluck($column);
        $groupedValues = $columnValues->groupBy(function ($value) {
            return $value;
        });
        foreach ($groupedValues as $key => $value) {
            $totals[] = [
                $column => $value->first(),
                "total" => $value->count(),
            ];
        }
        return collect($totals);
    }

}
