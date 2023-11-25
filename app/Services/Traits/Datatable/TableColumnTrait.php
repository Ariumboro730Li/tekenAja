<?php

namespace App\Services\Traits\Datatable;

use App\Helpers\SqlBindingHelper;

trait TableColumnTrait
{

    use SqlBindingHelper;

    /**
     * Apply the graph column action.
     *
     * @return void
     */
    private function applyTableColumnAction(): void
    {
        $column = $this->request->input('column');
        if (!$column) {
            $this->handleColumnNotFound();
        } else {
            $this->applyDataGroup();
            $this->toYajraTableColumn();
        }
    }

}
