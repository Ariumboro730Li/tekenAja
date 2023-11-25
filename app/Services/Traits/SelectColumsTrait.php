<?php

namespace App\Services\Traits;

trait SelectColumsTrait
{
    public function selectColumns(){
        if($this->request["select-column-export"] == 0){
            $this->request->columns = ["*"];
        }else{
            $this->request->columns = $this->request->columns;
        }
    }
}
