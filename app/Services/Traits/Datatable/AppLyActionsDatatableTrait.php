<?php

namespace App\Services\Traits\Datatable;

trait AppLyActionsDatatableTrait {

    use GraphColumnTrait, TableColumnTrait, ApplyDataGroupTrait, ToYajraTableColumnTrait;

    /**
     * Apply the actions based on the request type.
     *
     * @return instance
     */
    public function applyActions(): object
    {
        $type = $this->request->input('type');
        if ($type == "graph_column") {
            $this->applyGraphColumnAction();
        } else if($type == "table_column"){
            $this->applyTableColumnAction();
        } else {
            $this->toYajra();
        }

        return $this;
    }
}
