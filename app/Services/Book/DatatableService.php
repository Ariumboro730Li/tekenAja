<?php

namespace App\Services\Book;

use App\Helpers\ExecTimeHelpers;
use App\Helpers\IsItemProjectTrait;
use App\Services\Traits\Datatable\AppLyActionsDatatableTrait;
use App\Services\Traits\GetBuilder;
use App\Services\Traits\GetMessageTrait;
use App\Services\Traits\YajraTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DatatableService
{

    use AppLyActionsDatatableTrait, YajraTrait, GetMessageTrait, GetBuilder, IsItemProjectTrait;

    protected Request $request;

    public function __construct(Request $request)
    {
        $this->exec_time = ExecTimeHelpers::setTime();
        $this->request = $request;
    }

    public function applyQueryBuilder(): object
    {
        $data =  DB::table(function ($query) {
            $query->selectRaw('books.id as id, books.book_name as book_name, authors.author_name as author_name, users.name as updated_by')
            ->from('books')
            ->join('authors', 'books.author_id', '=', 'authors.id')
            ->leftjoin('users', 'books.user_id', '=', 'users.id')
            ->orderBy('books.id', 'desc');
        })->get();

        $this->builder = $data;
        return $this;
    }

    public function editColumnsYajra(){
        if (isset($this->yajra) && $this->request->column == null) {
            $this->yajraAction();
            $this->yajra->rawColumns($this->raw_columns);
            $this->result = $this->yajra->toJson()->original;
        }
        return $this;
    }

    protected function yajraAction(){
        $this->yajra->editColumn('action', function ($item) {
            $id = $this->isItemProject($item, 'id');
            $action = '<a href="javascript:void(0)" onclick="editBook('.$id.')" class="btn btn-sm btn-primary">Edit</a>';
            $action .= '&nbsp<a href="javascript:void(0)" onclick="deleteBook('.$id.')"  class="btn btn-sm btn-danger">Delete</a>';
            return $action;
        });
        $this->raw_columns[] = "action";
    }

}
