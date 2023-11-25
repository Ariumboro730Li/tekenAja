<?php

namespace App\Helpers;

trait IsItemProjectTrait
{
    protected function isItemProject($item, $column){
        if (is_array($item)) {
            $result = $item[$column] ?? null;
        } elseif (is_object($item)) {
            $result = $item->$column ?? null;
        } else {
            $result = null; // Handle other cases if needed
        }

        return $result;
    }

}
