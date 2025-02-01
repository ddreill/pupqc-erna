<?php

namespace App\Http\Controllers\Admin;

use App\Models\Room;
use App\Models\Term;
use App\Models\Subject;
use App\Models\TimeSlot;
use App\Models\Gradesheet;

//Model
use App\Models\Instructor;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\GradesheetPages;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;

class GradesheetController extends Controller
{
    /*
|--------------------------------------------------------------------------
|    Begin::Views
|--------------------------------------------------------------------------
|
|    All functions that renders or display a page
|
*/

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('gradesheet_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {

            $query = Gradesheet::with(['subject', 'instructor', 'semester', 'room', 'timeSlots', 'pages'])
                ->withCount(['students'])
                ->withSum('pages', 'grdsheetpg_rowNo')
                ->when($request->has('subject') && $request->subject, function ($query) use ($request) {

                    $query->whereHas('subject', function ($query) use ($request) {
                        $query->whereIn('subj_id',  explode(",", $request->subject));
                    });
                })
                ->when($request->has('room') && $request->room, function ($query) use ($request) {

                    $query->whereHas('room', function ($query) use ($request) {
                        $query->whereIn('room_id',  explode(",", $request->room));
                    });
                })
                ->when($request->has('semester') && $request->semester, function ($query) use ($request) {

                    $query->whereHas('semester', function ($query) use ($request) {
                        $query->whereIn('term_id',  explode(",", $request->semester));
                    });
                })
                ->when($request->has('acad_year') && $request->acad_year, function ($query) use ($request) {
                    $query->whereIn('class_acadYear', explode(",", $request->acad_year));
                })
                ->when($request->has('instructor') && $request->instructor, function ($query) use ($request) {

                    $query->whereHas('instructor', function ($query) use ($request) {
                        $query->whereIn('inst_id',  explode(",", $request->instructor));
                    });
                })
                ->when($request->has('file_storge_type') && $request->file_storge_type, function ($query) use ($request) {
                    $query->whereIn('class_fstorage', explode(",", $request->file_storge_type));
                })
                ->when($request->has('total_students') && $request->total_students, function ($query) use ($request) {
                    $totalStudents = explode(",", $request->total_students);
                    $query->has('students', '>=', $totalStudents[0])->has('students', '<=', $totalStudents[1]);
                })
                ->when($request->has('total_slots') && $request->total_slots, function ($query) use ($request) {
                    $query->whereHas('pages', function ($query) use ($request) {

                        $totalSlots = explode(",", $request->total_slots);

                        $query->select(DB::raw('SUM(grdsheetpg_rowNo) as total_slots'))
                            ->havingRaw('total_slots >= ?', [$totalSlots[0]])
                            ->havingRaw('total_slots <= ?', [$totalSlots[1]]);
                    });
                })
                ->when($request->has('created_at') && $request->created_at, function ($query) use ($request) {
                    $query->whereBetween('class_createdAt', explode(",", $request->created_at));
                })
                ->when($request->has('updated_at') && $request->updated_at, function ($query) use ($request) {
                    $query->whereBetween('class_updatedAt', explode(",", $request->updated_at));
                });


            $table = Datatables::eloquent($query)
                ->addColumn('schedule', function ($row) {
                    return $row->getSchedule($row->room ? $row->room->room_name : "", $row->timeSlots);
                })
                ->addColumn('instructor', function ($row) {
                    return $row->instructor->full_name;
                })
                ->editColumn('pages_sum_grdsheetpg_row_no', function ($row) {
                    return sprintf("%s", $row->pages_sum_grdsheetpg_row_no ? $row->pages_sum_grdsheetpg_row_no : 0,);
                })
                ->addColumn('actions', function ($row) {
                    $viewGate      = 'gradesheet_show';
                    $editGate      = 'gradesheet_edit';
                    $deleteGate    = 'gradesheet_delete';

                    $primaryKey = 'class_id';
                    $resource = 'gradesheet';

                    return view('admin.gradesheets.partials.datatable-action-btns', compact(
                        'viewGate',
                        'editGate',
                        'deleteGate',
                        'row',
                        'primaryKey',
                        'resource',
                    ));
                })
                ->editColumn('class_acadYear', function ($row) {
                    return  sprintf("%s - %s", $row->class_acadYear, $row->class_acadYear + 1);
                })
                ->rawColumns(['actions', 'students_count']);

            return $table->make(true);
        }

