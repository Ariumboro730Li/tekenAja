<?php

namespace App\Helpers;

trait SqlBindingHelper
{
    public function sqlBinding(object $data){
        $sql = $data->toSql();
        $bindings = $data->getBindings();
        foreach ($bindings as $binding) {
            $value = is_numeric($binding) ? $binding : "'" . $binding . "'";
            $sql = preg_replace('/\?/', $value, $sql, 1);
        }
        return $sql;
    }
}
