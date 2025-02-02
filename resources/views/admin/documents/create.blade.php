@extends('admin.settings.partials.template')

@section('settings-title')
    {{ __('global.add') }} {{ __('cruds.document.title_singular') }}
@endsection

@section('settings-page-title')
    {{ __('global.add') }} {{ __('cruds.document.title_singular') }}
@endsection

@section('settings-breadcrumbs')
    {{ Breadcrumbs::render('settings.documents.create') }}
@endsection

@section('settings-content')
    <div class="card mb-6 mb-xl-9">
        <div class="card-header border-0">
            <div class="card-title">
                <h2>{{ __('global.add') }} {{ __('cruds.document.title') }}</h2>
            </div>
        </div>
        <div class="card-body pt-0 pb-5">
            <form method="POST" action="{{ route('settings.documents.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="fv-row mb-7">
                    <label class="required fs-6 fw-bold mb-2">{{ __('cruds.document.fields.docu_name') }}</label>
                    <input type="text" class="form-control {{ $errors->has('docu_name') ? 'is-invalid' : '' }}"
                        placeholder="" name="docu_name" value="{{ old('docu_name', '') }}" required />
                    @if ($errors->has('docu_name'))
                        <span class="text-danger">{{ $errors->first('docu_name') }}</span>
                    @endif
                </div>

                <div class="fv-row mb-7">
                    <label class="fs-6 fw-bold mb-2">{{ __('cruds.document.fields.docu_description') }}</label>
                    <input type="text" class="form-control {{ $errors->has('docu_description') ? 'is-invalid' : '' }}"
                        placeholder="" name="docu_description" value="{{ old('docu_description', '') }}" />
                    @if ($errors->has('docu_description'))
                        <span class="text-danger">{{ $errors->first('docu_description') }}</span>
                    @endif
                </div>

                <div class="fv-row mb-7">
                    <label class="required fs-6 fw-bold mb-2">{{ __('cruds.document.fields.docu_category') }}</label>
                    <select class="form-control {{ $errors->has('docu_category') ? 'is-invalid' : '' }}"
                        name="docu_category" required>
                        <option value="" selected disabled>{{ __('global.select') }}
                            {{ __('cruds.document.fields.docu_category') }}</option>
                        @foreach ((new App\Enums\DocumentCategoriesEnum())->getDisplayNames() as $value => $displayName)
                            <option value="{{ $value }}"
                                {{ old('docu_category', '') == $value ? 'selected' : '' }}>
                                {{ $displayName }}
                            </option>
                        @endforeach
                    </select>

                    @if ($errors->has('docu_category'))
                        <span class="text-danger">{{ $errors->first('docu_category') }}</span>
                    @endif
                </div>


                <div id="typesList" class="card card-flush h-lg-50 mb-7">

                    <div class="card-header p-0">
                        <h3 class="card-title text-gray-800">{{ __('cruds.documentType.title') }}
                        </h3>
                        <div class="card-toolbar">
                            <button type="button" id="documentTypeAddBtn" class="btn btn-sm btn-light-primary">
                                <i class="fa-solid fa-plus me-2"></i>
                                {{ __('global.add') }} {{ __('cruds.documentType.title') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if (sizeOf(old('types', [])) >= 1)
                            @foreach (old('types') as $key => $type)
                                <div class="row {{ $key >= 1 ? 'mt-4' : '' }}" types-row-index="{{ $key }}">
                                    <div class="col-sm-11">
                                        <input type="text" class="form-control" placeholder=""
                                            name="types[{{ $key }}][docuType_name]"
                                            value="{{ $type['docuType_name'] }}" required />
                                    </div>
                                    <div class="col-sm-1 align-middle">
                                        <button types-row-index="{{ $key }}" type="button"
                                            class="btn btn-secondary btn-sm btn-icon removeBtn">
                                            <i class="fa-solid fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="row" types-row-index="0">
                                <div class="col-sm-11">
                                    <input type="text" class="form-control" placeholder="" name="types[0][docuType_name]"
                                        value="" required />
                                </div>
                                <div class="col-sm-1 align-middle">
                                    <button types-row-index="0" type="button"
                                        class="btn btn-secondary btn-sm btn-icon removeBtn">
                                        <i class="fa-solid fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <div class="row mt-4" id="documentTypeTemplate" hidden>
                            <div class="col-sm-11">
                                <input type="text" class="form-control" placeholder="" input-name="types.docuType_name"
                                    value="" />
                            </div>
                            <div class="col-sm-1 align-middle">
                                <button type="button" class="btn btn-secondary btn-sm btn-icon removeBtn">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="row mt-4" id="documentTypeEmpty" hidden>
                            <div class="text-center text-gray-700 fw-semibold fs-7">
                                {{ __('global.no_added', ['attribute' => __('cruds.documentType.title')]) }}
                            </div>
                        </div>
                    </div>
                </div>


                <div class="text-end">
                    <a href="{{ route('settings.documents.index') }}"
                        class="btn btn-light me-3">{{ __('global.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">
                        <span>{{ __('global.submit') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('settings-scripts')
    <script type="text/javascript">
        KTUtil.onDOMContentLoaded((function() {

            var typeList = $(`#typesList .card-body`);
            $(typeList).find(`button.removeBtn`).on('click', function(e) {

                $(typeList).find(
                        `div[types-row-index="${$(this).attr('types-row-index')}"]`)
                    .remove();

                if ($(typeList).find(`div[types-row-index]`).length >= 1) {
                    $(`#documentTypeEmpty`).attr("hidden", true);
                } else {
                    $(`#documentTypeEmpty`).attr("hidden", false);
                }
            });

            var typesRowIndex = 0;

            $(`#documentTypeAddBtn`).on("click", function() {

                $(`#documentTypeEmpty`).attr("hidden", true);

                typesRowIndex++;

                const template = $(`#documentTypeTemplate`);
                const clone = template.clone(true);
                clone.removeAttr("id").removeAttr("hidden").attr("types-row-index", typesRowIndex);


                $(clone).find(`input[input-name="types.docuType_name"]`).attr("name",
                    `types[${typesRowIndex}][docuType_name]`).attr("required", true);

                $(clone).find('button.removeBtn').attr("types-row-index", typesRowIndex);

                clone.insertBefore(template);

            });

        }));
    </script>
@endsection
