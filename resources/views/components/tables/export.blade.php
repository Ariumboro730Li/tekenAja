<!-- Accordions Bordered -->
<div style="padding-left: 35px; padding-top: 10px; padding-right:35px">
    <div class="accordion custom-accordionwithicon custom-accordion-border accordion-border-box accordion-danger"
        id="accordionBordered_{{$container}}">
        <div class="accordion-item">
            <h2 class="accordion-header text-danger" id="accordionbordered_{{$container}}Example1">
                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                    data-bs-target="#accor_borderedExamplecollapse1_{{$container}}" aria-expanded="true"
                    aria-controls="accor_borderedExamplecollapse1_{{$container}}">
                    Click here to export data of
                     {{strtoupper($title_dt)}}
                    <i class="fa fa-book" aria-hidden="true"></i>
                </button>
            </h2>
            <div id="accor_borderedExamplecollapse1_{{$container}}" class="accordion-collapse collapse"
                aria-labelledby="accordionbordered_{{$container}}Example1" data-bs-parent="#accordionBordered_{{$container}}">
                <div class="accordion-body">
                    <div class="col-12">
                        <div class="card-header">
                            <form id="exportTable{{$container}}">
                                @csrf
                                <div class="row mb-3">
                                    <textarea id="exportParams{{$container}}" hidden></textarea>
                                    <div class="col-12" hidden>
                                        <p>Select Columns :</p>
                                        <input type="radio" name="select-column-export" id="select-column-export-{{$container}}" value="0" checked> all
                                        <input type="radio" name="select-column-export" id="select-column-export-{{$container}}" value="1"> selected
                                    </div>
                                    <select id="option-column-export{{$container}}" hidden multiple name="columns[]">
                                    </select>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-6">
                                                <p>File will be named as : (Rename if you like)</p>
                                                <div class="mb-3">
                                                    <input type="text" class="form-control" name="name"
                                                        id="exportName{{$container}}" aria-describedby="helpId"
                                                        value="{{ $named_as }}">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <p>Exported as :</p>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="file_type"
                                                        id="file_type_excel" checked value="xlsx">
                                                    <label class="form-check-label" for="inlineRadio1">Excel</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="file_type"
                                                        id="file_type_excel" value="csv">
                                                    <label class="form-check-label" for="inlineRadio2">CSV</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a href="javascript:void(0)" class="btn btn-danger btn-sm col-12" onclick="submitExport(`{{$container}}`, `{{$url_export}}`)">Export</a>
                            </form>
                            <div id="exportPort"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
