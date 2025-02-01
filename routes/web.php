<?php

use App\Models\Configuration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Auth\SSOController;

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('dashboard')->with('status', session('status'));
    }

    return redirect()->route('dashboard');
})->name('home');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Auth::routes();

Route::group(['middleware' => ['auth'], 'prefix' => 'email', 'as' => "verification."], function () {

    Route::get('/email/verify', function () {
        return view('auth.verify');
    })->name('notice');

    Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect('/home')->with('verified', true);
    })->middleware(['signed'])->name('verify');

    Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link has been sent.');
    })->middleware(['throttle:6,1'])->name('send');

    Route::get('/email/verification-notification', function (\Illuminate\Http\Request $request) {
        return view('auth.verify');
    })->name('resend');
});

/*
|--------------------------------------------------------------------------
| Google SSO Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'auth', 'as' => 'sso.'], function () {

    Route::get('{provider}', [SSOController::class, 'login'])->name('login');
    Route::get('redirect/{provider}', [SSOController::class, 'redirect'])->name('redirect');
    Route::get('register/{provider}/{token}', [SSOController::class, 'register'])->name('register');
});

$defaultMiddlewares = ['auth', 'user.status'];

if (Schema::hasTable((new Configuration())->table)) {
    foreach (Configuration::select('key', 'value')->whereIn('key', ['panel.2fa', 'panel.email_verified'])->get() as $config) {
        switch ($config->key) {
            case 'panel.2fa':
                if ($config->value == "on") {
                    array_push($defaultMiddlewares, '2fa');
                }
                break;

            case 'panel.email_verified':
                if ($config->value == "on") {
                    array_push($defaultMiddlewares, 'verified');
                }
                break;
        }
    }
}

Route::group(['middleware' => $defaultMiddlewares], function () {

    Route::group(['namespace' => 'Admin'], function () {

        // Dashboard
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
        Route::get('ajax/student-per-year', 'DashboardController@ajax_retrieve_total_student_per_year');

        // Global Search
        Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');

        // Gradesheets
        Route::resource('gradesheets', 'GradesheetController');

        // Students
        Route::resource('students', 'StudentController');
        Route::post('students/{student}/archive', 'StudentController@archive')->name('students.archive');
        Route::post('students/{student}/unarchive', 'StudentController@unarchive')->name('students.unarchive');
        Route::get('students/{student}/export/envelope-document-evaluation', 'StudentController@exportDocuments')->name('students.export.envelope-document-evaluation');
        Route::get('students/{student}/export/scholastic-data', 'StudentController@exportScholastic')->name('students.export.scholastic-data');

        // User Accounts
        Route::patch('users/{user}/status', 'UsersController@updateStatus')->name('users.update-status');
        Route::resource('users', 'UsersController');

        // Roles
        Route::resource('roles', 'RolesController');

        // User Alerts
        Route::get('user-alerts/read', 'UserAlertsController@read');
        Route::resource('user-alerts', 'UserAlertsController', ['except' => ['edit', 'update']]);

        // In-app Messaging
        Route::get('messages', 'MessagesController@index')->name('messages.index');
        Route::get('messages/create', 'MessagesController@createTopic')->name('messages.createTopic');
        Route::post('messages', 'MessagesController@storeTopic')->name('messages.storeTopic');
        Route::get('messages/inbox', 'MessagesController@showInbox')->name('messages.showInbox');
        Route::get('messages/outbox', 'MessagesController@showOutbox')->name('messages.showOutbox');
        Route::get('messages/{topic}', 'MessagesController@showMessages')->name('messages.showMessages');
        Route::delete('messages/{topic}', 'MessagesController@destroyTopic')->name('messages.destroyTopic');
        Route::post('messages/{topic}/reply', 'MessagesController@replyToTopic')->name('messages.reply');
        Route::get('messages/{topic}/reply', 'MessagesController@showReply')->name('messages.showReply');


        Route::get('gradesheet/create', 'GradesheetController@create')->name('admin.gradesheet.create');
        Route::get('gradesheet/{gradesheet}', 'GradesheetController@show')->whereNumber('gradesheet')->name('admin.gradesheet.show');
        Route::post('gradesheet/{gradesheet}/validate/student-enrollment', 'GradesheetController@validateStudentEnrollment')->name('admin.gradesheet.validate.student-enrollment');
        Route::post('gradesheet/{gradesheet}/form-validate/student', 'GradesheetController@validateStudent')->name('admin.gradesheet.form-validate.student');
        Route::get('gradesheet/{gradesheet}/pages', 'GradesheetController@getPages')->name('admin.gradesheet.pages');
        Route::get('gradesheet/{gradesheet}/page-rows', 'GradesheetController@getPageRows')->name('admin.gradesheet.page.rows');
        Route::post('gradesheet/{gradesheet}/page-details', 'GradesheetController@getPageDetails')->name('admin.gradesheet.pages-details');

        Route::post('gradesheet/store', 'GradesheetController@store');
        Route::get('gradesheet/{gradesheet}/edit', 'GradesheetController@edit')->name('admin.gradesheet.edit');
        Route::post('gradesheet/{gradesheet}', 'GradesheetController@save')->name('admin.gradesheet.update');

        Route::post('gradesheet/{gradesheet}/students', 'GradesheetController@storeStudents')->name('admin.gradesheet-students.store');
        Route::get('gradesheet/{gradesheet}/students/{student}', 'GradesheetController@showStudent')->name('admin.gradesheet-students.show');
        Route::post('gradesheet/{gradesheet}/students/{student}', 'GradesheetController@updateStudent')->name('admin.gradesheet-students.update');
        Route::delete('gradesheet/{gradesheet}/students/{student}', 'GradesheetController@destroyStudent')->name('admin.gradesheet-students.destroy');

        Route::get('gradesheet/{gradesheet}/generate/pdf', 'GradesheetController@generatePdf')->name('admin.gradesheet.generate.pdf');
        Route::post('student/profile/add', 'StudentController@ajax_insert');
        Route::post('student/profile/retrieve', 'StudentController@ajax_retrieve')->name('admin.student.ajaxRetrieve');
        Route::post('student/profile/edit', 'StudentController@ajax_edit');

        Route::get('ajax/student/profile/retrieve-all', 'StudentController@ajax_retrieve_student_list')->name('admin.student.fetch');
        Route::get('ajax/student/profile/retrieve-archived', 'StudentController@ajax_retrieve_archived_student_list');
        Route::post('ajax/student/profile/validate-studentNo', 'StudentController@ajax_validate_studentNo');

        Route::get('student/profile/add', 'StudentController@create_profile')->name('admin.student.create');
        Route::get('student/profile/{profile_uuid}/edit', 'StudentController@edit_profile')->name('admin.student.edit');

        Route::post('student/profile/retrieve-documents', 'StudentController@ajax_retrieve_documents');
        Route::post('student/profile/retrieve-prev-college', 'StudentController@ajax_retrieve_prevCollege');

        // Subject
        Route::post('subject/select2', 'SubjectController@ajax_select2_search');

        // Instructor
        Route::post('instructor/select2', 'InstructorController@ajax_select2_search');

        // Course
        Route::post('select2/course', 'CourseController@select2');

        // Room
        Route::post('room/select2', 'RoomController@ajax_select2_search');

        // Documents Type
        Route::get('documents/category/{category}', 'DocumentController@ajax_retrieve_by_category');
        Route::post('documents/document-types', 'DocumentController@ajax_retrieveTypes');

        // School Year
        Route::post('select2/settings/school-year/base', 'SchoolYearController@ajax_select2_base_search');
    });

    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {

        Route::group(['namespace' => 'Admin'], function () {

            Route::get('/', function () {
                return view('admin.settings.index');
            })->name('index');

            // Documents
            Route::resource('documents', 'DocumentController');

            // Courses 
            Route::resource('courses', 'CourseController');

            // Honors
            Route::resource('honors', 'HonorsController');

            // Rooms
            Route::resource('rooms', 'RoomController');

            // Instructors
            Route::resource('instructors', 'InstructorController');

            // Subjects
            Route::resource('subjects', 'SubjectController');

            // Year Level
            Route::resource('year-levels', 'YearLevelController');

            // Semesters
            Route::resource('semesters', 'SemesterController');
            Route::post('semester/select2', 'SemesterController@ajax_select2_search');

            // School year
            Route::resource('school-years', 'SchoolYearController');
            Route::post('school-year/select2', 'SchoolYearController@ajax_select2_plus_search');

            // Backups
            Route::resource('backups', 'BackupController');
            Route::get('backups/download/{file_name}', 'BackupController@download')->name('backups.export');
        });
    });

    Route::group(['prefix' => 'account', 'as' => 'account.', 'namespace' => 'Auth'], function () {
        // Account Settings
        if (file_exists(app_path('Http/Controllers/Auth/UserAccountController.php'))) {
            Route::get('overview', 'UserAccountController@overview')->name('overview');
            Route::get('settings', 'UserAccountController@settings')->name('settings');
            Route::post('edit/password', 'UserAccountController@updatePassword')->name('update_password');
            Route::post('edit/profile', 'UserAccountController@updateProfile')->name('update_profile');
        }
    });

    Route::group(['namespace' => 'Auth'], function () {
        // Two Factor Authentication
        if (file_exists(app_path('Http/Controllers/Auth/TwoFactorController.php'))) {
            Route::get('two-factor', 'TwoFactorController@show')->name('twoFactor.show');
            Route::post('two-factor', 'TwoFactorController@check')->name('twoFactor.check');
            Route::get('two-factor/resend', 'TwoFactorController@resend')->name('twoFactor.resend');
        }
    });
});