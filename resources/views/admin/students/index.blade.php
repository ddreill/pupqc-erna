<x-default-layout title="{{ __('Students') }}">

    <x-slot:title>
        {{ __('global.list_of', ['attribute' => __('cruds.student.title')]) }}
    </x-slot:title>

    <x-slot:pageTitle>
        {{ __('global.list_of', ['attribute' => __('cruds.student.title')]) }}
    </x-slot:pageTitle>

    <x-slot:breadcrumbs>
        {{ Breadcrumbs::render('students') }}
    </x-slot:breadcrumbs>

    <x-slot:content>
        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-column flex-lg-row-auto w-100 w-xl-300px mb-10">
                <div class="card card-xl-stretch">
                    <div class="card-header">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-800">Filter</span>
                        </h3>
                    </div>
                    <div class="card-body pt-5">
                        <div class="mb-5">
                            <label
                                class="form-label fs-6 fw-semibold">{{ __('cruds.student.fields.stud_cour_stud_id') }}</label>
                            <input class="form-control" value="" id="filterCourse" />
                        </div>
                        <div class="mb-5">
                            <label
                                class="form-label fs-6 fw-semibold">{{ __('cruds.student.fields.stud_academicStatus') }}</label>
                            <input class="form-control" value="" id="filterAcademicStatus" />
                        </div>

                        <div class="mb-5">
                            <label
                                class="form-label fs-6 fw-semibold">{{ __('cruds.student.fields.stud_admissionType') }}</label>
                            <input class="form-control" value="" id="filterAdmissionType" />
                        </div>

                        <div class="mb-5">
                            <label
                                class="form-label fs-6 fw-semibold">{{ __('cruds.student.fields.stud_yearOfAdmission') }}</label>
                            <input class="form-control" value="" id="filterYearOfAdmission" />
                        </div>

                        <div class="mb-7">
                            <label
                                class="form-label fs-6 fw-semibold">{{ __('cruds.student.fields.stud_recordType') }}</label>
                            <div class="d-flex">
                                <div class="form-check form-check-custom me-10">
                                    @php $nonSis = \App\Enums\RecordTypeEnum::NONSIS; @endphp
                                    <input class="form-check-input filterRecordType" type="checkbox"
                                        value="{{ $nonSis }}" id="NONSISCheckbox" />
                                    <label class="form-check-label" for="NONSISCheckbox">
                                        {{ (new App\Enums\RecordTypeEnum())->getDisplayNames()[$nonSis] }}
                                    </label>
                                </div>
                                <div class="form-check form-check-custom">
                                    @php $sis = \App\Enums\RecordTypeEnum::SIS; @endphp

                                    <input class="form-check-input filterRecordType" type="checkbox"
                                        value="{{ $sis }}" id="SISCheckbox" />
                                    <label class="form-check-label" for="SISCheckbox">
                                        {{ (new App\Enums\RecordTypeEnum())->getDisplayNames()[$sis] }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="rounded border p-7 bg-white mb-10">
                            <div class="d-flex flex-stack">
                                <div class="me-5">
                                    <label
                                        class="fs-6 fw-semibold form-label">{{ __('Show Honorable Dismissed') }}</label>
                                </div>

                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input id="filterShowHonorableDismissed" class="form-check-input" type="checkbox">
                                </label>
                            </div>
                        </div>

                        <div class="separator my-10 opacity-75"></div>
                        <div class="mb-5">
                            <label class="form-label fw-semibold">Created at</label>
                            <div class="d-flex">
                                <div class="input-group">
                                    <input class="form-control  rounded rounded-end-0" id="filterCreatedAt"
                                        placeholder="Pick date range" />
                                    <button class="btn btn-icon btn-light">
                                        <span class="svg-icon svg-icon-5">
                                            <i class="fa-light fa-xmark"></i>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="mb-5">
                            <label class="form-label fw-semibold">Updated at</label>
                            <div class="d-flex">
                                <div class="input-group">
                                    <input class="form-control  rounded rounded-end-0" id="filterUpdatedAt"
                                        placeholder="Pick date range" />
                                    <button class="btn btn-icon btn-light">
                                        <span class="svg-icon svg-icon-5">
                                            <i class="fa-light fa-xmark"></i>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        @can('student_archive_access')
                            <div class="mb-10" hidden>
                                <label class="form-label fw-semibold">Archived at</label>
                                <div class="d-flex">
                                    <div class="input-group">
                                        <input class="form-control  rounded rounded-end-0" id="filterArchivedAt"
                                            placeholder="Pick date range" />
                                        <button class="btn btn-icon btn-light">
                                            <span class="svg-icon svg-icon-5">
                                                <i class="fa-light fa-xmark"></i>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endcan

                        <button class="btn btn-primary d-block w-100 fw-semibold px-6 mb-2" id="filterApplyBtn">Apply
                            filter</button>

                    </div>
                </div>
            </div>

            <div class="flex-lg-row-fluid ms-lg-15">

                @can('student_archive_access')
                    <div class="rounded border p-10 bg-white mb-10">
                        <div class="d-flex flex-stack">
                            <div class="me-5">
                                <label class="fs-6 fw-semibold form-label">{{ __('Show archived students') }}</label>
                                <div class="fs-7 fw-semibold text-muted">{{ __('Only archived students will be shown') }}
                                </div>
                            </div>

                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input id="showArchived" class="form-check-input" type="checkbox">
                                <span class="form-check-label fw-semibold text-nowrap text-muted" id="showArchivedLabel">
                                    Hidden
                                </span>
                            </label>
                        </div>
                    </div>
                @endcan

                <div class="card mb-6 mb-xl-9">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            @include('partials.dataTables.search', [
                                'resource' => convertToSnakeCase(__('cruds.student.title_singular')),
                                'resourceDisplay' => __('cruds.student.title_singular'),
                            ])
                        </div>
                        <div class="card-toolbar">
                            @can('student_create')
                                <div class="d-flex justify-content-end">
                                    @include('partials.buttons.create', [
                                        'createRoute' => route('students.create'),
                                        'resource' => convertToSnakeCase(__('cruds.student.title_singular')),
                                        'resourceDisplay' => __('cruds.student.title_singular'),
                                    ])
                                </div>
                            @endcan
                        </div>
                    </div>

                    <div class="card-body py-3">

                        <table id="dataTable" class="table border table-rounded table-row-bordered gy-5 gs-7">
                            <thead>
                                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                    <th>{{ __('cruds.student.fields.stud_studentNo') }}</th>
                                    <th>{{ __('cruds.student.fields.stud_name') }}</th>
                                    <th>{{ __('cruds.student.fields.stud_cour_stud_id') }}</th>
                                    <th>{{ __('cruds.student.fields.stud_admissionType') }}</th>
                                    <th>{{ __('cruds.student.fields.stud_yearOfAdmission') }}</th>
                                    <th>{{ __('cruds.student.fields.stud_academicStatus') }}</th>
                                    <th>{{ __('cruds.student.fields.stud_recordType') }}</th>
                                    <th>{{ __('cruds.student.fields.remarks') }}</th>
                                    <th>{{ __('cruds.student.fields.stud_createdAt') }}</th>
                                    <th>{{ __('cruds.student.fields.stud_updatedAt') }}</th>
                                    <th>{{ __('cruds.student.fields.archived_at') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </x-slot:content>

    <x-slot:scripts>
        <script type="text/javascript">
            KTUtil.onDOMContentLoaded((function() {

                initializedTagify(document.querySelector("#filterCourse"), @json($filterCourses), true);
                initializedTagify(document.querySelector("#filterAcademicStatus"), @json($filterAcademicStatus),
                    true);
                initializedTagify(document.querySelector("#filterAdmissionType"), @json($filterAdmissionType),
                    true);
                initializedTagify(document.querySelector("#filterYearOfAdmission"), @json($filterYearOfAdmission));

                var filterCreatedAt = initializedFlatpickr("#filterCreatedAt", "range", true);
                var filterUpdatedAt = initializedFlatpickr("#filterUpdatedAt", "range", true);
                var filterArchivedAt = initializedFlatpickr("#filterArchivedAt", "range", true);

                const resource = "student";

                let table = $("#dataTable").DataTable({
                    buttons: $.extend(true, [], $.fn.dataTable.defaults.buttons),
                    serverSide: true,
                    processing: true,
                    retrieve: true,
                    searchDelay: 400,
                    ajax: {
                        url: "{{ route('students.index') }}",
                        data: {
                            "course": function() {
                                return $("#filterCourse").val();
                            },
                            "academic_status": function() {
                                return $("#filterAcademicStatus").val()
                            },
                            "admission_type": function() {
                                return $("#filterAdmissionType").val();
                            },
                            "admission_year": function() {
                                return $("#filterYearOfAdmission").val();
                            },
                            "record_type": function() {
                                return getCheckedValues(".filterRecordType");
                            },
                            "honorable_dismissed": function() {
                                return getToggleValues("#filterShowHonorableDismissed");
                            },
                            "created_at": function() {
                                getFlatpickrDates(filterCreatedAt);
                            },
                            "updated_at": function() {
                                getFlatpickrDates(filterUpdatedAt);
                            },
                            "archived_at": function() {
                                return !$($("#filterArchivedAt").parent().parent().parent()).is(
                                    ":hidden") ? getFlatpickrDates(filterArchivedAt) : "";
                            },
                            "show_archived": function() {
                                return !$($("#filterArchivedAt").parent().parent().parent()).is(
                                    ":hidden") ? "1" : "0";
                            },
                        }
                    },
                    columns: [{
                            data: 'stud_studentNo',
                            className: "align-middle"
                        },
                        {
                            data: 'full_name',
                            className: "align-middle"
                        },
                        {
                            data: 'course_code',
                            searchable: false,
                            orderable: false,
                            className: "align-middle"
                        },
                        {
                            data: 'stud_admissionType',
                            searchable: false,
                            className: "align-middle"
                        },
                        {
                            data: 'stud_yearOfAdmission',
                            searchable: false,
                            className: "align-middle"
                        },
                        {
                            data: 'stud_academicStatus',
                            searchable: false,
                            orderable: false,
                            className: "align-middle"
                        },
                        {
                            data: 'stud_recordType',
                            searchable: false,
                            orderable: false,
                            className: "align-middle"
                        },
                        {
                            data: 'remarks',
                            searchable: false,
                            orderable: false,
                            className: "align-middle"
                        },
                        {
                            data: 'stud_createdAt',
                            orderable: false,
                            className: "align-middle"
                        },
                        {
                            data: 'stud_updatedAt',
                            orderable: false,
                            className: "align-middle"
                        },
                        {
                            data: 'archived_at',
                            orderable: false,
                            className: "align-middle",
                            visible: false
                        },
                        {
                            data: 'actions',
                            searchable: false,
                            orderable: false,
                            className: "text-end align-middle"
                        },
                    ],
                    orderCellsTop: true,
                    order: [
                        [0, 'asc']
                    ],
                    lengthMenu: [
                        [10, 25, 50, 100],
                        ['10', '25', '50', '100']
                    ],
                    pageLength: 10,
                    drawCallback: function(settings, json) {
                        KTMenu.createInstances();

                        $('[data-bs-toggle="tooltip"]').tooltip();

                        initializedFormSubmitConfirmation(`[${resource}-unarchive="true"]`,
                            `-${resource}-unarchive`,
                            "unarchive", "warning", "warning");

                        initializedFormSubmitConfirmation(`[${resource}-archive="true"]`,
                            `-${resource}-archive`,
                            "archive", "dark", "warning");
                    }
                });

                $(`#${resource}DataTableSearch`).on('keyup', function() {
                    table.search($(this).val()).draw();
                });

                $("#filterApplyBtn").on("click", function() {
                    table.ajax.reload();
                });

                @can('student_archive_access')
                    $("#showArchived").on("click", function(e) {
                        var parent = $("#filterArchivedAt").parent().parent().parent();

                        if (!$(parent).is(":hidden")) {
                            $(parent).prop("hidden", true);
                            $("#showArchivedLabel").text("Hidden");
                            table.column(10).visible(false);
                        } else {
                            $(parent).prop("hidden", false);
                            $("#showArchivedLabel").text("Showing");
                            table.column(10).visible(true);
                        }

                        table.ajax.reload();
                    });
                @endcan
            }));
        </script>
    </x-slot:scripts>
</x-default-layout>
