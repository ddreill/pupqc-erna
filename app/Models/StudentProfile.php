<?php

namespace App\Models;

use Carbon\Carbon;
use Faker\Generator;
use App\Traits\Archivable;
use Illuminate\Database\Eloquent\Model;
use App\Observers\StudentActionObserver;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentProfile extends Model
{
    use SoftDeletes, Archivable;

    public static function boot()
    {
        parent::boot();

        User::observe(StudentActionObserver::class);
    }

    public $table = 'r_student';
    public $primaryKey = 'stud_id';

    const CREATED_AT = 'stud_createdAt';
    const UPDATED_AT = 'stud_updatedAt';
    const DELETED_AT = 'stud_deletedAt';

    protected $fillable = [
        'stud_uuid',
        'stud_studentNo',
        'stud_firstName',
        'stud_middleName',
        'stud_lastName',
        'stud_addressLine',
        'stud_addressCity',
        'stud_addressProvince',
        'stud_yearOfAdmission',
        'stud_admissionType',
        'stud_isGraduated',
        'stud_dateExited',
        'stud_honor',
        'stud_recordType',
        'stud_academicStatus',
        'stud_recordStatus',
        'stud_isHonorableDismissed',
        'stud_honorableDismissedStatus',
        'stud_honorableDismissedDate',
        'stud_honorableDismissedSchool',
        'stud_validationStatus',
        'stud_remarks',
        'cour_stud_id',
        'curr_stud_id',
        'user_stud_id'
    ];

    protected $dates = [
        'stud_createdAt',
        'stud_updatedAt',
        'stud_deletedAt',
        'archived_at',
    ];

    protected $cast = [
        'stud_isHonorableDismissed' => 'bool'
    ];

    public function getFullNameAttribute()
    {
        return sprintf('%s, %s %s', $this->stud_lastName, $this->stud_firstName, $this->stud_middleName);
    }

    public function getArchivedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setArchivedAtAttribute($value)
    {
        $this->attributes['archived_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }



    public function getEntranceDocuments()
    {
        return $this->documents()->where('docu_category', \App\Enums\DocumentCategoriesEnum::ENTRANCE)->where('docu_isPermanent', 'NO')->get();
    }

    public function getRecordsDocuments()
    {
        return $this->documents()->where('docu_category', \App\Enums\DocumentCategoriesEnum::RECORDS)->where('docu_isPermanent', 'NO')->get();
    }

    public function getExitDocuments()
    {
        return $this->documents()->where('docu_category', \App\Enums\DocumentCategoriesEnum::EXIT)->where('docu_isPermanent', 'NO')->get();
    }

    public function getRegistrationCerts()
    {
        return $this->documents()
            ->where('docu_category', \App\Enums\DocumentCategoriesEnum::RECORDS)
            ->where('docu_id', '1')
            ->get();
    }

    public function gradesheets()
    {
        return $this->belongsToMany(Gradesheet::class, 't_student_enrolled_subjects', 'stud_enrsub_id', 'class_enrsub_id')->withPivot([
            'enrsub_midtermGrade',
            'enrsub_finalGrade',
            'enrsub_finalRating',
            'enrsub_grade_status',
            'enrsub_status',
        ]);
    }

    public function getGrades()
    {
        $studentGrades = [];

        $studentGradesQuery = $this->with([
            'gradesheets',
            'gradesheets.subject',
            'gradesheets.instructor',
            'gradesheets.semester'
        ])->whereHas('gradesheets', function ($query) {
            $query->where('stud_enrsub_id', $this->stud_id);
        })->get()->map(function ($student) {
            return $student->gradesheets->sortByDesc('class_acadYear')
                ->groupBy('class_acadYear')->map(function ($gradesheets) {
                    return $gradesheets->groupBy(function ($gradesheet) {
                        return $gradesheet->semester->term_name;
                    })->map(function ($gradesheet) {
                        return $gradesheet->sortByDesc(function ($gradesheet) {
                            return $gradesheet->semester->term_order;
                        });
                    });
                });
        })->first();

        if ($studentGradesQuery) {
            $studentGrades = $studentGradesQuery;
        }

        return $studentGrades;
    }

    public function getScholasticSummary()
    {
        $totalSemesters['all'] = 0;
        $totalSemesters['summer'] = 0;

        $studentGrades = $this->getGrades();

        foreach ($studentGrades as $year => $schoolYears) {
            foreach ($schoolYears as $semester => $semesters) {
                if ($semester == "Summer Semester") {
                    $totalSemesters['summer']++;
                }

                $totalSemesters['all']++;
            }
        }

        return $totalSemesters;
    }

    public function getActivityLogs()
    {
        return ActivityLog::with(['user'])->where(['subject_type' => 'App\Models\StudentProfile', 'subject_id' => $this->stud_id])->orderBy('created_at', 'desc')->limit(4)->get();
    }

    public function honor()
    {
        return $this->hasOne(Honor::class, 'honor_id', 'stud_honor');
    }

    public function course()
    {
        return $this->hasOne(Course::class, 'cour_id', 'cour_stud_id');
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class, 't_submitted_documents', 'subm_student', 'subm_document')->withPivot([
            'subm_documentType',
            'subm_documentType_1',
            'subm_documentType_2',
            'subm_documentType_3',
            'subm_documentCategory',
            'subm_remarks',
            'subm_dateSubmitted'
        ]);
    }

    public function schools()
    {
        return $this->hasMany(PreviousSchool::class, 'extsch_stud_id', 'stud_id');
    }



    // Subject details
    public function fetchOne($md5Id)
    {

        $data = $this->leftJoin('s_course', 'cour_stud_id', '=', 'cour_id')
            ->whereRaw('md5(stud_id) = "' . $md5Id . '"')
            ->selectRaw('r_student.*
            , md5(stud_id) as stud_id_md5
            , cour_name as stud_course_name
            , cour_code as stud_course')
            ->get();

        $data[0]['stud_fullName'] = format_name(1, null,  $data[0]['stud_firstName'], $data[0]['stud_middleName'], $data[0]['stud_lastName'], $data[0]['stud_suffix']);
        return $data;
    }

    public function insertOne($data)
    {

        $faker = new Generator();
        $faker->addProvider(new \Faker\Provider\Base($faker));

        $this->stud_studentNo = $data['studentNo'];
        $this->stud_uuid = $faker->uuid();
        $this->stud_firstName = $data['firstName'];
        $this->stud_middleName = $data['middleName'];
        $this->stud_lastName = $data['lastName'];
        $this->stud_suffix = $data['suffix'];
        $this->cour_stud_id = $data['course'];
        $this->stud_yearOfAdmission = $data['yearOfAdmission'];
        $this->stud_admissionType = $data['admissionType'];
        $this->stud_isGraduated = $data['isGraduated'];

        if ($data['isGraduated'] == "YES") {
            $this->stud_dateGraduated = date("Y-m-d", strtotime($data['dateGraduated']));
            $this->stud_honor = $data['honor'];
        } else {
            $this->stud_dateGraduated = null;
            $this->stud_honor = null;
        }

        $this->stud_addressLine = $data['addressLine'];
        $this->stud_addressCity = $data['addressCity'];
        $this->stud_addressProvince = $data['addressProvince'];
        $this->stud_recordStatus = 'UNVALIDATED';
        $this->save();

        return $this->id;
    }

    public function fetchAll($filter)
    {

        $user = \Auth::user();
        $user_roleID = $user->roles->pluck('id')->toArray()[0];

        if ($user_roleID == 2) {
            if ($filter) {
                array_push($filter, array(["user_stud_id" => $user->id]));
            } else {
                $filter = ["user_stud_id" => $user->id];
            }
        }

        $data = $this->leftJoin('s_course', 'cour_stud_id', '=', 'cour_id')
            ->selectRaw('r_student.*
            , md5(stud_id) as stud_id_md5
            , cour_name as stud_course_name
            , cour_code as stud_course')
            ->where($filter)
            ->get();

        $i = 0;
        foreach ($data as $profile) {

            $data[$i]['stud_fullName'] = format_name(1, null,  $profile['stud_firstName'], $profile['stud_middleName'], $profile['stud_lastName'], $profile['stud_suffix']);
            $i++;
        }

        return $data;
    }

    public function edit($md5Id, $data)
    {
        $user = \Auth::user();
        $user_roleID = $user->roles->pluck('id')->toArray()[0];

        if ($user_roleID == 2) {
            $data["stud_recordStatus"] = "UNVALIDATED";
        }

        $this->whereRaw('md5(stud_id) = "' . $md5Id . '"')
            ->update($data);
    }

    public function remove($md5Id)
    {
        $this->whereRaw('md5(stud_id) = "' . $md5Id . '"')
            ->delete();
    }
}
