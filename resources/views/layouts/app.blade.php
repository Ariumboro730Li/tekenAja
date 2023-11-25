<!doctype html>
<html lang="en">

<head>
    <title>Teken AJA</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.css' integrity='sha512-KOWhIs2d8WrPgR4lTaFgxI35LLOp5PRki/DxQvb7mlP29YZ5iJ5v8tiLWF7JLk5nDBlgPP1gHzw96cZ77oD7zQ==' crossorigin='anonymous'/>
</head>

<body>
  <header>
    <!-- place navbar here -->
    <nav class="navbar navbar-expand-sm navbar-dark" style="background-color:navy;">
        <a class="navbar-brand mx-3" href="{{route('home')}}">Teken<b class="text-danger">Aja</b></a>
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId"
            aria-expanded="false" aria-label="Toggle navigation"></button>
        <div class="collapse navbar-collapse" id="collapsibleNavId">
            <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                @guest
                @else
                    <li class="nav-item">
                        <a class="nav-link
                            @if (Route::currentRouteName() == 'book.page')
                                active
                            @endif
                        " href="{{route('book.page')}}" aria-current="page">Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link
                            @if (Route::currentRouteName() == 'author.page')
                                active
                            @endif
                        " href="{{route('author.page')}}" aria-current="page">Authors</a>
                    </li>
                    @if (Auth::user()->role_id == 1)
                        <li class="nav-item">
                            <a class="nav-link
                            @if (Route::currentRouteName() == 'user.page')
                                active
                            @endif
                            " href="{{route('user.page')}}">Users</a>
                        </li>
                    @endif
                @endguest
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    @guest
                        <a class="btn-sm btn btn-outline-light" href="{{route('login')}}">Login</a>
                    @else
                        <div class="btn-group dropstart">
                            <button type="button" class="btn btn-sm btn-outline-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                {{Auth::user()->name}}
                            </button>
                            <ul class="dropdown-menu">
                                <a class="dropdown-item" href="{{route('logout')}}">Logout</a>
                            </ul>
                        </div>
                    @endguest
                </li>
            </ul>
        </div>
    </nav>
  </header>
  <main class="mt-5">
    @if (Session::has('message'))
        <div class="container mb-3">
            <div class="alert alert-success" role="alert">
                {{ Session::get('message') }}
            </div>
        </div>
    @endif
    @yield('content')
  </main>
  <footer>
    <!-- place footer here -->
  </footer>

  <script>
    let containerTables = {};
    let configView = {
        url: "{{ url('/') }}",
    };
    </script>
    <script>
        const swalSuccess = (message = "Sucess Hoorayy !!!") => {
            swal({
                text: message,
                icon: 'success'
            })
        };

        const swalError = (message = "Something Wrong !!!") => {
            swal({
                text: message,
                icon: 'error'
            })
        };

        const swalWarning = (message = "Please check something !!!") => {
            swal({
                text: message,
                icon: 'warning'
            })
        };

        const swalErrorValidation = (resp) => {
            let messageErr = '';
            Object.keys(resp.responseJSON.data).forEach(element => {
                messageErr += resp.responseJSON.data[element][0].toString() + '\n';
            });
            swalError(messageErr);
        };
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js'></script>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js'></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="{{asset('assets/js/dataTable.min.js')}}"></script>
    <script src="{{asset('assets/js/submitParameters.min.js')}}"></script>
    @stack('script-component')
    @yield('script')
</body>

</html>
