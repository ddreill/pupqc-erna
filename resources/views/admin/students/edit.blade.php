<x-default-layout title="Edit Student" pageTitle="Edit Student">

    <x-slot:breadcrumbs>
        {{ Breadcrumbs::render('students.edit', $stud_profile->stud_uuid) }}
    </x-slot:breadcrumbs>

    <x-slot:content>
        <div class="post" id="kt_post">

            <div id="kt_content_container" class="container-fluid">

                <form class="form" action="#" id="kt_form_add_student_profile">
                    <input type="text" hidden name="id" value="{{ $stud_profile->stud_id }}" />

                    <div class="d-flex flex-column flex-xl-row">
                        <div class="d-flex flex-column flex-lg-row-auto gap-7 gap-lg-10 w-100 w-xl-450px mb-10">

                            <div class="card card-flush py-4">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Student Number</h2>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="fv-row mb-7">
                                        <input type="text" class="form-control " name="studentNo"
                                            value="{!! $stud_profile->stud_studentNo ? $stud_profile->stud_studentNo : '' !!}" />
                                        <div class="text-muted fs-7 mb-5 mt-2">This is required and recommended to be
                                            unique
                                        </div>
                                    </div>

                                    <div class="fv-row">
                                        <label class="form-label">Course</label>
                                        <select class="form-select " data-control="select2"
                                            data-placeholder="Select a course"
                                            data-dropdown-parent="#kt_form_add_student_profile" name="course">
                                            <option></option>
                                            @foreach ($formData_course as $course)
                                                <option value="{{ $course->cour_id }}">
                                                    {{ $course->cour_code . ' ― ' . $course->cour_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-flush py-4">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Admission details</h2>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="fv-row mb-7">
                                        <select class="form-select " data-control="select2"
                                            data-placeholder="Admission Type"
                                            data-dropdown-parent="#kt_form_add_student_profile" name="admissionType">
                                            <option></option>
                                            <option value="FRESHMEN">Freshmen</option>
                                            <option value="TRANSFEREE">Transferee</option>
                                            <option value="LADDERIZED">Ladderized</option>
                                        </select>
                                        <div class="text-muted fs-7 mb-5 mt-2">Set the Admission type</div>
                                    </div>

                                    <div class="fv-row">
                                        <label class="form-label">Year of Admission</label>
                                        <select class="form-select " data-control="select2"
                                            data-placeholder="Select a year"
                                            data-dropdown-parent="#kt_form_add_student_profile" name="yearOfAdmission">
                                            <option></option>
                                            @foreach ($formData_year as $year)
                                                <option value="{{ $year->syear_year }}">{{ $year->syear_year }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-flush py-4">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Academic Status</h2>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="fv-row mb-7">
                                        <select class="form-select " data-control="select2"
                                            data-placeholder="Academic Status"
                                            data-dropdown-parent="#kt_form_add_student_profile" name="academicStatus">
                                            <option></option>
                                            @foreach ((new App\Enums\AcademicStatusEnum())->getSelectable() as $value => $displayName)
                                                <option value="{{ $value }}">
                                                    {{ $displayName }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="text-muted fs-7 mb-5 mt-2">Set the Academic Status</div>
                                    </div>

                                    <div class="fv-row mb-7" style="display:none !important">
                                        <label class="form-label">Date Exited</label>
                                        <input class="form-control " value="{!! $stud_profile->stud_dateExited ? date('m/d/Y', strtotime($stud_profile->stud_dateExited)) : '' !!}" name="dateExited">
                                    </div>

                                    <div class="fv-row" style="display:none !important">
                                        <select class="form-select " data-control="select2" data-allow-clear="true"
                                            data-placeholder="Honor" data-dropdown-parent="#kt_form_add_student_profile"
                                            name="honor">
                                            <option></option>
                                            @foreach ($formData_honors as $honor)
                                                <option value="{{ $honor->honor_id }}">
                                                    {{ $honor->honor_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-flush py-4 bg-danger bg-opacity-15"
                                id="kt_form_add_student_profile_honorableDissmissed">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2 class="">Honorable Dismissal</h2>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="fv-row mb-7" style="display:none !important">
                                        <label class="form-label">Have granted an Honorable Dismissal?</label>

                                        <select class="form-select " data-control="select2"
                                            data-placeholder="Yes or No"
                                            data-dropdown-parent="#kt_form_add_student_profile"
                                            name="isHonorableDismissed">
                                            <option></option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="fv-row mb-7" style="display:none !important">
                                        <select class="form-select " data-control="select2" data-placeholder="Status"
                                            data-dropdown-parent="#kt_form_add_student_profile"
                                            name="honorableDismissedStatus">
                                            <option></option>
                                            <option value="ISSUED">Issued</option>
                                            <option value="GRANTED">Granted</option>
                                        </select>
                                    </div>

                                    <div class="fv-row mb-7" style="display:none !important">
                                        <label class="form-label ">Date Issued</label>
                                        <input class="form-control " value="{!! $stud_profile->stud_honorableDismissedDate
                                            ? date('m/d/Y', strtotime($stud_profile->stud_honorableDismissedDate))
                                            : '' !!}"
                                            name="honorableDismissedDate">
                                    </div>

                                    <div class="fv-row" style="display:none !important">
                                        <label class="form-label ">School name and Address</label>
                                        <input class="form-control " value="{!! $stud_profile->stud_honorableDismissedSchool ? $stud_profile->stud_honorableDismissedSchool : '' !!}"
                                            name="honorableDismissedSchool">
                                        <div class=" fs-7 mb-5 mt-2">The school where the document will be transferred
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-lg-row-fluid ms-lg-15 gap-7 gap-lg-10">
                            <div class="card card-flush py-4">
                                <div class="card-body">
                                    <div class="d-flex flex-stack">
                                        <div class="me-7">
                                            <h2 class="mb-0">Record Type</h2>
                                        </div>
                                        <div>
                                            <div class="fv-row">
                                                <select class="form-select " data-control="select2"
                                                    data-placeholder="Select a record type"
                                                    data-dropdown-parent="#kt_form_add_student_profile"
                                                    name="recordType">
                                                    <option></option>
                                                    <option value="NONSIS">Non SIS</option>
                                                    <option value="SIS">SIS</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>

                            <ul
                                class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-8 mt-10">
                                <li class="nav-item">
                                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                                        href="#kt_form_add_student_profile_general_tab">General Information</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link text-active-primary pb-4 " data-kt-countup-tabs="true"
                                        data-bs-toggle="tab" href="#kt_form_add_student_profile_envelope">Envelope
                                        Documents
                                    </a>
                                </li>

                            </ul>

                            <div class="tab-content" id="myTabContent">

                                <div class="tab-pane fade show active" id="kt_form_add_student_profile_general_tab"
                                    role="tabpanel">

                                    <div class="card card-flush mb-10">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>General Information</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">

                                            <div class="row g-9 mb-7">

                                                <div class="col-md-6 fv-row">
                                                    <label class="required form-label">First name</label>
                                                    <input class="form-control "
                                                        oninput="this.value = this.value.toUpperCase()"
                                                        value="{!! $stud_profile->stud_firstName ? $stud_profile->stud_firstName : '' !!}" name="firstName">
                                                </div>

                                                <div class="col-md-6 fv-row">
                                                    <label class="form-label">Middle name</label>
                                                    <input class="form-control "
                                                        oninput="this.value = this.value.toUpperCase()"
                                                        value="{!! $stud_profile->stud_middleName ? $stud_profile->stud_middleName : '' !!}" name="middleName">
                                                </div>
                                            </div>

                                            <div class="row g-9 mb-7">

                                                <div class="col-md-12 fv-row">
                                                    <label class="required form-label">Last name</label>
                                                    <input class="form-control "
                                                        oninput="this.value = this.value.toUpperCase()"
                                                        value="{!! $stud_profile->stud_lastName ? $stud_profile->stud_lastName : '' !!}" name="lastName">
                                                </div>
                                            </div>

                                            <div class="text-muted fs-7 mb-15 mt-2">Set the Student name for better
                                                indentification
                                            </div>


                                            <div class="fv-row mb-7">
                                                <label class="form-label">Permanent Address</label>
                                                <input class="form-control "
                                                    oninput="this.value = this.value.toUpperCase()"
                                                    value="{!! $stud_profile->stud_addressLine ? $stud_profile->stud_addressLine : '' !!}" name="addressLine">
                                            </div>

                                            <div class="row mb-7">
                                                <div class="col-md-6 fv-row">
                                                    <label class="form-label fs-5 fw-bold mb-3">Province</label>
                                                    <select class="form-select " data-control="select2"
                                                        data-placeholder="Select a province"
                                                        data-dropdown-parent="#kt_form_add_student_profile"
                                                        data-allow-clear="true" name="addressProvince">
                                                        <option></option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 fv-row">
                                                    <label class="form-label fs-5 fw-bold mb-3">City</label>
                                                    <select class="form-select " data-control="select2"
                                                        data-placeholder="Select a city"
                                                        data-dropdown-parent="#kt_form_add_student_profile"
                                                        data-allow-clear="true" name="addressCity">
                                                        <option></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card card-flush mb-10">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Elementary and High School</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">

                                            <div class="row g-9 mb-7">

                                                <div class="col-md-9 fv-row">
                                                    <label class="form-label">Elementary School name</label>
                                                    <input class="form-control "
                                                        oninput="this.value = this.value.toUpperCase()"
                                                        @if ($stud_profile->stud_sPrimary) value="{!! $stud_profile->stud_sPrimary->extsch_name ? $stud_profile->stud_sPrimary->extsch_name : '' !!}" 
                                                    @else 
                                                    value="" @endif
                                                        name="es_name">
                                                </div>


                                                <div class="col-md-3 fv-row">
                                                    <label class="form-label">Year graduated</label>
                                                    <input class="form-control "
                                                        @if ($stud_profile->stud_sPrimary) value="{!! $stud_profile->stud_sPrimary->extsch_yearExit ? $stud_profile->stud_sPrimary->extsch_yearExit : '' !!}"
                                                    @else 
                                                    value="" @endif
                                                        name="es_yearGraduated">
                                                </div>
                                            </div>

                                            <div class="row g-9">

                                                <div class="col-md-9 fv-row">
                                                    <label class="form-label">High School name</label>
                                                    <input class="form-control "
                                                        oninput="this.value = this.value.toUpperCase()"
                                                        @if ($stud_profile->stud_sSecondary) value=" {!! $stud_profile->stud_sSecondary->extsch_name ? $stud_profile->stud_sSecondary->extsch_name : '' !!}"
                                                @else
                                                value="" @endif
                                                        name="hs_name">
                                                </div>


                                                <div class="col-md-3 fv-row">
                                                    <label class="form-label">Year graduated</label>
                                                    <input class="form-control "
                                                        @if ($stud_profile->stud_sSecondary) value=" {!! $stud_profile->stud_sSecondary->extsch_yearExit ? $stud_profile->stud_sSecondary->extsch_yearExit : '' !!}"
                                                @else
                                                value="" @endif
                                                        name="hs_yearGraduated">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card card-flush mb-10" id="kt_form_add_student_profile_prev_college"
                                        style="display: none !important">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Previous College or University</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0"
                                            id="kt_form_add_student_profile_prev_college_table">

                                            <div class="row">
                                                <div class="col-8">
                                                    <div class="fv-row">
                                                        <input placeholder="School name and address"
                                                            class="form-control "
                                                            oninput="this.value = this.value.toUpperCase()"
                                                            name="college[0][name]">
                                                    </div>
                                                </div>


                                                <div class="col-3">
                                                    <div class="fv-row">
                                                        <input placeholder="Year Exited" class="form-control "
                                                            name="college[0][yearExited]">
                                                    </div>
                                                </div>


                                                <div class="col-1">
                                                    <button kt_form_add_student_profile_prev_college_addBtn
                                                        type="button" class="btn btn-icon btn-dark btn-sm"><i
                                                            class="fas fa-plus"></i></button>
                                                </div>
                                            </div>

                                            <div id="kt_form_add_student_profile_prev_college_template"
                                                class="row mt-5" style="display: none !important">
                                                <div class="col-8">

                                                    <div class="fv-row">
                                                        <input placeholder="School name and address"
                                                            data-name="college.name"
                                                            oninput="this.value = this.value.toUpperCase()"
                                                            class="form-control ">
                                                    </div>
                                                </div>

                                                <div class="col-3">

                                                    <div class="fv-row">
                                                        <input placeholder="Year Exited"
                                                            data-name="college.yearExited" class="form-control ">
                                                    </div>
                                                </div>

                                                <div class="col-1">
                                                    <button kt_form_add_student_profile_prev_college_removeBtn
                                                        type="button" class="btn btn-icon btn-secondary btn-sm"><i
                                                            class="fas fa-minus"></i></button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>

                                <div class="tab-pane fade" id="kt_form_add_student_profile_envelope" role="tabpanel">

                                    <div class="card mb-10">
                                        <div class="card-header py-3 border-dashed border-bottom-1 border-0 border-gray-300"
                                            style="min-height:45px">
                                            <div class="card-title">
                                                <h2 class=" text-gray-800">Documents Category</h2>
                                            </div>
                                            <div class="card-toolbar">
                                                <ul class="nav nav-stretch fs-6 fw-bold nav-line-tabs nav-line-tabs-2x border-transparent text-uppercase"
                                                    role="tablist">

                                                    <li class="nav-item" role="presentation">
                                                        <a class="nav-link text-active-primary active"
                                                            data-bs-toggle="tab" role="tab"
                                                            href="#kt_student_view_school_primary"
                                                            aria-selected="true">
                                                            Entrance </a>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <a class="nav-link text-active-primary" data-bs-toggle="tab"
                                                            role="tab" href="#kt_student_view_school_secondary"
                                                            aria-selected="false">
                                                            Records </a>
                                                    </li>

                                                    <li class="nav-item" role="presentation">
                                                        <a class="nav-link text-active-primary" data-bs-toggle="tab"
                                                            role="tab" href="#kt_student_view_school_tertiary"
                                                            aria-selected="false"> Exit
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="card-body px-0 py-10">
                                            <div class="tab-content" id="myTabContent">
                                                <div class="tab-pane fade active show"
                                                    id="kt_student_view_school_primary" role="tabpanel">

                                                    <div id="kt_form_add_student_profile_document_entrance_list"
                                                        class="px-8">

                                                        <div class="d-flex justify-content-between">

                                                            <div class="fv-row w-25">
                                                                <select class="form-select " data-control="select2"
                                                                    data-placeholder="Document"
                                                                    data-dropdown-parent="#kt_form_add_student_profile"
                                                                    data-allow-clear="true"
                                                                    name="documents[entrance][0][docu]">
                                                                    <option></option>
                                                                    @foreach ($formData_docu_ent as $docu)
                                                                        <option value="{{ $docu->docu_id }}">
                                                                            {{ $docu->docu_name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="fv-row w-25">
                                                                <select class="form-select " data-control="select2"
                                                                    data-placeholder="Type"
                                                                    data-dropdown-parent="#kt_form_add_student_profile"
                                                                    data-allow-clear="true"
                                                                    name="documents[entrance][0][type]">
                                                                    <option></option>

                                                                </select>
                                                            </div>

                                                            <div class="fv-row">
                                                                <input placeholder="Date Submitted"
                                                                    class="form-control "
                                                                    name="documents[entrance][0][date_submitted]">
                                                            </div>

                                                            <div>
                                                                <button
                                                                    kt_form_add_student_profile_document_entrance_addBtn
                                                                    type="button"
                                                                    class="btn btn-icon btn-dark btn-sm"><i
                                                                        class="fas fa-plus"></i></button>
                                                            </div>
                                                        </div>

                                                        <div id="kt_form_add_student_profile_document_entrance_template"
                                                            class="d-flex justify-content-between mt-5"
                                                            style="display:none !important;">

                                                            <div class="fv-row w-25">
                                                                <select class="form-select "
                                                                    data-placeholder="Document"
                                                                    data-dropdown-parent="#kt_form_add_student_profile"
                                                                    data-allow-clear="true"
                                                                    data-name="documents.docu">
                                                                    <option></option>
                                                                    @foreach ($formData_docu_ent as $docu)
                                                                        <option value="{{ $docu->docu_id }}">
                                                                            {{ $docu->docu_name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="fv-row w-25">
                                                                <select class="form-select " data-placeholder="Type"
                                                                    data-dropdown-parent="#kt_form_add_student_profile"
                                                                    data-allow-clear="true"
                                                                    data-name="documents.type">
                                                                    <option></option>
                                                                </select>
                                                            </div>

                                                            <div class="fv-row">
                                                                <input placeholder="Date Submitted"
                                                                    class="form-control "
                                                                    data-name="documents.date_submitted">
                                                            </div>

                                                            <div>
                                                                <button
                                                                    kt_form_add_student_profile_document_entrance_removeBtn
                                                                    type="button"
                                                                    class="btn btn-icon btn-secondary btn-sm"><i
                                                                        class="fas fa-minus"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="tab-pane fade" id="kt_student_view_school_secondary"
                                                    role="tabpanel">

                                                    <div
                                                        class="rounded-3 p-10 pb-15 mx-5 mb-15 border-1 border border-gray-200">
                                                        <h4 class="mb-5 text-gray-800 fw-bold">Registration
                                                            Certificates
                                                        </h4>

                                                        <div
                                                            id="kt_form_add_student_profile_document_records_regcert_list">
                                                            <div class="row">

                                                                <div class="col-3">
                                                                    <select class="form-select "
                                                                        data-placeholder="School Year"
                                                                        data-dropdown-parent="#kt_form_add_student_profile"
                                                                        data-allow-clear="true"
                                                                        name="documents_fix[records][regcert][0][sy]">
                                                                        <option></option>
                                                                        @foreach ($formData_year as $year)
                                                                            <option value="{{ $year->syear_year }}">
                                                                                {{ $year->syear_year }} -
                                                                                {{ $year->syear_year + 1 }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="col-3">
                                                                    <select class="form-select "
                                                                        data-placeholder="Semester"
                                                                        data-dropdown-parent="#kt_form_add_student_profile"
                                                                        data-allow-clear="true"
                                                                        name="documents_fix[records][regcert][0][sem]">
                                                                        <option></option>
                                                                        @foreach ($formData_terms as $term)
                                                                            <option value="{{ $term->term_name }}">
                                                                                {{ $term->term_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="col-2">
                                                                    <select class="form-select "
                                                                        data-placeholder="Year level"
                                                                        data-dropdown-parent="#kt_form_add_student_profile"
                                                                        data-allow-clear="true"
                                                                        name="documents_fix[records][regcert][0][yrlvl]">
                                                                        <option></option>
                                                                        @foreach ($formData_yrLevel as $year)
                                                                            <option value="{{ $year->year_name }}">
                                                                                {{ $year->year_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="col-3">
                                                                    <input placeholder="Date Submitted"
                                                                        class="form-control "
                                                                        name="documents_fix[records][regcert][0][date_submitted]">
                                                                </div>

                                                                <div class="col-1 d-flex flex-center">
                                                                    <button
                                                                        kt_form_add_student_profile_document_records_regcert_addBtn
                                                                        type="button"
                                                                        class="btn btn-icon btn-dark btn-sm"><i
                                                                            class="fas fa-plus"></i></button>
                                                                </div>
                                                            </div>

                                                            <div id="kt_form_add_student_profile_document_records_regcert_template"
                                                                class="mt-5 row" style="display:none !important;">

                                                                <div class="col-3">
                                                                    <select class="form-select "
                                                                        data-placeholder="School Year"
                                                                        data-dropdown-parent="#kt_form_add_student_profile"
                                                                        data-allow-clear="true"
                                                                        data-name="documents_fix.sy">
                                                                        <option></option>
                                                                        @foreach ($formData_year as $year)
                                                                            <option value="{{ $year->syear_year }}">
                                                                                {{ $year->syear_year }} -
                                                                                {{ $year->syear_year + 1 }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="col-3">
                                                                    <select class="form-select "
                                                                        data-placeholder="Semester"
                                                                        data-dropdown-parent="#kt_form_add_student_profile"
                                                                        data-allow-clear="true"
                                                                        data-name="documents_fix.sem">
                                                                        <option></option>
                                                                        @foreach ($formData_terms as $term)
                                                                            <option value="{{ $term->term_name }}">
                                                                                {{ $term->term_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="col-2">
                                                                    <select class="form-select "
                                                                        data-placeholder="Year level"
                                                                        data-dropdown-parent="#kt_form_add_student_profile"
                                                                        data-allow-clear="true"
                                                                        data-name="documents_fix.yrlvl">
                                                                        <option></option>
                                                                        @foreach ($formData_yrLevel as $year)
                                                                            <option value="{{ $year->year_name }}">
                                                                                {{ $year->year_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="col-3">
                                                                    <input placeholder="Date Submitted"
                                                                        class="form-control "
                                                                        data-name="documents_fix.date_submitted">
                                                                </div>

                                                                <div class="col-1 d-flex flex-center">
                                                                    <button
                                                                        kt_form_add_student_profile_document_records_regcert_removeBtn
                                                                        type="button"
                                                                        class="btn btn-icon btn-secondary btn-sm"><i
                                                                            class="fas fa-minus"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div id="kt_form_add_student_profile_document_records_list"
                                                        class="mx-5 p-10 py-0">
                                                        <h4 class="mb-5 text-gray-800 fw-bold">Other documents</h4>

                                                        <div class="d-flex justify-content-between">

                                                            <div class="fv-row w-25">
                                                                <select class="form-select " data-control="select2"
                                                                    data-placeholder="Document"
                                                                    data-dropdown-parent="#kt_form_add_student_profile"
                                                                    data-allow-clear="true"
                                                                    name="documents[records][0][docu]">
                                                                    <option></option>
                                                                    @foreach ($formData_docu_rec as $docu)
                                                                        <option value="{{ $docu->docu_id }}">
                                                                            {{ $docu->docu_name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="fv-row w-25">
                                                                <select class="form-select " data-control="select2"
                                                                    data-placeholder="Type"
                                                                    data-dropdown-parent="#kt_form_add_student_profile"
                                                                    data-allow-clear="true"
                                                                    name="documents[records][0][type]">
                                                                    <option></option>

                                                                </select>
                                                            </div>

                                                            <div class="fv-row">
                                                                <input placeholder="Date Submitted"
                                                                    class="form-control "
                                                                    name="documents[records][0][date_submitted]">
                                                            </div>

                                                            <div>
                                                                <button
                                                                    kt_form_add_student_profile_document_records_addBtn
                                                                    type="button"
                                                                    class="btn btn-icon btn-dark btn-sm"><i
                                                                        class="fas fa-plus"></i></button>
                                                            </div>
                                                        </div>

                                                        <div id="kt_form_add_student_profile_document_records_template"
                                                            class="d-flex justify-content-between mt-5"
                                                            style="display:none !important;">

                                                            <div class="fv-row w-25">
                                                                <select class="form-select "
                                                                    data-placeholder="Document"
                                                                    data-dropdown-parent="#kt_form_add_student_profile"
                                                                    data-allow-clear="true"
                                                                    data-name="documents.docu">
                                                                    <option></option>
                                                                    @foreach ($formData_docu_rec as $docu)
                                                                        <option value="{{ $docu->docu_id }}">
                                                                            {{ $docu->docu_name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="fv-row w-25">
                                                                <select class="form-select " data-placeholder="Type"
                                                                    data-dropdown-parent="#kt_form_add_student_profile"
                                                                    data-allow-clear="true"
                                                                    data-name="documents.type">
                                                                    <option></option>
                                                                </select>
                                                            </div>

                                                            <div class="fv-row">
                                                                <input placeholder="Date Submitted"
                                                                    class="form-control "
                                                                    data-name="documents.date_submitted">
                                                            </div>

                                                            <div>
                                                                <button
                                                                    kt_form_add_student_profile_document_records_removeBtn
                                                                    type="button"
                                                                    class="btn btn-icon btn-secondary btn-sm"><i
                                                                        class="fas fa-minus"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="tab-pane fade" id="kt_student_view_school_tertiary"
                                                    role="tabpanel">

                                                    <div id="kt_form_add_student_profile_document_exit_list"
                                                        class="px-8" style="display:none !important">

                                                        <div class="d-flex justify-content-between">

                                                            <div class="fv-row w-25">
                                                                <select class="form-select " data-control="select2"
                                                                    data-placeholder="Document"
                                                                    data-dropdown-parent="#kt_form_add_student_profile"
                                                                    data-allow-clear="true"
                                                                    name="documents[exit][0][docu]">
                                                                    <option></option>
                                                                    @foreach ($formData_docu_ext as $docu)
                                                                        <option value="{{ $docu->docu_id }}">
                                                                            {{ $docu->docu_name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="fv-row w-25">
                                                                <select class="form-select " data-control="select2"
                                                                    data-placeholder="Type"
                                                                    data-dropdown-parent="#kt_form_add_student_profile"
                                                                    data-allow-clear="true"
                                                                    name="documents[exit][0][type]">
                                                                    <option></option>

                                                                </select>
                                                            </div>

                                                            <div class="fv-row">
                                                                <input placeholder="Date Submitted"
                                                                    class="form-control "
                                                                    name="documents[exit][0][date_submitted]">
                                                            </div>

                                                            <div>
                                                                <button kt_form_add_student_profile_document_exit_addBtn
                                                                    type="button"
                                                                    class="btn btn-icon btn-dark btn-sm"><i
                                                                        class="fas fa-plus"></i></button>
                                                            </div>
                                                        </div>

                                                        <div id="kt_form_add_student_profile_document_exit_template"
                                                            class="d-flex justify-content-between mt-5"
                                                            style="display:none !important;">

                                                            <div class="fv-row w-25">
                                                                <select class="form-select "
                                                                    data-placeholder="Document"
                                                                    data-dropdown-parent="#kt_form_add_student_profile"
                                                                    data-allow-clear="true"
                                                                    data-name="documents.docu">
                                                                    <option></option>
                                                                    @foreach ($formData_docu_ext as $docu)
                                                                        <option value="{{ $docu->docu_id }}">
                                                                            {{ $docu->docu_name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="fv-row w-25">
                                                                <select class="form-select " data-placeholder="Type"
                                                                    data-dropdown-parent="#kt_form_add_student_profile"
                                                                    data-allow-clear="true"
                                                                    data-name="documents.type">
                                                                    <option></option>
                                                                </select>
                                                            </div>

                                                            <div class="fv-row">
                                                                <input placeholder="Date Submitted"
                                                                    class="form-control "
                                                                    data-name="documents.date_submitted">
                                                            </div>

                                                            <div>
                                                                <button
                                                                    kt_form_add_student_profile_document_exit_removeBtn
                                                                    type="button"
                                                                    class="btn btn-icon btn-secondary btn-sm"><i
                                                                        class="fas fa-minus"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="kt_form_add_student_profile_document_exit_na"
                                                        class="d-flex flex-center py-10" style="">
                                                        <p class="mb-0 mt-3 fs-5">This is applicable for
                                                            <b>Graduated</b>
                                                            and
                                                            <b>Honorable Dismissed</b> academic status only
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ url('/student/profile') }}" class="btn btn-light me-5">Cancel</a>
                                <button type="button" id="kt_form_add_student_profile_save_view"
                                    class="btn btn-primary me-2">
                                    <span class="indicator-label">Save Changes and View</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <button type="button" id="kt_form_add_student_profile_save" class="btn btn-success">
                                    <span class="indicator-label"><i class="fas fa-save me-1"></i> Save Changes</span>
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

                // Form logics
                let fv = init_formValidation("kt_form_add_student_profile", {
                    'recordType': {
                        validators: {
                            notEmpty: {
                                message: 'Record type is required'
                            },
                        },
                    },
                    'studentNo': {
                        validators: {
                            notEmpty: {
                                message: 'Student number is required'
                            },
                            remote: {
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    studentId: "{{ $stud_profile->stud_id }}"
                                },
                                message: 'Student number is already in use',
                                method: 'POST',
                                url: '{{ url('/ajax/student/profile/validate-studentNo') }}',
                            },
                        },
                    },
                    'firstName': {
                        validators: {
                            notEmpty: {
                                message: 'First name is required'
                            },
                        },
                    },
                    'lastName': {
                        validators: {
                            notEmpty: {
                                message: 'Last name is required'
                            },
                        },
                    },
                    'course': {
                        validators: {
                            notEmpty: {
                                message: 'Course is required'
                            },
                        },
                    },
                    'admissionType': {
                        validators: {
                            notEmpty: {
                                message: 'Admission type is required'
                            },
                        },
                    },
                    'yearOfAdmission': {
                        validators: {
                            notEmpty: {
                                message: 'Year of admission is required'
                            },
                        },
                    },
                    'academicStatus': {
                        validators: {
                            notEmpty: {
                                message: 'Academic status is required'
                            },
                        },
                    },
                    'dateExited': {
                        validators: {
                            notEmpty: {
                                message: 'Date is required',
                            },
                            date: {
                                format: 'MM/DD/YYYY',
                                message: 'The value is not a valid date or doesn\'t have a valid format (MM/DD/YYYY)',
                            },
                        }
                    },
                    "isHonorableDismissed": {
                        validators: {
                            notEmpty: {
                                enabled: false,
                                message: 'This is required',
                            },
                        },
                    },
                    "honorableDismissedStatus": {
                        validators: {
                            notEmpty: {
                                enabled: false,
                                message: 'Honorable Dissmisal Status is required',
                            },
                        },
                    },
                    "honorableDismissedDate": {
                        validators: {
                            notEmpty: {
                                enabled: false,
                                message: 'Date issued is required',
                            },
                            date: {
                                format: 'MM/DD/YYYY',
                                message: 'The value is not a valid date or doesn\'t have a valid format (MM/DD/YYYY)',
                            },
                        },
                    },
                    "honorableDismissedSchool": {
                        validators: {
                            notEmpty: {
                                enabled: false,
                                message: 'School name and address is required',
                            },
                        },
                    },
                    "college[0][name]": {
                        validators: {
                            notEmpty: {
                                enabled: false,
                                message: 'College name is required',
                            },
                        },
                    },
                    "college[0][yearExited]": {
                        validators: {
                            notEmpty: {
                                enabled: false,
                                message: 'College year exited is required',
                            },
                        },
                    },
                });

                const collegeNameValidators = {
                    validators: {
                        notEmpty: {
                            message: 'College name is required',
                        },
                    },
                }

                const collegeYearExitedValidators = {
                    validators: {
                        notEmpty: {
                            message: 'College year exited is required',
                        },
                    },
                }

                const resetPrevCollegeData = function() {

                    prevCollegeRows = $("#kt_form_add_student_profile_prev_college_table").find(
                        'div[data-row-index]');

                    if (prevCollegeRows.length >= 1) {
                        for (let index = 0; index < prevCollegeRows.length; index++) {

                            let rowIndex = $(prevCollegeRows[index]).attr('data-row-index');
                            removeCollege(rowIndex);
                        }
                    }

                    fv.disableValidator("college[0][name]")
                        .disableValidator("college[0][yearExited]");

                    $("#kt_form_add_student_profile_prev_college_table input").val(
                        "");
                    $("#kt_form_add_student_profile_prev_college_table input").val("");
                }

                const resetAcadStatusData = function() {
                    const dateExitedElem = $("#kt_form_add_student_profile [name='dateExited']").parent();
                    const honorElem = $("#kt_form_add_student_profile [name='honor']").parent();

                    $(dateExitedElem).attr("style", "display:none !important");
                    $(honorElem).attr("style", "display:none !important");

                }

                const showAcadStatusData = function(acadStatus) {

                    // Academic Status
                    const dateExitedElem = $("#kt_form_add_student_profile [name='dateExited']").parent();
                    const honorElem = $("#kt_form_add_student_profile [name='honor']").parent();

                    /// Reset Academic Status Form
                    fv.disableValidator("dateExited");

                    // Envelope Documents
                    const exitDocList = $("#kt_form_add_student_profile_document_exit_list");
                    const exitDocNA = $("#kt_form_add_student_profile_document_exit_na");

                    /// Reset visibility of Envelop Documents - Exit
                    $(exitDocList).attr("style", "display: none !important");
                    $(exitDocNA).attr("style", "");


                    /// Academic Status
                    if (acadStatus == "GRD") {

                        fv.enableValidator("dateExited");

                        $(exitDocList).attr("style", "");
                        $(exitDocNA).attr("style", "display: none !important");

                        $(dateExitedElem).attr("style", "");
                        $(honorElem).attr("style", "");
                    } else if (acadStatus == "DIS") {

                        $(exitDocList).attr("style", "");
                        $(exitDocNA).attr("style", "display: none !important");

                    }
                }

                const showHonorDissmisData = function(acadStatus) {

                    const honorableDissmisalForm = $("#kt_form_add_student_profile_honorableDissmissed");
                    const isDismissed = $(honorableDissmisalForm).find("[name='isHonorableDismissed']")
                        .parent();
                    const status = $(honorableDissmisalForm).find("[name='honorableDismissedStatus']").parent();
                    const dateIssued = $(honorableDissmisalForm).find("[name='honorableDismissedDate']")
                        .parent();
                    const school = $(honorableDissmisalForm).find("[name='honorableDismissedSchool']").parent();

                    // Reset Form and Visibility
                    fv.disableValidator("isHonorableDismissed")
                        .disableValidator("honorableDismissedStatus")
                        .disableValidator("honorableDismissedDate")
                        .disableValidator("honorableDismissedSchool");

                    $(honorableDissmisalForm).attr("style", "display: none !important");
                    $(honorableDissmisalForm).find(".fv-row").attr("style", "display: none !important");
                    $(honorableDissmisalForm).find("select.form-select").val("").trigger(
                        "change");

                    if (acadStatus == "GRD") {

                        fv.enableValidator("isHonorableDismissed");
                        $(honorableDissmisalForm).attr("style", "");
                        $(isDismissed).attr("style", "");

                        $($(isDismissed).find("[name='isHonorableDismissed']")).on("change", function() {

                            // Reset Form
                            fv.disableValidator("honorableDismissedStatus")
                                .disableValidator("honorableDismissedDate")
                                .disableValidator("honorableDismissedSchool");

                            $(status).attr("style", "display: none !important");
                            $(dateIssued).attr("style", "display: none !important");
                            $(school).attr("style", "display: none !important");
                            $($(status).find("[name='honorableDismissedStatus']")).val("").trigger(
                                "change");
                            let v = $(this).val();

                            if (v == "1") {

                                fv.enableValidator("honorableDismissedStatus");
                                $(status).attr("style", "");

                                $($(status).find("[name='honorableDismissedStatus']")).on("change",
                                    function() {

                                        let sv = $(this).val();

                                        if (sv == "ISSUED") {
                                            fv.enableValidator("honorableDismissedDate");
                                            $(dateIssued).attr("style", "");
                                            $(school).attr("style", "display: none !important");

                                        } else if (sv == "GRANTED") {
                                            fv.enableValidator("honorableDismissedDate")
                                                .enableValidator("honorableDismissedSchool");

                                            $(dateIssued).attr("style", "");
                                            $(school).attr("style", "");
                                        }

                                    });

                            }
                        });
                    } else if (acadStatus == "DIS") {

                        fv.enableValidator("isHonorableDismissed");
                        $(honorableDissmisalForm).attr("style", "");
                        $($(isDismissed).find("[name='isHonorableDismissed']")).val("1").trigger("change");

                        fv.enableValidator("honorableDismissedStatus");
                        $(status).attr("style", "");

                        $($(status).find("[name='honorableDismissedStatus']")).on("change",
                            function() {

                                let sv = $(this).val();

                                if (sv == "ISSUED") {
                                    fv.enableValidator("honorableDismissedDate");
                                    $(dateIssued).attr("style", "");
                                    $(school).attr("style", "display: none !important");

                                } else if (sv == "GRANTED") {
                                    fv.enableValidator("honorableDismissedDate")
                                        .enableValidator("honorableDismissedSchool");

                                    $(dateIssued).attr("style", "");
                                    $(school).attr("style", "");
                                }

                            });

                    }
                }
                const resetFormData = function() {

                    $('#kt_form_add_student_profile select.form-select').val('').trigger(
                        'change');
                    $('#kt_form_add_student_profile input.form-control').val('');

                }

                const retrieveFormData = function() {

                    return $("#kt_form_add_student_profile").serialize();
                }

                $("#kt_form_add_student_profile_clearAllFields").on("click", function() {

                    resetFormData();
                });

                $("#kt_form_add_student_profile_save").on("click", function() {

                    const add_submitBtnId = "kt_form_add_student_profile_save";

                    fv.validate().then(function(e) {

                        if ('Valid' == e) {

                            trigger_btnIndicator(add_submitBtnId, "loading");
                            axios({
                                method: "POST",
                                url: "{{ url('/student/profile/edit') }}",
                                data: retrieveFormData(),
                                timeout: "{{ $axios_timeout }}"
                            }).then(function(respond) {

                                trigger_btnIndicator(add_submitBtnId, "default");
                                if (respond.status == 200) {

                                    display_axios_success(respond.data.message);

                                    setInterval(() => {
                                        window.location =
                                            "{{ route('students.index') }}";
                                    }, 1500);
                                } else {

                                    display_modal_error(
                                        "{{ __('modal.error') }}");
                                }

                                resetFormData();

                            }).catch(function(error) {

                                display_axios_error(error);
                            });
                        } else {
                            display_modal_error("{{ __('modal.error_validation') }}");
                        }
                    });
                });

                $("#kt_form_add_student_profile_save_view").on("click", function() {

                    const add_submitBtnId = "kt_form_add_student_profile_save_view";

                    fv.validate().then(function(e) {

                        if ('Valid' == e) {

                            trigger_btnIndicator(add_submitBtnId, "loading");
                            axios({
                                method: "POST",
                                url: "{{ url('/student/profile/edit') }}",
                                data: retrieveFormData(),
                                timeout: "{{ $axios_timeout }}"
                            }).then(function(respond) {

                                trigger_btnIndicator(add_submitBtnId, "default");
                                if (respond.status == 200) {

                                    display_axios_success(respond.data.message);

                                    setInterval(() => {
                                        window.location = (
                                            "{{ route('students.show', ['student' => ':student']) }}"
                                        ).replace(':student', respond.data.id);
                                    }, 1500);
                                } else {

                                    display_modal_error(
                                        "{{ __('modal.error') }}");
                                }

                                resetFormData();

                            }).catch(function(error) {

                                display_axios_error(error);
                            });
                        } else {
                            display_modal_error("{{ __('modal.error_validation') }}");
                        }
                    });
                });

                // Initialize Form fields

                const collegeTemplate = $("#kt_form_add_student_profile_prev_college_template");
                let rowIndex = 0;

                const removeCollege = function(rowIndex) {

                    fv.removeField("college[" + rowIndex + "][name]")
                        .removeField("college[" + rowIndex + "][yearExited]");

                    $("#kt_form_add_student_profile_prev_college_table").find(
                        'div[data-row-index="' + rowIndex + '"]').remove();
                }

                const createCollegeRow = function(rowIndex, value) {

                    const clone = collegeTemplate.clone(true);
                    clone.removeAttr("id");

                    clone.attr("style", "");
                    clone.attr("data-row-index", rowIndex);

                    clone.insertBefore(collegeTemplate);

                    $(clone).find('[data-name="college.name"]').attr("name", "college[" + rowIndex +
                        "][name]");
                    $(clone).find('[data-name="college.yearExited"]').attr("name", "college[" +
                        rowIndex + "][yearExited]");

                    if (value) {

                        $(clone).find('[data-name="college.name"]').val(value.extsch_name);
                        $(clone).find('[data-name="college.yearExited"]').val(value.extsch_yearExit);
                    }

                    fv.addField("college[" + rowIndex + "][name]", collegeNameValidators)
                        .addField("college[" + rowIndex + "][yearExited]",
                            collegeYearExitedValidators);

                    const removeBtn = clone.find(
                        'button[kt_form_add_student_profile_prev_college_removeBtn]');
                    removeBtn.attr('data-row-index', rowIndex);

                    $(removeBtn).on('click', function(e) {

                        const index = $(this).attr('data-row-index');
                        removeCollege(index);
                    })

                }

                $("#kt_form_add_student_profile button[kt_form_add_student_profile_prev_college_addBtn]")
                    .on(
                        "click",
                        function() {

                            rowIndex++;

                            const clone = collegeTemplate.clone(true);
                            clone.removeAttr("id");

                            clone.attr("style", "");
                            clone.attr("data-row-index", rowIndex);

                            clone.insertBefore(collegeTemplate);

                            $(clone).find('[data-name="college.name"]').attr("name", "college[" + rowIndex +
                                "][name]");
                            $(clone).find('[data-name="college.yearExited"]').attr("name", "college[" +
                                rowIndex + "][yearExited]");

                            fv.addField("college[" + rowIndex + "][name]", collegeNameValidators)
                                .addField("college[" + rowIndex + "][yearExited]",
                                    collegeYearExitedValidators);

                            const removeBtn = clone.find(
                                'button[kt_form_add_student_profile_prev_college_removeBtn]');
                            removeBtn.attr('data-row-index', rowIndex);

                            $(removeBtn).on('click', function(e) {

                                const index = $(this).attr('data-row-index');
                                removeCollege(index);
                            })

                            Inputmask({
                                "mask": "9999"
                            }).mask("#kt_form_add_student_profile [name='college[" + rowIndex +
                                "][yearExited]']");
                        });




                @if ($stud_profile->stud_admissionType == 'TRANSFEREE' || $stud_profile->stud_admissionType == 'LADDERIZED')

                    axios({
                        method: "POST",
                        url: "{{ url('/student/profile/retrieve-prev-college') }}",
                        data: {
                            id: "{{ $stud_profile->stud_id_md5 }}"
                        },
                        timeout: "{{ $axios_timeout }}"
                    }).then(function(respond) {

                        if (respond.status == 200) {

                            let pc = respond.data;

                            if (pc.length >= 1) {

                                $(formElement).find("[name='college[0][name]']").val(pc[0]['extsch_name']);
                                $(formElement).find("[name='college[0][yearExited]']").val(pc[0][
                                    'extsch_yearExit'
                                ]);

                                pc.shift();
                                pc.forEach(e => {

                                    rowIndex++;
                                    createCollegeRow(rowIndex, e);
                                });

                            }

                        } else {

                            display_modal_error("{{ __('modal.error') }}");
                        }

                    }).catch(function(error) {

                        display_axios_error(error);
                    });
                @endif

                var city = new City();

                city.getProvinces().forEach((e) => {
                    $(`<option value="${e}">${e.toUpperCase()}</option>`).appendTo(
                        "#kt_form_add_student_profile [name='addressProvince']");
                });

                $("#kt_form_add_student_profile [name='addressProvince']").on("change", function() {

                    if ($(this).val()) {
                        var c = new City();

                        $("#kt_form_add_student_profile [name='addressCity']").empty();

                        c.getCities($("#kt_form_add_student_profile [name='addressProvince']")
                                .val())
                            .forEach((e) => {
                                $(`<option value="${e}">${e.toUpperCase()}</option>`).appendTo(
                                    "#kt_form_add_student_profile [name='addressCity']");
                            });
                    }
                });

                $("#kt_form_add_student_profile [name='admissionType']").on("change", function() {

                    const admissionType = $(this).val();

                    if (admissionType == "FRESHMEN" || admissionType == "") {

                        resetPrevCollegeData();

                        $("#kt_form_add_student_profile_prev_college").attr("style",
                            "display: none !important");
                    } else if (admissionType == "TRANSFEREE" || admissionType == "LADDERIZED") {

                        fv.enableValidator("college[0][name]")
                            .enableValidator("college[0][yearExited]");

                        $("#kt_form_add_student_profile_prev_college").attr("style", "");
                    }

                });

                $("#kt_form_add_student_profile [name='academicStatus']").on("change", function() {

                    resetAcadStatusData();
                    showAcadStatusData($(this).val());
                    showHonorDissmisData($(this).val());
                });

                Inputmask({
                    "mask": "99/99/9999"
                }).mask("#kt_form_add_student_profile [name='dateExited']");

                Inputmask({
                    "mask": "99/99/9999"
                }).mask("#kt_form_add_student_profile [name='honorableDismissedDate']");

                Inputmask({
                    "mask": "9999"
                }).mask("#kt_form_add_student_profile [name='college[0][yearExited]']");

                Inputmask({
                    "mask": "9999"
                }).mask("#kt_form_add_student_profile [name='hs_yearGraduated']");

                Inputmask({
                    "mask": "9999"
                }).mask("#kt_form_add_student_profile [name='es_yearGraduated']");

                // Documents
                let ent_rowIndex = 0;
                let rec_rowIndex = 0;
                let ext_rowIndex = 0;

                const removeDocumentRow = function(rowIndex, docuCategory) {

                    $("#kt_form_add_student_profile_document_" + docuCategory + "_list").find(
                        'div[data-row-index="' + rowIndex + '"]').remove();
                }

                const initDocumentRow = function(rowIndex, docuCategory) {

                    $("#kt_form_add_student_profile_document_" + docuCategory +
                            "_list [name='documents[" + docuCategory + "][" + rowIndex + "][docu]']")
                        .select2();

                    $("#kt_form_add_student_profile_document_" + docuCategory +
                            "_list [name='documents[" + docuCategory + "][" + rowIndex + "][type]']")
                        .select2();

                    Inputmask({
                        "mask": "99/99/9999"
                    }).mask("#kt_form_add_student_profile_document_" + docuCategory +
                        "_list [name='documents[" + docuCategory + "][" + rowIndex +
                        "][date_submitted]']");

                    $("#kt_form_add_student_profile_document_" + docuCategory +
                            "_list [name='documents[" + docuCategory + "][" + rowIndex + "][docu]']")
                        .on(
                            "change",
                            function() {

                                const docuTypesElement = $(
                                    "#kt_form_add_student_profile_document_" + docuCategory +
                                    "_list [name='documents[" + docuCategory + "][" + rowIndex +
                                    "][type]"
                                );

                                id = $(this).val();
                                type = $(docuTypesElement).val();

                                if (type) {
                                    $(docuTypesElement).empty().append(
                                        '<option></option><option value="' +
                                        type + '">' + type + '</option>');
                                } else {
                                    $(docuTypesElement).empty().append('<option></option>');
                                }


                                if (id || id != "") {
                                    axios({
                                        method: "POST",
                                        url: "{{ url('/documents/document-types') }}",
                                        data: {
                                            id
                                        },
                                        timeout: "{{ $axios_timeout }}"
                                    }).then(function(respond) {

                                        if (respond.status == 200) {

                                            let docuTypes = respond.data;

                                            docuTypes.forEach(type => {

                                                $(docuTypesElement).append(
                                                    '<option value="' +
                                                    type
                                                    .docuType_name + '">' + type
                                                    .docuType_name +
                                                    '</option>');
                                            });
                                        } else {

                                            display_modal_error("{{ __('modal.error') }}");
                                        }

                                    }).catch(function(error) {

                                        display_axios_error(error);
                                    });
                                }
                            });
                }

                const createDocumentRow = function(rowIndex, docuCategory, value) {

                    const template = $("#kt_form_add_student_profile_document_" + docuCategory +
                        "_template");
                    const clone = template.clone(true);
                    clone.removeAttr("id");

                    clone.attr("style", "");
                    clone.attr("data-row-index", rowIndex);

                    clone.insertBefore(template);

                    $(clone).find('[data-name="documents.docu"]').attr("name", "documents[" +
                        docuCategory +
                        "][" +
                        rowIndex +
                        "][docu]");
                    $(clone).find('[data-name="documents.type"]').attr("name", "documents[" +
                        docuCategory +
                        "][" +
                        rowIndex + "][type]");
                    $(clone).find('[data-name="documents.date_submitted"]').attr("name",
                        "documents[" + docuCategory + "][" +
                        rowIndex + "][date_submitted]");


                    if (value.subm_document) {

                        $(clone).find('[data-name="documents.docu"]')
                            .append(
                                '<option value="' + value
                                .subm_document + '" selected="selected">' + value.docu_name +
                                '</option>');
                    }

                    if (value.subm_documentType) {

                        $(clone).find('[data-name="documents.type"]')
                            .append(
                                '<option value="' + value.subm_documentType + '" selected="selected">' +
                                value.subm_documentType + ' </option>');
                    }

                    if (value.subm_dateSubmitted) {
                        console.log(value.date_submitted);

                        $(clone).find('[data-name="documents.date_submitted"]')
                            .val(moment(value.subm_dateSubmitted).format('L'));
                    }

                    const removeBtn = clone.find(
                        'button[kt_form_add_student_profile_document_' + docuCategory +
                        '_removeBtn]');
                    removeBtn.attr('data-row-index', rowIndex);

                    $(removeBtn).on('click', function(e) {

                        const index = $(this).attr('data-row-index');
                        removeDocumentRow(index, docuCategory);
                    });

                    initDocumentRow(rowIndex, docuCategory);
                }

                $("#kt_form_add_student_profile_document_entrance_list button[kt_form_add_student_profile_document_entrance_addBtn]")
                    .on(
                        "click",
                        function() {

                            ent_rowIndex++;

                            const template = $("#kt_form_add_student_profile_document_entrance_template");
                            const clone = template.clone(true);
                            clone.removeAttr("id");

                            clone.attr("style", "");
                            clone.attr("data-row-index", ent_rowIndex);

                            clone.insertBefore(template);

                            $(clone).find('[data-name="documents.docu"]').attr("name",
                                "documents[entrance][" +
                                ent_rowIndex +
                                "][docu]");
                            $(clone).find('[data-name="documents.type"]').attr("name",
                                "documents[entrance][" +
                                ent_rowIndex + "][type]");
                            $(clone).find('[data-name="documents.date_submitted"]').attr("name",
                                "documents[entrance][" +
                                ent_rowIndex + "][date_submitted]");

                            const removeBtn = clone.find(
                                'button[kt_form_add_student_profile_document_entrance_removeBtn]');
                            removeBtn.attr('data-row-index', ent_rowIndex);

                            $(removeBtn).on('click', function(e) {

                                const index = $(this).attr('data-row-index');
                                removeDocumentRow(index, "entrance");
                            });

                            initDocumentRow(ent_rowIndex, "entrance");
                        });

                $("#kt_form_add_student_profile_document_records_list button[kt_form_add_student_profile_document_records_addBtn]")
                    .on(
                        "click",
                        function() {

                            rec_rowIndex++;

                            const template = $("#kt_form_add_student_profile_document_records_template");
                            const clone = template.clone(true);
                            clone.removeAttr("id");

                            clone.attr("style", "");
                            clone.attr("data-row-index", rec_rowIndex);

                            clone.insertBefore(template);

                            $(clone).find('[data-name="documents.docu"]').attr("name",
                                "documents[records][" +
                                rec_rowIndex +
                                "][docu]");
                            $(clone).find('[data-name="documents.type"]').attr("name",
                                "documents[records][" +
                                rec_rowIndex + "][type]");
                            $(clone).find('[data-name="documents.date_submitted"]').attr("name",
                                "documents[records][" +
                                rec_rowIndex + "][date_submitted]");

                            const removeBtn = clone.find(
                                'button[kt_form_add_student_profile_document_records_removeBtn]');
                            removeBtn.attr('data-row-index', rec_rowIndex);

                            $(removeBtn).on('click', function(e) {

                                const index = $(this).attr('data-row-index');
                                removeDocumentRow(index, "records");
                            });

                            initDocumentRow(rec_rowIndex, "records");
                        });

                $("#kt_form_add_student_profile_document_exit_list button[kt_form_add_student_profile_document_exit_addBtn]")
                    .on(
                        "click",
                        function() {

                            ext_rowIndex++;

                            const template = $("#kt_form_add_student_profile_document_exit_template");
                            const clone = template.clone(true);
                            clone.removeAttr("id");

                            clone.attr("style", "");
                            clone.attr("data-row-index", ext_rowIndex);

                            clone.insertBefore(template);

                            $(clone).find('[data-name="documents.docu"]').attr("name", "documents[exit][" +
                                ext_rowIndex +
                                "][docu]");
                            $(clone).find('[data-name="documents.type"]').attr("name", "documents[exit][" +
                                ext_rowIndex + "][type]");
                            $(clone).find('[data-name="documents.date_submitted"]').attr("name",
                                "documents[exit][" +
                                ext_rowIndex + "][date_submitted]");

                            const removeBtn = clone.find(
                                'button[kt_form_add_student_profile_document_exit_removeBtn]');
                            removeBtn.attr('data-row-index', ext_rowIndex);

                            $(removeBtn).on('click', function(e) {

                                const index = $(this).attr('data-row-index');
                                removeDocumentRow(index, "exit");
                            });

                            initDocumentRow(ext_rowIndex, "exit");
                        });

                // Fixed Documents

                let f_rec_rowIndex = 0;

                const removeDocumentFixRow = function(rowIndex, docuCategory, fixDocu) {

                    $("#kt_form_add_student_profile_document_" + docuCategory + "_" + fixDocu + "_list")
                        .find(
                            'div[data-row-index="' + rowIndex + '"]').remove();
                }

                const initDocumentFixRow = function(rowIndex, docuCategory, fixDocu) {

                    if (docuCategory == "records") {

                        if (fixDocu == "regcert") {

                            $("#kt_form_add_student_profile_document_" + docuCategory + "_" + fixDocu +
                                "_list [name='documents_fix[" + docuCategory + "][" + fixDocu +
                                "][" +
                                rowIndex +
                                "][sy]']").select2();

                            $("#kt_form_add_student_profile_document_" + docuCategory + "_" + fixDocu +
                                "_list [name='documents_fix[" + docuCategory + "][" + fixDocu +
                                "][" +
                                rowIndex +
                                "][sem]']").select2();

                            $("#kt_form_add_student_profile_document_" + docuCategory + "_" + fixDocu +
                                "_list [name='documents_fix[" + docuCategory + "][" + fixDocu +
                                "][" +
                                rowIndex +
                                "][yrlvl]']").select2();

                            Inputmask({
                                "mask": "99/99/9999"
                            }).mask("#kt_form_add_student_profile_document_" + docuCategory + "_" +
                                fixDocu +
                                "_list [name='documents_fix[" + docuCategory + "][" + fixDocu +
                                "][" +
                                rowIndex +
                                "][date_submitted]']");
                        }
                    }
                }

                const createDocumentFixRow = function(rowIndex, docuCategory, fixDocu, value) {

                    const template = $("#kt_form_add_student_profile_document_" + docuCategory + "_" +
                        fixDocu +
                        "_template");
                    const clone = template.clone(true);
                    clone.removeAttr("id");

                    clone.attr("style", "");
                    clone.attr("data-row-index", rowIndex);

                    clone.insertBefore(template);

                    if (docuCategory == "records") {

                        if (fixDocu == "regcert") {

                            $(clone).find('[data-name="documents_fix.sy"]').attr("name",
                                "documents_fix[" +
                                docuCategory +
                                "][" + fixDocu + "][" +
                                rowIndex +
                                "][sy]");
                            $(clone).find('[data-name="documents_fix.sem"]').attr("name",
                                "documents_fix[" +
                                docuCategory +
                                "][" + fixDocu + "][" +
                                rowIndex + "][sem]");
                            $(clone).find('[data-name="documents_fix.yrlvl"]').attr("name",
                                "documents_fix[" +
                                docuCategory +
                                "][" + fixDocu + "][" +
                                rowIndex + "][yrlvl]");
                            $(clone).find('[data-name="documents_fix.date_submitted"]').attr("name",
                                "documents_fix[" + docuCategory + "][" + fixDocu + "][" +
                                rowIndex + "][date_submitted]");


                            if (value.subm_documentType) {

                                $(clone).find("[data-name='documents_fix.sy']")
                                    .append(
                                        '<option value="' + value.subm_documentType +
                                        '" selected="selected">' +
                                        value.subm_documentType + ' - ' + (
                                            parseInt(value.subm_documentType) + 1) + ' </option>'
                                    );
                            }

                            if (value.subm_documentType_1) {
                                $(clone).find("[data-name='documents_fix.sem']")
                                    .append(
                                        '<option value="' + value
                                        .subm_documentType_1 +
                                        '" selected="selected">' + value
                                        .subm_documentType_1 +
                                        ' </option>');
                            }

                            if (value.subm_documentType_2) {
                                $(clone).find("[data-name='documents_fix.yrlvl']")
                                    .append(
                                        '<option value="' + value
                                        .subm_documentType_2 +
                                        '" selected="selected">' + value
                                        .subm_documentType_2 +
                                        ' </option>');
                            }

                            if (value.subm_dateSubmitted) {
                                $(clone).find(
                                        "data-[name='documents_fix.date_submitted']")
                                    .val(moment(value.subm_dateSubmitted).format('L'));
                            }
                        }
                    }

                    const removeBtn = clone.find(
                        'button[kt_form_add_student_profile_document_' + docuCategory + "_" +
                        fixDocu +
                        '_removeBtn]');
                    removeBtn.attr('data-row-index', rowIndex);

                    $(removeBtn).on('click', function(e) {

                        const index = $(this).attr('data-row-index');
                        removeDocumentFixRow(index, docuCategory, fixDocu);
                    });

                    initDocumentFixRow(rowIndex, docuCategory, fixDocu);
                }

                // Records
                // Registration Certificates
                $("#kt_form_add_student_profile_document_records_regcert_list button[kt_form_add_student_profile_document_records_regcert_addBtn]")
                    .on(
                        "click",
                        function() {

                            f_rec_rowIndex++;

                            const template = $(
                                "#kt_form_add_student_profile_document_records_regcert_template");
                            const clone = template.clone(true);
                            clone.removeAttr("id");

                            clone.attr("style", "");
                            clone.attr("data-row-index", f_rec_rowIndex);

                            clone.insertBefore(template);

                            $(clone).find('[data-name="documents_fix.sy"]').attr("name",
                                "documents_fix[records][regcert][" +
                                f_rec_rowIndex +
                                "][sy]");
                            $(clone).find('[data-name="documents_fix.sem"]').attr("name",
                                "documents_fix[records][regcert][" +
                                f_rec_rowIndex + "][sem]");
                            $(clone).find('[data-name="documents_fix.yrlvl"]').attr("name",
                                "documents_fix[records][regcert][" +
                                f_rec_rowIndex + "][yrlvl]");
                            $(clone).find('[data-name="documents_fix.date_submitted"]').attr("name",
                                "documents_fix[records][regcert][" +
                                f_rec_rowIndex + "][date_submitted]");

                            const removeBtn = clone.find(
                                'button[kt_form_add_student_profile_document_records_regcert_removeBtn]'
                            );
                            removeBtn.attr('data-row-index', f_rec_rowIndex);

                            $(removeBtn).on('click', function(e) {

                                const index = $(this).attr('data-row-index');
                                removeDocumentFixRow(index, "records", "regcert");
                            });

                            initDocumentFixRow(f_rec_rowIndex, "records", "regcert");
                        });



                // Fill
                const formElement = $("#kt_form_add_student_profile");
                $(formElement).find("[name='course']").val("{!! $stud_profile->cour_stud_id ? $stud_profile->cour_stud_id : '' !!}").trigger("change");
                $(formElement).find("[name='admissionType']").val("{!! $stud_profile->stud_admissionType ? $stud_profile->stud_admissionType : '' !!}").trigger(
                    "change");
                $(formElement).find("[name='yearOfAdmission']").val("{!! $stud_profile->stud_yearOfAdmission ? $stud_profile->stud_yearOfAdmission : '' !!}").trigger(
                    "change");
                $(formElement).find("[name='academicStatus']").val("{!! $stud_profile->stud_academicStatus ? $stud_profile->stud_academicStatus : '' !!}").trigger(
                    "change");
                $(formElement).find("[name='honor']").val("{!! $stud_profile->stud_honor ? $stud_profile->stud_honor : '' !!}").trigger("change");

                $(formElement).find("[name='recordType']").val("{!! $stud_profile->stud_recordType ? $stud_profile->stud_recordType : '' !!}").trigger(
                    "change");

                $(formElement).find("[name='addressProvince']").val("{!! $stud_profile->stud_addressProvince ? $stud_profile->stud_addressProvince : '' !!}").trigger(
                    "change");
                $(formElement).find("[name='addressCity']").val("{!! $stud_profile->stud_addressCity ? $stud_profile->stud_addressCity : '' !!}").trigger(
                    "change");
                $(formElement).find("[name='isHonorableDismissed']").val("{!! $stud_profile->stud_isHonorableDismissed ? $stud_profile->stud_isHonorableDismissed : '' !!}").trigger(
                    "change");
                $(formElement).find("[name='honorableDismissedStatus']").val("{!! $stud_profile->stud_honorableDismissedStatus ? $stud_profile->stud_honorableDismissedStatus : '' !!}").trigger(
                    "change");

                axios({
                    method: "POST",
                    url: "{{ url('/student/profile/retrieve-documents') }}",
                    data: {
                        id: "{{ $stud_profile->stud_id_md5 }}"
                    },
                    timeout: "{{ $axios_timeout }}"
                }).then(function(respond) {

                    if (respond.status == 200) {

                        let docu = respond.data.documents;
                        let docu_ent = docu.entrance;
                        let docu_rec = docu.records;
                        let docu_ext = docu.exit;


                        if (docu_ent.length >= 1) {

                            if (docu_ent[0].docu_id) {

                                $(formElement).find("[name='documents[entrance][0][docu]']").append(
                                    '<option value="' + docu_ent[0].docu_id +
                                    '" selected="selected">' +
                                    docu_ent[0].docu_name + ' </option>');
                            }

                            if (docu_ent[0].subm_documentType) {
                                $(formElement).find("[name='documents[entrance][0][type]']").append(
                                    '<option value="' + docu_ent[0].subm_documentType +
                                    '" selected="selected">' + docu_ent[0].subm_documentType +
                                    ' </option>');
                            }

                            if (docu_ent[0].subm_dateSubmitted) {
                                $(formElement).find(
                                        "[name='documents[entrance][0][date_submitted]']")
                                    .val(moment(docu_ent[0].subm_dateSubmitted).format('L'));
                            }

                            initDocumentRow(ent_rowIndex, "entrance");

                            docu_ent.shift();
                            docu_ent.forEach(d => {

                                ent_rowIndex++;
                                createDocumentRow(ent_rowIndex, "entrance", d);
                            });
                        } else {

                            initDocumentRow(ent_rowIndex, "entrance");
                        }

                        if (docu_rec.length >= 1) {

                            if (docu_rec[0].docu_id) {

                                $(formElement).find("[name='documents[records][0][docu]']").append(
                                    '<option value="' + docu_rec[0].docu_id +
                                    '" selected="selected">' +
                                    docu_rec[0].docu_name + ' </option>');
                            }

                            if (docu_rec[0].subm_documentType) {
                                $(formElement).find("[name='documents[records][0][type]']").append(
                                    '<option value="' + docu_rec[0].subm_documentType +
                                    '" selected="selected">' + docu_rec[0].subm_documentType +
                                    ' </option>');
                            }

                            if (docu_rec[0].subm_dateSubmitted) {
                                $(formElement).find(
                                        "[name='documents[records][0][date_submitted]']")
                                    .val(moment(docu_rec[0].subm_dateSubmitted).format('L'));
                            }

                            initDocumentRow(rec_rowIndex, "records");

                            docu_rec.shift();
                            docu_rec.forEach(d => {

                                rec_rowIndex++;
                                createDocumentRow(rec_rowIndex, "records", d);
                            });
                        } else {

                            initDocumentRow(rec_rowIndex, "records");
                        }

                        if (docu_ext.length >= 1) {

                            if (docu_ext[0].docu_id) {

                                $(formElement).find("[name='documents[exit][0][docu]']").append(
                                    '<option value="' + docu_ext[0].docu_id +
                                    '" selected="selected">' +
                                    docu_ext[0].docu_name + ' </option>');
                            }

                            if (docu_ext[0].subm_documentType) {
                                $(formElement).find("[name='documents[exit][0][type]']").append(
                                    '<option value="' + docu_ext[0].subm_documentType +
                                    '" selected="selected">' + docu_ext[0].subm_documentType +
                                    ' </option>');
                            }

                            if (docu_ext[0].subm_dateSubmitted) {
                                $(formElement).find("[name='documents[exit][0][date_submitted]']")
                                    .val(moment(docu_ext[0].subm_dateSubmitted).format('L'));
                            }

                            initDocumentRow(ext_rowIndex, "exit");

                            docu_ext.shift();
                            docu_ext.forEach(d => {

                                ext_rowIndex++;
                                createDocumentRow(ext_rowIndex, "exit", d);
                            });
                        } else {

                            initDocumentRow(ext_rowIndex, "exit");
                        }

                        let docu_fixed = respond.data.documents_fixed;
                        let docu_fixed_rec = docu_fixed.records;

                        let docu_fixed_rec_regicard = docu_fixed_rec.regcert;
                        if (docu_fixed_rec_regicard.length >= 1) {

                            if (docu_fixed_rec_regicard[0].subm_documentType) {

                                $(formElement).find(
                                        "[name='documents_fix[records][regcert][0][sy]']")
                                    .append(
                                        '<option value="' + docu_fixed_rec_regicard[0]
                                        .subm_documentType +
                                        '" selected="selected">' +
                                        docu_fixed_rec_regicard[0].subm_documentType + ' - ' + (
                                            parseInt(docu_fixed_rec_regicard[0].subm_documentType) +
                                            1) +
                                        ' </option>'
                                    );
                            }

                            if (docu_fixed_rec_regicard[0].subm_documentType_1) {
                                $(formElement).find(
                                        "[name='documents_fix[records][regcert][0][sem]']")
                                    .append(
                                        '<option value="' + docu_fixed_rec_regicard[0]
                                        .subm_documentType_1 +
                                        '" selected="selected">' + docu_fixed_rec_regicard[0]
                                        .subm_documentType_1 +
                                        ' </option>');
                            }

                            if (docu_fixed_rec_regicard[0].subm_documentType_2) {
                                $(formElement).find(
                                        "[name='documents_fix[records][regcert][0][yrlvl]']")
                                    .append(
                                        '<option value="' + docu_fixed_rec_regicard[0]
                                        .subm_documentType_2 +
                                        '" selected="selected">' + docu_fixed_rec_regicard[0]
                                        .subm_documentType_2 +
                                        ' </option>');
                            }


                            if (docu_fixed_rec_regicard[0].subm_dateSubmitted) {
                                $(formElement).find(
                                        "[name='documents_fix[records][regcert][0][date_submitted]']"
                                    )
                                    .val(moment(docu_fixed_rec_regicard[0].subm_dateSubmitted)
                                        .format('L'));
                            }

                            initDocumentFixRow(f_rec_rowIndex, "records", "regcert");

                            docu_fixed_rec_regicard.shift();
                            docu_fixed_rec_regicard.forEach(d => {

                                f_rec_rowIndex++;
                                createDocumentFixRow(f_rec_rowIndex, "records", "regcert",
                                    d);
                            });
                        } else {

                            initDocumentFixRow(f_rec_rowIndex, "records", "regcert")
                        }


                    } else {

                        display_modal_error("{{ __('modal.error') }}");
                    }

                }).catch(function(error) {

                    display_axios_error(error);
                });



            }));
        </script>
    </x-slot:scripts>

</x-default-layout>
