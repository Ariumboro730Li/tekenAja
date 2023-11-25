@extends('layouts.app')
@section('content')
    <div class="container">
        <a name="" id="" class="btn btn-sm btn-warning shadow col-12" href="javascript:void(0)" onclick="addNewAuthor()" role="button" >Add New Author</a>

        <!-- Modal -->
        <div class="modal fade" id="authorModal" tabindex="-1" aria-labelledby="authorModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="authorForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="authorModalLabel"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="authorModalBody">
                        </div>
                        <div class="modal-footer" id="authorModalFooter">
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="mt-4 shadow">
            @php
                $data_row_view = ['author', 'total_books', 'updated_by', 'action'];
                $data_row_original = ['author_name', 'total_books', 'updated_by', 'action'];
                $name_db = 'Authors';
            @endphp
            @component('components.tables.datatable', [
                'named_as' => $name_db,
                'url' => '/authors/datatable',
                'row_view' => $data_row_view,
                'row_original' => $data_row_original,
                'row_selected' => $data_row_original,
                'container' => 'datatable_authors',
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
        submitTable('datatable_authors');

        function dataModal(){
            $(`#authorModalBody`).attr(`hidden`, true);
            $(`#authorModalBody`).html(`
                <input type="hidden" name="id" value="100" id="authorId">
                <div class="mb-3">
                    <label for="author_name" class="form-label">Author Name</label>
                    <input type="text" class="form-control" id="author_name" name="name" placeholder="Author Name" autocomplete="off">
                </div>
            `);
        }

        function addNewAuthor(){
            $(`#authorModal`).modal('show');
            $(`#authorModalLabel`).text('Add New Author');
            dataModal();
            fetchAuthorList();
            $(`#authorModalBody`).attr(`hidden`, false);
            $(`#authorForm`).on('submit', function(e){
                e.preventDefault();
                submitAuthorForm();
            })
        }

        function fetchAuthorList(authorId){
            $.ajax({
                url: "{{route('author.list')}}",
                type: "get",
                success: function(res){
                    $(`#authorList`).html(`
                        <option disabled selected>Select Author</option>
                    `);
                    Object.keys(res).forEach(element => {
                        let selected = (authorId == res[element].id) ? 'selected' : '';
                        $(`#authorList`).append(`
                            <option value="${res[element].id}" ${selected}>${res[element].author_name}</option>
                        `);
                    })
                },
                error: function(err){
                    swalError('Something Wrong');
                }
            })
        }

        function submitAuthorForm(type = 'post'){
            let data = $('#authorForm').serialize();
            $.ajax({
                url: "{{url('authors')}}",
                type: type,
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res){
                    swalSuccess(res.message);
                    $(`#authorModal`).modal('hide');
                    $(`#authorForm`).off('submit');
                    $(`#authorForm`).trigger('reset');
                    $(`#datatable_authors`).DataTable().ajax.reload();
                },
                error: function(resp){
                    let messageErr = '';
                    console.log(resp.responseJSON.message);
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

        function submitDeleteAuthor(id){
            $.ajax({
                url: `{{url('authors')}}/${id}`,
                type: 'delete',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res){
                    swalSuccess(res.message);
                    $(`#authorModal`).modal('hide');
                    $(`#authorForm`).off('submit');
                    $(`#authorForm`).trigger('reset');
                    $(`#datatable_authors`).DataTable().ajax.reload();
                },
                error: function(resp){
                    swalError(resp.responseJSON.message);
                }
            })
        }

        function fetchById(id){
            let dataAuthor = [];
            let url =  `{{ route('author.fetch-by-id', ['id' => ':id']) }}`.replace(':id', id);
            $.ajax({
                url: url,
                type: "get",
                async: false,
                success: function(res){
                    dataAuthor = res.data;
                },
                error: function(err){
                    swalError('Something Wrong');
                }
            })
            return dataAuthor;
        }

        function editAuthor(id){
            $(`#authorModal`).modal('show');
            $(`#authorModalLabel`).text('Edit Author');
            dataModal();
            let dataAuthor = fetchById(id);
            $(`#authorId`).val(dataAuthor.id);
            $(`#author_name`).val(dataAuthor.author_name);
            fetchAuthorList(dataAuthor.author_id);
            $(`#authorModalBody`).attr(`hidden`, false);
            $(`#authorForm`).on('submit', function(e){
                e.preventDefault();
                submitAuthorForm('PUT');
            })
        }

        function deleteAuthor(id){
            $(`#authorModal`).modal('show');
            $(`#authorModalLabel`).text('Delete Author');
            let dataAuthor = fetchById(id);
            $(`#authorModalBody`).html(`
                <h5>Are you sure want to delete '${dataAuthor.author_name}' as Author ?</h5>
            `);
            $(`#authorModalBody`).attr(`hidden`, false);
            $(`#authorForm`).on('submit', function(e){
                submitDeleteAuthor(id);
                e.preventDefault();
            })
        }

    </script>
@endsection
