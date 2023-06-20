<?php

namespace App\Http\Controllers\Admin;

use Faker\Generator;
use App\Models\Documents;
use Illuminate\Http\Request;
use App\Models\DocumentsType;
use App\Models\StudentGrades;
use Illuminate\Http\Response;
use App\Models\PreviousSchool;

//Controller 
use App\Models\StudentProfile;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DocumentsSubmitted;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

//Model
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Admin\TermController as Terms;

use App\Http\Controllers\Admin\HonorController as Honors;
use App\Http\Controllers\Admin\CourseController as Course;
use App\Http\Controllers\Admin\AcadYearController as AcadYears;
use App\Http\Controllers\Admin\YearLevelController as YearLevel;

class StudentProfileController extends Controller
{
    /*
|--------------------------------------------------------------------------
|    Begin::Views
|--------------------------------------------------------------------------
|
|    All functions that renders or display a page
|
*/

    public function index()
    {
        return view('admin.student_profile.index', ['menu_header' => 'Menu', 'title' => "List of Student Profile", "menu" => "student-profile", "sub_menu" => "student-profile", "breadcrumb" => [["name" => "List of Student Profile"]]]);
    }

    public function archived()
    {
        return view('admin.student_profile.archived', ['menu_header' => 'Menu', 'title' => "List of Archived Student Profile", "menu" => "student-profile", "sub_menu" => "student-profile", "breadcrumb" => [["name" => "List of Student Profile", "url" => "/student/profile/"], ["name" => "List of Archived Student Profile"]]]);
    }

