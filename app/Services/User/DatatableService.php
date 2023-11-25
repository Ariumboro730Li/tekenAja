<?php

namespace App\Services\User;

use App\Helpers\ExecTimeHelpers;
use App\Helpers\IsItemProjectTrait;
use App\Models\User;
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
            $query->selectRaw('users.id as id,
                users.name as name,
                user_roles.role_name as role_name,
                users.role_id as role_id,
                users.email as email,
                users.email_verified_at as email_verified_at,
                users.updated_at as updated_at,
                CASE
                    WHEN users.deleted_at is null THEN "Active"
                    ELSE "Disabled"
                END AS is_active
                '
            )
            ->from('users')
            ->leftjoin('user_roles', 'users.role_id', '=', 'user_roles.id')
            ->orderBy('users.id', 'desc')
            ->get();
        });
        $this->builder = $data;
        return $this;
    }

    public function editColumnsYajra(){
        if (isset($this->yajra) && $this->request->column == null) {
            $this->yajraAction();
            $this->yajraRole();
            $this->yajraIsActive();
            $this->yajra->rawColumns($this->raw_columns);
            $this->result = $this->yajra->toJson()->original;
        }
        return $this;
    }

    public function yajraRole(){
        $this->yajra->editColumn('role_name', function ($item) {
            $roleId = $this->isItemProject($item, 'role_id');
            $class = ($roleId == 1) ? 'primary' : 'outline-primary';
            $html = '<span class="btn btn-sm btn-'.$class.'">'.$item->role_name.'</span>';
            return $html;
        });
        $this->raw_columns[] = "role_name";
    }

    protected function yajraIsActive(){
        $this->yajra->editColumn('is_active', function ($item) {
            $isActive = $this->isItemProject($item, 'is_active');
            $class = ($isActive == "Active") ? 'success' : 'danger';
            $html = '<span class="btn btn-sm btn-outline-'.$class.'">'.$isActive.'</span>';
            return $html;
        });
        $this->raw_columns[] = "is_active";
    }

    protected function yajraAction(){
        $this->yajra->editColumn('action', function ($item) {
            $id = $this->isItemProject($item, 'id');
            $isActive = $this->isItemProject($item, 'is_active');
            $action = '<a href="javascript:void(0)" onclick="editUser('.$id.')" class="btn btn-sm btn-primary">Edit</a>';
            if($isActive == "Active"){
                $action .= '&nbsp<a href="javascript:void(0)" onclick="deleteUser('.$id.')" class="btn btn-sm btn-danger">Disabled</a>';
            } else {
                $action .= '&nbsp<a href="javascript:void(0)" onclick="enableUser('.$id.')" class="btn btn-sm btn-warning">Activate</a>';
            }
            return $action;
        });
        $this->raw_columns[] = "action";
    }

}
