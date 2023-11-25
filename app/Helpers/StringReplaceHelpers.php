<?php

namespace App\Helpers;

trait StringReplaceHelpers {

    /**
     * Replaces "data_search_" with an empty string in the given string.
     *
     * @param string $str The string to replace the text in.
     * @return string The modified string with "data_search_" removed.
     */

    private $data_search_prefix = 'data_search_';
    public function dataSearchReplace($str){
        return str_replace($this->data_search_prefix, "", $str);
    }

}