        $filterSubjects = Subject::select([
            DB::raw('`subj_id` as `id`'),
            DB::raw('`subj_code` as `value`'),
        ])->get()->toArray();

        $filterRooms = Room::select([
            DB::raw('`room_id` as `id`'),
            DB::raw('`room_name` as `value`'),
        ])->get()->toArray();

        $filterSemesters = Term::select([
            DB::raw('`term_id` as `id`'),
            DB::raw('`term_name` as `value`'),
        ])->order()->get()->toArray();

        $filterAcadYears = SchoolYear::select([
            DB::raw('`syear_year` as `id`'),
            DB::raw('CONCAT(`syear_year`," - ", syear_year + 1) as `value`'),
        ])->order()->get()->toArray();

        $filterInstructors = Instructor::get()->map(function ($instructor) {
            $newInstructor['id'] = $instructor->inst_id;
            $newInstructor['value'] = $instructor->full_name;

            return $newInstructor;
        });

        $filterMaxStudent = Gradesheet::withCount(['students'])->get()->map(function ($gradesheet) {
            return $gradesheet->students_count;
        })->max();

        $filterMaxSlots = Gradesheet::withSum('pages', 'grdsheetpg_rowNo')->get()->map(function ($gradesheet) {
            return $gradesheet->pages_sum_grdsheetpg_row_no;
        })->max();

