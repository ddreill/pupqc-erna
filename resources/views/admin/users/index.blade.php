@extends('admin.users.partials.template')

@section('user-title')
    {{ __('global.list_of', ['attribute' => __('cruds.user.title')]) }}
@endsection

@section('user-page-title')
    {{ __('global.list_of', ['attribute' => __('cruds.user.title')]) }}
@endsection

@section('user-breadcrumbs')
    {{ Breadcrumbs::render('users') }}
@endsection

@section('user-content')
    <div class="card pt-4 mb-6 mb-xl-9">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                @include('partials.dataTables.search', [
                    'resource' => __('cruds.user.title_singular'),
                    'resourceDisplay' => __('cruds.user.title_singular'),
                ])
            </div>
            <div class="card-toolbar">
                <div class="d-flex justify-content-end">
                    @include('partials.buttons.create', [
                        'createRoute' => route('users.create'),
                        'resource' => __('cruds.user.title_singular'),
                        'resourceDisplay' => __('cruds.user.title_singular'),
                    ])
                </div>
            </div>
        </div>

        <div class="card-body py-3">

            <table id="dataTable" class="table border table-rounded table-row-bordered gy-5 gs-7">
                <thead>
                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                        <th>{{ __('cruds.user.fields.name') }}</th>
                        <th>{{ __('cruds.user.fields.email') }}</th>
                        <th>{{ __('cruds.user.fields.roles') }}</th>
                        <th>{{ __('cruds.user.fields.is_active') }}</th>
                        <th>{{ __('cruds.user.fields.last_seen') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('user-scripts')
    <script type="text/javascript">
        KTUtil.onDOMContentLoaded((function() {

            const resource = "user";

            let table = $("#dataTable").DataTable({
                buttons: $.extend(true, [], $.fn.dataTable.defaults.buttons),
                serverSide: true,
                processing: true,
                retrieve: true,
                searchDelay: 400,
                ajax: {
                    url: "{{ route('users.index') }}"
                },
                columns: [{
                        data: 'name',
                        className: "align-middle"
                    },
                    {
                        data: 'email',
                        className: "align-middle"
                    },
                    {
                        data: 'role',
                        searchable: false,
                        orderable: false,
                        className: "align-middle"
                    },
                    {
                        data: 'is_active',
                        searchable: false,
                        className: "align-middle"
                    },
                    {
                        data: 'last_seen',
                        searchable: false,
                        className: "align-middle"
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

                    initializedFormSubmitConfirmation(`[${resource}-destroy="true"]`,
                        `-${resource}-destroy`,
                        "delete", "danger", "warning");
                    initializedFormSubmitConfirmation(`[${resource}-update-status="true"]`,
                        `-${resource}-update-status`, "update", "warning", "warning");
                }
            });

            $(`#${resource}DataTableSearch`).on('keyup', function() {
                table.search($(this).val()).draw();
            });
        }));
    </script>
@endsection
