<x-default-layout title="View Gradesheet" pageTitle="View Gradesheet">
    <x-slot:breadcrumbs>
        {{ Breadcrumbs::render('gradesheets.show', $class->class_id) }}
    </x-slot:breadcrumbs>

    <x-slot:content>
        <div class="post d-flex flex-column-fluid" id="kt_post">

            <div id="kt_content_container" class="container-fluid">

                <div class="d-flex flex-column flex-xl-row">

                    <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">

                        <div class="card mb-5 mb-xl-8">

                            <div class="card-body pt-15">
                                <div class="d-flex flex-center flex-column mb-5">
                                    <p class="mb-1 text-gray-800 fw-boldest fs-5">{{ $class->class_subj_code }}</p>
                                    <p class="fs-3 fw-bolder mb-1 text-center">
                                        {{ $class->class_subj_name }}</p>

                                    {!! $class->class_section
                                        ? '<span class="badge badge-light-dark fs-6 my-5">' . $class->class_section . '<span>'
                                        : '' !!}
                                </div>

                                <div class="d-flex flex-stack fs-4 py-3">
                                    <div class="fw-bolder rotate collapsible" data-bs-toggle="collapse"
                                        href="#kt_class_view_details" role="button" aria-expanded="false"
                                        aria-controls="kt_class_view_details">Details
                                        <span class="ms-2 rotate-180">
                                            <span class="svg-icon svg-icon-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                        fill="black" />
                                                </svg>
                                            </span>
                                        </span>
                                    </div>
                                    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                                        data-bs-trigger="hover" title="Edit gradesheet details">
                                        <a href="{{ route('admin.gradesheet.edit', $class->class_id) }}"
                                            class="btn btn-sm btn-light-warning">Edit</a>
                                    </span>
                                </div>

                                <div class="separator separator-dashed my-3"></div>
                                <div id="kt_class_view_details" class="collapse show">
                                    <div class="py-5 fs-6">

                                        <div class="fw-bolder mt-5">Semester & SY</div>
                                        <div class="text-gray-600">{{ $class->class_sem_sy }}</div>

                                        <div class="fw-bolder mt-5">Day & Time</div>
                                        <div class="text-gray-600">
                                            {{ $class->class_day_time ? $class->class_day_time : 'N/A' }}</div>

                                        <div class="fw-bolder mt-5">Room</div>
                                        <div class="text-gray-600">{{ $class->class_room ? $class->class_room : 'N/A' }}
                                        </div>

                                        <div class="fw-bolder mt-5">Instructor</div>
                                        <div class="text-gray-600">{{ $class->class_instructor }}</div>
                                    </div>
                                </div>

                                <div class="d-flex flex-stack fs-4 py-3">
                                    <div class="fw-bolder rotate collapsible" data-bs-toggle="collapse"
                                        href="#kt_class_view_pages" role="button" aria-expanded="false"
                                        aria-controls="kt_class_view_pages">Page & File details
                                        <span class="ms-2 rotate-180">
                                            <span class="svg-icon svg-icon-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                        fill="black" />
                                                </svg>
                                            </span>
                                        </span>
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-3"></div>
                                <div id="kt_class_view_pages"
                                    class="collapse {{ sizeOf($class->gradesheet_pages) >= 1 ? 'hide' : 'show' }}">
                                    <div class="py-5 fs-6">

                                        @if (sizeOf($class->gradesheet_pages) < 1)
                                            <div
                                                class="notice d-flex bg-light-danger rounded border-danger border mb-5 p-6 mt-3 text-center">
                                                <p class="mb-0"><span class="fw-bold">IMPORTANT</span>: Pages is
                                                    required, please edit this gradesheet</p>
                                            </div>
                                        @endif

                                        <div class="fw-bolder mt-3">File location</div>
                                        <div class="text-gray-600">{{ $class->class_file_storage_type }}</div>

                                        <div class="fw-bolder mt-5">Pages</div>
                                        <div class="text-gray-600">{{ sizeOf($class->gradesheet_pages) }} pages</div>

                                        @if ($class->class_file_storage_type == 'LOCAL')
                                            @if ($class->class_flink)
                                                <p class="ms-1 mb-0 fs-7 text-gray-600">File path: <i
                                                        class="fs-7 fw-normal">{{ $class->class_flink }}</i></p>
                                            @endif

                                            @if ($class->class_fname)
                                                <p class="ms-1 mb-0 fs-7 text-gray-600">File name: <span
                                                        class="fs-7 fw-normal">{{ $class->class_fname }}</span>
                                                </p>
                                            @endif

                                            <ul class="mt-3">
                                                @foreach ($class->gradesheet_pages as $page)
                                                    <li class="fw-bold fs-6 mb-2 text-gray-600">Page
                                                        {{ $page->grdsheetpg_pgNo }}
                                                        <div class="ms-1 badge badge-secondary">
                                                            {{ sizeOf($page->students) }}/<span
                                                                class="fw-bolder">{{ $page->grdsheetpg_rowNo }}</span>
                                                        </div>

                                                        @if ($page->grdsheetpg_flink)
                                                            <p class="ms-1 mb-0 fs-7 text-gray-600 fw-normal">File path:
                                                                <i
                                                                    class="fs-7 fw-normal text-gray-500">{{ $page->grdsheetpg_flink }}</i>
                                                            </p>
                                                        @endif

                                                        @if ($page->grdsheetpg_fname)
                                                            <p class="ms-1 mb-0 fs-7 text-gray-600 fw-normal">File name:
                                                                <span
                                                                    class="text-gray-500">{{ $page->grdsheetpg_fname }}</span>
                                                            </p>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif

                                        @if ($class->class_file_storage_type == 'ONLINE')
                                            @if ($class->class_flink)
                                                <div
                                                    class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-5 p-6 mt-3">
                                                    <div class="d-flex flex-stack flex-grow-1">
                                                        <div class="fw-bold">
                                                            <div class="fs-6 text-gray-700">Access the gradesheet file
                                                                online<br>
                                                                <a href="{{ $class->class_flink }}" class="me-1"
                                                                    target="_blank">Click here to view
                                                                    the file</a>

                                                                @if ($class->class_fname)
                                                                    <p class="mb-0 fs-7 text-gray-600 fw-normal">File
                                                                        name:
                                                                        <span
                                                                            class="text-gray-500">{{ $class->class_fname }}</span>
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <ul class="mt-3">
                                                @foreach ($class->gradesheet_pages as $page)
                                                    <li class="fw-bold fs-6 mb-2 text-gray-600">

                                                        @if ($page->grdsheetpg_flink)
                                                            <a href="{{ $page->grdsheetpg_flink }}"
                                                                target="_blank">Page
                                                                {{ $page->grdsheetpg_pgNo }}
                                                                <span class="ms-1 badge badge-secondary">
                                                                    {{ sizeOf($page->students) }}/<span
                                                                        class="fw-bolder">{{ $page->grdsheetpg_rowNo }}</span>
                                                                </span>
                                                            </a>
                                                        @else
                                                            <div>Page
                                                                {{ $page->grdsheetpg_pgNo }}
                                                                <span class="ms-1 badge badge-secondary">
                                                                    {{ sizeOf($page->students) }}/<span
                                                                        class="fw-bolder">{{ $page->grdsheetpg_rowNo }}</span>
                                                                </span>
                                                            </div>
                                                        @endif

                                                        @if ($page->grdsheetpg_fname)
                                                            <p class="ms-1 mb-0 fs-7 text-gray-600 fw-normal">File
                                                                name: <span
                                                                    class="text-gray-500">{{ $page->grdsheetpg_fname }}</span>
                                                            </p>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="flex-lg-row-fluid ms-lg-15">

                        <div class="card mb-5 mb-xl-8">
                            <div class="card-header border-0 pt-6">
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546"
                                                    height="2" rx="1"
                                                    transform="rotate(45 17.0365 15.1223)" fill="black" />
                                                <path
                                                    d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                    fill="black" />
                                            </svg>
                                        </span>
                                        <input type="text" data-kt-student-grades-table-filter="search"
                                            class="form-control  w-250px ps-15" placeholder="Search" />
                                    </div>
                                </div>
                                <div class="card-toolbar">
                                    <div class="d-flex justify-content-end">
                                        <div id="kt_student_grades_table_buttons" class="me-2">

                                        </div>
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_add_student_grade ">Add Student</button>
                                    </div>
                                </div>
                            </div>
                            <form class="form" action="#" id="kt_form_edit_student_grade">

                                <div class="card-body py-3">

                                    <div>
                                        <div id="edit_template_location" style="display:none !important">
                                            <div class="mb-3 fv-row">
                                                <select data-name="page_no" class="form-select "
                                                    data-placeholder="Page No." data-dropdown-css-class="w-200px"
                                                    data-hide-search="true"
                                                    data-dropdown-parent="#kt_form_edit_student_grade">
                                                    <option></option>
                                                </select>
                                            </div>

                                            <div class="fv-row">
                                                <select data-name="row_no" class="form-select "
                                                    data-placeholder="Row No." data-dropdown-css-class="w-100px"
                                                    data-hide-search="true"
                                                    data-dropdown-parent="#kt_form_edit_student_grade" disabled>
                                                    <option></option>
                                                </select>
                                            </div>

                                        </div>

                                        <div class="fv-row" id="edit_template_midterm_grade"
                                            style="display:none !important">
                                            <select class="grades form-select " data-placeholder="Grade"
                                                data-allow-clear="true"
                                                data-dropdown-parent="#kt_form_edit_student_grade"
                                                data-name="midterm_grade">
                                                <option></option>
                                                @foreach (get_grading_list() as $grade)
                                                    <option value="{{ $grade['value'] }}">
                                                        {{ $grade['value'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="fv-row" id="edit_template_final_grade"
                                            style="display:none !important">
                                            <select class="grades form-select " data-placeholder="Grade"
                                                data-allow-clear="true"
                                                data-dropdown-parent="#kt_form_edit_student_grade"
                                                data-name="final_grade">
                                                <option></option>
                                                @foreach (get_grading_list() as $grade)
                                                    <option value="{{ $grade['value'] }}">
                                                        {{ $grade['value'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="fv-row" id="edit_template_final_rating"
                                            style="display:none !important">
                                            <select class="grades form-select " data-placeholder="Rating"
                                                data-allow-clear="true"
                                                data-dropdown-parent="#kt_form_edit_student_grade"
                                                data-name="final_rating">
                                                <option></option>
                                                @foreach (get_final_rating_list() as $grade)
                                                    <option value="{{ $grade['value'] }}">
                                                        {{ $grade['value'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="fv-row" id="edit_template_grade_status"
                                            style="display:none !important">
                                            <select class="grades form-select " data-placeholder="Status"
                                                data-allow-clear="true"
                                                data-dropdown-parent="#kt_form_edit_student_grade"
                                                data-name="grade_status">
                                                <option></option>
                                                @foreach (get_grading_status_list() as $grade)
                                                    <option value="{{ $grade['value'] }}">
                                                        {{ $grade['value'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <table id="kt_student_grades_table" class="table table-row-bordered gy-5 gs-7">
                                        <thead>
                                            <tr class="fw-bolder fs-6 text-gray-800 px-7">
                                                <th>Location</th>
                                                <th>Student No.</th>
                                                <th>Name</th>
                                                <th>Course</th>
                                                <th>Midterm</th>
                                                <th>Final</th>
                                                <th>Final Rating</th>
                                                <th>Grade Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="kt_modal_add_student_grade">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <form class="form" action="#" id="kt_modal_add_student_grade_form">

                        <div class="modal-header" id="kt_modal_add_student_grade_header">
                            <h2 class="fw-bolder">Add Student</h2>
                            <div id="kt_modal_add_student_grade_close"
                                class="btn btn-icon btn-sm btn-active-icon-primary">
                                <span class="svg-icon svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                            rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                            transform="rotate(45 7.41422 6)" fill="black" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <div class="modal-body py-10 px-lg-17">

                            <div class="row mt-3 mb-10">
                                <div class="col-11">
                                    <div class="row">
                                        <div class="col-6 fv-row">
                                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                                <span class="required">Student</span>
                                            </label>
                                            <select id="kt_modal_add_student_grade_search_student"
                                                name="gradesheet_student" class="form-select  form-select-lg ps-5"
                                                data-placeholder="Search by Student number or full name"
                                                data-dropdown-parent="#kt_modal_add_student_grade">
                                                <option></option>
                                            </select>
                                        </div>

                                        <div class="col-3 fv-row">
                                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                                <span class="required">Page No.</span>
                                            </label>
                                            <select id="kt_modal_add_student_grade_gradesheet_pages"
                                                name="gradesheet_page" class="form-select  form-select-lg"
                                                data-placeholder="Select a Page No." data-dropdown-css-class="w-200px"
                                                data-hide-search="true"
                                                data-dropdown-parent="#kt_modal_add_student_grade_form">
                                                <option></option>
                                            </select>
                                            <input data-name="page.no" type="text" hidden />
                                        </div>

                                        <div class="col-3 fv-row">
                                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                                <span class="required">Row No.</span>
                                            </label>
                                            <select id="kt_modal_add_student_grade_gradesheet_page_rows"
                                                name="gradesheet_page_rows" class="form-select  form-select-lg"
                                                data-placeholder="Row No." data-dropdown-css-class="w-100px"
                                                data-hide-search="true"
                                                data-dropdown-parent="#kt_modal_add_student_grade_form">
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-1 my-auto text-center">
                                    <button id="kt_modal_add_student_grade_import_add" type="button"
                                        class="btn btn-icon btn-success btn-sm">
                                        <span class="indicator-label"><i class="fas fa-plus"></i></span>
                                        <span class="indicator-progress">
                                            <span class="spinner-border spinner-border-sm align-middle"></span></span>
                                    </button>
                                </div>
                            </div>

                            <div class="separator separator-content border-secondary my-10"><span
                                    class="w-250px fw-bolder">Added
                                    Student Profiles</span></div>

                            <div class="card-body pt-0" id="kt_modal_add_student_grade_imported_list">

                                <div id="kt_modal_add_student_grade_imported_template"
                                    class="row mt-7 border border-dashed border-bottom-1 border-top-0 border-left-0 border-right-0"
                                    style="display:none !important">

                                    <div class="col-3">
                                        <input data-name="student.id" type="text" hidden>
                                        <input data-name="student.page" type="text" hidden>
                                        <input data-name="student.row" type="text" hidden>

                                        <p class="mb-0 fs-6 fw-bold" data-name="student.no"></p>
                                        <p class="fs-6" data-name="student.name"></p>
                                        <p class="mb-2 fs-7">Page <span data-name="student.d_page"></span>, #<span
                                                data-name="student.d_row"></span></p>
                                    </div>

                                    <div class="col-2">
                                        <div class="fv-row">
                                            <select class="form-select " data-placeholder="Midterm"
                                                data-allow-clear="true"
                                                data-dropdown-parent="#kt_modal_add_student_grade_form"
                                                data-name="student.midterm_grade">
                                                <option></option>
                                                @foreach (get_grading_list() as $grade)
                                                    <option value="{{ $grade['value'] }}">
                                                        {{ $grade['value'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="fv-row">
                                            <select class="form-select " data-placeholder="Final"
                                                data-allow-clear="true"
                                                data-dropdown-parent="#kt_modal_add_student_grade_form"
                                                data-name="student.final_grade">
                                                <option></option>
                                                @foreach (get_grading_list() as $grade)
                                                    <option value="{{ $grade['value'] }}">
                                                        {{ $grade['value'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="fv-row">
                                            <select class="form-select " data-placeholder="Final Rating"
                                                data-allow-clear="true"
                                                data-dropdown-parent="#kt_modal_add_student_grade_form"
                                                data-name="student.final_rating">
                                                <option></option>
                                                @foreach (get_final_rating_list() as $grade)
                                                    <option value="{{ $grade['value'] }}">
                                                        {{ $grade['value'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="fv-row">
                                            <select class="form-select " data-placeholder="Status"
                                                data-allow-clear="true"
                                                data-dropdown-parent="#kt_modal_add_student_grade_form"
                                                data-name="student.grade_status">
                                                <option></option>
                                                @foreach (get_grading_status_list() as $grade)
                                                    <option value="{{ $grade['value'] }}">
                                                        {{ $grade['value'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-1 text-center">
                                        <button kt_modal_add_student_grade_imported_remove type="button"
                                            class="btn btn-icon btn-secondary btn-sm py-1"><i
                                                class="fas fa-minus"></i></button>
                                    </div>
                                </div>

                                <div id="kt_modal_add_student_grade_imported_empty" class="text-center">
                                    <p class="fw-bold fs-6 py-5 bg-gray-200 rounded-2 mb-0 lh-2">No
                                        added student
                                        <br><span class="text-muted fs-8 fw-normal">Fill-out the form above to add a
                                            new
                                            student</span>
                                    </p>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer flex-center">
                            <button type="reset" id="kt_modal_add_student_grade_cancel"
                                class="btn btn-light me-3">Discard</button>
                            <button type="button" id="kt_modal_add_student_grade_submit" class="btn btn-primary"
                                disabled>
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-slot:content>

    <x-slot:scripts>
        <script type="text/javascript">
            KTUtil.onDOMContentLoaded((function() {

                let table_fv = init_formValidation("kt_form_edit_student_grade", []);

                // Table
                var table = $("#kt_student_grades_table").DataTable({
                    processing: true,
                    serverSide: true,
                    dom: '<"row my-5"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end"p>>rt<"row my-5"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"li><"col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end"p>>',
                    ajax: {
                        url: "{{ route('admin.gradesheet.show', $class->class_id) }}",
                        error: function(xhr, error, code) {

                            console.log(error);

                            display_modal_error("{{ __('modal.error_datatable') }}");
                        }
                    },
                    columns: [{
                            data: 'location',
                            name: 'location',
                            searchable: false,
                            width: "5%"
                        },
                        {
                            data: 'stud_no',
                            name: 'stud_no',
                            width: "10%"

                        },
                        {
                            data: 'name',
                            name: 'name',
                            width: "30%"

                        },
                        {
                            data: 'course',
                            name: 's_course.cour_code',
                            searchable: false,
                            orderable: false
                        },
                        {
                            data: 'midterm_grade',
                            name: 'enrsub_midtermGrade',
                            searchable: false,
                            width: "7%"
                        },
                        {
                            data: 'final_grade',
                            name: 'enrsub_finalGrade',
                            searchable: false,
                            width: "7%"
                        },
                        {
                            data: 'final_rating',
                            name: 'enrsub_finalRating',
                            searchable: false,
                            width: "7%"
                        },
                        {
                            data: 'grade_status',
                            name: 'enrsub_grade_status',
                            searchable: false,
                            width: "7%"
                        },
                        {
                            data: 'action',
                            searchable: false,
                            orderable: false
                        },
                    ],
                    "rowCallback": function(row, data) {
                        $(row).addClass('align-middle');
                    }
                });

                $('[data-kt-student-grades-table-filter="search"]').on('keyup', function(e) { // Search bar 
                    table.search(e.target.value).draw();
                });

                $("#kt_student_grades_table").on("click", "[kt_student_grades_table_edit]", function() {

                    const row_dom = $(this).closest("tr");

                    $("#kt_student_grades_table [kt_student_grades_table_edit]").attr("disabled", true);
                    $("#kt_student_grades_table [kt_student_grades_table_delete]").attr("disabled",
                        true);

                    rowData = table.row($(this).closest("tr")).data();

                    const page_id = rowData['page_id'];
                    const row_no = rowData['row_no'];
                    const midterm_grade = rowData['midterm_grade'];
                    const final_grade = rowData['final_grade'];
                    const final_rating = rowData['final_rating'];
                    const grade_status = rowData['grade_status'];

                    const location_clone = $("#edit_template_location").clone(true);
                    const midterm_grade_clone = $("#edit_template_midterm_grade").clone(true);
                    const final_grade_clone = $("#edit_template_final_grade").clone(true);
                    const final_rating_clone = $("#edit_template_final_rating").clone(true);
                    const grade_status_clone = $("#edit_template_grade_status").clone(true);

                    location_clone.removeAttr("id").attr("style", "");
                    midterm_grade_clone.removeAttr("id").attr("style", "");
                    final_grade_clone.removeAttr("id").attr("style", "");
                    final_rating_clone.removeAttr("id").attr("style", "");
                    grade_status_clone.removeAttr("id").attr("style", "");

                    $(location_clone).find('[data-name="page_no"]').attr("name",
                        "grade[grdsheetpg_enrsub_id]");
                    $(location_clone).find('[data-name="row_no"]').attr("name",
                        "grade[enrsub_rowNo]");
                    $(midterm_grade_clone).find('[data-name="midterm_grade"]').attr("name",
                        "grade[enrsub_midtermGrade]");
                    $(final_grade_clone).find('[data-name="final_grade"]').attr("name",
                        "grade[enrsub_finalGrade]");
                    $(final_rating_clone).find('[data-name="final_rating"]').attr("name",
                        "grade[enrsub_finalRating]");
                    $(grade_status_clone).find('[data-name="grade_status"]').attr("name",
                        "grade[enrsub_grade_status]");


                    if (midterm_grade) {

                        $(midterm_grade_clone).find('[data-name="midterm_grade"]').append(
                            `<option value="${midterm_grade}" selected>${midterm_grade}</option>`);
                    }

                    if (final_grade) {

                        $(final_grade_clone).find('[data-name="final_grade"]').append(
                            `<option value="${final_grade}" selected>${final_grade}</option>`);
                    }

                    if (final_rating) {

                        $(final_rating_clone).find('[data-name="final_rating"]').append(
                            `<option value="${final_rating}" selected>${final_rating}</option>`);
                    }

                    if (grade_status) {

                        $(grade_status_clone).find('[data-name="grade_status"]').append(
                            `<option value="${grade_status}" selected>${grade_status}</option>`);
                    }

                    rowData['location'] = $(location_clone).prop('outerHTML');
                    rowData['midterm_grade'] = $(midterm_grade_clone).prop('outerHTML');
                    rowData['final_grade'] = $(final_grade_clone).prop('outerHTML');
                    rowData['final_rating'] = $(final_rating_clone).prop('outerHTML');
                    rowData['grade_status'] = $(grade_status_clone).prop('outerHTML');
                    rowData['action'] = `<div class="d-flex justify-content-start flex-shrink-0">
                    <button type="button" id="kt_student_grades_table_save" class="btn btn-success btn-sm me-1">
                        <span class="indicator-label"><i class="fas fa-save me-1"></i>Save </span>
                        <span class="indicator-progress">Saving...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>

                    <button type="button" kt_student_grades_table_cancel class="btn btn-icon btn-secondary btn-sm">
                    <i class="fas fa-ban"></i>
                    </button>
                </div>`;
                    table.row($(this).closest("tr")).data(rowData);

                    let selectPages = $(row_dom).find('[data-name="page_no"]').select2({
                        minimumResultsForSearch: Infinity,
                        ajax: {
                            url: "{{ route('admin.gradesheet.pages', $class->class_id) }}",
                            dataType: 'json',
                            delay: 250,
                            processResults: function(data) {

                                (data).forEach((e, i) => {
                                    data[i]["id"] = e.grdsheetpg_id;
                                    data[i]["disabled"] = (e.students).length >= e
                                        .grdsheetpg_rowNo;
                                });

                                return {
                                    "results": data,
                                    "pagination": {
                                        "more": false
                                    }
                                };
                            },
                            cache: true,
                        },
                        templateResult: function(item) {

                            if (!item.id) {
                                return item.text;
                            }

                            var span = document.createElement('div');
                            var template = '';

                            template += `<div class="d-flex justify-content-between">`;

                            template +=
                                `<div>Page ${item.grdsheetpg_pgNo} </div><div class="d-flex flex-stack gap-2">${(item.id == page_id) ? '<span class="badge badge-success py-1 px-2"><i class="fas fa-check"></i></span>' : ''}<span class="badge badge-secondary fw-bolder">${item['students'].length}/${item.grdsheetpg_rowNo}</span></div>`;

                            template += `</div>`;

                            span.innerHTML = template;

                            return $(span);

                        },
                        templateSelection: function(item) {

                            if (!item.id) {
                                return item.text;
                            }

                            var span = document.createElement('div');
                            var template = '';

                            template +=
                                `<span>Page ${item.grdsheetpg_pgNo} </span>`;

                            span.innerHTML = template;

                            return $(span);
                        },
                        language: {
                            searching: function() {
                                return "Searching Pages..";
                            }
                        },
                    });

                    let selectPageRows = $(row_dom).find('[data-name="row_no"]').select2({
                        minimumResultsForSearch: Infinity,
                        ajax: {
                            url: "{{ route('admin.gradesheet.page.rows', $class->class_id) }}",
                            data: function(params) {
                                return {
                                    page_id: $(selectPages).val(),
                                };
                            },
                            dataType: 'json',
                            delay: 250,
                            processResults: function(data, params) {

                                (data).forEach((e, i) => {

                                    data[i]["id"] = e.no;
                                    data[i]["disabled"] = e.has_student;
                                });

                                return {
                                    "results": data
                                };
                            },
                            cache: true,
                        },
                        templateResult: function(item) {

                            if (!item.id) {
                                return item.text;
                            }

                            let content = '';

                            if (!item.disabled) {
                                content +=
                                    `<p>${item.no}</p>`;
                            } else {
                                content += `<div class="d-flex justify-content-between">`;

                                content +=
                                    `<div>${item.no}</div><div>${(item.has_student && item.no != row_no) ? '<i class="fas fa-check"></i>' : '<span class="badge badge-success py-1 px-2"><i class="fas fa-check"></i></span>'}</div>`;

                                content += `</div>`;
                            }

                            return $(content);

                        },
                        templateSelection: function(item) {

                            if (!item.id) {
                                return item.text;
                            }

                            return item.no;
                        },
                        disabled: true,
                        language: {
                            searching: function() {
                                return "Searching Rows..";
                            }
                        },
                    });

                    $(selectPages).on("change", function() {
                        if (page = $(this).val()) {
                            selectPageRows.prop('disabled', false);
                            table_fv.revalidateField('grade[grdsheetpg_enrsub_id]');
                            $(selectPageRows).val("").trigger("change");
                        } else {
                            selectPageRows.prop('disabled', true);
                        }
                    });

                    $(selectPageRows).on("change", function() {
                        if (page = $(this).val()) {
                            table_fv.revalidateField('grade[enrsub_rowNo]');
                        }
                    });

                    $(row_dom).find(".grades").select2({
                        tags: true
                    });

                    table_fv.addField('grade[grdsheetpg_enrsub_id]', {
                        validators: {
                            notEmpty: {
                                message: 'This is required'
                            },
                        },
                    });

                    table_fv.addField('grade[enrsub_rowNo]', {
                        validators: {
                            notEmpty: {
                                message: 'This is required'
                            },
                        },
                    });

                    $(row_dom).on("click", "[kt_student_grades_table_cancel]", function() {
                        table.draw();
                    });

                    $(row_dom).on("click", "#kt_student_grades_table_save", function() {

                        rowData = table.row($(this).closest("tr")).data();

                        table_fv.validate(['grade[grdsheetpg_enrsub_id]',
                            'grade[enrsub_rowNo]'
                        ]).then(
                            function(e) {

                                let url =
                                    "{{ route('admin.gradesheet-students.update', [$class->class_id, ':student']) }}";
                                url = url.replace(':student', rowData['stud_id']);

                                $("#kt_student_grades_table [kt_student_grades_table_cancel]")
                                    .attr(
                                        "disabled", true);

                                const edit_submitBtnId = "kt_student_grades_table_save";
                                trigger_btnIndicator(edit_submitBtnId, "loading");
                                axios({
                                    method: "POST",
                                    url: url,
                                    data: $("#kt_form_edit_student_grade").serialize(),
                                    timeout: "{{ $axios_timeout }}"
                                }).then(function(respond) {

                                    trigger_btnIndicator(edit_submitBtnId, "default");
                                    if (respond.status == 200) {

                                        display_toastr_success(respond.data.message);
                                        table.draw();
                                    } else {

                                        display_modal_error("{{ __('modal.error') }}");
                                        $("#kt_student_grades_table [kt_student_grades_table_cancel]")
                                            .attr(
                                                "disabled", false);
                                    }

                                }).catch(function(error) {

                                    display_modal_error(error);
                                });

                            });

                    });

                })

                $("#kt_student_grades_table").on("click", "[kt_student_grades_table_delete]", function() {

                    rowData = table.row($(this).closest("tr")).data();

                    let url =
                        "{{ route('admin.gradesheet-students.destroy', [$class->class_id, ':student']) }}";
                    url = url.replace(':student', rowData['stud_id']);

                    Swal.fire({
                        text: "{{ __('modal.confirmation', ['action' => 'delete']) }}",
                        icon: 'warning',
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        allowOutsideClick: false,
                        confirmButtonText: "{{ __('modal.confirm_btn', ['action' => 'delete']) }}",
                        cancelButtonText: "{{ __('modal.cancel_btn') }}",
                        customClass: {
                            confirmButton: 'btn btn-primary',
                            cancelButton: 'btn btn-active-light',
                        },
                    }).then(function(t) {
                        if (t.isConfirmed) {
                            axios({
                                method: "DELETE",
                                url,
                                timeout: "{{ $axios_timeout }}"
                            }).then(function(respond) {

                                if (respond.status == 200) {
                                    display_toastr_info(respond.data.message);
                                } else {
                                    display_modal_error("{{ __('modal.error') }}");
                                }

                                table.draw();
                            }).catch(function(error) {
                                display_axios_error(error);
                            });
                        }
                    });

                });

                // Add Student Grades Modal
                var add_student_modal = init_modal("kt_modal_add_student_grade");

                let fv = init_formValidation("kt_modal_add_student_grade_form", {
                    'gradesheet_student': {
                        validators: {
                            notEmpty: {
                                message: 'Student is required'
                            },
                            remote: {
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                url: "{{ route('admin.gradesheet.form-validate.student', $class->class_id) }}",
                                message: "Student has already been added",
                                method: "POST",
                                delay: 1000
                            }
                        },
                    },
                    'gradesheet_page': {
                        validators: {
                            notEmpty: {
                                message: 'Page No. is required'
                            },
                        },
                    },
                    'gradesheet_page_rows': {
                        validators: {
                            notEmpty: {
                                message: 'Row No. is required'
                            },
                        },
                    },
                });

                const fetchPageDetails = function(page_id) {
                    let promise = $.ajax({
                        url: "{{ route('admin.gradesheet.pages-details', $class->class_id) }}",
                        type: "POST",
                        data: {
                            page_id
                        },
                    }).done(function(responseData, status, xhr) {}).fail(function(xhr, status,
                        err) {});
                    return promise;
                };

                const fetchStudentProfile = function(gradesheet_student) {
                    let promise = $.ajax({
                        url: "{{ route('admin.student.ajaxRetrieve') }}",
                        type: "POST",
                        data: {
                            id: gradesheet_student
                        },
                    }).done(function(responseData, status, xhr) {}).fail(function(xhr, status,
                        err) {});
                    return promise;
                };

                const checkAddedStudent = function(stud_id) {

                    let id = new Array();
                    let stud_ids = $('#kt_modal_add_student_grade_form [data-name="student.id"]');

                    for (let c = 0; c < stud_ids.length; c++) {
                        id.push($(stud_ids[c]).val());
                    }

                    return id.includes(stud_id.toString());
                }

                const countAddedStudentPerPage = function(page_id) {

                    let count = 0;
                    $(`#kt_modal_add_student_grade_form div[data-row-index] [data-name="student.page"]`).each(
                        function(e) {
                            if ($(this).val() == page_id) {
                                count++;
                            }
                        });

                    return count;
                }

                const checkAddedStudentRowSelected = function(page_id, row_no) {

                    let rows = new Array();

                    $(`#kt_modal_add_student_grade_form div[data-row-index] [data-name="student.page"]`).each(
                        function(e) {

                            let page = $(this);

                            if ($(page).val() == page_id) {
                                rows.push($(page).siblings(`[data-name="student.row"]`).val());
                            }
                        });
                    return rows.includes(row_no.toString());
                }

                const recheckEmptyStudents = function() {
                    if ($("#kt_modal_add_student_grade_imported_list").find(
                            'div[data-row-index]').length <= 0) {
                        $("#kt_modal_add_student_grade_imported_empty").attr("hidden", false);
                        $("#kt_modal_add_student_grade_submit").attr("disabled", true);
                    } else {
                        $("#kt_modal_add_student_grade_imported_empty").attr("hidden", true);
                        $("#kt_modal_add_student_grade_submit").attr("disabled", false);
                    }
                }

                const removeImportedStudent = function(rowIndex) {

                    $("#kt_modal_add_student_grade_imported_list").find(
                        'div[data-row-index="' + rowIndex + '"]').remove();

                    recheckEmptyStudents();
                }

                const resetAddStudentFields = function() {

                    $("select[name='gradesheet_student']").val("").trigger(
                        "change");
                    $("select[name='gradesheet_page']").val("").trigger(
                        "change");
                    $("select[name='gradesheet_page_rows']").val("").trigger(
                        "change");
                }

                const resetAddStudentForm = function() {

                    $("#kt_modal_add_student_grade_imported_list").find(
                        'div[data-row-index]').remove();

                    resetAddStudentFields();
                    recheckEmptyStudents();
                }

                $('#kt_modal_add_student_grade_search_student').select2({
                    ajax: {
                        url: "{{ route('admin.student.fetch') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                term: params.term || '',
                                recordType: 'NONSIS',
                                page: params.page || 1
                            };
                        },
                        processResults: function(data, params) {

                            (data.results).forEach((e, i) => {
                                data.results[i]["id"] = e.stud_id;
                                data.results[i]["disabled"] = checkAddedStudent(e.stud_id);
                            });

                            return data;
                        },
                        cache: true,
                    },
                    templateResult: function(item) {

                        if (!item.stud_id) {
                            return item.text;
                        }

                        let content = '';

                        content += '<div class="d-flex flex-stack">';

                        content += '<div class="d-flex justify-content-start" style="gap:12px">'

                        content += '<div class="my-auto">';
                        content +=
                            '<span><i class="fa-duotone fa-circle-user fs-2"></i></span>';
                        content += '</div>';

                        content += '<div class="my-auto">'
                        content +=
                            `<p class="mb-0"><b>${item.stud_studentNo}</b></p> 
                        <p class="mb-0">${item.stud_lastName}, ${item.stud_firstName} ${item.stud_middleName}</p>
                        <p class="mb-0">${item.stud_course}</p>`;
                        content += '</div>';

                        content += '</div>';

                        if (item.disabled) {
                            content += '<div>';
                            content +=
                                `<span class="badge badge-success py-1 px-2"><i class="fas fa-check"></i></span>`;
                            content += '</div>';

                        }

                        content += '</div>'



                        return $(content);

                    },
                    templateSelection: function(item) {

                        if (!item.stud_id) {
                            return item.text;
                        }

                        return $(
                            `<span>${item.stud_lastName}, ${item.stud_firstName} ${item.stud_middleName}</span>`
                        );
                    },
                    language: {
                        searching: function() {
                            return "Searching Students..";
                        }
                    },
                });

                $('#kt_modal_add_student_grade_gradesheet_pages').select2({
                    minimumResultsForSearch: Infinity,
                    ajax: {
                        url: "{{ route('admin.gradesheet.pages', $class->class_id) }}",
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {

                            (data).forEach((e, i) => {
                                data[i]["id"] = e.grdsheetpg_id;
                                data[i]["disabled"] = ((e.students).length +
                                    countAddedStudentPerPage(e
                                        .grdsheetpg_id)) >= e.grdsheetpg_rowNo;
                            });

                            return {
                                "results": data,
                                "pagination": {
                                    "more": false
                                }
                            };
                        },
                        cache: true,
                    },
                    templateResult: function(item) {

                        if (!item.id) {
                            return item.text;
                        }

                        var span = document.createElement('div');
                        var template = '';

                        template += `<div class="d-flex justify-content-between">`;

                        template +=
                            `<div>Page ${item.grdsheetpg_pgNo} </div><div class="d-flex flex-stack gap-2">${(countAddedStudentPerPage(item.id) >= 1) ? '<span class="badge badge-success fw-bolder">' + countAddedStudentPerPage(item.id) + '</span>' : ''}<span class="badge badge-secondary fw-bolder">${item['students'].length}/${item.grdsheetpg_rowNo}</span></div>`;

                        template += `</div>`;

                        span.innerHTML = template;

                        return $(span);

                    },
                    templateSelection: function(item) {

                        if (!item.id) {
                            return item.text;
                        }

                        var span = document.createElement('div');
                        var template = '';

                        template +=
                            `<span>Page ${item.grdsheetpg_pgNo} </span>`;

                        span.innerHTML = template;

                        return $(span);
                    },
                    language: {
                        searching: function() {
                            return "Searching Pages..";
                        }
                    },
                });

                let selectPgRows = $('#kt_modal_add_student_grade_gradesheet_page_rows').select2({
                    minimumResultsForSearch: Infinity,
                    ajax: {
                        url: "{{ route('admin.gradesheet.page.rows', $class->class_id) }}",
                        data: function(params) {
                            return {
                                page_id: $("#kt_modal_add_student_grade_gradesheet_pages")
                                    .val(),
                            };
                        },
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data, params) {

                            (data).forEach((e, i) => {

                                data[i]["id"] = e.no;
                                data[i]["disabled"] = checkAddedStudentRowSelected($(
                                        "#kt_modal_add_student_grade_gradesheet_pages")
                                    .val(), e.no) || e.has_student;
                            });

                            return {
                                "results": data
                            };
                        },
                        cache: true,
                    },
                    templateResult: function(item) {

                        if (!item.id) {
                            return item.text;
                        }

                        let content = '';

                        if (!item.disabled) {
                            content +=
                                `<p>${item.no}</p>`;
                        } else {
                            content += `<div class="d-flex justify-content-between">`;

                            content +=
                                `<div>${item.no}</div><div>${(item.has_student) ? '<i class="fas fa-check"></i>' : '<span class="badge badge-success py-1 px-2"><i class="fas fa-check"></i></span>'}</div>`;

                            content += `</div>`;
                        }

                        return $(content);

                    },
                    templateSelection: function(item) {

                        if (!item.id) {
                            return item.text;
                        }

                        return item.no;
                    },
                    disabled: true,
                    language: {
                        searching: function() {
                            return "Searching Rows..";
                        }
                    },
                });

                const template = $("#kt_modal_add_student_grade_imported_template");
                let rowIndex = 0;

                $("button#kt_modal_add_student_grade_import_add").on("click", function() {

                    const add_student_addBtnId = "kt_modal_add_student_grade_import_add";
                    trigger_btnIndicator(add_student_addBtnId, "loading");

                    fv.validate(['gradesheet_student', 'gradesheet_page', 'gradesheet_page_rows']).then(
                        function(e) {
                            if (e == "Valid") {

                                fetchStudentProfile($('select[name="gradesheet_student"]').val())
                                    .done(
                                        function(stud_profile) {
                                            trigger_btnIndicator(add_student_addBtnId, "default");

                                            stud_profile = stud_profile[0];

                                            rowIndex++;

                                            const clone = template.clone(true);
                                            clone.removeAttr("id");

                                            clone.attr("style", "align-items: center !important");
                                            clone.attr("data-row-index", rowIndex);

                                            clone.insertBefore(template);

                                            $(clone).find('input[data-name="student.id"]').attr(
                                                "name",
                                                `students[${rowIndex}][stud_enrsub_id]`).val(
                                                stud_profile[
                                                    'stud_id']);
                                            $(clone).find('input[data-name="student.page"]').attr(
                                                "name",
                                                `students[${rowIndex}][grdsheetpg_enrsub_id]`).val(
                                                $(
                                                    "select[name='gradesheet_page']").val());
                                            $(clone).find('input[data-name="student.row"]').attr(
                                                "name",
                                                `students[${rowIndex}][enrsub_rowNo]`).val($(
                                                "select[name='gradesheet_page_rows']").val());


                                            $(clone).find('span[data-name="student.d_page"]').text($(
                                                "input[data-name='page.no']").val());
                                            $(clone).find('span[data-name="student.d_row"]').text($(
                                                "select[name='gradesheet_page_rows']").val());

                                            $(clone).find('[data-name="student.no"]').text(
                                                stud_profile['stud_studentNo']);
                                            $(clone).find('[data-name="student.name"]').text(
                                                stud_profile[
                                                    'stud_lastName'] +
                                                ", " + stud_profile[
                                                    'stud_firstName'] + " " + ((
                                                        stud_profile['stud_middleName']) ?
                                                    stud_profile[
                                                        'stud_middleName'] : ""));

                                            $(clone).find('[data-name="student.midterm_grade"]')
                                                .attr("name",
                                                    "students[" +
                                                    rowIndex +
                                                    "][enrsub_midtermGrade]").select2({
                                                    tags: true
                                                });
                                            $(clone).find('[data-name="student.final_grade"]')
                                                .attr(
                                                    "name",
                                                    "students[" +
                                                    rowIndex +
                                                    "][enrsub_finalGrade]").select2({
                                                    tags: true
                                                });
                                            $(clone).find('[data-name="student.final_rating"]')
                                                .attr("name",
                                                    "students[" +
                                                    rowIndex +
                                                    "][enrsub_finalRating]").select2({
                                                    tags: true
                                                });
                                            $(clone).find('[data-name="student.grade_status"]')
                                                .attr("name",
                                                    "students[" +
                                                    rowIndex +
                                                    "][enrsub_grade_status]").select2({
                                                    tags: true
                                                });

                                            const removeBtn = clone.find(
                                                'button[kt_modal_add_student_grade_imported_remove]'
                                            );
                                            removeBtn.attr('data-row-index', rowIndex);

                                            $(removeBtn).on('click', function(e) {

                                                const index = $(this).attr(
                                                    'data-row-index');
                                                removeImportedStudent(index);
                                            });

                                            resetAddStudentFields();
                                            recheckEmptyStudents();
                                        });
                            } else {
                                trigger_btnIndicator(add_student_addBtnId, "default");
                            }
                        });

                });

                $('#kt_modal_add_student_grade_close, #kt_modal_add_student_grade_cancel').on("click",
                    function(t) {
                        t.preventDefault();

                        Swal.fire({
                            text: "{{ __('modal.confirmation', ['action' => 'cancel']) }}",
                            icon: 'warning',
                            showCancelButton: !0,
                            buttonsStyling: !1,
                            confirmButtonText: "{{ __('modal.confirm_btn', ['action' => 'cancel']) }}",
                            cancelButtonText: "{{ __('modal.cancel_btn') }}",
                            customClass: {
                                confirmButton: 'btn btn-primary',
                                cancelButton: 'btn btn-active-light',
                            },
                        }).then(function(t) {
                            if (t.value) {
                                resetAddStudentForm();
                                add_student_modal.hide();
                            }
                        });
                    });


                $('select[name="gradesheet_page"]').on("change", function() {
                    if (page = $(this).val()) {

                        fv.revalidateField('gradesheet_page');
                        selectPgRows.prop('disabled', false);
                        $(selectPgRows).val("").trigger("change");

                        fetchPageDetails(page).done(function(respond) {
                            if (respond.length >= 1) {
                                $("input[data-name='page.no']").val(respond[0]['grdsheetpg_pgNo']);
                            }
                        });

                    } else {
                        selectPgRows.prop('disabled', true);
                    }
                });

                $('select[name="gradesheet_student"]').on("change", function() {
                    if ($(this).val()) {
                        fv.revalidateField('gradesheet_student');
                    }
                });

                $('select[name="gradesheet_page_rows"]').on("change", function() {
                    if ($(this).val()) {
                        fv.revalidateField('gradesheet_page_rows');
                    }
                });

                $("#kt_modal_add_student_grade_submit").on("click", function() {

                    const add_stuedent_submitBtnId = "kt_modal_add_student_grade_submit";
                    trigger_btnIndicator(add_stuedent_submitBtnId, "loading");

                    axios({
                        method: "POST",
                        url: "{{ route('admin.gradesheet-students.store', $class->class_id) }}",
                        data: $("#kt_modal_add_student_grade_form").serialize(),
                        timeout: "{{ $axios_timeout }}"
                    }).then(function(respond) {

                        trigger_btnIndicator(add_stuedent_submitBtnId, "default");
                        if (respond.status == 200) {

                            display_axios_success(respond.data.message);

                            table.draw();
                            resetAddStudentForm();
                            add_student_modal.hide();
                        } else {

                            display_modal_error("{{ __('modal.error') }}");
                        }

                    }).catch(function(error) {

                        display_modal_error(error);
                    });
                });

            }));
        </script>
    </x-slot:scripts>

</x-default-layout>
