@extends('layouts.app')
@section('content')
    <div class="container">
        <a name="" id="" class="btn btn-sm btn-warning shadow col-12" href="javascript:void(0)" onclick="addNewBook()" role="button" >Add New Book</a>

        <!-- Modal -->
        <div class="modal fade" id="bookModal" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="bookForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="bookModalLabel"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="bookModalBody">
                        </div>
                        <div class="modal-footer" id="bookModalFooter">
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="mt-4 shadow">
            @php
                $data_row_view = ['title', 'author', 'updated_by', 'action'];
                $data_row_original = ['book_name', 'author_name', 'updated_by', 'action'];
                $name_db = 'Books';
            @endphp
            @component('components.tables.datatable', [
                'named_as' => $name_db,
                'url' => '/books/datatable',
                'row_view' => $data_row_view,
                'row_original' => $data_row_original,
                'row_selected' => $data_row_original,
                'container' => 'datatable_books',
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
        submitTable('datatable_books');

        function dataModal(){
            $(`#bookModalBody`).attr(`hidden`, true);
            $(`#bookModalBody`).html(`
                <input type="hidden" name="id" value="100" id="bookId">
                <div class="mb-3">
                    <label for="book_name" class="form-label">Book Name</label>
                    <input type="text" class="form-control" id="book_name" name="book_name" placeholder="Book Name" autocomplete="off">
                </div>
                <div class="mb-3">
                    <label for="author_name" class="form-label">Author Name</label>
                    <select class="form-select" name="author_id" id="authorList">
                    </select>
                </div>
            `);
        }

        function addNewBook(){
            $(`#bookModal`).modal('show');
            $(`#bookModalLabel`).text('Add New Book');
            dataModal();
            fetchAuthorList();
            $(`#bookModalBody`).attr(`hidden`, false);
            $(`#bookForm`).on('submit', function(e){
                e.preventDefault();
                submitBookForm();
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

        function submitBookForm(type = 'post'){
            let data = $('#bookForm').serialize();
            $.ajax({
                url: "{{url('books')}}",
                type: type,
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res){
                    swalSuccess(res.message);
                    $(`#bookModal`).modal('hide');
                    $(`#bookForm`).off('submit');
                    $(`#bookForm`).trigger('reset');
                    $(`#datatable_books`).DataTable().ajax.reload();
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

        function submitDeleteBook(id){
            $.ajax({
                url: `{{url('books')}}/${id}`,
                type: 'delete',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res){
                    swalSuccess(res.message);
                    $(`#bookModal`).modal('hide');
                    $(`#bookForm`).off('submit');
                    $(`#bookForm`).trigger('reset');
                    $(`#datatable_books`).DataTable().ajax.reload();
                },
                error: function(err){
                    swalError('Something Wrong');
                }
            })
        }

        function fetchById(id){
            let dataBook = [];
            let url =  `{{ route('book.fetch-by-id', ['id' => ':id']) }}`.replace(':id', id);
            console.log(url);
            $.ajax({
                url: url,
                type: "get",
                async: false,
                success: function(res){
                    dataBook = res.data;
                },
                error: function(err){
                    swalError('Something Wrong');
                }
            })
            return dataBook;
        }

        function editBook(id){
            $(`#bookModal`).modal('show');
            $(`#bookModalLabel`).text('Edit Book');
            dataModal();
            let dataBook = fetchById(id);
            $(`#bookId`).val(dataBook.id);
            $(`#book_name`).val(dataBook.book_name);
            fetchAuthorList(dataBook.author_id);
            $(`#bookModalBody`).attr(`hidden`, false);
            $(`#bookForm`).on('submit', function(e){
                e.preventDefault();
                submitBookForm('PUT');
            })
        }

        function deleteBook(id){
            $(`#bookModal`).modal('show');
            $(`#bookModalLabel`).text('Delete Book');
            let dataBook = fetchById(id);
            $(`#bookModalBody`).html(`
                <h5>Are you sure want to delete this book ?</h5>
                <ul>
                    <li>Title : ${dataBook.book_name}</li>
                    <li>Author : ${dataBook.author_name}</li>
                <ul>
            `);
            $(`#bookModalBody`).attr(`hidden`, false);
            $(`#bookForm`).on('submit', function(e){
                console.log('submit')
                submitDeleteBook(id);
                e.preventDefault();
            })
        }

    </script>
@endsection
