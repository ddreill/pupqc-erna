<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Services\ConfigurationService;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\StoreUserRequest;
use Spatie\Activitylog\Models\Activity;
use App\Http\Requests\UpdateUserRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\UpdateUserStatusRequest;
use Symfony\Component\HttpFoundation\Response;


class UsersController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = User::with(['roles'])->approved();

            $table = Datatables::eloquent($query);

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->editColumn('email', function ($row) {
                if ($row->email_verified_at) {
                    return sprintf('<span>%s<i class="fa-duotone fa-badge-check ms-2 text-primary"></i></span>', $row->email);
                } else {
                    return $row->email ? $row->email : '';
                }
            });

            $table->editColumn('role', function ($row) {
                $labels = [];
                foreach ($row->roles as $role) {
                    $labels[] = sprintf('<span class="badge badge-secondary">%s</span>', $role->title);
                }

                return implode(' ', $labels);
            });

            $table->editColumn('is_active', function ($row) {
                return view('admin.users.partials.datatable-is-active', compact('row'));
            });

            $table->addColumn('actions', function ($row) {
                $editGate      = 'user_edit';
                $deleteGate    = 'user_delete';
                $crudRoutePart = 'users';

                return view('partials.dataTables.actionBtns', compact(
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('actions', function ($row) {
                $editGate = 'user_edit';
                $deleteGate = 'user_delete';

                $crudRoutePart = 'users';
                $resource = 'user';

                $viewData = [];

                if ($row->id != auth()->user()->id) {
                    $viewData = [
                        "editGate" => $editGate,
                        "deleteGate" => $deleteGate,
                        "crudRoutePart" => $crudRoutePart,
                        "resource" => $resource,
                        "row" => $row,
                    ];
                } else {
                    $viewData = [
                        "crudRoutePart" => $crudRoutePart,
                        "resource" => $resource,
                        "row" => $row,
                    ];
                }

                return view('partials.dataTables.actionBtns', $viewData);
            });

            $table->rawColumns(['actions', 'role', 'email']);

            return $table->make(true);
        }

        $twoFactorKey = 'panel.2fa';
        $twoFactor = Config::get($twoFactorKey);

        $emailVerifiedKey = 'panel.email_verified';
        $emailVerified = Config::get($emailVerifiedKey);

        $activityLogs = Activity::where('causer_type', 'App\Models\User')->orderBy('created_at', 'desc')->limit(4)->get();
        $onlineUsers = User::online(5)->count();

        addJavascriptFile(asset('assets/js/datatables.js'));
        return view('admin.users.index', compact('twoFactor', 'twoFactorKey', 'emailVerified', 'emailVerifiedKey', 'activityLogs', 'onlineUsers'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');
        $activityLogs = Activity::where('causer_type', 'App\Models\User')->orderBy('created_at', 'desc')->limit(4)->get();
        $onlineUsers = User::online(5)->count();

        $twoFactorKey = 'panel.2fa';
        $twoFactor = Config::get($twoFactorKey);

        $emailVerifiedKey = 'panel.email_verified';
        $emailVerified = Config::get($emailVerifiedKey);


        return view('admin.users.create', compact('twoFactor', 'twoFactorKey', 'emailVerified', 'emailVerifiedKey', 'roles', 'activityLogs', 'onlineUsers'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->all());
        $user->roles()->sync($request->input('roles', []));

        session()->flash('message', __('global.create_success', ["attribute" => sprintf("<b>%s %s</b>", __('global.new'), __('cruds.user.title_singular'))]));

        return redirect()->route('users.index');
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');

        $user->load('roles');
        $activityLogs = Activity::where('causer_type', 'App\Models\User')->orderBy('created_at', 'desc')->limit(4)->get();
        $onlineUsers = User::online(5)->count();

        $twoFactorKey = 'panel.2fa';
        $twoFactor = Config::get($twoFactorKey);

        $emailVerifiedKey = 'panel.email_verified';
        $emailVerified = Config::get($emailVerifiedKey);

        return view('admin.users.edit', compact('twoFactor', 'twoFactorKey', 'emailVerified', 'emailVerifiedKey', 'user', 'roles', 'activityLogs', 'onlineUsers'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
        $user->roles()->sync($request->input('roles', []));

        session()->flash('message', __('global.update_success', ["attribute" => sprintf("<b>%s</b>", __('cruds.user.title_singular'))]));

        return redirect()->route('users.index');
    }

    public function updateStatus(UpdateUserStatusRequest $request, User $user)
    {
        $user->update($request->all());

        session()->flash('message', __('global.update_success', ["attribute" => sprintf("<b>%s</b>", __('cruds.user.title_singular'))]));

        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        session()->flash('info', __('global.delete_success', ["attribute" => sprintf("<b>%s</b>", __('cruds.user.title_singular'))]));

        return back();
    }
}
