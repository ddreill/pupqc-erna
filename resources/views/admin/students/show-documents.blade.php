@extends('admin.students.partials.show-template')

@section('student-page-title', __('global.view') . ' ' . __('cruds.student.title_singular') . ' - Envelope Documents')

@section('student-styles')
    <style>
        .accordion.accordion-icon-collapse .accordion-icon .accordion-icon-off {
            display: none !important;
        }

        .accordion.accordion-icon-collapse .collapsed .accordion-icon .accordion-icon-off {
            display: inline-flex !important;
        }

        .accordion.accordion-icon-collapse .accordion-icon .accordion-icon-on {
            display: inline-flex !important;
        }

        .accordion.accordion-icon-collapse .collapsed .accordion-icon .accordion-icon-on {
            display: none !important;
        }
    </style>
@endsection

@section('student-content')
    <div class="py-3 py-lg-6 ">
        <div class="d-flex flex-stack ">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Envelope Documents
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

                @can('student_generate_document_evaluation')
                    <a href="{{ route('students.export.envelope-document-evaluation', $student->stud_id) }}" target="_blank"
                        class="btn fw-bold btn-primary">Export to PDF </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="rounded border p-10 bg-white">
        <div class="accordion accordion-icon-collapse" id="envelopeDocuments">
            <div class="mb-5">
                <div class="accordion-header py-3 d-flex" data-bs-toggle="collapse"
                    data-bs-target="#envelopeDocumentsEntrance">
                    <span class="accordion-icon">
                        <i class="fa-duotone fa-square-plus fs-3 accordion-icon-off"></i>
                        <i class="fa-duotone fa-square-minus fs-3 accordion-icon-on"></i>
                    </span>
                    <h3 class="fs-4 fw-semibold mb-0 ms-4">ENTRANCE DOCUMENTS</h3>
                </div>

                <div id="envelopeDocumentsEntrance" class="fs-6 collapse show ps-10" data-bs-parent="#envelopeDocuments">
                    @if (sizeOf($entranceDocuments = $student->getEntranceDocuments()) >= 1)
                        <ol class="ps-0 mt-3">
                            @foreach ($entranceDocuments as $document)
                                <li class="fs-6 mb-1">
                                    <span>{{ $document->docu_name }}</span>
                                    @if ($document->pivot->subm_documentType)
                                        {{ ' · ' . $document->pivot->subm_documentType }}
                                    @endif
                                    @if ($document->pivot->subm_dateSubmitted)
                                        - <em
                                            class="fs-7">{{ 'Submitted on ' . formatDate($document->pivot->subm_dateSubmitted) }}</em>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    @else
                        <p class="mb-0 mt-3 text-start">No available Entrance documents</p>
                    @endif
                </div>
            </div>
            <div class="mb-5">
                <div class="accordion-header py-3 d-flex" data-bs-toggle="collapse"
                    data-bs-target="#envelopeDocumentsRecords">
                    <span class="accordion-icon">
                        <i class="fa-duotone fa-square-plus fs-3 accordion-icon-off"></i>
                        <i class="fa-duotone fa-square-minus fs-3 accordion-icon-on"></i>
                    </span>
                    <h3 class="fs-4 fw-semibold mb-0 ms-4">RECORDS DOCUMENTS</h3>
                </div>

                <div id="envelopeDocumentsRecords" class="fs-6 collapse ps-10" data-bs-parent="#envelopeDocuments">
                    @php
                        $recordDocuments = $student->getRecordsDocuments();
                        $registrationCerts = $student->getRegistrationCerts();
                    @endphp


                    @if (sizeOf($registrationCerts) >= 1 || sizeOf($recordDocuments) >= 1)

                        @if (sizeOf($registrationCerts) >= 1)
                            <div class="my-3">
                                <h4 class="mb-3 fs-6">Registration Certificates</h4>
                                <ul>
                                    @foreach ($registrationCerts as $registrationCert)
                                        <li class="fs-6 mb-1">
                                            <span>
                                                {{ 'SY ' . $registrationCert->pivot->subm_documentType . '-\'' . substr($registrationCert->pivot->subm_documentType + 1, 2, 2) }}</span>
                                            @if ($registrationCert->pivot->subm_documentType_1)
                                                {{ '· ' . $registrationCert->pivot->subm_documentType_1 }}
                                            @endif
                                            @if ($registrationCert->pivot->subm_documentType_2)
                                                {{ '― ' . $registrationCert->pivot->subm_documentType_2 }}
                                            @endif
                                            @if ($registrationCert->pivot->subm_dateSubmitted)
                                                - <em
                                                    class="ms-1 fs-7 fw-normal">{{ 'Submitted on ' . formatDate($registrationCert->pivot->subm_dateSubmitted) }}</em>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (sizeOf($recordDocuments) >= 1)
                            <div class="pb-0 mt-5">
                                <h4 class="mb-3 fs-6">Other Documents</h4>

                                <ol class="">
                                    @foreach ($recordDocuments as $document)
                                        <li class="fs-6 mb-1">
                                            <span>{{ $document->docu_name }}</span>
                                            @if ($document->pivot->subm_documentType)
                                                {{ ' · ' . $document->pivot->subm_documentType }}
                                            @endif
                                            @if ($document->pivot->subm_dateSubmitted)
                                                - <em
                                                    class="fs-7 fw-normal">{{ 'Submitted on ' . formatDate($document->pivot->subm_dateSubmitted) }}</em>
                                            @endif
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        @endif
                    @else
                        <p class="mb-0 mt-3 text-start">No available Records documents</p>
                    @endif
                </div>
            </div>
            <div class="mb-5">
                <div class="accordion-header py-3 d-flex" data-bs-toggle="collapse" data-bs-target="#envelopeDocumentsExit">
                    <span class="accordion-icon">
                        <i class="fa-duotone fa-square-plus fs-3 accordion-icon-off"></i>
                        <i class="fa-duotone fa-square-minus fs-3 accordion-icon-on"></i>
                    </span>
                    <h3 class="fs-4 fw-semibold mb-0 ms-4">EXIT DOCUMENTS</h3>
                </div>

                <div id="envelopeDocumentsExit" class="fs-6 collapse ps-10" data-bs-parent="#envelopeDocuments">
                    @if (sizeOf($exitDocuments = $student->getExitDocuments()) >= 1)
                        <ol class="ps-0 mt-3">
                            @foreach ($exitDocuments as $document)
                                <li class="fs-6 mb-1">
                                    <span>{{ $document->docu_name }}</span>
                                    @if ($document->pivot->subm_documentType)
                                        {{ ' · ' . $document->pivot->subm_documentType }}
                                    @endif
                                    @if ($document->pivot->subm_dateSubmitted)
                                        - <em
                                            class="fs-7 fw-normal">{{ 'Submitted on ' . formatDate($document->pivot->subm_dateSubmitted) }}</em>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    @else
                        <p class="mb-0 mt-3 text-start">No available Exit documents</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
