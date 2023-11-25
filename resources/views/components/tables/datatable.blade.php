@php
$container = $container ?? 'dataTable';
$row_selected = $row_selected ?? [];
$length_menu_table = $length_menu_table ?? [5, 10, 25, 50, 100];
$selected_column = $selected_column ?? true;
$all_column = $all_column ?? false;
$is_find_data_column = $is_find_data_column ?? 1;
$is_server_side = $is_server_side ?? 1;
$title_dt = $title_dt ?? 'Datatable';
$ajax_type = $ajax_type ?? 'POST';
$graph_summary = $grap_summary ?? true;
@endphp
{{-- @if ($graph_summary) --}}
<div class="modal fade" id="modalDataTable">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">{{trans('general.detail_column')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div id="graph-column-datatable"></div><br>
                    <hr><br>
                    <div class="table-responsive">
                        <table id="table-column-datatable"
                            class="table table-bordered nowrap table-striped align-middle mt-5" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    {{-- <th id="table-column-datatable-tbody-value">Value</th> --}}
                                    <th>Item</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @endif --}}
<div class="row">
<div class="col-lg-12">
    <div class="card text-start">
        <div class="row">
            <div class="col-12">
                <div class="card-header">
                    <h3 class="mb-0 text-center" id="titleDt{{$container}}">{{ $title_dt }}</h3>
                    <div class="col-12 mt-3"
                        @if (!$selected_column)
                            hidden
                        @endif
                    >
                            <form id="select-column-form-{{ $container }}">
                                <div class="mb-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio"
                                            name="radio-datatable-{{ $container }}"
                                            onchange="arrangeColumns{{ $container }}(`{{ $container }}`, 0)"
                                            value="0"
                                            @if (!$selected_column)
                                                checked
                                            @endif
                                            >
                                        <label class="form-check-label" for="radio-datatable-{{ $container }}">
                                            {{trans('general.all_column')}}
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio"
                                            name="radio-datatable-{{ $container }}"
                                            onchange="arrangeColumns{{ $container }}(`{{ $container }}`, 1)" value="1"
                                            @if ($selected_column)
                                                checked
                                            @endif
                                            >
                                        <label class="form-check-label" for="radio-datatable-{{ $container }}">
                                            {{trans('general.select_column_manually')}}
                                        </label>
                                    </div>
                                    <div id="select-column-datatable-{{ $container }}"
                                        class="mt-3">
                                        <select class="form-control select-column-2-{{$container}}" multiple>
                                            @foreach ($row_original as $key => $th)
                                                @if ($th != 'ID')
                                                    <option value="{{ $row_original[$key] }}">{{ ucwords(str_replace("_", " ", $row_view[$key])) }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <div class="mt-3">
                                            <button class="btn btn-success btn-sm"
                                                onclick="arrangeColumns{{ $container }}('{{ $container }}', 3)"
                                                type="button">  {{trans('general.submit_columns')}}</button>
                                            <button class="btn btn-danger btn-sm"
                                                id="reset-column-datatable-{{ $container }}"
                                                onclick="arrangeColumns{{ $container }}('{{ $container }}', 'x')"
                                                type="button"> {{trans('general.reset_columns')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                    </div>

                </div>
            </div>
            <!-- Accordion Flush Example -->
            @if (isset($url_export))
                @include("components.tables.export",
                [
                    'title_dt' => $title_dt
                ])
            @endif
        </div>
        <div class="card-body">
            <form id="findDataIn{{ $container }}">
                <div class="table-responsive" id="table-responsive-{{ $container }}"></div>
            </form>
        </div>
    </div>
</div>
</div>
@push('script-component')
@php
    $array_original = json_encode($row_original);
    $array_view = json_encode($row_view);
    $array_selected = json_encode($row_selected);
@endphp
<script>
    let columns_{{$container}} = @php echo $array_original @endphp;
    let columns_view_{{$container}} = @php echo $array_view @endphp;
    let columns_selected_{{$container}} = @php echo $array_selected @endphp;
    let selected_column{{$container}} = @php echo $selected_column ? 1 : 0 @endphp;

    $(".select-column-2-{{$container}}").select2({
        tags: true
    });

    $(".select-column-2-original-{{$container}}").on("select2:select", function(evt) {
        var element = evt.params.data.element;
        var $element = $(element);
        $element.detach();
        let id_col_original = $element[0].value;
        $(this).append(columns_view_{{$container}}[id_col_original]);
        $(this).trigger("change");
    });

    $(".select-column-2-{{$container}}").on("select2:select", function(evt) {
        var element = evt.params.data.element;
        var $element = $(element);
        $element.detach();
        $(this).append($element);
        $(this).trigger("change");
    });

    this.columnSelected_{{$container}} = columns_{{$container}};

    window[`setParamsTable{{$container}}`] = function(params) {
        this.paramsTable_{{$container}} = params;
        return this;
    };

    window[`arrangeColumns{{$container}}`] = function (container, value) {
        let column_local = localStorage.getItem(`{{ $container }}`);
        $(`input[id="select-column-export-${container}"][value="1"]`).prop('checked', true);
        if (value == 0) {
            $(`input[id="select-column-export-${container}"][value="0"]`).prop('checked', true);
            this.columnSelected_{{$container}} = columns_{{$container}};
            window[`arrangeTable{{$container}}`](container, value);
        } else if (value == 1) {
            this.columnSelected_{{$container}} = column_local ? column_local.split(",") : columns_selected_{{$container}};
            $(".select-column-2-{{$container}}").val(this.columnSelected_{{$container}}).trigger("change");
            window[`arrangeColumns{{$container}}`](container, 3);
        } else if (value == 3) {
            this.columnSelected_{{$container}} = $(".select-column-2-{{$container}}").val();
            window[`arrangeTable{{$container}}`](container, value);
            localStorage.setItem(`{{ $container }}`, this.columnSelected_{{$container}});
        } else {
            $(".select-column-2-{{$container}}").val(columns_selected_{{$container}}).trigger("change");
            localStorage.setItem(`{{ $container }}`, $(".select-column-2-{{$container}}").val());
            window[`arrangeColumns{{$container}}`](container, 3);
        }
    }

    // if (!selected_column{{$container}}) {
    //     $(`#reset-column-datatable-{{ $container }}`).trigger("click");
    // }
    // !! 0 = false
    // !! 1 = true
    // !! x = reset
    window[`arrangeTable{{$container}}`] = function (container, value) {
        let options = [];
        if (value  == "0") {
            col_export = ["*"];
        } else {
            col_export = this.columnSelected_{{$container}};
        }
        $(`#option-column-export${container}`).empty()
        col_export.forEach((element, key) => {
            if (element != "ID") {
               options.push(`<option value="${element}" selected>${element}</option>`)
            }
        })

        $(`#option-column-export${container}`).append(options.join(''))

        $(`#select-column-datatable-${container}`).attr("hidden", value == 0 ? true : false);

        let thead = [`<th style="min-width: 90px !important">ID</th>`];
        let theadSearch = [`<th>data_search_ID</th>`];

        this.columnSelected_{{$container}}.forEach((element, key) => {
            if (element != "ID") {
                let statusIndex = (columns_{{$container}}).indexOf(element);
                thead.push(`<th style="min-width: 90px !important">${columns_view_{{$container}}[statusIndex]}</th>`)
                theadSearch.push(`<th>data_search_${element}</th>`)
            }
        })
        let theads = ((thead.toString()).replaceAll(",", "")).replaceAll("_", " ");
        $(`#table-responsive-${container}`).html(
            `<table id="${container}" class="table table-bordered nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                            ${theads.toUpperCase()}
                        </tr>
                    </thead>
                    <thead class="search">
                        <tr>
                            ${(theadSearch.toString()).replaceAll(",", "")}
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>`
        );
        window[`table{{$container}}`](this.paramsTable_{{$container}}, this.columnSelected_{{$container}});
    }

    window[`setData{{$container}}`] = function() {
        this.url = `{{ url('') }}{{ $url ?? '/data-source/nossa-open/data-table' }}?`;
        return this;
    }

    window[`table{{$container}}`] = function(params, columns_table) {
        let container = `{{$container}}`;
        let set_data = window[`setData{{$container}}`]();
        this.container = new InitDatatable(`{{$container}}`);
        this.container.ajax_type = "{{$ajax_type}}";
        this.container.data_columns = window[`tableColumns{{$container}}`](columns_table);
        this.container.url = set_data.url;
        this.container.params = params;
        this.container.lengthMenuMainTable = @php echo json_encode($length_menu_table) @endphp;
        this.container.isFindDataColumn = @php echo $is_find_data_column @endphp;
        this.container.isServerSide = @php echo $is_server_side @endphp;
        this.container.columnSearch();
        this.container.createTable();
        Object.assign(containerTables, {"{{$container}}" : this.container});
        console.log(containerTables);
    };

    window[`autoLoadTable{{$container}}`] = function() {
        let table =  containerTables["{{$container}}"];
        table.autoLoad();
    }

    window[`tableColumns{{$container}}`] = function (dataColumns) {
        let arr_columns = [];
        arr_columns.push({
            data: 'DT_RowIndex',
            orderable: false,
            searchable: false
        });
        dataColumns.forEach(element => {
            if (element != "ID") {
                arr_columns.push({
                    data: element
                });
            }
        });
        return arr_columns;
    }
</script>
@endpush
