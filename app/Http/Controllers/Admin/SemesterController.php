<?php

namespace App\Http\Controllers\Admin;

use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreSemesterRequest;
use App\Http\Requests\UpdateSemesterRequest;
use Yajra\DataTables\Facades\DataTables;

class SemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('semester_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Term::select(sprintf('%s.*', (new Term)->table));

            $table = Datatables::eloquent($query);

            $table->editColumn('term_order', function ($row) {
                return $row->term_order ? $row->term_order : '';
            });

            $table->editColumn('term_name', function ($row) {
                return $row->term_name ? $row->term_name : '';
            });

            $table->addColumn('actions', function ($row) {
                $editGate      = 'semester_edit';
                $deleteGate    = 'semester_delete';

                $crudRoutePart = 'settings.semesters';
                $primaryKey = 'term_id';
                $resource = 'semester';

                return view('partials.dataTables.actionBtns', compact(
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'primaryKey',
                    'resource',
                    'row'
                ));
            });

            $table->rawColumns(['actions']);

            return $table->make(true);
        }

        addJavascriptFile(asset('assets/js/datatables.js'));
        return view('admin.semesters.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('semester_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.semesters.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSemesterRequest $request)
    {
        $term = Term::create($request->all());
        session()->flash('message', __('global.create_success', ["attribute" => sprintf("<b>%s %s</b>", __('global.new'), __('cruds.semester.title_singular'))]));

        return redirect()->route('settings.semesters.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Term $semester)
    {
        abort_if(Gate::denies('semester_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.semesters.edit', compact('semester'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSemesterRequest $request, Term $semester)
    {
        $semester->update($request->all());

        session()->flash('message', __('global.update_success', ["attribute" => sprintf("<b>%s</b>", __('cruds.semester.title_singular'))]));

        return redirect()->route('settings.semesters.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Term $semester)
    {
        abort_if(Gate::denies('semester_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $semester->delete();

        session()->flash('info', __('global.delete_success', ["attribute" => sprintf("<b>%s</b>", __('cruds.semester.title_singular'))]));

        return back();
    }

    public function ajax_select2_search(Request $request)
    {

        if ($request->ajax()) {

            $term = trim($request->term);
            $terms = Term::select(
                DB::raw('term_id as id'),
                DB::raw('term_name as text'),
            )->where('term_name', 'LIKE',  '%' . $term . '%')
                ->orderBy('term_order', 'asc')
                ->simplePaginate(10);

            $morePages = true;

            if (empty($terms->nextPageUrl())) {
                $morePages = false;
            }

            $results = array(
                "results" => $terms->items(),
                "pagination" => array(
                    "more" => $morePages
                )
            );

            return response()->json($results);
        }
    }
}
