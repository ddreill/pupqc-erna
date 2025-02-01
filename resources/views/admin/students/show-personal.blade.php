@extends('admin.students.partials.show-template')

@section('student-page-title', __('global.view') . ' ' . __('cruds.student.title_singular') . ' - Personal & Student Information')

@section('student-content')
    <div class="py-3 py-lg-6 ">
        <div class="d-flex flex-stack ">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Personal & Student Information
                </h1>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                @can('student_edit')
                    @if (!$student->archived())
                        @include('partials.buttons.edit', [
                            'editRoute' => route('students.edit', $student->stud_uuid),
                        ])
                    @endif
                @endcan
            </div>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-body fs-6">

            <div class="row mb-5">
                <div class="col-sm-4">
                    <div class="text-gray-700">Student Number</div>
                    <div class="fw-bold">{{ $student->stud_studentNo }}</div>
                </div>
                <div class="col-sm-8">
                    <div class="text-gray-700">Course</div>
                    <div class="fw-bold">{{ $student->course->cour_name }}</div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="text-gray-700">Admission type</div>
                    <div class="fw-bold">{{ $student->stud_admissionType }}</div>
                </div>
                <div class="col-sm-8">
                    <div class="text-gray-700">Year of Admission</div>
                    <div class="fw-bold">{{ $student->stud_yearOfAdmission }}</div>
                </div>
            </div>

            <h5 class="mt-10 fs-3 text-gray-800">PERSONAL INFORMATION</h5>
            <div class="separator separator-dashed mt-3 mb-7"></div>

            <div class="row">
                <div class="col-sm-5">
                    <div class="text-gray-700">Full name</div>
                    <div class="fw-bold">
                        {{ $student->full_name }}
                    </div>
                </div>
                <div class="col-sm-7">
                    <div class="text-gray-700">Permanent Address</div>
                    <div class="fw-bold">
                        {{ $student->stud_addressLine }} <br>
                        {{ $student->stud_addressCity }} <br>
                        {{ $student->stud_addressProvince }}
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="card mb-0">
        <div class="card-header border-dashed border-bottom-1 border-0 border-gray-300" style="min-height:45px">
            <div class="card-title">
                <h5 class="fs-3 text-gray-800">PREVIOUS SCHOOLS</h5>
            </div>
            <div class="card-toolbar">
                <ul class="nav nav-stretch fs-6 fw-bold nav-line-tabs nav-line-tabs-2x border-transparent text-uppercase"
                    role="tablist">

                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-active-primary active" data-bs-toggle="tab" role="tab"
                            href="#kt_student_view_school_primary"> Primary </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-active-primary" data-bs-toggle="tab" role="tab"
                            href="#kt_student_view_school_secondary"> Secondary
                        </a>
                    </li>

                    @if (
                        $student->stud_admissionType == \App\Enums\AdmissionStatusEnum::TRANSFEREE ||
                            $student->stud_admissionType == \App\Enums\AdmissionStatusEnum::LADDERIZED)
                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-active-primary" data-bs-toggle="tab" role="tab"
                                href="#kt_student_view_school_tertiary"> Tertiary
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="card-body py-10">
            <div class="tab-content" id="myTabContent">

                @foreach ($student->schools()->get() as $school)
                    <div class="tab-pane fade show active" id="kt_student_view_school_primary" role="tabpanel">
                        @if ($school->extsch_educType == \App\Enums\StudentPreviousSchoolsTypeEnum::PRIMARY)
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="text-gray-700">School</div>
                                    <div class="fw-bold">
                                        {{ $school->extsch_name }}</div>
                                </div>
                                <div class="w-25">
                                    <div class="text-gray-700">Year Graduated</div>
                                    <div class="fw-bold">
                                        {{ $school->extsch_yearExit }}</div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="tab-pane fade" id="kt_student_view_school_secondary" role="tabpanel">
                        @if ($school->extsch_educType == \App\Enums\StudentPreviousSchoolsTypeEnum::SECONDARY)
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="text-gray-700">School</div>
                                    <div class="fw-bold">
                                        {{ $school->extsch_name }}</div>
                                </div>
                                <div class="w-25">
                                    <div class="text-gray-700">Year Graduated</div>
                                    <div class="fw-bold">
                                        {{ $school->extsch_yearExit }}</div>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if (
                        ($student->stud_admissionType == 'TRANSFEREE' || $student->stud_admissionType == 'LADDERIZED') &&
                            $school->extsch_educType == \App\Enums\StudentPreviousSchoolsTypeEnum::TERTIARY)
                        <div class="tab-pane fade" id="kt_student_view_school_tertiary" role="tabpanel">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="text-gray-700">School</div>
                                    <div class="fw-bold">
                                        {{ $school->extsch_name }}</div>
                                </div>
                                <div class="w-25">
                                    <div class="text-gray-700">Year Exited</div>
                                    <div class="fw-bold">
                                        {{ $school->extsch_yearExit }}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

    </div>
@endsection
