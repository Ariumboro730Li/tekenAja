@extends('layouts.app')
@section('content')
<div class="container">
    <a name="" id="" class="btn btn-sm btn-warning shadow col-12" href="javascript:void(0)" onclick="addNewUser()" role="button" >Add New User</a>

    <!-- Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="userForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userModalLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="userModalBody">
                    </div>
                    <div class="modal-footer" id="userModalFooter">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="mt-4 shadow">
        @php
            $data_row_view = ['name', 'email', 'role_name', 'status', 'action'];
            $data_row_original = ['name', 'email', 'role_name', 'is_active', 'action'];
            $name_db = 'Users';
        @endphp
        @component('components.tables.datatable', [
            'named_as' => $name_db,
            'url' => '/users/datatable',
            'row_view' => $data_row_view,
            'row_original' => $data_row_original,
            'row_selected' => $data_row_original,
            'container' => 'datatable_users',
            'selected_column' => true,
            'ajax_type' => 'get',
            'graph_summary' => false,
            'length_menu_table' => [10, 25, 50],
            'is_find_data_column' => 1,
        ])
        @endcomponent
    </div>
</div>
@endsection
@section('script')
    <script>
        submitTable('datatable_users');

        function dataModal(){
            $(`#userModalBody`).attr(`hidden`, true);
            $(`#userModalBody`).html(`
                <input type="hidden" name="id" value="100" id="userId">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="User Name" autocomplete="off">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="User Email" autocomplete="off">
                </div>
                <div class="mb-3">
                    <label for="role_id" class="form-label">Role</label>
                    <select class="form-select" name="role_id" id="roleList">
                    </select>
                </div>
                <div class="mb-3" id="inputPassword">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="User Password" autocomplete="off">
                </div>
            `);
            $(`#inputPassword`).attr(`hidden`, false);
        }

        function addNewUser(){
            $(`#userModal`).modal('show');
            $(`#userModalLabel`).text('Add New User');
            dataModal();
            fetchRoleList();
            $(`#userModalBody`).attr(`hidden`, false);
            $(`#userForm`).on('submit', function(e){
                e.preventDefault();
                submitUserForm();
            })
        }

        function fetchRoleList(userId){
            $.ajax({
                url: "{{route('user.roles.list')}}",
                type: "get",
                success: function(res){
                    $(`#roleList`).html(`
                        <option disabled selected>Select User</option>
                    `);
                    Object.keys(res).forEach(element => {
                        let selected = (userId == res[element].id) ? 'selected' : '';
                        $(`#roleList`).append(`
                            <option value="${res[element].id}" ${selected}>${(res[element].role_name).toUpperCase()}</option>
                        `);
                    })
                },
                error: function(err){
                    swalError('Something Wrong');
                }
            })
        }

        function submitUserForm(type = 'post'){
            let data = $('#userForm').serialize();
            $.ajax({
                url: "{{url('users')}}",
                type: type,
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res){
                    swalSuccess(res.message);
                    modalClosedReloadTable();
                },
                error: function(resp){
                    let messageErr = '';
                    if (resp.responseJSON.message != "Validation errors") {
                        messageErr = resp.responseJSON.message;
                    } else {
                        Object.keys(resp.responseJSON.data).forEach(element => {
                            messageErr += resp.responseJSON.data[element][0].toString() + '\n';
                        });
                    }
                    swalError(messageErr);
                }
            })
        }

        function submitDeleteUser(id){
            $.ajax({
                url: `{{url('users')}}/${id}`,
                type: 'delete',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res){
                    swalSuccess(res.message);
                    modalClosedReloadTable();
                },
                error: function(err){
                    swalError('Something Wrong');
                }
            })
        }

        function submitActivateUser(id){
            $.ajax({
                url: `{{url('users/activate')}}/${id}`,
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res){
                    swalSuccess(res.message);
                    modalClosedReloadTable();
                },
                error: function(err){
                    swalError('Something Wrong');
                }
            })
        }

        function modalClosedReloadTable(){
            $(`#userModal`).modal('hide');
            $(`#userForm`).off('submit');
            $(`#userForm`).trigger('reset');
            $(`#datatable_users`).DataTable().ajax.reload();
        }

        function fetchById(id){
            let dataUser = [];
            let url =  `{{ route('user.fetch-by-id', ['id' => ':id']) }}`.replace(':id', id);
            console.log(url);
            $.ajax({
                url: url,
                type: "get",
                async: false,
                success: function(res){
                    dataUser = res.data;
                },
                error: function(err){
                    swalError('Something Wrong');
                }
            })
            return dataUser;
        }

        function editUser(id){
            $(`#userModal`).modal('show');
            $(`#userModalLabel`).text('Edit User');
            dataModal();
            $(`#inputPassword`).attr(`hidden`, true);
            let dataUser = fetchById(id);
            $(`#userId`).val(dataUser.id);
            $(`#name`).val(dataUser.name);
            $(`#email`).val(dataUser.email);
            fetchRoleList(dataUser.role_id);
            $(`#userModalBody`).attr(`hidden`, false);
            $(`#userForm`).on('submit', function(e){
                e.preventDefault();
                submitUserForm('PUT');
            })
        }

        function deleteUser(id){
            $(`#userModal`).modal('show');
            $(`#userModalLabel`).text('Disable User');
            let dataUser = fetchById(id);
            $(`#userModalBody`).html(`
                <h5 class="text-center">Are you sure want to disable <br>'${dataUser.name}'<br> as a user ?</h5>
            `);
            $(`#userModalBody`).attr(`hidden`, false);
            $(`#userForm`).on('submit', function(e){
                console.log('submit')
                submitDeleteUser(id);
                e.preventDefault();
            })
        }

        function enableUser(id){
            $(`#userModal`).modal('show');
            $(`#userModalLabel`).text('Disable User');
            let dataUser = fetchById(id);
            $(`#userModalBody`).html(`
                <h5 class="text-center">Are you sure want to activate <br>'${dataUser.name}'<br> as a user ?</h5>
            `);
            $(`#userModalBody`).attr(`hidden`, false);
            $(`#userForm`).on('submit', function(e){
                console.log('submit')
                submitActivateUser(id);
                e.preventDefault();
            })
        }

    </script>
@endsection