        addJavascriptFile(asset('assets/js/datatables.js'));
        return view('admin.gradesheets.index', compact('filterSubjects', 'filterRooms', 'filterSemesters', 'filterAcadYears', 'filterInstructors', 'filterMaxStudent', 'filterMaxSlots'));
    }

    /** 
     *  Show the form for creating a new resource
     * 
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.gradesheets.create', ['title' => 'Add Gradesheet', "menu" => "class", "breadcrumb" => [["name" => "List of Gradesheets", "url" => "/class"], ["name" => "Add Gradesheet"]]]);
    }

    /** 
     *  Display the specified resource
     * 
     * @param \App\Models\Gradesheet $gradesheet
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Gradesheet $gradesheet)
    {
        if ($request->ajax()) {

            $query = Gradesheet::query()
                ->leftJoin('t_student_enrolled_subjects', 't_student_enrolled_subjects.class_enrsub_id', '=', 's_class.class_id')
                ->leftJoin('r_student', 'r_student.stud_id', '=', 't_student_enrolled_subjects.stud_enrsub_id')
                ->leftJoin('s_course', 's_course.cour_id', '=', 'r_student.cour_stud_id')
                ->leftJoin('s_gradesheet_page', 's_gradesheet_page.grdsheetpg_id', '=', 't_student_enrolled_subjects.grdsheetpg_enrsub_id')
                ->where('t_student_enrolled_subjects.class_enrsub_id', $gradesheet->class_id)
                ->select([
                    DB::raw('t_student_enrolled_subjects.enrsub_rowNo as `row_no`'),
                    DB::raw('t_student_enrolled_subjects.grdsheetpg_enrsub_id as `page_id`'),
                    DB::raw('s_gradesheet_page.grdsheetpg_pgNo as `page_no`'),
                    DB::raw('r_student.stud_id as stud_id'),
                    DB::raw('t_student_enrolled_subjects.enrsub_id as id'),
                    'r_student.stud_uuid',
                    'r_student.stud_studentNo',
                    'r_student.stud_lastName',
                    'r_student.stud_firstName',
                    'r_student.stud_middleName',
                    DB::raw('s_course.cour_code as course'),
                    DB::raw('t_student_enrolled_subjects.enrsub_midtermGrade as midterm_grade'),
                    DB::raw('t_student_enrolled_subjects.enrsub_finalGrade as final_grade'),
                    DB::raw('t_student_enrolled_subjects.enrsub_finalRating as final_rating'),
                    DB::raw('t_student_enrolled_subjects.enrsub_grade_status as grade_status')
                ]);

            return Datatables::of($query)
                ->setRowId('stud_id')
                ->addColumn('location', function ($row) {
                    return ($row->page_no && $row->row_no) ? sprintf('<span class="fw-bold">Page %s</span>, <br>№ %s', $row->page_no, $row->row_no) : '';
                })
                ->addColumn('stud_no', function ($row) {
                    return '<a href="' . url('/students') . "/" . $row->stud_id . '">' . $row->stud_studentNo . '</a>';
                })
                ->addColumn('name', function ($row) {
                    return '<span class="fw-bold">' . $row->stud_lastName . ',</span>' . ' ' .  $row->stud_firstName . ' ' . $row->stud_middleName;
                })
                ->addColumn('action', function ($row) {
                    return '<div class="d-flex justify-content-start flex-shrink-0">
                        <button type="button" kt_student_grades_table_edit class="btn btn-warning btn-sm me-1">
                            <i class="fa-duotone fa-pen-to-square me-2 fs-6"></i>Edit
                        </button>
        
                        <button type="button" kt_student_grades_table_delete class="btn btn-icon btn-light-danger btn-sm">
                        <i class="fa-duotone fa-trash"></i>
                        </button>
                    </div>';
                })
                ->filterColumn('name', function ($query, $keyword) {
                    $query->whereRaw("CONCAT(stud_firstName, ' ', stud_middleName, ' ', stud_lastName) like ?", ["%{$keyword}%"]);
                })
                ->filterColumn('stud_no', function ($query, $keyword) {
                    $query->whereRaw("stud_studentNo like ?", ["%{$keyword}%"]);
                })
                ->orderColumn('location', function ($query, $order) {
                    $query->orderBy('s_gradesheet_page.grdsheetpg_pgNo', $order)
                        ->orderBy('t_student_enrolled_subjects.enrsub_rowNo', ($order == "asc") ? 'desc' : 'asc');
                })
                ->rawColumns(['location', 'stud_no', 'name', 'action'])
                ->make(true);
        }

        $fs = Gradesheet::leftJoin('s_room', 'class_room_id', '=', 'room_id')
            ->leftJoin('s_subject', 'class_subj_id', '=', 'subj_id')
            ->leftJoin('s_instructor', 'class_inst_id', '=', 'inst_id')
            ->leftJoin('s_term', 'class_term_id', '=', 'term_id')
            ->selectRaw('
            class_id 
            , md5(class_id) as class_id_md5
            , class_flink
            , class_fname
            , subj_code as class_subj_code
            , subj_name as class_subj_name
            , class_fstorage as class_file_storage_type
            , class_section as class_section
            , CONCAT(term_name, ", ", class_acadYear, "-", class_acadYear + 1) as class_sem_sy
            , room_name as class_room
            , CONCAT(inst_firstName, " ", inst_lastName) as class_instructor')
            ->where(["class_id" => $gradesheet->class_id])
            ->get()[0];

        $sched_timeSlot_day = "";
        $sched_timeSlot_time = "";

        $ts = new TimeSlot();
        $sched_timeSlots = $ts->select(['time_id', 'time_day', 'time_duration'])
            ->where("time_class_id", $fs->class_id)
            ->get();

        if (sizeOf($sched_timeSlots) == 1) {
            $sched_timeSlot_day .= ($sched_timeSlots[0]['time_day']) ? $sched_timeSlots[0]['time_day'] : "";
            $sched_timeSlot_time .= ($sched_timeSlots[0]['time_duration']) ? $sched_timeSlots[0]['time_duration'] : "";
        } else if (sizeOf($sched_timeSlots) >= 1) {

            for ($a = 0; $a < sizeOf($sched_timeSlots); $a++) {

                if ($a == 0) { // start

                    $sched_timeSlot_day .= ($sched_timeSlots[$a]['time_day']) ? $sched_timeSlots[$a]['time_day'] . "/" : "";
                    $sched_timeSlot_time .= ($sched_timeSlots[$a]['time_duration']) ? $sched_timeSlots[$a]['time_duration'] . "/" : "";
                } else if (($a != sizeOf($sched_timeSlots) - 1) && ($a > 0 && $a < sizeOf($sched_timeSlots) - 1)) { // mid

                    $sched_timeSlot_day .= ($sched_timeSlots[$a]['time_day']) ? $sched_timeSlots[$a]['time_day'] . "/" : "";
                    $sched_timeSlot_time .= ($sched_timeSlots[$a]['time_duration']) ? $sched_timeSlots[$a]['time_duration'] . "/" : "";
                } else if ($a == sizeOf($sched_timeSlots) - 1) { // last

                    $sched_timeSlot_day .= ($sched_timeSlots[$a]['time_day']) ? $sched_timeSlots[$a]['time_day'] : "";
                    $sched_timeSlot_time .= ($sched_timeSlots[$a]['time_duration']) ? $sched_timeSlots[$a]['time_duration'] : "";
                }
            }
        }

        $fs['class_day_time'] = ($sched_timeSlot_day ? $sched_timeSlot_day : "") . ($sched_timeSlot_time ? " " . $sched_timeSlot_time : "");

        $gradesheetPages = Gradesheet::with(['pages' => function ($query) {
            $query->whereRaw('grdsheetpg_deletedAt IS NULL');
        }, 'pages.students' => function ($query) use ($gradesheet) {
            $query->wherePivot('class_enrsub_id', $gradesheet->class_id);
        }])->find($gradesheet->class_id);

        $fs['gradesheet_pages'] = $gradesheetPages->pages;

        return view('admin.gradesheets.show', ['menu_header' => 'Menu', 'title' => "Gradesheet details", "menu" => "class", "sub_menu" => "student-grades", "class" => $fs, "breadcrumb" => [["name" => "List of Gradesheets", "url" => "/gradesheet"], ["name" => "Gradesheet details"]]]);
    }

    /** 
     *  Update the specific resource
     * 
     * @param \App\Models\Gradesheet $gradesheet
     * @return \Illuminate\Http\Response
     */
    public function edit(Gradesheet $gradesheet)
    {
        $fs = Gradesheet::leftJoin('s_room', 'class_room_id', '=', 'room_id')
            ->leftJoin('s_subject', 'class_subj_id', '=', 'subj_id')
            ->leftJoin('s_instructor', 'class_inst_id', '=', 'inst_id')
            ->leftJoin('s_term', 'class_term_id', '=', 'term_id')
            ->selectRaw('
            class_id as gradesheet_id
            , md5(class_id) as gradesheet_id_md5
            , class_fstorage as gradesheet_file_storage
            , class_flink as gradesheet_file_link
            , class_fname as gradesheet_file_name
            , subj_id as gradesheet_subj_id
            , subj_code as gradesheet_subj_code
            , subj_name as gradesheet_subj_name
            , class_fstorage as gradesheet_file_storage_type
            , class_section as gradesheet_section
            , term_id as gradesheet_term_id
            , term_name as gradesheet_term_name
            , class_acadYear as gradesheet_class_acadYear
            , CONCAT(class_acadYear, " - ", class_acadYear + 1) as gradesheet_class_acadYear_name
            , room_id as gradesheet_room_id
            , room_name as gradesheet_room
            , inst_id as gradesheet_instructor_id
            , CONCAT(inst_firstName, COALESCE(CONCAT(" ", SUBSTRING(inst_middleName, 1, 1), "."), ""), " ", inst_lastName) as gradesheet_instructor_name')
            ->where(["class_id" => $gradesheet->class_id])
            ->get()[0]->toArray();

        $fs['gradesheet_day_time'] = [];

        $ts = new TimeSlot();
        $dayTimes = $ts->select(['time_id', 'time_day', 'time_duration'])
            ->where("time_class_id", $fs['gradesheet_id'])
            ->get();

        foreach ($dayTimes as $dayTime) {

            $time = explode(' - ', $dayTime['time_duration']);
            array_push($fs['gradesheet_day_time'], [
                "time_id" => $dayTime['time_id'],
                "time_day" => $dayTime['time_day'],
                "time_day_from" => $time[0],
                "time_day_to" => $time[1]
            ]);
        }

        $fs['gradesheet_pages'] = GradesheetPages::select(["grdsheetpg_id", "grdsheetpg_rowNo", "grdsheetpg_flink", "grdsheetpg_fname"])->where("grdsheetpg_gradesheet_id", $fs['gradesheet_id'])->get();

        return view('admin.gradesheets.edit', ['menu_header' => 'Menu', 'title' => "Gradesheet details", "menu" => "class", "sub_menu" => "student-grades", "gradesheet" => $fs, "breadcrumb" => [["name" => "List of Gradesheets", "url" => "/gradesheet"], ["name" => "Edit Gradesheet"]]]);
    }
    /*
|--------------------------------------------------------------------------
|    End::Views
|--------------------------------------------------------------------------
|
*/


    /*
|--------------------------------------------------------------------------
|    Begin::Functions
|-------------------------------------------------------------------------- 
|
*/

    /** 
     *  Create a new resource via ajax request
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'semester' => 'required',
            'school_year' => 'required',
            'instructor' => 'required',
        ]);

        $gradesheetData = [
            "class_section" => $request->section,
            "class_acadYear" => $request->school_year,
            "class_fstorage" => $request->gradesheet_file['storage_type'],
            "class_flink" => $request->gradesheet_file['file_path'],
            "class_fname" => $request->gradesheet_file['file_name'],
            "class_term_id" => $request->semester,
            "class_room_id" => $request->room,
            "class_subj_id" => $request->subject,
            "class_inst_id" => $request->instructor,
            "class_createdAt" => now()
        ];
        $gradesheetPages = $request->gradesheet_file['pages'];
        $gradesheetTimeSlot = ($request->timeslot) ? $request->timeslot : [];

        $gradesheetId = Gradesheet::insertGetId($gradesheetData);

        // insert gradesheet timeslots
        foreach ($gradesheetTimeSlot as $timeSlot) {
            TimeSlot::insert(["time_day" => $timeSlot['day'], "time_duration" => $timeSlot["time_start"] . " - " . $timeSlot["time_end"], "time_class_id" => $gradesheetId]);
        }

        // insert gradesheet pages
        $i = 1;
        foreach ($gradesheetPages as $page) {

            GradesheetPages::insert(["grdsheetpg_pgNo" => $i, "grdsheetpg_rowNo" => $page['tot_rows'], "grdsheetpg_flink" => $page["file_path"], "grdsheetpg_fname" => $page['file_name'], "grdsheetpg_gradesheet_id" => $gradesheetId]);
            $i++;
        }

        return response()->json([
            'message' => __('modal.added_success', ['attribute' => 'Gradesheet']),
            'id' => $gradesheetId
        ]);
    }

    /** 
     *  Create a new resource via ajax request
     * 
     * @param \App\Models\Gradesheet $gradesheet
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function save(Gradesheet $gradesheet, Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'semester' => 'required',
            'school_year' => 'required',
            'instructor' => 'required',
        ]);

        $gradesheetData = [
            "class_section" => $request->section,
            "class_acadYear" => $request->school_year,
            "class_fstorage" => $request->gradesheet_file['storage_type'],
            "class_flink" => $request->gradesheet_file['file_path'],
            "class_fname" => $request->gradesheet_file['file_name'],
            "class_term_id" => $request->semester,
            "class_room_id" => $request->room,
            "class_subj_id" => $request->subject,
            "class_inst_id" => $request->instructor,
            "class_updatedAt" => now()
        ];

        Gradesheet::where(["class_id" => $gradesheet->class_id])->update($gradesheetData);

        // Pages
        $gradesheetPages = $request->gradesheet_file['pages'];

        $newGrdSheetPage = [];
        $retainedGrdSheetPages = [];

        $i = 1;
        foreach ($gradesheetPages as $page) {

            if (array_key_exists('id', $page)) {
                GradesheetPages::where(['grdsheetpg_id' => $page['id'], 'grdsheetpg_gradesheet_id' => $gradesheet->class_id])->update(["grdsheetpg_rowNo" => $page['tot_rows'], "grdsheetpg_flink" => $page["file_path"], "grdsheetpg_fname" => $page['file_name']]);

                array_push($retainedGrdSheetPages, $page['id']);
            } else {
                array_push($newGrdSheetPage, ["grdsheetpg_pgNo" => $i, "grdsheetpg_rowNo" => $page['tot_rows'], "grdsheetpg_flink" => $page["file_path"], "grdsheetpg_fname" => $page['file_name'], "grdsheetpg_gradesheet_id" => $gradesheet->class_id]);
            }
            $i++;
        }

        GradesheetPages::where(['grdsheetpg_gradesheet_id' => $gradesheet->class_id])->whereNotIn('grdsheetpg_id', $retainedGrdSheetPages)->delete();
        GradesheetPages::insert($newGrdSheetPage);

        // Time slot
        $gradesheetTimeSlot = ($request->timeslot) ? $request->timeslot : [];

        $newGrdSheetTimeSlot = [];
        $retainedGrdSheetTimeSlot = [];

        $o = 1;
        foreach ($gradesheetTimeSlot as $timeSlot) {

            if (array_key_exists('time_id', $timeSlot)) {
                TimeSlot::where(['time_id' => $timeSlot['time_id']])->update(["time_day" => $timeSlot['day'], "time_duration" => $timeSlot["time_start"] . " - " . $timeSlot["time_end"]]);

                array_push($retainedGrdSheetTimeSlot, $timeSlot['time_id']);
            } else {
                array_push($newGrdSheetTimeSlot, ["time_day" => $timeSlot['day'], "time_duration" => $timeSlot["time_start"] . " - " . $timeSlot["time_end"], "time_class_id" => $gradesheet->class_id]);
            }
            $o++;
        }

        if (sizeOf($retainedGrdSheetTimeSlot) >= 1) {
            TimeSlot::where('time_class_id', $gradesheet->class_id)->whereNotIn('time_id', $retainedGrdSheetTimeSlot)->delete();
        } else if (sizeOf($retainedGrdSheetTimeSlot) <= 0) {
            TimeSlot::where('time_class_id', $gradesheet->class_id)->delete();
        }

        TimeSlot::insert($newGrdSheetTimeSlot);

        return response()->json([
            'message' => __('modal.updated_success', ['attribute' => 'Gradesheet']),
            'id' => $gradesheet->class_id
        ], Response::HTTP_OK);
    }

    /** 
     *  Validate if the student is added on the Gradesheet
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Gradesheet $gradesheet
     * @return \Illuminate\Http\Response
     */
    public function validateStudentEnrollment(Request $request, Gradesheet $gradesheet)
    {
        return response()->json($gradesheet->students()->where(function ($query) use ($request) {
            $query->orWhere('stud_id', $request->stud_id)->orWhere('stud_uuid', $request->stud_id)->orWhereRaw(sprintf("md5(stud_id) = '%s'", $request->stud_id));
        })->exists(), Response::HTTP_OK);
    }

    /** 
     *  Get Pages
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Gradesheet $gradesheet
     * @return \Illuminate\Http\Response
     */
    public function getPages(Request $request, Gradesheet $gradesheet)
    {
        $gradesheetPages = Gradesheet::with(['pages' => function ($query) use ($request) {
            $query->when($request->has('term'), function ($query) use ($request) {
                $query->where('grdsheetpg_pgNo', 'LIKE', '%' . $request->term . '%');
            });
            $query->whereRaw('grdsheetpg_deletedAt IS NULL');
        }, 'pages.students' => function ($query) use ($gradesheet) {
            $query->wherePivot('class_enrsub_id', $gradesheet->class_id);
        }])->find($gradesheet->class_id);

        return response()->json($gradesheetPages->pages, Response::HTTP_OK);
    }

    /** 
     *  Get Page rows
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Gradesheet $gradesheet
     * @return \Illuminate\Http\Response
     */
    public function getPageRows(Request $request, Gradesheet $gradesheet)
    {
        $request->validate([
            'page_id' => 'required',
        ]);

        $pageRows = [];
        $pageRowNo = $gradesheet->pages()->where('grdsheetpg_id', $request->page_id)->value('grdsheetpg_rowNo');

        for ($i = 0; $i < $pageRowNo; $i++) {

            $rowNo = $i + 1;

            array_push($pageRows, [
                "no" => $rowNo,
                "has_student" => $gradesheet->students()->wherePivot('grdsheetpg_enrsub_id', $request->page_id)->wherePivot('enrsub_rowNo', $rowNo)->exists()
            ]);
        }

        return response()->json($pageRows, Response::HTTP_OK);
    }

    /** 
     *  Get Page details
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Gradesheet $gradesheet
     * @return \Illuminate\Http\Response
     */
    public function getPageDetails(Request $request, Gradesheet $gradesheet)
    {
        $request->validate([
            'page_id' => 'required',
        ]);

        return response()->json($gradesheet->pages()->where('grdsheetpg_id', $request->page_id)->get(), Response::HTTP_OK);
    }

    /** 
     *  Save added students
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Gradesheet $gradesheet
     * @return \Illuminate\Http\Response
     */
    public function storeStudents(Request $request, Gradesheet $gradesheet)
    {
        $request->validate([
            'students' => 'required|array',
        ]);

        $gradesheet->students()->attach($request->input('students', []));

        return response()->json([
            'message' => __('modal.added_success', ['attribute' => 'Student grades']),
        ], Response::HTTP_OK);
    }

    // Gradesheet Student grades 

    /** 
     *  Save changes on student grades
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Gradesheet $gradesheet
     * @return \Illuminate\Http\Response
     */
    public function updateStudent(Request $request, Gradesheet $gradesheet, $student)
    {
        $gradesheet->students()->updateExistingPivot($student, $request->input('grade', []));

        return response()->json(['message' => __('modal.updated_success', ['attribute' => 'Student Grade'])], Response::HTTP_OK);
    }

    /** 
     *  Destroy student grades
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Gradesheet $gradesheet
     * @return \Illuminate\Http\Response
     */
    public function destroyStudent(Gradesheet $gradesheet, $student)
    {
        $gradesheet->students()->detach($student);

        return response()->json(['message' => __('modal.deleted_success', ['attribute' => 'Student Grade'])], Response::HTTP_OK);
    }

    public function generatePdf(Gradesheet $gradesheet)
    {

        $fs = Gradesheet::leftJoin('s_room', 'class_room_id', '=', 'room_id')
            ->leftJoin('s_subject', 'class_subj_id', '=', 'subj_id')
            ->leftJoin('s_instructor', 'class_inst_id', '=', 'inst_id')
            ->leftJoin('s_term', 'class_term_id', '=', 'term_id')
            ->selectRaw('
         subj_code as class_subj_code
        , subj_name as class_subj_name
        , subj_units as class_subj_units
        , class_section as class_section
        , term_name as class_semester
        , CONCAT(class_acadYear,"-", class_acadYear + 1) as class_sy
        , room_name as class_room')
            ->where(["class_id" => $gradesheet->class_id])
            ->get()[0];

        $sched_timeSlot_day = "";
        $sched_timeSlot_time = "";

        $ts = new TimeSlot();
        $sched_timeSlots = $ts->select(['time_id', 'time_day', 'time_duration'])
            ->where("time_class_id", $gradesheet->class_id)
            ->get();

        if (sizeOf($sched_timeSlots) == 1) {
            $sched_timeSlot_day .= ($sched_timeSlots[0]['time_day']) ? $sched_timeSlots[0]['time_day'] : "";
            $sched_timeSlot_time .= ($sched_timeSlots[0]['time_duration']) ? $sched_timeSlots[0]['time_duration'] : "";
        } else if (sizeOf($sched_timeSlots) >= 1) {

            for ($a = 0; $a < sizeOf($sched_timeSlots); $a++) {

                if ($a == 0) { // start

                    $sched_timeSlot_day .= ($sched_timeSlots[$a]['time_day']) ? $sched_timeSlots[$a]['time_day'] . "/" : "";
                    $sched_timeSlot_time .= ($sched_timeSlots[$a]['time_duration']) ? $sched_timeSlots[$a]['time_duration'] . "/" : "";
                } else if (($a != sizeOf($sched_timeSlots) - 1) && ($a > 0 && $a < sizeOf($sched_timeSlots) - 1)) { // mid

                    $sched_timeSlot_day .= ($sched_timeSlots[$a]['time_day']) ? $sched_timeSlots[$a]['time_day'] . "/" : "";
                    $sched_timeSlot_time .= ($sched_timeSlots[$a]['time_duration']) ? $sched_timeSlots[$a]['time_duration'] . "/" : "";
                } else if ($a == sizeOf($sched_timeSlots) - 1) { // last

                    $sched_timeSlot_day .= ($sched_timeSlots[$a]['time_day']) ? $sched_timeSlots[$a]['time_day'] : "";
                    $sched_timeSlot_time .= ($sched_timeSlots[$a]['time_duration']) ? $sched_timeSlots[$a]['time_duration'] : "";
                }
            }
        }

        $fs['class_day_time'] = ($sched_timeSlot_day ? $sched_timeSlot_day : "") . ($sched_timeSlot_time ? " " . $sched_timeSlot_time : "");

        $fs['students'] = Gradesheet::query()
            ->leftJoin('t_student_enrolled_subjects', 't_student_enrolled_subjects.class_enrsub_id', '=', 's_class.class_id')
            ->leftJoin('r_student', 'r_student.stud_id', '=', 't_student_enrolled_subjects.stud_enrsub_id')
            ->leftJoin('s_course', 's_course.cour_id', '=', 'r_student.cour_stud_id')
            ->leftJoin('s_gradesheet_page', 's_gradesheet_page.grdsheetpg_id', '=', 't_student_enrolled_subjects.grdsheetpg_enrsub_id')
            ->where('t_student_enrolled_subjects.class_enrsub_id', $gradesheet->class_id)
            ->select([
                'r_student.stud_studentNo',
                'r_student.stud_lastName',
                'r_student.stud_firstName',
                'r_student.stud_middleName',
                DB::raw('t_student_enrolled_subjects.enrsub_midtermGrade as midterm_grade'),
                DB::raw('t_student_enrolled_subjects.enrsub_finalGrade as final_grade'),
                DB::raw('t_student_enrolled_subjects.enrsub_finalRating as final_rating'),
                DB::raw('t_student_enrolled_subjects.enrsub_grade_status as grade_status')
            ])->get();


        $gradesheet = $fs;
        $pdf = Pdf::loadView('admin.gradesheets.pdf.index', compact('gradesheet'));

        $pdf->render();
        $canvas = $pdf->getCanvas();

        $height = $canvas->get_height();
        $width = $canvas->get_width();

        $canvas->set_opacity(.5, "Multiply");
        $canvas->page_text(35, $height - 30, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));

        return $pdf->stream();
    }
    /*
|--------------------------------------------------------------------------
|    End::Functions
|-------------------------------------------------------------------------- 
|
*/


    /*
|--------------------------------------------------------------------------
|    Begin::FormValidation Server-side
|-------------------------------------------------------------------------- 
|
*/

    /** 
     *  Validate if the student is added on the Gradesheet
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Gradesheet $gradesheet
     * @return \Illuminate\Http\Response
     */
    public function validateStudent(Request $request, Gradesheet $gradesheet)
    {

        return response()->json([
            "valid" => !($gradesheet->students()->where(function ($query) use ($request) {
                $query->orWhere('stud_id', $request->gradesheet_student)->orWhere('stud_uuid', $request->gradesheet_student)->orWhereRaw(sprintf("md5(stud_id) = '%s'", $request->gradesheet_student));
            })->exists()),
            "message" => "Student has already been added"
        ], Response::HTTP_OK);
    }

    /*
|--------------------------------------------------------------------------
|    End::FormValidation Server-side
|-------------------------------------------------------------------------- 
|
*/

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gradesheet $gradesheet)
    {
        abort_if(Gate::denies('gradesheet_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $gradesheet->delete();

        session()->flash('info', __('global.delete_success', ["attribute" => sprintf("<b>%s</b>", __('cruds.gradesheet.title_singular'))]));

        return redirect()->route('gradesheets.index');
    }
}
