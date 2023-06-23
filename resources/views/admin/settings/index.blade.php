@extends('layouts.fluid')

@section('content')
    <div class="post d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="d-flex flex-column flex-xl-row">
                <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">

                    @include('admin._partials.settings.menu')
                </div>

                <div class="flex-lg-row-fluid ms-lg-15">
                    <div class="tab-content">

                        <div class="tab-pane fade show active">
                            <div class="card pt-4 mb-6 mb-xl-9">
                                <div class="card-header border-0">
                                    <div class="card-title">
                                        <h2>{{ __('cruds.systemSetup.title') }}</h2>
                                    </div>
                                </div>
                                <div class="card-body pt-0 pb-5">
                                    <p class="mb-7">This is a crucial aspect of SRIMS, as it involves organizing and
                                        configuring the software components necessary for the application to function
                                        optimally.</p>

                                    <ul class="nav nav-pills d-flex justify-content-around nav-pills-custom gap-3 mb-6">

                                        <li class="nav-item mb-3 me-0" role="presentation">
                                            <a class="nav-link nav-link-border-solid btn btn-outline btn-flex btn-active-color-primary flex-column flex-stack pt-9 pb-7 page-bg show"
                                                href="{{ route('settings.documents.index') }}"
                                                style="width: 133px;height: 130px" aria-selected="false">
                                                <div class="nav-icon mb-3">
                                                    <i class="fa-duotone fa-folder-open fs-1"></i>
                                                </div>

                                                <div class="">
                                                    <span
                                                        class="text-gray-800 fw-bold fs-5 d-block">{{ __('cruds.document.title') }}</span>
                                                    <span class="text-gray-400 fw-semibold fs-7">Students</span>
                                                </div>
                                            </a>
                                        </li>

                                        <li class="nav-item mb-3 me-0" role="presentation">
                                            <a class="nav-link nav-link-border-solid btn btn-outline btn-flex btn-active-color-primary flex-column flex-stack pt-9 pb-7 page-bg show"
                                                href="" style="width: 133px;height: 130px" aria-selected="false">
                                                <div class="nav-icon mb-3">
                                                    <i class="fa-duotone fa-diploma fs-1"></i>
                                                </div>

                                                <div class="">
                                                    <span class="text-gray-800 fw-bold fs-5 d-block">Courses</span>
                                                    <span class="text-gray-400 fw-semibold fs-7">Students</span>
                                                </div>
                                            </a>
                                        </li>

                                        <li class="nav-item mb-3 me-0" role="presentation">
                                            <a class="nav-link nav-link-border-solid btn btn-outline btn-flex btn-active-color-primary flex-column flex-stack pt-9 pb-7 page-bg show"
                                                href="" style="width: 133px;height: 130px" aria-selected="false">
                                                <div class="nav-icon mb-3">
                                                    <i class="fa-duotone fa-file-certificate fs-1"></i>
                                                </div>

                                                <div class="">
                                                    <span class="text-gray-800 fw-bold fs-5 d-block">Honors</span>
                                                    <span class="text-gray-400 fw-semibold fs-7">Students</span>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>

                                    <ul class="nav nav-pills d-flex justify-content-around nav-pills-custom gap-3 mb-6">
                                        <li class="nav-item mb-3 me-0" role="presentation">
                                            <a class="nav-link nav-link-border-solid btn btn-outline btn-flex btn-active-color-primary flex-column flex-stack pt-9 pb-7 page-bg show"
                                                href="" style="width: 133px;height: 130px" aria-selected="false">
                                                <div class="nav-icon mb-3">
                                                    <i class="fa-duotone fa-door-open fs-1"></i>
                                                </div>

                                                <div class="">
                                                    <span class="text-gray-800 fw-bold fs-5 d-block">Rooms</span>
                                                    <span class="text-gray-400 fw-semibold fs-7">Gradesheets</span>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item mb-3 me-0" role="presentation">
                                            <a class="nav-link nav-link-border-solid btn btn-outline btn-flex btn-active-color-primary flex-column flex-stack pt-9 pb-7 page-bg show"
                                                href="" style="width: 133px;height: 130px" aria-selected="false">
                                                <div class="nav-icon mb-3">
                                                    <i class="fa-duotone fa-book-bookmark fs-1"></i>
                                                </div>

                                                <div class="">
                                                    <span class="text-gray-800 fw-bold fs-5 d-block">Subjects</span>
                                                    <span class="text-gray-400 fw-semibold fs-7">Gradesheets</span>
                                                </div>
                                            </a>
                                        </li>

                                        <li class="nav-item mb-3 me-0" role="presentation">
                                            <a class="nav-link nav-link-border-solid btn btn-outline btn-flex btn-active-color-primary flex-column flex-stack pt-9 pb-7 page-bg show"
                                                href="" style="width: 133px;height: 130px" aria-selected="false">
                                                <div class="nav-icon mb-3">
                                                    <i class="fa-duotone fa-chalkboard-user fs-1"></i>
                                                </div>

                                                <div class="">
                                                    <span class="text-gray-800 fw-bold fs-5 d-block">Instructors</span>
                                                    <span class="text-gray-400 fw-semibold fs-7">Gradesheets</span>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection