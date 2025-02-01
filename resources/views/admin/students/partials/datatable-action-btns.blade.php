<div class="d-flex justify-content-end flex-shrink-0">

    @isset($viewGate)
        @can($viewGate)
            <a href="{{ route('students.show', $row->stud_id) }}"
                data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="tooltip-inverse"
                title="{{ __('global.view') }}" class="btn btn-icon btn-light btn-active-dark btn-sm me-1">
                <span class="svg-icon">
                    <i class="fa-solid fa-eye"></i>
                </span>
            </a>
        @endcan
    @endisset

    @isset($editGate)
        @can($editGate)
            <a href="{{ route('students.edit', $row->stud_uuid) }}"
                class="btn btn-icon btn-light-warning btn-active-warning btn-sm me-1" data-bs-toggle="tooltip"
                data-bs-placement="right" data-bs-custom-class="tooltip-inverse" title="{{ __('global.edit') }}">
                <span class="svg-icon">
                    <i class="fa-solid fa-pen-to-square"></i>
                </span>
            </a>
        @endcan
    @endisset

    @if (isset($docuEvalGate) || isset($scholasticGate))
        @if (Gate::check([$docuEvalGate]) || Gate::check([$scholasticGate]))
            <a href="#" class="btn btn-sm btn-icon btn-light btn-active-secondary me-1"
                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-bs-toggle="tooltip"
                data-bs-placement="right" data-bs-custom-class="tooltip-inverse" title="{{ __('global.export') }}">
                <span class="svg-icon">
                    <i class="fa-solid fa-file-arrow-down"></i>
                </span>
            </a>

            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-250px py-4"
                data-kt-menu="true">


                @can($docuEvalGate)
                    <div class="menu-item px-3 ">
                        <a href="{{ route('students.export.envelope-document-evaluation', $row->stud_id) }}"
                            class="menu-link px-3" target="_blank">
                            <span class="menu-icon">
                                <i class="fa-duotone fa-file-pdf fs-5"></i>
                            </span>
                            <span class="menu-title">Document Evaluation</span></a>
                    </div>
                @endcan

                @can($scholasticGate)
                    @if ($row->stud_recordType == 'NONSIS')
                        <div class="menu-item px-3 ">
                            <a href="{{ route('students.export.scholastic-data', $row->stud_id) }}"
                                class="menu-link px-3" target="_blank">
                                <span class="menu-icon">
                                    <i class="fa-duotone fa-file-pdf fs-5"></i>
                                </span>
                                <span class="menu-title">Scholastic Data</span></a>
                        </div>
                    @endif
                @endcan
            </div>
        @endif
    @endif

    @isset($archiveGate)
        @can($archiveGate)
            @php
                $formId = bin2hex(random_bytes(5));
            @endphp

            <form action="{{ route('students.archive', $row->stud_id) }}" method="POST"
                id="{{ $formId }}-{{ $resource }}-archive" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-custom-class="tooltip-inverse" title="{{ __('global.archive') }}">
                @csrf
                <button type="button" {{ $resource }}-archive="true"
                    class="btn btn-icon btn-light btn-active-dark btn-sm" data-id="{{ $formId }}">
                    <span class="svg-icon">
                        <i class="fa-solid fa-folder-arrow-down"></i>
                    </span>
                </button>
            </form>
        @endcan
    @endisset

    @isset($unarchiveGate)
        @can($unarchiveGate)
            @php
                $formId = bin2hex(random_bytes(5));
            @endphp

            <form action="{{ route('students.unarchive', $row->stud_id) }}" method="POST"
                id="{{ $formId }}-{{ $resource }}-unarchive" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-custom-class="tooltip-inverse" title="{{ __('global.unarchive') }}">
                @method('POST')
                @csrf
                <button type="button" {{ $resource }}-unarchive="true"
                    class="btn btn-icon btn-light-warning btn-active-warning btn-sm" data-id="{{ $formId }}">
                    <span class="svg-icon">
                        <i class="fa-solid fa-folder-arrow-up"></i>
                    </span>
                </button>
            </form>
        @endcan
    @endisset

</div>
