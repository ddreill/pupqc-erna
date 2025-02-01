<x-default-layout title="">

    <x-slot:title>
        @yield('user-title')
    </x-slot:title>

    <x-slot:pageTitle>
        @yield('user-page-title')
    </x-slot:pageTitle>

    <x-slot:breadcrumbs>
        @yield('user-breadcrumbs')
    </x-slot:breadcrumbs>

    <x-slot:content>
        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                <a href="#" class="card card-xl-stretch mb-5 mb-xl-8 bg-success hoverable">
                    <div class="card-body p-2">
                        <div class="d-flex">
                            <span class="d-flex align-items-center rounded p-4 me-3">
                                <i class="fa-duotone text-gray-100 fa-circle-user display-6"></i>
                            </span>
                            <div class="d-flex flex-row-fluid align-items-center flex-wrap my-lg-0 me-2">
                                <div class="flex-grow-1 my-lg-0 my-2 me-2">
                                    <span class="text-gray-100 fw-bold fs-6">{{ $onlineUsers }}</span>
                                    <span class="text-gray-100 fw-bold d-block fw-semibold ">Online
                                        {{ pluralized('user', $onlineUsers) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

                <div class="card card-xl-stretch mb-5 mb-xl-8 bg-gray-200">
                    <div class="card-header align-items-center border-0 mt-4">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="fw-bold text-dark">Two-Factor Authentication</span>
                            <span class="text-muted mt-1 fs-7">Extra layer, heightened security.</span>
                        </h3>
                    </div>
                    <div class="card-body pt-3">
                        <form method="POST" action=""
                            enctype="multipart/form-data">

                            @method('PUT')
                            @csrf
                            @if ($twoFactor == 'on')
                                <button type="submit" value="off" name="value" class="btn btn-success"><i
                                        class="fa-solid fa-lock-keyhole me-2"></i> Secured</button>
                            @else
                                <button type="submit" value="on" name="value" class="btn btn-dark"> <i
                                        class="fa-solid fa-lock-keyhole-open me-2"></i> Unsecured</button>
                            @endif
                        </form>
                    </div>
                </div>

                <div class="card card-xl-stretch mb-5 mb-xl-8 bg-gray-200">

                    <div class="card-body">
                        <div class="d-flex">
                            <span class="d-flex align-items-center rounded p-4 me-3">
                                <i class="fa-duotone fa-envelope-circle-check fs-1"></i>
                            </span>
                            <div class="d-flex flex-row-fluid align-items-center flex-wrap my-lg-0 me-2">
                                <div class="flex-grow-1 my-lg-0 my-2 me-2">
                                    <span class="text-gray-800 fw-bold fs-4">Email verification</span>
                                    <span class="text-muted d-block pt-1 fs-7 fw-semibold">Enhanced security and <br>
                                        trust.</span>
                                </div>
                                <div class="d-flex align-items-center">

                                    <form method="POST" action=""
                                        enctype="multipart/form-data">

                                        @method('PUT')
                                        @csrf
                                        @if ($emailVerified == 'on')
                                            <button type="submit" value="off" name="value"
                                                class="btn btn-icon btn-success btn-sm border-0"><i
                                                    class="fa-solid fa-shield-check fs-2"></i></button>
                                        @else
                                            <button type="submit" value="on" name="value"
                                                class="btn btn-icon btn-dark btn-sm border-0"><i
                                                    class="fa-solid fa-shield-xmark fs-2"></i></button>
                                        @endif
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-xl-stretch">
                    <div class="card-header pt-7 pb-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-800">User's Logs</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-8">as of {{ formatDatetime(now()) }}</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="#" class="btn btn-sm btn-light">Show More</a>
                        </div>
                    </div>
                    <div class="card-body pt-5">
                        @forelse ($activityLogs as $key => $activityLog)
                            @if ($key >= 1)
                                <div class="separator separator-dashed my-4"></div>
                            @endif
                            <div class="d-flex flex-stack">
                                <div class="d-flex align-items-center me-1">
                                    <div class="flex-grow-1">
                                        <span
                                            class="text-gray-800 fw-semibold d-block fs-7">{{ $activityLog->description }}</span>
                                    </div>
                                </div>
                                <span
                                    class="text-gray-800 fw-bold fs-7">{{ formatDatetime($activityLog->created_at) }}</span>
                            </div>
                        @empty
                            <p class="text-center mb-0 text-muted">
                                {{ __('global.no_available', ['attribute' => 'Logs']) }}
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="flex-lg-row-fluid ms-lg-15">
                <div class="tab-content">
                    <div class="tab-pane fade show active">
                        @yield('user-content')
                    </div>
                </div>
            </div>
        </div>
    </x-slot:content>

    <x-slot:scripts>
        @yield('user-scripts')
    </x-slot:scripts>

</x-default-layout>
