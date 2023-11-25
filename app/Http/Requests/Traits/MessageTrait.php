<?php

namespace App\Http\Requests\Traits;

trait MessageTrait {

    public function messages(){
        return [
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute folder must not exceed :max in size.',
            'mimes' => 'The :attribute must be a file of type: :values.',
            'string' => 'The :attribute must be a string.',
            'integer' => 'The :attribute must be an integer.',
            'array' => 'The :attribute must be an Array.',
            'unique' => 'The :attribute has already been taken.',
        ];
    }

}
