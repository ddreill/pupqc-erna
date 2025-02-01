<x-default-layout title="Create Gradesheet" pageTitle="Create Gradesheet">

    <x-slot:breadcrumbs>
        {{ Breadcrumbs::render('gradesheets.create') }}
    </x-slot:breadcrumbs>

    <x-slot:content>
        <div class="post" id="kt_post">

            <div id="kt_content_container" class="container-fluid">
                
                <form class="form" action="#" id="kt_class_form_create">

                    <div class="d-flex flex-column flex-xl-row ">
                        <div class="flex-column flex-lg-row-auto w-425px mb-10">

                            <div class="card card-flush py-4 mb-10">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Subject</h2>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="fv-row">
                                        <select class="form-select " data-placeholder="Select a subject"
                                            data-dropdown-parent="#kt_class_form_create" name="subject">
                                            <option></option>
                                        </select>
                                        <div class="text-muted fs-7 mt-2">Set the Subject</div>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-flush py-4 mb-10">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Section</h2>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="fv-row">
                                        <input type="text" class="form-control " name="section" />
                                    </div>
                                </div>
                            </div>

                            <div class="card card-flush py-4 mb-10">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Semester & School year</h2>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="fv-row mb-7">
                                        <select class="form-select " data-placeholder="Select a semester"
                                            data-dropdown-parent="#kt_class_form_create" name="semester">
                                            <option></option>
                                        </select>
                                    </div>

                                    <div class="fv-row">
                                        <label class="form-label">School year</label>
                                        <select class="form-select " data-placeholder="Select a year"
                                            data-dropdown-parent="#kt_class_form_create" name="school_year">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-flush py-4 mb-10">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Instructor</h2>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="fv-row">
                                        <select class="form-select " data-placeholder="Select an instructor"
                                            data-dropdown-parent="#kt_class_form_create" name="instructor">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-flush py-4 mb-10">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Room</h2>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="fv-row">
                                        <select class="form-select " data-placeholder="Select a room"
                                            data-dropdown-parent="#kt_class_form_create" name="room"
                                            data-allow-clear="true">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex-lg-row-fluid ms-10">
                            <div class="card card-flush py-4 mb-10" id="kt_class_timeslot">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Day & Time slots</h2>
                                    </div>
                                    <div class="card-toolbar">
                                        <div class="d-flex justify-content-end">

                                            <button type="button" kt-class-timeslot-component="createBtn"
                                                class="btn btn-success btn-sm"><i class="fa-solid fa-plus me-2"></i>Add
                                                Timeslot</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body py-0">
                                    <div class="scroll-y me-n7 pe-7" id="kt_modal_add_customer_scroll"
                                        data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}"
                                        data-kt-scroll-max-height="auto"
                                        data-kt-scroll-dependencies="#kt_class_timeslot"
                                        data-kt-scroll-wrappers="#kt_class_timeslot_scroll"
                                        data-kt-scroll-offset="100px">

                                        <div class="pt-5 pb-4">
                                            <table class="table table-row-dashed fs-6 gy-5 mb-0 w-100"
                                                kt-class-timeslot-component="table">
                                                <thead class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                                    <tr>
                                                        <th>Day</th>
                                                        <th>Start time</th>
                                                        <th>End time</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="align-middle">
                                                    <tr kt-class-timeslot-component="formTemplate"
                                                        style="display:none !important">
                                                        <td>
                                                            <div class="fv-row">
                                                                <select class="form-select " data-hide-search="true"
                                                                    data-placeholder="Select a day"
                                                                    data-dropdown-parent="#kt_class_form_create"
                                                                    data-name="timeslot.day">
                                                                    <option></option>
                                                                    @php
                                                                        $day_list = get_day_list();
                                                                    @endphp
                                                                    @for ($i = 0; $i < sizeOf($day_list); $i++)
                                                                        <option value="{{ $day_list[$i]['value'] }}">
                                                                            {{ $day_list[$i]['text'] }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>

                                                        </td>
                                                        <td>
                                                            <div class="fv-row">
                                                                <input class="form-control " placeholder="Pick a time"
                                                                    data-name="timeslot.time_start" />
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="fv-row">
                                                                <input class="form-control " placeholder="Pick a time"
                                                                    data-name="timeslot.time_end" />
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex justify-content-start flex-shrink-0">

                                                                <button type="button"
                                                                    kt-class-timeslot-component="clearTimeBtn"
                                                                    class="btn btn-icon btn-warning btn-sm me-1"
                                                                    data-bs-toggle="tooltip" data-bs-placement="right"
                                                                    data-bs-custom-class="tooltip-inverse"
                                                                    title="Clear time">
                                                                    <i class="fa-solid fa-delete-left fs-6"></i>
                                                                </button>

                                                                <button type="button"
                                                                    kt-class-timeslot-component="removeBtn"
                                                                    class="btn btn-icon btn-light-danger btn-sm"
                                                                    data-bs-toggle="tooltip" data-bs-placement="right"
                                                                    data-bs-custom-class="tooltip-inverse"
                                                                    title="Remove">
                                                                    <i class="fa-duotone fa-trash fs-6"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <tr kt-class-timeslot-component="empty">
                                                        <td colspan="4" class="text-center">
                                                            <p
                                                                class="fw-bold fs-6 py-5 bg-gray-200 rounded-2 mb-0 lh-2">
                                                                No
                                                                available time slot
                                                                <br><span class="text-muted fs-8 fw-normal">Click
                                                                    <b>Add
                                                                        Timeslot</b> to create a new Timeslot</span>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="card card-flush py-4 mb-10" id="kt_class_gradesheet_file">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2><i class="fa-duotone fa-memo-circle-info me-2"></i>Gradesheet File details
                                        </h2>
                                    </div>
                                </div>

                                <div class="card-body py-0">

                                    <div class="col-6 mb-7">
                                        <div class="fv-row">
                                            <label class="form-label required">File storage type</label>
                                            <div class="input-group input-group-solid flex-nowrap">
                                                <span class="input-group-text"><i
                                                        class="fa-duotone fa-hard-drive fs-4"></i></span>
                                                <div class="overflow-hidden flex-grow-1">
                                                    <select class="form-select  rounded-start-0"
                                                        data-control="select2"
                                                        data-placeholder="Select a storage drive"
                                                        data-hide-search="true" name="gradesheet_file[storage_type]">
                                                        <option></option>
                                                        <option value="LOCAL" selected>Local Drive</option>
                                                        <option value="ONLINE">Online (Google Drive, OneDrive, Dropbox,
                                                            etc.)
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="py-5 px-4 border-1 border rounded">
                                        <div class="row mb-5">
                                            <div class="col-7">
                                                <div class="fv-row">
                                                    <label class="form-label"
                                                        kt-class-gradesheet-file-component="filePathName">File
                                                        path</label>
                                                    <input class="form-control " name="gradesheet_file[file_path]" />
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <div class="fv-row">
                                                    <label class="form-label">File name</label>
                                                    <div class="input-group input-group-solid flex-nowrap">
                                                        <input class="form-control "
                                                            name="gradesheet_file[file_name]" />
                                                        <span class="input-group-text">.pdf</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-muted fs-7">Provide if the page(s) is merged in the same .pdf
                                            file.
                                        </div>
                                    </div>



                                    <div class="card card-flush py-4">
                                        <div class="card-header p-0">
                                            <div class="card-title">
                                                <h3>Pages</h3>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="scroll-y me-n7 pe-7" id="kt_modal_add_customer_scroll"
                                                data-kt-scroll="true"
                                                data-kt-scroll-activate="{default: false, lg: true}"
                                                data-kt-scroll-max-height="450px"
                                                data-kt-scroll-dependencies="#kt_class_gradesheet_file"
                                                data-kt-scroll-wrappers="#kt_class_gradesheet_file_scroll"
                                                data-kt-scroll-offset="350px">

                                                <table class="table table-row-dashed fs-6 gy-5 mb-0 w-100"
                                                    kt-class-gradesheet-file-component="table">
                                                    <thead
                                                        class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                                        <tr>
                                                            <th>Page no.</th>
                                                            <th class="required">Total rows</th>
                                                            <th kt-class-gradesheet-file-component="filePathName">File
                                                                path
                                                                <i class="bi bi-info-circle-fill ms-1 text-gray-600"
                                                                    data-bs-custom-class="tooltip-inverse"
                                                                    data-bs-toggle="tooltip"
                                                                    title="Provide if the page(s) is separated .pdf file"></i>
                                                            </th>
                                                            <th>File name <i
                                                                    class="bi bi-info-circle-fill ms-1 text-gray-600"
                                                                    data-bs-custom-class="tooltip-inverse"
                                                                    data-bs-toggle="tooltip"
                                                                    title="Provide if the page(s) is separated .pdf file"></i>
                                                            </th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="align-middle">
                                                        <tr>
                                                            <td class="text-center">
                                                                1
                                                            </td>
                                                            <td>
                                                                <div class="fv-row">
                                                                    <input class="form-control "
                                                                        name="gradesheet_file[pages][0][tot_rows]" />
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="fv-row">
                                                                    <input class="form-control "
                                                                        name="gradesheet_file[pages][0][file_path]" />
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="fv-row">
                                                                    <div
                                                                        class="input-group input-group-solid flex-nowrap">
                                                                        <input class="form-control "
                                                                            name="gradesheet_file[pages][0][file_name]" />
                                                                        <span class="input-group-text">.pdf</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div
                                                                    class="d-flex justify-content-start flex-shrink-0">

                                                                    <button type="button"
                                                                        kt-class-gradesheet-file-component="createBtn"
                                                                        class="btn btn-icon btn-success btn-sm me-1"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="right"
                                                                        data-bs-custom-class="tooltip-inverse"
                                                                        title="Add Page">
                                                                        <i
                                                                            class="fa-duotone fa-file-circle-plus fs-6"></i>
                                                                    </button>

                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <tr kt-class-gradesheet-file-component="formTemplate"
                                                            style="display:none !important">
                                                            <td class="text-center">
                                                                <span
                                                                    data-name="gradesheet_file[pages].no_display"></span>
                                                            </td>
                                                            <td>
                                                                <div class="fv-row">
                                                                    <input class="form-control "
                                                                        data-name="gradesheet_file[pages].tot_rows" />
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="fv-row">
                                                                    <input class="form-control "
                                                                        data-name="gradesheet_file[pages].file_path" />
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="fv-row">
                                                                    <div
                                                                        class="input-group input-group-solid flex-nowrap">
                                                                        <input class="form-control "
                                                                            data-name="gradesheet_file[pages].file_name" />
                                                                        <span class="input-group-text">.pdf</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div
                                                                    class="d-flex justify-content-start flex-shrink-0">

                                                                    <button type="button"
                                                                        kt-class-gradesheet-file-component="removeBtn"
                                                                        class="btn btn-icon btn-light-danger btn-sm me-1"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="right"
                                                                        data-bs-custom-class="tooltip-inverse"
                                                                        title="Remove">
                                                                        <i class="fa-solid fa-minus fs-6"></i>
                                                                    </button>

                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('gradesheets.index') }}" class="btn btn-light me-5">Cancel</a>
                                <button type="button" gradesheet-component="saveBtn"
                                    id="kt_class_gradesheet_file_component_saveViewBtn" class="btn btn-primary me-2">
                                    <span class="indicator-label">Save and Add Students</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <button type="button" gradesheet-component="saveBtn"
                                    id="kt_class_gradesheet_file_component_saveAddMoreBtn"
                                    class="btn btn-primary me-2">
                                    <span class="indicator-label">Save and Add more</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <button type="button" gradesheet-component="saveBtn"
                                    id="kt_class_gradesheet_file_component_saveBtn" class="btn btn-success">
                                    <span class="indicator-label"><i class="fas fa-save me-1"></i> Save</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </x-slot:content>

    <x-slot:scripts>
        <script type="text/javascript">
            KTUtil.onDOMContentLoaded((function() {

                // Begin::Global Configuration
                $('button[data-bs-toggle="tooltip"]').click(function(e) {
                    $(this).tooltip("hide");
                });

                let fv = init_formValidation("kt_class_form_create", {
                    'subject': {
                        validators: {
                            notEmpty: {
                                message: 'Subject is required'
                            },
                        },
                    },
                    'semester': {
                        validators: {
                            notEmpty: {
                                message: 'Semester is required'
                            },
                        },
                    },
                    'school_year': {
                        validators: {
                            notEmpty: {
                                message: 'School year is required'
                            },
                        },
                    },
                    'instructor': {
                        validators: {
                            notEmpty: {
                                message: 'Instructor is required'
                            },
                        },
                    },
                    'gradesheet_file[storage_type]': {
                        validators: {
                            notEmpty: {
                                message: 'Storage type is required'
                            },
                        },
                    },
                    'gradesheet_file[pages][0][tot_rows]': {
                        validators: {
                            notEmpty: {
                                message: 'Total rows is required'
                            },
                        },
                    },
                });
                // End::Global Configuration

                // Begin::Input Fields
                $("[name='subject']").select2({
                    minimumInputLength: 2,
                    ajax: {
                        url: "{{ url('/subject/select2') }}",
                        type: "POST",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                _token: "{{ csrf_token() }}",
                                term: params.term || '',
                                page: params.page || 1
                            };
                        },
                        processResults: function(data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results,
                                pagination: {
                                    more: (params.page * 10) < data.count_filtered
                                }
                            };
                        },
                        cache: true,
                    },
                    templateResult: function(item) {

                        if (!item.id) {
                            return item.text;
                        }

                        var span = document.createElement('span');
                        var template = '';

                        template += '<div class="d-flex align-items-center">';
                        template += '<div class="d-flex flex-column">'
                        template += '<span class="fw-bolder lh-1">' + item.subj_code + '</span>';
                        template += '<span class="fw-bold lh-2">' + item.subj_name + '</span>';
                        template += '<span class="text-muted">' + item.subj_units + ' units</span>';
                        template += '</div>';
                        template += '</div>';

                        span.innerHTML = template;

                        return $(span);
                    },
                    language: {
                        inputTooShort: function(args) {
                            // args.minimum is the minimum required length
                            // args.input is the user-typed text
                            return `{{ __('ajax.search_by', ['data' => 'Subject Code or Name']) }}`;
                        },
                    },
                });

                $("[name='semester']").select2({
                    ajax: {
                        url: "{{ url('/settings/semester/select2') }}",
                        type: "POST",
                        dataType: 'json',
                        data: function(params) {
                            return {
                                _token: "{{ csrf_token() }}",
                                term: params.term || '',
                                page: params.page || 1
                            };
                        },
                        processResults: function(data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results,
                                pagination: {
                                    more: (params.page * 10) < data.count_filtered
                                }
                            };
                        },
                        cache: true,
                    },
                    language: {
                        searching: function() {
                            return "{{ __('ajax.retrieving_data', ['data' => 'semester']) }}";
                        }
                    },
                });

                $("[name='school_year']").select2({
                    ajax: {
                        url: "{{ url('/settings/school-year/select2') }}",
                        type: "POST",
                        dataType: 'json',
                        data: function(params) {
                            return {
                                _token: "{{ csrf_token() }}",
                                term: params.term || '',
                                page: params.page || 1
                            };
                        },
                        processResults: function(data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results,
                                pagination: {
                                    more: (params.page * 10) < data.count_filtered
                                }
                            };
                        },
                        cache: true,
                    },
                    language: {
                        searching: function() {
                            return "{{ __('ajax.retrieving_data', ['data' => 'school year']) }}";
                        }
                    },
                });

                $("[name='instructor']").select2({
                    ajax: {
                        url: "{{ url('/instructor/select2') }}",
                        type: "POST",
                        dataType: 'json',
                        data: function(params) {
                            return {
                                _token: "{{ csrf_token() }}",
                                term: params.term || '',
                                page: params.page || 1
                            };
                        },
                        processResults: function(data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results,
                                pagination: {
                                    more: (params.page * 10) < data.count_filtered
                                }
                            };
                        },
                        cache: true,
                    },
                    templateResult: function(item) {

                        if (!item.id) {
                            return item.text;
                        }

                        var span = document.createElement('span');
                        var template = '';

                        template += '<div class="d-flex align-items-center">';
                        template += '<div class="d-flex flex-column">'
                        template += '<span class="lh-2">ENo.: <span class="fw-bolder">' + item.emp_no +
                            '</span></span>';
                        template += '<span class="fw-bold">' + item.fullname + '</span>';
                        template += '</div>';
                        template += '</div>';

                        span.innerHTML = template;

                        return $(span);
                    },
                    templateSelection: function(item) {

                        if (!item.fullname) {
                            return item.text;
                        }

                        return item.fullname;
                    },
                    language: {
                        searching: function() {
                            return "{{ __('ajax.retrieving_data', ['data' => 'instructors']) }}";
                        }
                    },
                });

                $("[name='room']").select2({
                    ajax: {
                        url: "{{ url('/room/select2') }}",
                        type: "POST",
                        dataType: 'json',
                        data: function(params) {
                            return {
                                _token: "{{ csrf_token() }}",
                                term: params.term || '',
                                page: params.page || 1
                            };
                        },
                        processResults: function(data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results,
                                pagination: {
                                    more: (params.page * 10) < data.count_filtered
                                }
                            };
                        },
                        cache: true,
                    },
                    language: {
                        searching: function() {
                            return "{{ __('ajax.retrieving_data', ['data' => 'rooms']) }}";
                        }
                    },
                });
                // End::Input Fields


                // Begin::Timeslot
                let timeslot_rowIndex = 0;

                $("#kt_class_timeslot [kt-class-timeslot-component='createBtn']").on("click", function() {

                    timeslot_rowIndex++;

                    const template = $("#kt_class_timeslot [kt-class-timeslot-component='formTemplate']");
                    const clone = template.clone(true);
                    clone.removeAttr("kt-class-timeslot-component");

                    clone.attr("style", "");
                    clone.attr("data-row-index", timeslot_rowIndex);

                    clone.insertBefore(template);

                    $(clone).find('[data-name="timeslot.day"]').attr("name", "timeslot[" +
                        timeslot_rowIndex + "][day]").select2({
                        allowClear: true,
                        minimumResultsForSearch: Infinity
                    });
                    $(clone).find('[data-name="timeslot.time_start"]').attr("name", "timeslot[" +
                        timeslot_rowIndex + "][time_start]").flatpickr({
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "G:i K",
                        defaultHour: 6,
                        defaultMinute: 0
                    });
                    $(clone).find('[data-name="timeslot.time_end"]').attr("name", "timeslot[" +
                        timeslot_rowIndex + "][time_end]").flatpickr({
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "G:i K",
                        defaultHour: 17,
                        defaultMinute: 0
                    });

                    $(clone).find('[data-bs-toggle="tooltip"]').tooltip();

                    const clearTimeBtn = clone.find('button[kt-class-timeslot-component="clearTimeBtn"]');
                    const removeBtn = clone.find('button[kt-class-timeslot-component="removeBtn"]');

                    clearTimeBtn.attr('data-row-index', timeslot_rowIndex);
                    removeBtn.attr('data-row-index', timeslot_rowIndex);

                    // Hide the empty message
                    $('#kt_class_timeslot [kt-class-timeslot-component="empty"]').attr("style",
                        "display: none !important");

                    $(clearTimeBtn).on('click', function(e) {

                        const index = $(this).attr('data-row-index');

                        $("#kt_class_timeslot [kt-class-timeslot-component='table'] tbody").find(
                                'input[name="timeslot[' + index +
                                '][time_start]"], input[name="timeslot[' + index + '][time_end]"]')
                            .val("");

                    });

                    $(removeBtn).on('click', function(e) {

                        const index = $(this).attr('data-row-index');
                        const tbody = $(
                            "#kt_class_timeslot [kt-class-timeslot-component='table'] tbody");

                        $(tbody).find('tr[data-row-index="' + index + '"]').remove();

                        const timeSlot_count = $(tbody).find('tr[data-row-index]').length;

                        if (!timeSlot_count) {
                            $('#kt_class_timeslot [kt-class-timeslot-component="empty"]').attr(
                                "style", "");
                        }
                    });

                });
                // End::Timeslot


                // Begin::Gradesheet file
                $('#kt_class_gradesheet_file [name="gradesheet_file[storage_type]"]').on("change", function() {

                    let storage_type = $(this).val();
                    if (storage_type == "LOCAL") {
                        $('#kt_class_gradesheet_file [kt-class-gradesheet-file-component="filePathName"]')
                            .empty().append(
                                `File path <i class="bi bi-info-circle-fill ms-1 text-gray-600" data-bs-custom-class="tooltip-inverse" data-bs-toggle="tooltip" title="Provide if the page(s) is separated .pdf file"></i>`
                            );
                    } else if (storage_type == "ONLINE") {
                        $('#kt_class_gradesheet_file [kt-class-gradesheet-file-component="filePathName"]')
                            .empty().append(
                                `File access link 
                        <i class="bi bi-info-circle-fill ms-1 text-gray-600" data-bs-custom-class="tooltip-inverse" data-bs-toggle="tooltip" title="Provide if the page(s) is separated .pdf file"></i>`
                            );
                    }

                    $('#kt_class_gradesheet_file [kt-class-gradesheet-file-component="filePathName"] i[data-bs-toggle="tooltip"]')
                        .tooltip();
                });

                let gradesheet_file_rowIndex = 0;

                const restartPageCounter = function() {

                    const tbody = $(
                        "#kt_class_gradesheet_file [kt-class-gradesheet-file-component='table'] tbody");
                    let pages = $(tbody).find("tr[data-row-index]");

                    if (pages.length >= 1) {

                        for (let i = 0; i < pages.length; i++) {

                            let rowIndex = $(pages[i]).attr("data-row-index");
                            $(tbody).find("tr[data-row-index='" + rowIndex +
                                "'] [data-name='gradesheet_file[pages].no_display']").text(i + 2);
                        }
                    }

                }
                // End::Gradesheet file

                $('#kt_class_gradesheet_file [kt-class-gradesheet-file-component="createBtn"]').on("click",
                    function() {

                        gradesheet_file_rowIndex++;

                        const template = $(
                            '#kt_class_gradesheet_file [kt-class-gradesheet-file-component="formTemplate"]');
                        const clone = template.clone(true);
                        clone.removeAttr("kt-class-gradesheet-file-component");

                        clone.attr("style", "");
                        clone.attr("data-row-index", gradesheet_file_rowIndex);

                        clone.insertBefore(template);

                        $(clone).find('[data-name="gradesheet_file[pages].tot_rows"]').attr("name",
                            "gradesheet_file[pages][" +
                            gradesheet_file_rowIndex + "][tot_rows]");
                        $(clone).find('[data-name="gradesheet_file[pages].file_path"]').attr("name",
                            "gradesheet_file[pages][" +
                            gradesheet_file_rowIndex + "][file_path]");
                        $(clone).find('[data-name="gradesheet_file[pages].file_name"]').attr("name",
                            "gradesheet_file[pages][" +
                            gradesheet_file_rowIndex + "][file_name]");

                        $(clone).find('[data-bs-toggle="tooltip"]').tooltip();
                        restartPageCounter();

                        const removeBtn = clone.find('button[kt-class-gradesheet-file-component="removeBtn"]');

                        removeBtn.attr('data-row-index', gradesheet_file_rowIndex);

                        $(removeBtn).on('click', function(e) {

                            const index = $(this).attr('data-row-index');
                            const tbody = $(
                                "#kt_class_gradesheet_file [kt-class-gradesheet-file-component='table'] tbody"
                            );

                            $(tbody).find('tr[data-row-index="' + index + '"]').remove();
                            restartPageCounter();

                        });

                    });

                // Begin::Save Button
                $('[gradesheet-component="saveBtn"]').on("click", function() {

                    const add_submitBtnId = $(this).attr('id');

                    fv.validate().then(function(e) {

                        if ('Valid' == e) {

                            trigger_btnIndicator(add_submitBtnId, "loading");
                            axios({
                                method: "POST",
                                url: "{{ url('/gradesheet/store') }}",
                                data: $("#kt_class_form_create").serialize(),
                                timeout: "{{ $axios_timeout }}"
                            }).then(function(respond) {

                                trigger_btnIndicator(add_submitBtnId, "default");
                                if (respond.status == 200) {

                                    display_axios_success(respond.data.message);

                                    let window_location = "";

                                    switch (add_submitBtnId) {


                                        case "kt_class_gradesheet_file_component_saveViewBtn":
                                            window_location = (
                                                "{{ route('admin.gradesheet.show', ':gradesheet') }}"
                                            ).replace(':gradesheet', respond.data
                                                .id);
                                            break;

                                        case "kt_class_gradesheet_file_component_saveAddMoreBtn":
                                            window_location = (
                                                "{{ route('admin.gradesheet.create') }}"
                                            );
                                            break;

                                        case "kt_class_gradesheet_file_component_saveBtn":
                                            window_location = (
                                                "{{ route('gradesheets.index') }}"
                                            );
                                            break;
                                    }

                                    setInterval(() => {
                                        window.location = window_location;
                                    }, 1500);

                                } else {

                                    display_modal_error("{{ __('modal.error') }}");
                                }

                                // resetFormData();

                            }).catch(function(error) {

                                display_modal_error(error);
                            });
                        } else {
                            display_modal_error("{{ __('modal.error') }}");
                        }
                    });
                });
                // End::Save Button

            }));
        </script>
    </x-slot:scripts>

</x-default-layout>