    public function show_profile($profile_uuid)
    {

        $sp = new StudentProfile();
        $tp = $sp->leftJoin('s_course', 'cour_stud_id', '=', 'cour_id')
            ->selectRaw('r_student.*
            , md5(stud_id) as stud_id_md5
            , cour_name as stud_course_name
            , cour_code as stud_course 
            , DATE_FORMAT(stud_createdAt, "%Y-%m-%d") as `stud_createdAt_m_f`
            , DATE_FORMAT(stud_createdAt, "%r") as `stud_createdAt_t_f`
            , IFNULL(DATE_FORMAT(stud_updatedAt, "%Y-%m-%d"), "") as `stud_updatedAt_m_f`
            , IFNULL(DATE_FORMAT(stud_updatedAt, "%r"), "") as `stud_updatedAt_t_f`
            ')
            ->where(['stud_uuid' => $profile_uuid])
            ->get();

        if (sizeof($tp) == 1) {

            $vd = [
                'menu_header' => 'View Student Profile', 'title' => 'View Student Profile', "menu" => "student-records", "sub_menu" => "student-profile", "breadcrumb" => [["name" => "List of Student Profile", "url" => "/student/profile/"], ["name" => "View Student Profile"]]
            ];

            $fp = $tp[0];
            $sId = $fp['stud_id'];

            $ps = new PreviousSchool();
            $fp['stud_sPrimary'] = $ps->where(["extsch_stud_id" => $sId, "extsch_educType" => "PRIMARY"])
                ->get();

            $fp['stud_sSecondary'] = $ps->where(["extsch_stud_id" => $sId, "extsch_educType" => "SECONDARY"])
                ->get();

            $fp['stud_sPrimary'] = (sizeOf($fp['stud_sPrimary']) == 1) ? $fp['stud_sPrimary'][0] : [];
            $fp['stud_sSecondary'] = (sizeOf($fp['stud_sSecondary']) == 1) ? $fp['stud_sSecondary'][0] : [];


            if ($fp['stud_admissionType'] == "TRANSFEREE" || $fp['stud_admissionType'] == "LADDERIZED") {

                $fp['stud_sTertiary'] = $ps->where(["extsch_stud_id" => $sId, "extsch_educType" => "TRANSFER"])
                    ->get();
            }

            if ($fp['stud_recordType'] == "NONSIS") {
                $g = [];
                $g['total_semester'] = 0;
                $g['total_summer_semester'] = 0;

                $sg = new StudentGrades();
                $school_year = $sg->leftJoin('s_class', 'class_enrsub_id', '=', 'class_id')
                    ->selectRaw('DISTINCT class_acadYear as acad_year
                        , CONCAT(class_acadYear, " - ", class_acadYear + 1) as acad_year_long
                        , CONCAT(class_acadYear, "-\'" ,SUBSTRING(class_acadYear + 1, 3, 2)) as acad_year_short
                        ')
                    ->whereRaw('stud_enrsub_id = "' . $sId . '"')
                    ->orderBy('acad_year', 'desc')
                    ->get();

                $i = 0;
                foreach ($school_year as $year) {

                    $semesters = $sg->leftJoin('s_class', 'class_enrsub_id', '=', 'class_id')
                        ->leftJoin('s_term', 'class_term_id', '=', 'term_id')
                        ->selectRaw('DISTINCT term_name, term_order')
                        ->whereRaw('stud_enrsub_id = "' . $sId . '" and class_acadYear = "' . $year->acad_year . '"')
                        ->orderBy('term_order', 'desc')
                        ->get();

                    $a = 0;
                    foreach ($semesters as $semester) {
                        $g['total_semester'] += 1;

                        if ($semester['term_name'] == "Summer Semester") {
                            $g['total_summer_semester'] += 1;
                        }

                        $grades = $sg->leftJoin('r_student', 'stud_enrsub_id', '=', 'stud_id')
                            ->leftJoin('s_class', 'class_enrsub_id', '=', 'class_id')
                            ->leftJoin('s_instructor', 'class_inst_id', '=', 'inst_id')
                            ->leftJoin('s_subject', 'class_subj_id', '=', 'subj_id')
                            ->leftJoin('s_term', 'class_term_id', '=', 'term_id')
                            ->selectRaw('t_student_enrolled_subjects.*
                    , md5(enrsub_id) as enrsub_id_md5
                    , inst_firstName as enrsub_inst_firstName
                    , inst_middleName as enrsub_inst_middleName
                    , inst_lastName as enrsub_inst_lastName
                    , inst_suffix as enrsub_inst_suffixName
                    , subj_code as enrsub_subj_code
                    , subj_name as enrsub_subj_name
                    , subj_units as enrsub_subj_units
                    , class_section as enrsub_sche_section
                    ')
                            ->whereRaw('stud_enrsub_id = "' . $sId . '" and term_name = "' . $semester->term_name . '"and class_acadYear = "' . $year->acad_year . '"')
                            ->get();

                        $b = 0;
                        foreach ($grades as $grade) {


                            $grades[$b]['enrsub_grade_display'] = $grade['enrsub_finalRating'];
                            $grades[$b]['enrsub_grade_display_status'] = $grade['enrsub_grade_status'];

                            $grades[$b]['enrsub_inst_fullName'] = format_name(1, "",  $grades[$b]['enrsub_inst_firstName'], $grades[$b]['enrsub_inst_middleName'], $grades[$b]['enrsub_inst_lastName'], $grades[$b]['enrsub_inst_suffixName']);

                            $b++;
                        }

                        $semesters[$a]['subjects'] = $grades;
                        $a++;
                    }

                    $school_year[$i]['semesters'] = $semesters;
                    $i++;
                }

                $g["grades"] = $school_year;

                $vd = array_merge($vd, [
                    "stud_grades" => $g['grades'], "stud_total_semester" => $g['total_semester'], "stud_total_summer_semester" => $g['total_summer_semester']
                ]);
            }

            $dsi = new DocumentsSubmitted();
            $ds["entrance"] = $dsi->leftjoin("s_documents", 'subm_document', '=', 'docu_id')
                ->where(["subm_documentCategory" => "ENTRANCE", "subm_student" => $sId])->get();
            $ds["records"] = $dsi->leftjoin("s_documents", 'subm_document', '=', 'docu_id')
                ->where(["subm_documentCategory" => "RECORDS", "subm_student" => $sId, "docu_isPermanent" => "NO"])->get();
            $ds["exit"] = $dsi->leftjoin("s_documents", 'subm_document', '=', 'docu_id')
                ->where(["subm_documentCategory" => "EXIT", "subm_student" => $sId])->get();


            // Get fixed Documents

            // -- Records

            // --- Registration Cards
            $dfs["records"]['regcert'] = $dsi->leftjoin("s_documents", 'subm_document', '=', 'docu_id')
                ->where(["subm_documentCategory" => "RECORDS", "subm_student" => $sId, "docu_id" => "1"])
                ->orderBy('subm_documentType', 'desc')
                ->orderBy('subm_documentType_1', 'desc')->get();


            return view('admin.student_profile.show', array_merge($vd, ["menu" => "student-profile", "stud_profile" => $fp, "stud_documents" => $ds, "stud_documents_fixed" => $dfs]));
        } else {

            return view('errors.not-found', ['menu_header' => 'Menu', 'title' => "Profile not found", "menu" => "student-records", "sub_menu" => "student-profile"]);
        };
    }

    public function create_profile()
    {
        $acadYears = (new AcadYears)->getAllYears();
        $course = (new Course)->getAllCourses();
        $honors = (new Honors)->getAllHonors();
        $terms = (new Terms)->getAllTerm();
        $yearLevel = (new YearLevel)->getAllYearLevel();


        $dti = new Documents();
        $dtsi = new DocumentsType();

        $dt_ent = $dti->where(["docu_category" => "ENTRANCE"])
            ->get();
        if (sizeOf($dt_ent) >= 1) {
            $dt_ent[0]["types"] = $dtsi->where(["docuType_document" => $dt_ent[0]['docu_id']])->get();
        }

        $dt_rec = $dti->where(["docu_category" => "RECORDS", "docu_isPermanent" => "NO"])
            ->get();
        if (sizeOf($dt_rec) >= 1) {
            $dt_rec[0]["types"] = $dtsi->where(["docuType_document" => $dt_rec[0]['docu_id']])->get();
        }

        $dt_ext = $dti->where(["docu_category" => "EXIT"])
            ->get();
        if (sizeOf($dt_ext) >= 1) {
            $dt_ext[0]["types"] = $dtsi->where(["docuType_document" => $dt_ext[0]['docu_id']])->get();
        }

        return view('admin.student_profile.create', ['title' => 'Add Student Profile', "menu" => "student-profile", "formData_honors" => $honors, "formData_course" => $course, "formData_year" => $acadYears, "formData_terms" => $terms, "formData_yrLevel" => $yearLevel, "formData_docu_ent" => $dt_ent, "formData_docu_rec" => $dt_rec, "formData_docu_ext" => $dt_ext, "breadcrumb" => [["name" => "List of Student Profile", "url" => "/student/profile/"], ["name" => "Add Student Profile"]]]);
    }

    public function edit_profile($profile_uuid)
    {
        $sp = new StudentProfile();
        $tp = $sp->leftJoin('s_course', 'cour_stud_id', '=', 'cour_id')
            ->selectRaw('r_student.*
            , md5(stud_id) as stud_id_md5
            , cour_name as stud_course_name
            , cour_code as stud_course 
            , DATE_FORMAT(stud_createdAt, "%Y-%m-%d") as `stud_createdAt_m_f`
            , DATE_FORMAT(stud_createdAt, "%r") as `stud_createdAt_t_f`
            , IFNULL(DATE_FORMAT(stud_updatedAt, "%Y-%m-%d"), "") as `stud_updatedAt_m_f`
            , IFNULL(DATE_FORMAT(stud_updatedAt, "%r"), "") as `stud_updatedAt_t_f`
            ')
            ->where(['stud_uuid' => $profile_uuid])
            ->get();


        if (sizeof($tp) == 1) {

            $acadYears = (new AcadYears)->getAllYears();
            $course = (new Course)->getAllCourses();
            $honors = (new Honors)->getAllHonors();
            $terms = (new Terms)->getAllTerm();
            $yearLevel = (new YearLevel)->getAllYearLevel();


            $dti = new Documents();
            $dtsi = new DocumentsType();

            $dt_ent = $dti->where(["docu_category" => "ENTRANCE"])
                ->get();
            if (sizeOf($dt_ent) >= 1) {
                $dt_ent[0]["types"] = $dtsi->where(["docuType_document" => $dt_ent[0]['docu_id']])->get();
            }

            $dt_rec = $dti->where(["docu_category" => "RECORDS", "docu_isPermanent" => "NO"])
                ->get();
            if (sizeOf($dt_rec) >= 1) {
                $dt_rec[0]["types"] = $dtsi->where(["docuType_document" => $dt_rec[0]['docu_id']])->get();
            }

            $dt_ext = $dti->where(["docu_category" => "EXIT"])
                ->get();
            if (sizeOf($dt_ext) >= 1) {
                $dt_ext[0]["types"] = $dtsi->where(["docuType_document" => $dt_ext[0]['docu_id']])->get();
            }


            $vd = [
                'menu_header' => 'Student Profile', 'title' => 'Edit Student Profile', "menu" => "student-records", "sub_menu" => "student-profile"
            ];

            $fp = $tp[0];
            $sId = $fp['stud_id'];

            $ps = new PreviousSchool();
            $fp['stud_sPrimary'] = $ps->where(["extsch_stud_id" => $sId, "extsch_educType" => "PRIMARY"])
                ->get();

            $fp['stud_sSecondary'] = $ps->where(["extsch_stud_id" => $sId, "extsch_educType" => "SECONDARY"])
                ->get();

            $fp['stud_sPrimary'] = (sizeOf($fp['stud_sPrimary']) == 1) ? $fp['stud_sPrimary'][0] : [];
            $fp['stud_sSecondary'] = (sizeOf($fp['stud_sSecondary']) == 1) ? $fp['stud_sSecondary'][0] : [];


            if ($fp['stud_admissionType'] == "TRANSFEREE" || $fp['stud_admissionType'] == "LADDERIZED") {

                $fp['stud_sTertiary'] = $ps->where(["extsch_stud_id" => $sId, "extsch_educType" => "TRANSFER"])
                    ->get();
            }




            return view('admin.student_profile.edit', array_merge($vd, ["menu" => "student-profile", "stud_profile" => $fp, "formData_honors" => $honors, "formData_course" => $course, "formData_year" => $acadYears, "formData_terms" => $terms, "formData_yrLevel" => $yearLevel, "formData_docu_ent" => $dt_ent, "formData_docu_rec" => $dt_rec, "formData_docu_ext" => $dt_ext, "breadcrumb" => [["name" => "List of Student Profile", "url" => "/student/profile/"], ["name" => "Edit Student Profile"]]]));
        } else {

            return view('errors.not-found', ['menu_header' => 'Menu', 'title' => "Profile not found", "menu" => "student-records", "sub_menu" => "student-profile"]);
        };
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

    public function createStudentProfile($student_details)
    {
        (new StudentProfile)->insertOne($student_details);
    }

    public function getAllStudentProfile($filter = null)
    {
        return (new StudentProfile)->fetchAll($filter);
    }

    public function getStudentProfile($md5Id)
    {
        return (new StudentProfile)->fetchOne($md5Id);
    }

    public function updateStudentProfile($md5Id, $student_details)
    {

        if ($student_details['isGraduated'] == "YES") {
            $student_details['dateGraduated'] = date("Y-m-d", strtotime($student_details['dateGraduated']));
            $student_details['honor'] = $student_details['honor'];
        } else {
            $student_details['dateGraduated'] = null;
            $student_details['honor'] = null;
        }

        (new StudentProfile)->edit($md5Id, [
            'stud_studentNo' => $student_details['studentNo'],
            'stud_firstName' => $student_details['firstName'],
            'stud_middleName' => $student_details['middleName'],
            'stud_lastName' => $student_details['lastName'],
            'stud_suffix' => $student_details['suffix'],
            'cour_stud_id' => $student_details['course'],
            'stud_yearOfAdmission' => $student_details['yearOfAdmission'],
            'stud_admissionType' => $student_details['admissionType'],
            'stud_isGraduated' => $student_details['isGraduated'],
            'stud_dateGraduated' => $student_details['dateGraduated'],
            'stud_honor' => $student_details['honor'],
            'stud_addressLine' => $student_details['addressLine'],
            'stud_addressCity' => $student_details['addressCity'],
            'stud_addressProvince' => $student_details['addressProvince'],
        ]);
    }

    public function removeStudentProfile($md5Id)
    {
        (new StudentProfile)->remove($md5Id);
    }

    public function generateEnvelopeDocumentEvaluation(StudentProfile $student, Request $request)
    {
        $dsi = new DocumentsSubmitted();
        $documents['all']["entrance"] = $dsi->leftjoin("s_documents", 'subm_document', '=', 'docu_id')
            ->where(["subm_documentCategory" => "ENTRANCE", "subm_student" => $student->stud_id])->get();
        $documents['all']["records"] = $dsi->leftjoin("s_documents", 'subm_document', '=', 'docu_id')
            ->where(["subm_documentCategory" => "RECORDS", "subm_student" => $student->stud_id, "docu_isPermanent" => "NO"])->get();
        $documents['all']["exit"] = $dsi->leftjoin("s_documents", 'subm_document', '=', 'docu_id')
            ->where(["subm_documentCategory" => "EXIT", "subm_student" => $student->stud_id])->get();

        $documents['default']["records"]['regcert'] = $dsi->leftjoin("s_documents", 'subm_document', '=', 'docu_id')
            ->where(["subm_documentCategory" => "RECORDS", "subm_student" => $student->stud_id, "docu_id" => "1"])
            ->orderBy('subm_documentType', 'desc')
            ->orderBy('subm_documentType_1', 'desc')->get();

        $paper_size = ($request->has('size')) ? (($request->input('size') != '') ? $request->input('size') : 'legal') : 'legal';

        $pdf = Pdf::loadView('admin.student_profile.pdf.document-evaluation', compact('student', 'documents'))->setPaper($paper_size);

        $pdf->render();

        return $pdf->stream();
    }

    public function generateScholasticData(StudentProfile $student, Request $request)
    {
        abort_if($student->stud_recordType != 'NONSIS', Response::HTTP_NOT_FOUND);

        $tp = $student->leftJoin('s_course', 'cour_stud_id', '=', 'cour_id')
            ->selectRaw('r_student.*
            , md5(stud_id) as stud_id_md5
            , cour_name as stud_course_name
            , cour_code as stud_course 
            , DATE_FORMAT(stud_createdAt, "%Y-%m-%d") as `stud_createdAt_m_f`
            , DATE_FORMAT(stud_createdAt, "%r") as `stud_createdAt_t_f`
            , IFNULL(DATE_FORMAT(stud_updatedAt, "%Y-%m-%d"), "") as `stud_updatedAt_m_f`
            , IFNULL(DATE_FORMAT(stud_updatedAt, "%r"), "") as `stud_updatedAt_t_f`
            ')
            ->where('stud_id', $student->stud_id)
            ->get();

        $vd = [];

        $fp = $tp[0];
        $sId = $fp['stud_id'];

        $ps = new PreviousSchool();
        $fp['stud_sPrimary'] = $ps->where(["extsch_stud_id" => $sId, "extsch_educType" => "PRIMARY"])
            ->get();

        $fp['stud_sSecondary'] = $ps->where(["extsch_stud_id" => $sId, "extsch_educType" => "SECONDARY"])
            ->get();

        $fp['stud_sPrimary'] = (sizeOf($fp['stud_sPrimary']) == 1) ? $fp['stud_sPrimary'][0] : [];
        $fp['stud_sSecondary'] = (sizeOf($fp['stud_sSecondary']) == 1) ? $fp['stud_sSecondary'][0] : [];


        if ($fp['stud_admissionType'] == "TRANSFEREE" || $fp['stud_admissionType'] == "LADDERIZED") {

            $fp['stud_sTertiary'] = $ps->where(["extsch_stud_id" => $sId, "extsch_educType" => "TRANSFER"])
                ->get();
        }

        if ($fp['stud_recordType'] == "NONSIS") {
            $g = [];
            $g['total_semester'] = 0;
            $g['total_summer_semester'] = 0;

            $sg = new StudentGrades();
            $school_year = $sg->leftJoin('s_class', 'class_enrsub_id', '=', 'class_id')
                ->selectRaw('DISTINCT class_acadYear as acad_year
                        , CONCAT(class_acadYear, " - ", class_acadYear + 1) as acad_year_long
                        , CONCAT(class_acadYear, "-\'" ,SUBSTRING(class_acadYear + 1, 3, 2)) as acad_year_short
                        ')
                ->whereRaw('stud_enrsub_id = "' . $sId . '"')
                ->orderBy('acad_year', 'desc')
                ->get();

            $i = 0;
            foreach ($school_year as $year) {

                $semesters = $sg->leftJoin('s_class', 'class_enrsub_id', '=', 'class_id')
                    ->leftJoin('s_term', 'class_term_id', '=', 'term_id')
                    ->selectRaw('DISTINCT term_name, term_order')
                    ->whereRaw('stud_enrsub_id = "' . $sId . '" and class_acadYear = "' . $year->acad_year . '"')
                    ->orderBy('term_order', 'desc')
                    ->get();

                $a = 0;
                foreach ($semesters as $semester) {
                    $g['total_semester'] += 1;

                    if ($semester['term_name'] == "Summer Semester") {
                        $g['total_summer_semester'] += 1;
                    }

                    $grades = $sg->leftJoin('r_student', 'stud_enrsub_id', '=', 'stud_id')
                        ->leftJoin('s_class', 'class_enrsub_id', '=', 'class_id')
                        ->leftJoin('s_instructor', 'class_inst_id', '=', 'inst_id')
                        ->leftJoin('s_subject', 'class_subj_id', '=', 'subj_id')
                        ->leftJoin('s_term', 'class_term_id', '=', 'term_id')
                        ->selectRaw('t_student_enrolled_subjects.*
                    , md5(enrsub_id) as enrsub_id_md5
                    , inst_firstName as enrsub_inst_firstName
                    , inst_middleName as enrsub_inst_middleName
                    , inst_lastName as enrsub_inst_lastName
                    , inst_suffix as enrsub_inst_suffixName
                    , subj_code as enrsub_subj_code
                    , subj_name as enrsub_subj_name
                    , subj_units as enrsub_subj_units
                    , class_section as enrsub_sche_section
                    ')
                        ->whereRaw('stud_enrsub_id = "' . $sId . '" and term_name = "' . $semester->term_name . '"and class_acadYear = "' . $year->acad_year . '"')
                        ->get();

                    $b = 0;
                    foreach ($grades as $grade) {


                        $grades[$b]['enrsub_grade_display'] = $grade['enrsub_finalRating'];
                        $grades[$b]['enrsub_grade_display_status'] = $grade['enrsub_grade_status'];

                        $grades[$b]['enrsub_inst_fullName'] = format_name(1, "",  $grades[$b]['enrsub_inst_firstName'], $grades[$b]['enrsub_inst_middleName'], $grades[$b]['enrsub_inst_lastName'], $grades[$b]['enrsub_inst_suffixName']);

                        $b++;
                    }

                    $semesters[$a]['subjects'] = $grades;
                    $a++;
                }

                $school_year[$i]['semesters'] = $semesters;
                $i++;
            }

            $g["grades"] = $school_year;

            $vd = array_merge($vd, [
                "stud_grades" => $g['grades'], "stud_total_semester" => $g['total_semester'], "stud_total_summer_semester" => $g['total_summer_semester']
            ]);
        }

        $dsi = new DocumentsSubmitted();
        $ds["entrance"] = $dsi->leftjoin("s_documents", 'subm_document', '=', 'docu_id')
            ->where(["subm_documentCategory" => "ENTRANCE", "subm_student" => $sId])->get();
        $ds["records"] = $dsi->leftjoin("s_documents", 'subm_document', '=', 'docu_id')
            ->where(["subm_documentCategory" => "RECORDS", "subm_student" => $sId, "docu_isPermanent" => "NO"])->get();
        $ds["exit"] = $dsi->leftjoin("s_documents", 'subm_document', '=', 'docu_id')
            ->where(["subm_documentCategory" => "EXIT", "subm_student" => $sId])->get();

        $dfs["records"]['regcert'] = $dsi->leftjoin("s_documents", 'subm_document', '=', 'docu_id')
            ->where(["subm_documentCategory" => "RECORDS", "subm_student" => $sId, "docu_id" => "1"])
            ->orderBy('subm_documentType', 'desc')
            ->orderBy('subm_documentType_1', 'desc')->get();


        $paper_size = ($request->has('size')) ? (($request->input('size') != '') ? $request->input('size') : 'legal') : 'legal';

        $pdf = Pdf::loadView('admin.student_profile.pdf.scholastic-data', compact('student', 'vd'))->setPaper($paper_size);


        if (sizeof($tp) == 1) {

            $vd = [];

            $fp = $tp[0];
            $sId = $fp['stud_id'];

            $ps = new PreviousSchool();
            $fp['stud_sPrimary'] = $ps->where(["extsch_stud_id" => $sId, "extsch_educType" => "PRIMARY"])
                ->get();

            $fp['stud_sSecondary'] = $ps->where(["extsch_stud_id" => $sId, "extsch_educType" => "SECONDARY"])
                ->get();

            $fp['stud_sPrimary'] = (sizeOf($fp['stud_sPrimary']) == 1) ? $fp['stud_sPrimary'][0] : [];
            $fp['stud_sSecondary'] = (sizeOf($fp['stud_sSecondary']) == 1) ? $fp['stud_sSecondary'][0] : [];


            if ($fp['stud_admissionType'] == "TRANSFEREE" || $fp['stud_admissionType'] == "LADDERIZED") {

                $fp['stud_sTertiary'] = $ps->where(["extsch_stud_id" => $sId, "extsch_educType" => "TRANSFER"])
                    ->get();
            }

            if ($fp['stud_recordType'] == "NONSIS") {
                $g = [];
                $g['total_semester'] = 0;
                $g['total_summer_semester'] = 0;

                $sg = new StudentGrades();
                $school_year = $sg->leftJoin('s_class', 'class_enrsub_id', '=', 'class_id')
                    ->selectRaw('DISTINCT class_acadYear as acad_year
                        , CONCAT(class_acadYear, " - ", class_acadYear + 1) as acad_year_long
                        , CONCAT(class_acadYear, "-\'" ,SUBSTRING(class_acadYear + 1, 3, 2)) as acad_year_short
                        ')
                    ->whereRaw('stud_enrsub_id = "' . $sId . '"')
                    ->orderBy('acad_year', 'desc')
                    ->get();

                $i = 0;
                foreach ($school_year as $year) {

                    $semesters = $sg->leftJoin('s_class', 'class_enrsub_id', '=', 'class_id')
                        ->leftJoin('s_term', 'class_term_id', '=', 'term_id')
                        ->selectRaw('DISTINCT term_name, term_order')
                        ->whereRaw('stud_enrsub_id = "' . $sId . '" and class_acadYear = "' . $year->acad_year . '"')
                        ->orderBy('term_order', 'desc')
                        ->get();

                    $a = 0;
                    foreach ($semesters as $semester) {
                        $g['total_semester'] += 1;

                        if ($semester['term_name'] == "Summer Semester") {
                            $g['total_summer_semester'] += 1;
                        }

                        $grades = $sg->leftJoin('r_student', 'stud_enrsub_id', '=', 'stud_id')
                            ->leftJoin('s_class', 'class_enrsub_id', '=', 'class_id')
                            ->leftJoin('s_instructor', 'class_inst_id', '=', 'inst_id')
                            ->leftJoin('s_subject', 'class_subj_id', '=', 'subj_id')
                            ->leftJoin('s_term', 'class_term_id', '=', 'term_id')
                            ->selectRaw('t_student_enrolled_subjects.*
                    , md5(enrsub_id) as enrsub_id_md5
                    , inst_firstName as enrsub_inst_firstName
                    , inst_middleName as enrsub_inst_middleName
                    , inst_lastName as enrsub_inst_lastName
                    , inst_suffix as enrsub_inst_suffixName
                    , subj_code as enrsub_subj_code
                    , subj_name as enrsub_subj_name
                    , subj_units as enrsub_subj_units
                    , class_section as enrsub_sche_section
                    ')
                            ->whereRaw('stud_enrsub_id = "' . $sId . '" and term_name = "' . $semester->term_name . '"and class_acadYear = "' . $year->acad_year . '"')
                            ->get();

                        $b = 0;
                        foreach ($grades as $grade) {


                            $grades[$b]['enrsub_grade_display'] = $grade['enrsub_finalRating'];
                            $grades[$b]['enrsub_grade_display_status'] = $grade['enrsub_grade_status'];

                            $grades[$b]['enrsub_inst_fullName'] = format_name(1, "",  $grades[$b]['enrsub_inst_firstName'], $grades[$b]['enrsub_inst_middleName'], $grades[$b]['enrsub_inst_lastName'], $grades[$b]['enrsub_inst_suffixName']);

                            $b++;
                        }

                        $semesters[$a]['subjects'] = $grades;
                        $a++;
                    }

                    $school_year[$i]['semesters'] = $semesters;
                    $i++;
                }

                $g["grades"] = $school_year;

                $vd = array_merge($vd, [
                    "stud_grades" => $g['grades'], "stud_total_semester" => $g['total_semester'], "stud_total_summer_semester" => $g['total_summer_semester']
                ]);
            }

            $dsi = new DocumentsSubmitted();
            $ds["entrance"] = $dsi->leftjoin("s_documents", 'subm_document', '=', 'docu_id')
                ->where(["subm_documentCategory" => "ENTRANCE", "subm_student" => $sId])->get();
            $ds["records"] = $dsi->leftjoin("s_documents", 'subm_document', '=', 'docu_id')
                ->where(["subm_documentCategory" => "RECORDS", "subm_student" => $sId, "docu_isPermanent" => "NO"])->get();
            $ds["exit"] = $dsi->leftjoin("s_documents", 'subm_document', '=', 'docu_id')
                ->where(["subm_documentCategory" => "EXIT", "subm_student" => $sId])->get();


            // Get fixed Documents

            // -- Records

            // --- Registration Cards
            $dfs["records"]['regcert'] = $dsi->leftjoin("s_documents", 'subm_document', '=', 'docu_id')
                ->where(["subm_documentCategory" => "RECORDS", "subm_student" => $sId, "docu_id" => "1"])
                ->orderBy('subm_documentType', 'desc')
                ->orderBy('subm_documentType_1', 'desc')->get();

            // return $vd['stud_grades'][0];
            $pdf = Pdf::loadView('admin.student_profile.pdf.scholastic-data', compact('student', 'vd'));

            $pdf->render();
            $canvas = $pdf->getCanvas();

            $height = $canvas->get_height();

            $canvas->set_opacity(.5, "Multiply");
            $canvas->page_text(35, $height - 30, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));

            return $pdf->stream();
        } else {

            return view('errors.not-found', ['menu_header' => 'Menu', 'title' => "Profile not found", "menu" => "student-records", "sub_menu" => "student-profile"]);
        };

        // $pdf = Pdf::loadView('admin.student_profile.pdf.scholastic-data', compact('student'));

        // $pdf->render();
        // $canvas = $pdf->getCanvas();

        // $height = $canvas->get_height();

        // $canvas->set_opacity(.5, "Multiply");
        // $canvas->page_text(35, $height - 30, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));

        // return $pdf->stream();
    }

    // -- Begin::Ajax Requests -- //

    /** 
     *  Validate Student No. if not duplicated via ajax call
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * 
     */
    public function ajax_validate_studentNo(Request $request)
    {
        $isValid = '';
        $filter = [['stud_studentNo', '=', $request->studentNo]];

        if ($request->has('studentId')) {
            array_push($filter, ["stud_id", '!=', $request->studentId]);
        }

        $query = StudentProfile::where($filter)->get();

        if (sizeOf($query) >= 1) {
            $isValid = false;
        } else {
            $isValid = true;
        }

        header('Content-Type: application/json');
        echo json_encode([
            'valid' => $isValid,
        ]);
    }

    /** 
     *  Store resource via ajax call
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function ajax_insert(Request $request)
    {

        $request->validate([
            'studentNo' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'course' => 'required'
        ]);

        $recordStatus = "UNVALIDATED";
        $admissionType = $request->admissionType;

        $faker = new Generator();
        $faker->addProvider(new \Faker\Provider\Uuid($faker));
        $sp_uuid = $faker->uuid();

        $sp = new StudentProfile();

        $ud = [
            "stud_studentNo" => $request->studentNo,
            "stud_uuid" => $sp_uuid,
            "stud_firstName" => strtoupper($request->firstName),
            "stud_middleName" => strtoupper($request->middleName),
            "stud_lastName" => strtoupper($request->lastName),
            "cour_stud_id" => $request->course,
            "stud_yearOfAdmission" => $request->yearOfAdmission,
            "stud_admissionType" => $admissionType,
            "stud_addressLine" => strtoupper($request->addressLine),
            "stud_addressCity" => $request->addressCity,
            "stud_addressProvince" => $request->addressProvince,
            "stud_academicStatus" => $request->academicStatus,
            "stud_recordStatus" => $recordStatus,
            "stud_recordType" => $request->recordType,
            "stud_dateExited" => ($request->dateExited) ? date('Y-m-d', strtotime($request->dateExited)) : NULL,
            "stud_honor" => $request->honor,
            "stud_createdAt" => NOW(),
            "user_stud_id" => \Auth::user()->id
        ];

        $ishd = $request->isHonorableDismissed;

        if ($ishd == "YES") {

            $ud = array_merge([
                "stud_isHonorableDismissed" => "YES"
            ], $ud);

            $hdstat = $request->honorableDismissedStatus;

            if ($hdstat == "ISSUED") {
                $ud = array_merge([
                    "stud_honorableDismissedStatus" => "ISSUED",
                    "stud_honorableDismissedDate" => ($request->honorableDismissedDate) ? date('Y-m-d', strtotime($request->honorableDismissedDate)) : NULL,
                    "stud_honorableDismissedSchool" => NULL,
                ], $ud);
            } else if ($hdstat == "GRANTED") {
                $ud = array_merge([
                    "stud_honorableDismissedStatus" => "GRANTED",
                    "stud_honorableDismissedDate" => ($request->honorableDismissedDate) ? date('Y-m-d', strtotime($request->honorableDismissedDate)) : NULL,
                    "stud_honorableDismissedSchool" => $request->honorableDismissedSchool
                ], $ud);
            }
        } else if ($ishd == "NO") {
            $ud = array_merge([
                "stud_isHonorableDismissed" => "NO",
                "stud_honorableDismissedStatus" => NULL,
                "stud_honorableDismissedDate" => NULL,
                "stud_honorableDismissedSchool" => NULL,
            ], $ud);
        }

        $spId = $sp->insertGetId($ud);

        $ps = new PreviousSchool();
        if ($request->es_name && $request->es_yearGraduated) {

            $ps->insert([
                [
                    "extsch_name" => strtoupper($request->es_name), "extsch_yearExit" => $request->es_yearGraduated, "extsch_educType" => "PRIMARY", "extsch_stud_id" => $spId, "extsch_createdAt" => NOW()
                ]
            ]);
        }
        if ($request->hs_name && $request->hs_yearGraduated) {
            $ps->insert([
                [
                    "extsch_name" => strtoupper($request->hs_name), "extsch_yearExit" => $request->hs_yearGraduated, "extsch_educType" => "SECONDARY", "extsch_stud_id" => $spId, "extsch_createdAt" => NOW()

                ]
            ]);
        }
        if ($admissionType && ($admissionType == "TRANSFEREE" || $admissionType == "LADDERIZED")) {
            $psc = $request['college'];

            foreach ($psc as $c) {

                $ps->insert([
                    [
                        "extsch_name" => strtoupper($c["name"]), "extsch_yearExit" => $c["yearExited"], "extsch_educType" => "TRANSFER", "extsch_stud_id" => $spId, "extsch_createdAt" => NOW()
                    ]
                ]);
            }
        }

        $dsi = new DocumentsSubmitted();
        $dsent = $request['documents']['entrance'];
        $dsrec = $request['documents']['records'];
        $dsext = $request['documents']['exit'];

        foreach ($dsent as $sd) {

            if ($sd['docu']) {

                $dsi->insert([
                    [
                        "subm_student" => $spId, "subm_document" => $sd["docu"], "subm_documentType" => $sd["type"], "subm_dateSubmitted" => ($sd["date_submitted"]) ? date('Y-m-d', strtotime($sd["date_submitted"])) : NULL, "subm_documentCategory" => "ENTRANCE", "subm_createdAt" => NOW()
                    ]
                ]);
            }
        }

        foreach ($dsrec as $sd) {

            if ($sd['docu']) {

                $dsi->insert([
                    [
                        "subm_student" => $spId, "subm_document" => $sd["docu"], "subm_documentType" => $sd["type"], "subm_dateSubmitted" => ($sd["date_submitted"]) ? date('Y-m-d', strtotime($sd["date_submitted"])) : NULL, "subm_documentCategory" => "RECORDS", "subm_createdAt" => NOW()
                    ]
                ]);
            }
        }

        if ($request->academicStatus == "GRD" || $request->academicStatus == "DIS") {

            foreach ($dsext as $sd) {

                if ($sd['docu']) {

                    $dsi->insert([
                        [
                            "subm_student" => $spId, "subm_document" => $sd["docu"], "subm_documentType" => $sd["type"], "subm_dateSubmitted" => ($sd["date_submitted"]) ? date('Y-m-d', strtotime($sd["date_submitted"])) : NULL, "subm_documentCategory" => "EXIT", "subm_createdAt" => NOW()
                        ]
                    ]);
                }
            }
        }

        /* 1 = Registration Certificate id */
        $dsfrec = $request['documents_fix']['records']['regcert'];

        foreach ($dsfrec as $sfd) {

            if ($sfd['sy'] && $sfd['sem'] && $sfd['yrlvl']) {

                $dsi->insert([
                    [
                        "subm_student" => $spId, "subm_document" => '1', "subm_documentType" => $sfd["sy"], "subm_documentType_1" => $sfd["sem"], "subm_documentType_2" => $sfd["yrlvl"], "subm_dateSubmitted" => ($sfd["date_submitted"]) ? date('Y-m-d', strtotime($sfd["date_submitted"])) : NULL, "subm_documentCategory" => "RECORDS", "subm_createdAt" => NOW()
                    ]
                ]);
            }
        }

        header('Content-Type: application/json');
        echo json_encode([
            'message' => __('modal.added_success', ['attribute' => 'Student Profile']),
            'id' => $sp_uuid
        ]);
    }

    /** 
     *  Retrieves the listing of the resource via Datatable
     * 
     * @return \Yajra\DataTables\Facades\DataTables
     */
    public function ajax_retrieve_student_list(Request $request)
    {
        $query = StudentProfile::leftJoin('s_course', 'cour_stud_id', '=', 'cour_id')->select([
            DB::raw('md5(stud_id) as stud_id_md5'),
            'stud_id',
            'stud_uuid',
            'stud_studentNo',
            'stud_firstName',
            'stud_middleName',
            'stud_lastName',
            DB::raw('cour_code as stud_course'),
            'stud_recordType',
            'stud_admissionType',
            'stud_yearOfAdmission',
            'stud_academicStatus',
            'stud_createdAt',
            'stud_updatedAt',
        ]);

        if ($request->has('draw')) {
            return Datatables::eloquent($query)
                ->setRowId('stud_id_md5')
                ->addColumn('stud_studNo', function ($row) {

                    return '<a href="' . url('/student/profile') . "/" . $row->stud_uuid . '" target="_blank">' . $row->stud_studentNo . '</a>';
                })
                ->addColumn('stud_name', function ($row) {
                    return format_name("2", null, $row->stud_firstName, $row->stud_middleName, $row->stud_lastName);
                })
                ->addColumn('stud_created_at', function ($row) {

                    return format_datetime(strtotime($row->stud_createdAt));
                })
                ->addColumn('stud_updated_at', function ($row) {

                    return ($row->stud_updatedAt) ? format_datetime(strtotime($row->stud_updatedAt)) : NULL;
                })
                ->addColumn('action', function ($row) {
                    return view('admin.student_profile.partials.datatableActions', compact('row'));
                })
                ->filterColumn('stud_name', function ($query, $keyword) {
                    $query->whereRaw("CONCAT(stud_firstName, ' ', stud_middleName, ' ', stud_lastName) like ?", ["%{$keyword}%"]);
                })
                ->filterColumn('stud_course', function ($query, $keyword) {
                    $query->where("s_course.cour_id", $keyword);
                })
                ->filterColumn('stud_yearOfAdmission', function ($query, $keyword) {
                    $query->where("stud_yearOfAdmission", $keyword);
                })
                ->filterColumn('stud_recordType', function ($query, $keyword) {
                    $query->where("stud_recordType", $keyword);
                })
                ->filterColumn('stud_academicStatus', function ($query, $keyword) {

                    // 'UNG - Undergraduate', 'RTN - Returnee', 'DIS - Honorable Dismissal', 'GRD - Graduated'

                    if ($keyword == "NA") {
                        $query->whereRaw("stud_academicStatus NOT IN ('UNG', 'RTN', 'DIS', 'GRD')");
                    } else {
                        $query->where("stud_academicStatus", $keyword);
                    }
                })
                ->filterColumn('stud_createdAt', function ($query, $keyword) {

                    $dateRange = explode("to", $keyword);
                    if (sizeOf($dateRange) == 2) {
                        $query->whereBetween("stud_createdAt", [$dateRange[0] . " 00:00:00", $dateRange[1] . " 23:59:00"]);
                    } else {
                        $query->whereBetween("stud_createdAt", [$keyword . " 00:00:00",  $keyword . " 23:59:00"]);
                    }
                })
                ->filterColumn('stud_updatedAt', function ($query, $keyword) {

                    $dateRange = explode("to", $keyword);
                    if (sizeOf($dateRange) == 2) {
                        $query->whereBetween("stud_updatedAt", [$dateRange[0] . " 00:00:00", $dateRange[1] . " 23:59:00"]);
                    } else {
                        $query->whereBetween("stud_updatedAt", [$keyword . " 00:00:00",  $keyword . " 23:59:00"]);
                    }
                })
                ->orderColumn('stud_name', function ($query, $order) {
                    $query->orderBy('stud_lastName', $order);
                })
                ->rawColumns(['stud_studNo', 'action'])
                ->toJson();
        } else {
            $results = $query->where('stud_studentNo', 'LIKE', '%' . $request->term . '%')->orWhereRaw("CONCAT(stud_firstName, ' ', stud_middleName, ' ', stud_lastName) like ?", ["%{$request->term}%"])->where('stud_recordType', '=', $request->recordType)->simplePaginate(10);

            if (empty($results->nextPageUrl())) {
                $morePages = false;
            } else {
                $morePages = true;
            }

            return response()->json([
                "results" => $results->items(),
                "pagination" => array(
                    "more" => $morePages
                )
            ], Response::HTTP_OK);
        }
    }

    /** 
     *  Retrieves the listing of the resource via Datatable
     * 
     * @return \Yajra\DataTables\Facades\DataTables
     */
    public function ajax_retrieve_archived_student_list()
    {
        // This function is for Admin only

        $query = StudentProfile::leftJoin('s_course', 'cour_stud_id', '=', 'cour_id')
            ->select(
                DB::raw('md5(stud_id) as stud_id_md5'),
                'stud_uuid',
                'stud_studentNo',
                'stud_firstName',
                'stud_middleName',
                'stud_lastName',
                DB::raw('cour_code as stud_course'),
                'stud_createdAt',
                'stud_deletedAt'
            )->onlyTrashed();


        return Datatables::eloquent($query)
            ->setRowId('stud_id_md5')
            ->addColumn('stud_name', function ($row) {
                return format_name("2", null, $row->stud_firstName, $row->stud_middleName, $row->stud_lastName);
            })
            ->addColumn('stud_created_at', function ($row) {

                return format_datetime(strtotime($row->stud_createdAt));
            })
            ->addColumn('stud_deleted_at', function ($row) {

                return format_datetime(strtotime($row->stud_deletedAt));
            })
            ->addColumn('action', function ($row) {

                return '<div class="d-flex justify-content-start flex-shrink-0">

                    <button type="button" kt_student_profile_table_restore class="btn btn-icon btn-light-warning btn-sm mx-2" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="tooltip-dark" title="Restore">
                        <i class="fa-solid fa-rotate-left fs-6"></i>
                    </button>
                </div>';
            })
            ->rawColumns(['stud_studNo', 'action'])
            ->toJson();
    }

    public function ajax_retrieve(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $sp = new StudentProfile();
        $fp = $sp->leftJoin('s_course', 'cour_stud_id', '=', 'cour_id')
            ->where(function ($query) use ($request) {
                $query->orWhere('stud_id', $request->id)->orWhere('stud_uuid', $request->id)->orWhereRaw(sprintf("md5(stud_id) = '%s'", $request->id));
            })
            ->selectRaw('r_student.*
            , md5(stud_id) as stud_id_md5
            , cour_name as stud_course_name
            , cour_code as stud_course')
            ->get();

        return response()->json($fp, Response::HTTP_OK);
    }

    public function ajax_retrieve_documents(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $spi = new StudentProfile();
        $fp = $spi->leftJoin('s_course', 'cour_stud_id', '=', 'cour_id')
            ->whereRaw('md5(stud_id) = "' . $request->id . '"')
            ->selectRaw('r_student.*
            , md5(stud_id) as stud_id_md5
            , cour_name as stud_course_name
            , cour_code as stud_course')
            ->get()[0];


        $sId = $fp['stud_id'];

        $dsi = new DocumentsSubmitted();
        $ds["entrance"] = $dsi->leftjoin("s_documents", 'subm_document', '=', 'docu_id')
            ->where(["subm_documentCategory" => "ENTRANCE", "subm_student" => $sId])->get();
        $ds["records"] = $dsi->leftjoin("s_documents", 'subm_document', '=', 'docu_id')
            ->where(["subm_documentCategory" => "RECORDS", "subm_student" => $sId, "docu_isPermanent" => "NO"])->get();
        $ds["exit"] = $dsi->leftjoin("s_documents", 'subm_document', '=', 'docu_id')
            ->where(["subm_documentCategory" => "EXIT", "subm_student" => $sId])->get();

        // Get fixed Documents

        // -- Records

        // --- Registration Cards
        $dfs["records"]['regcert'] = $dsi->leftjoin("s_documents", 'subm_document', '=', 'docu_id')
            ->where(["subm_documentCategory" => "RECORDS", "subm_student" => $sId, "docu_id" => "1"])->get();

        header('Content-Type: application/json');
        echo json_encode(["documents" => $ds, "documents_fixed" => $dfs]);
    }

    public function ajax_retrieve_prevCollege(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $spi = new StudentProfile();
        $fp = $spi->leftJoin('s_course', 'cour_stud_id', '=', 'cour_id')
            ->whereRaw('md5(stud_id) = "' . $request->id . '"AND (stud_admissionType = "TRANSFEREE" OR stud_admissionType = "LADDERIZED")')
            ->selectRaw('r_student.*
            , md5(stud_id) as stud_id_md5
            , cour_name as stud_course_name
            , cour_code as stud_course')
            ->get();

        if (sizeof($fp) == 1) {
            $fp = $fp[0];
            $sId = $fp['stud_id'];
            $ps = new PreviousSchool();

            $fs = $ps->where(["extsch_stud_id" => $sId, "extsch_educType" => "TRANSFER"])
                ->get();
        } else {
            $fs = [];
        }

        header('Content-Type: application/json');
        echo json_encode($fs);
    }

    public function ajax_edit(Request $request)
    {

        $request->validate([
            'id' => 'required',
            'studentNo' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'course' => 'required'
        ]);

        $spId = $request->id;
        $admissionType = $request->admissionType;

        $sp = new StudentProfile();
        $ud = [
            "stud_studentNo" => $request->studentNo,
            "stud_firstName" => strtoupper($request->firstName),
            "stud_middleName" => strtoupper($request->middleName),
            "stud_lastName" => strtoupper($request->lastName),
            "cour_stud_id" => $request->course,
            "stud_yearOfAdmission" => $request->yearOfAdmission,
            "stud_admissionType" => $admissionType,
            "stud_addressLine" => strtoupper($request->addressLine),
            "stud_addressCity" => $request->addressCity,
            "stud_addressProvince" => $request->addressProvince,
            "stud_academicStatus" => $request->academicStatus,
            "stud_recordType" => $request->recordType,
            "stud_dateExited" => ($request->dateExited) ? date('Y-m-d', strtotime($request->dateExited)) : NULL,
            "stud_honor" => $request->honor,
        ];

        $ishd = $request->isHonorableDismissed;

        if ($ishd == "YES") {

            $ud = array_merge([
                "stud_isHonorableDismissed" => "YES"
            ], $ud);

            $hdstat = $request->honorableDismissedStatus;

            if ($hdstat == "ISSUED") {
                $ud = array_merge([
                    "stud_honorableDismissedStatus" => "ISSUED",
                    "stud_honorableDismissedDate" => ($request->honorableDismissedDate) ? date('Y-m-d', strtotime($request->honorableDismissedDate)) : NULL,
                    "stud_honorableDismissedSchool" => NULL,
                ], $ud);
            } else if ($hdstat == "GRANTED") {
                $ud = array_merge([
                    "stud_honorableDismissedStatus" => "GRANTED",
                    "stud_honorableDismissedDate" => ($request->honorableDismissedDate) ? date('Y-m-d', strtotime($request->honorableDismissedDate)) : NULL,
                    "stud_honorableDismissedSchool" => $request->honorableDismissedSchool
                ], $ud);
            }
        } else if ($ishd == "NO") {
            $ud = array_merge([
                "stud_isHonorableDismissed" => "NO",
                "stud_honorableDismissedStatus" => NULL,
                "stud_honorableDismissedDate" => NULL,
                "stud_honorableDismissedSchool" => NULL,
            ], $ud);
        }

        $sp->where(["stud_id" => $spId])->update($ud);

        $ps = new PreviousSchool();
        $ps->where(["extsch_stud_id" => $spId])->forceDelete();

        if ($request->es_name && $request->es_yearGraduated) {

            $ps->insert([
                [
                    "extsch_name" => strtoupper($request->es_name), "extsch_yearExit" => $request->es_yearGraduated, "extsch_educType" => "PRIMARY", "extsch_stud_id" => $spId, "extsch_createdAt" => NOW()
                ]
            ]);
        }
        if ($request->hs_name && $request->hs_yearGraduated) {
            $ps->insert([
                [
                    "extsch_name" => strtoupper($request->hs_name), "extsch_yearExit" => $request->hs_yearGraduated, "extsch_educType" => "SECONDARY", "extsch_stud_id" => $spId, "extsch_createdAt" => NOW()

                ]
            ]);
        }
        if ($admissionType && ($admissionType == "TRANSFEREE" || $admissionType == "LADDERIZED")) {
            $psc = $request['college'];

            foreach ($psc as $c) {

                $ps->insert([
                    [
                        "extsch_name" => strtoupper($c["name"]), "extsch_yearExit" => $c["yearExited"], "extsch_educType" => "TRANSFER", "extsch_stud_id" => $spId, "extsch_createdAt" => NOW()
                    ]
                ]);
            }
        }

        $dsi = new DocumentsSubmitted();
        $dsi->where(["subm_student" => $spId])->forceDelete();

        $dsent = $request['documents']['entrance'];
        $dsrec = $request['documents']['records'];
        $dsext = $request['documents']['exit'];

        foreach ($dsent as $sd) {

            if ($sd['docu']) {

                $dsi->insert([
                    [
                        "subm_student" => $spId, "subm_document" => $sd["docu"], "subm_documentType" => $sd["type"], "subm_dateSubmitted" => ($sd["date_submitted"]) ? date('Y-m-d', strtotime($sd["date_submitted"])) : NULL, "subm_documentCategory" => "ENTRANCE", "subm_createdAt" => NOW()
                    ]
                ]);
            }
        }

        foreach ($dsrec as $sd) {

            if ($sd['docu']) {

                $dsi->insert([
                    [
                        "subm_student" => $spId, "subm_document" => $sd["docu"], "subm_documentType" => $sd["type"], "subm_dateSubmitted" => ($sd["date_submitted"]) ? date('Y-m-d', strtotime($sd["date_submitted"])) : NULL, "subm_documentCategory" => "RECORDS", "subm_createdAt" => NOW()
                    ]
                ]);
            }
        }

        if ($request->academicStatus == "GRD" || $request->academicStatus == "DIS") {

            foreach ($dsext as $sd) {

                if ($sd['docu']) {

                    $dsi->insert([
                        [
                            "subm_student" => $spId, "subm_document" => $sd["docu"], "subm_documentType" => $sd["type"], "subm_dateSubmitted" => ($sd["date_submitted"]) ? date('Y-m-d', strtotime($sd["date_submitted"])) : NULL, "subm_documentCategory" => "EXIT", "subm_createdAt" => NOW()
                        ]
                    ]);
                }
            }
        }


        /* 1 = Registration Certificate id */
        $dsfrec = $request['documents_fix']['records']['regcert'];

        foreach ($dsfrec as $sfd) {

            if ($sfd['sy'] && $sfd['sem']) {

                $dsi->insert([
                    [
                        "subm_student" => $spId, "subm_document" => '1', "subm_documentType" => $sfd["sy"], "subm_documentType_1" => $sfd["sem"], "subm_documentType_2" => $sfd["yrlvl"], "subm_dateSubmitted" => ($sfd["date_submitted"]) ? date('Y-m-d', strtotime($sfd["date_submitted"])) : NULL, "subm_documentCategory" => "RECORDS", "subm_createdAt" => NOW()
                    ]
                ]);
            }
        }

        header('Content-Type: application/json');
        echo json_encode([
            'message' => __('modal.updated_success', ['attribute' => 'Student Profile']),
            'id' => $sp->where(["stud_id" => $spId])->get()[0]['stud_uuid']
        ]);
    }

    /** 
     *  Retrieves the listing of the resource via Datatable
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function ajax_restore(Request $request)
    {
        // This function is for Admin only

        $request->validate([
            'id' => 'required'
        ]);

        $query = StudentProfile::where("stud_studentNo", StudentProfile::onlyTrashed()->whereRaw('md5(stud_id) = "' . $request->id . '"')->get()->pluck('stud_studentNo')[0])->get();

        if (sizeOf($query) >= 1) {

            return response()->json(["duplicated" => true, "message" => __('modal.restored_error_duplicate', ['attribute' => 'Student Profile', 'action' => "Duplicate Student No. found"])]);
        } else {

            StudentProfile::onlyTrashed()
                ->whereRaw('md5(stud_id) = "' . $request->id . '"')
                ->restore();

            return response()->json(["duplicated" => false, "message" => __('modal.restored_success', ['attribute' => 'Student Profile'])]);
        }
    }


    public function ajax_update_remarks(Request $request)
    {
        $sp = new StudentProfile();
        $sp->whereRaw('md5(stud_id) = "' . $request->id . '"')
            ->update(["stud_remarks" => $request->remarks]);

        header('Content-Type: application/json');
        echo json_encode([
            'message' => __('modal.updated_success', ['attribute' => 'Remarks'])
        ]);
    }

    public function ajax_archive(Request $request)
    {

        $request->validate([
            'id' => 'required'
        ]);

        $sp = new StudentProfile();
        $sp->whereRaw('md5(stud_id) = "' . $request->id . '"')
            ->delete();

        header('Content-Type: application/json');
        echo json_encode([
            'message' => __('modal.archived_success', ['attribute' => 'Student Profile'])
        ]);
    }

    // -- End::Ajax Requests -- //

    /*
|--------------------------------------------------------------------------
|    End::Functions
|-------------------------------------------------------------------------- 
|
*/
}
