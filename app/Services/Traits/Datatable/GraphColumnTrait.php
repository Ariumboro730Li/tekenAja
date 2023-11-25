<?php

namespace App\Services\Traits\Datatable;

use App\Helpers\StringReplaceHelpers;

trait GraphColumnTrait {

    use StringReplaceHelpers, ApplyDataGroupTrait;

    /**
     * Apply the graph column action.
     *
     * @return void
     */
    public function applyGraphColumnAction(): void
    {
        $column = $this->request->input('column');
        if (!$column) {
            $this->handleColumnNotFound();
        } else {
            $this->generateGraphDataForColumn($column);
        }
    }

    /**
     * handle action where column input is not found.
     *
     * @return void
     */
    public function handleColumnNotFound(): void
    {
        $this->http_status = 400;
        $this->result["message"] = "No COLUMN Input";
    }

    /**
     * generate data graph where column is exist.
     *
     * @return void
     */
    public function generateGraphDataForColumn(string $column): void
    {
        $this->useDataGroupToGenerateGraphData(function ($data) use ($column) {
                $column =  $this->dataSearchReplace($column);
                try {
                    $data_col = $data->$column ?? $data[$column];
                } catch (\Throwable $th) {
                    $data_col = "Blank";
                }
                $return =[
                    'name' => $data_col,
                    'y' => $data->total ?? $data['total'],
                ];
                return $return;
        });
    }

    /**
     * fetch data group to array.
     *
     * @return void
     */
    public function useDataGroupToGenerateGraphData(callable $callback): void
    {
        $this->applyDataGroup();

        // dd($this->sqlBinding($this->builder));
        if ($this->builder instanceof \Illuminate\Support\Collection) {
            $this->result['data'] = $this->builder->take(100)->map($callback)->toArray();
        } else {
            $this->result['data'] = $this->builder->limit(100)->get()->map($callback)->toArray();
        }
    }


}
