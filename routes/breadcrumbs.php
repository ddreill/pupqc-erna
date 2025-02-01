<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Dashboard
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('dashboard'));
});



// Messages
Breadcrumbs::for('messages', function (BreadcrumbTrail $trail) {
    $trail->push('Messages', route('messages.index'));
});

// Messages > New message
Breadcrumbs::for('messages.createTopic', function (BreadcrumbTrail $trail) {
    $trail->parent('messages');
    $trail->push('New message', route('messages.createTopic'));
});

// Messages > Inbox
Breadcrumbs::for('messages.showInbox', function (BreadcrumbTrail $trail) {
    $trail->parent('messages');
    $trail->push('Inbox', route('messages.showInbox'));
});

// Messages > Outbox
Breadcrumbs::for('messages.showOutbox', function (BreadcrumbTrail $trail) {
    $trail->parent('messages');
    $trail->push('Outbox', route('messages.showOutbox'));
});



// List of Students
Breadcrumbs::for('students', function (BreadcrumbTrail $trail) {
    $trail->push(__('global.list_of', ['attribute' => __('cruds.student.title')]), route('students.index'));
});

// List of Students > Show Student
Breadcrumbs::for('students.create', function (BreadcrumbTrail $trail) {
    $trail->parent('students');
    $trail->push(__('global.add') . " " . __('cruds.student.title_singular'), route('students.create'));
});

// List of Students > Show Student
Breadcrumbs::for('students.show', function (BreadcrumbTrail $trail, $student) {
    $trail->parent('students');
    $trail->push(__('global.view') . " " . __('cruds.student.title_singular'), route('students.show', $student));
});

Breadcrumbs::for('students.edit', function (BreadcrumbTrail $trail, $studentUuid) {
    $trail->parent('students');
    $trail->push(__('global.edit') . " " . __('cruds.student.title_singular'), route('students.edit', $studentUuid));
});



// List of Gradesheets
Breadcrumbs::for('gradesheets', function (BreadcrumbTrail $trail) {
    $trail->push(__('global.list_of', ['attribute' => __('cruds.gradesheet.title')]), route('gradesheets.index'));
});

Breadcrumbs::for('gradesheets.create', function (BreadcrumbTrail $trail) {
    $trail->parent('gradesheets');
    $trail->push(__('Create Gradesheet'), route('admin.gradesheet.create'));
});

Breadcrumbs::for('gradesheets.show', function (BreadcrumbTrail $trail, $gradesheet) {
    $trail->parent('gradesheets');
    $trail->push(__('global.view') . " " . __('Gradesheet'), route('admin.gradesheet.show', $gradesheet));
});

Breadcrumbs::for('gradesheets.edit', function (BreadcrumbTrail $trail, $gradesheet) {
    $trail->parent('gradesheets');
    $trail->push(__('global.edit') . " " . __('Gradesheet'), route('admin.gradesheet.edit', $gradesheet));
});




// List of Users
Breadcrumbs::for('users', function (BreadcrumbTrail $trail) {
    $trail->push(__('global.list_of', ['attribute' => __('cruds.user.title')]), route('users.index'));
});

// List of Users > Edit User
Breadcrumbs::for('users.edit', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('users');
    $trail->push(__('global.edit') . " " . __('cruds.user.title_singular'), route('users.edit', $user));
});



// System Configuration and Maintenance
Breadcrumbs::for('settings', function (BreadcrumbTrail $trail) {
    $trail->push(__('cruds.systemConfigMaintenance.title'), route('settings.index'));
});


// System Configuration and Maintenance > List of Documents
Breadcrumbs::for('settings.documents', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(__('global.list_of', ['attribute' => __('cruds.document.title')]), route('settings.documents.index'));
});

// System Configuration and Maintenance > List of Documents > Add Document
Breadcrumbs::for('settings.documents.create', function (BreadcrumbTrail $trail) {
    $trail->parent('settings.documents');
    $trail->push(__('global.add') . " " . __('cruds.document.title_singular'), route('settings.documents.create'));
});

// System Configuration and Maintenance > List of Documents > View Document
Breadcrumbs::for('settings.documents.show', function (BreadcrumbTrail $trail, $document) {
    $trail->parent('settings.documents');
    $trail->push(__('global.view') . " " . __('cruds.document.title_singular'), route('settings.documents.show', $document));
});

// System Configuration and Maintenance > List of Documents > Edit Document
Breadcrumbs::for('settings.documents.edit', function (BreadcrumbTrail $trail, $document) {
    $trail->parent('settings.documents');
    $trail->push(__('global.edit') . " " . __('cruds.document.title_singular'), route('settings.documents.edit', $document));
});


// System Configuration and Maintenance > List of Courses
Breadcrumbs::for('settings.courses', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(__('global.list_of', ['attribute' => __('cruds.course.title')]), route('settings.courses.index'));
});

// System Configuration and Maintenance > List of Courses > Add Course
Breadcrumbs::for('settings.courses.create', function (BreadcrumbTrail $trail) {
    $trail->parent('settings.courses');
    $trail->push(__('global.add') . " " . __('cruds.course.title_singular'), route('settings.courses.create'));
});

// System Configuration and Maintenance > List of Courses > Edit Course
Breadcrumbs::for('settings.courses.edit', function (BreadcrumbTrail $trail, $course) {
    $trail->parent('settings.courses');
    $trail->push(__('global.edit') . " " . __('cruds.course.title_singular'), route('settings.courses.edit', $course));
});


// System Configuration and Maintenance > List of Honors
Breadcrumbs::for('settings.honors', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(__('global.list_of', ['attribute' => __('cruds.honor.title')]), route('settings.honors.index'));
});

// System Configuration and Maintenance > List of Honors > Add Honor
Breadcrumbs::for('settings.honors.create', function (BreadcrumbTrail $trail) {
    $trail->parent('settings.honors');
    $trail->push(__('global.add') . " " . __('cruds.honor.title_singular'), route('settings.honors.create'));
});

// System Configuration and Maintenance > List of Honors > Edit Honor
Breadcrumbs::for('settings.honors.edit', function (BreadcrumbTrail $trail, $honor) {
    $trail->parent('settings.honors');
    $trail->push(__('global.edit') . " " . __('cruds.honor.title_singular'), route('settings.honors.edit', $honor));
});


// System Configuration and Maintenance > List of Rooms
Breadcrumbs::for('settings.rooms', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(__('global.list_of', ['attribute' => __('cruds.room.title')]), route('settings.rooms.index'));
});

// System Configuration and Maintenance > List of Rooms > Add Room
Breadcrumbs::for('settings.rooms.create', function (BreadcrumbTrail $trail) {
    $trail->parent('settings.rooms');
    $trail->push(__('global.add') . " " . __('cruds.room.title_singular'), route('settings.rooms.create'));
});

// System Configuration and Maintenance > List of Rooms > Edit Room
Breadcrumbs::for('settings.rooms.edit', function (BreadcrumbTrail $trail, $room) {
    $trail->parent('settings.rooms');
    $trail->push(__('global.edit') . " " . __('cruds.room.title_singular'), route('settings.rooms.edit', $room));
});


// System Configuration and Maintenance > List of Instructors
Breadcrumbs::for('settings.instructors', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(__('global.list_of', ['attribute' => __('cruds.instructor.title')]), route('settings.instructors.index'));
});

// System Configuration and Maintenance > List of Instructors > Add Instructor
Breadcrumbs::for('settings.instructors.create', function (BreadcrumbTrail $trail) {
    $trail->parent('settings.instructors');
    $trail->push(__('global.add') . " " . __('cruds.instructor.title_singular'), route('settings.instructors.create'));
});

// System Configuration and Maintenance > List of Instructors > Edit Instructor
Breadcrumbs::for('settings.instructors.edit', function (BreadcrumbTrail $trail, $instructor) {
    $trail->parent('settings.instructors');
    $trail->push(__('global.edit') . " " . __('cruds.instructor.title_singular'), route('settings.instructors.edit', $instructor));
});


// System Configuration and Maintenance > List of Subjects
Breadcrumbs::for('settings.subjects', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(__('global.list_of', ['attribute' => __('cruds.subject.title')]), route('settings.subjects.index'));
});

// System Configuration and Maintenance > List of Subjects > Add Subject
Breadcrumbs::for('settings.subjects.create', function (BreadcrumbTrail $trail) {
    $trail->parent('settings.subjects');
    $trail->push(__('global.add') . " " . __('cruds.subject.title_singular'), route('settings.subjects.create'));
});

// System Configuration and Maintenance > List of Subjects > Edit Subject
Breadcrumbs::for('settings.subjects.edit', function (BreadcrumbTrail $trail, $subject) {
    $trail->parent('settings.subjects');
    $trail->push(__('global.edit') . " " . __('cruds.subject.title_singular'), route('settings.subjects.edit', $subject));
});


// System Configuration and Maintenance > List of Year Levels
Breadcrumbs::for('settings.year-levels', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(__('global.list_of', ['attribute' => __('cruds.yearLevel.title')]), route('settings.year-levels.index'));
});

// System Configuration and Maintenance > List of Year Levels > Add Year Level
Breadcrumbs::for('settings.year-levels.create', function (BreadcrumbTrail $trail) {
    $trail->parent('settings.year-levels');
    $trail->push(__('global.add') . " " . __('cruds.yearLevel.title_singular'), route('settings.year-levels.create'));
});

// System Configuration and Maintenance > List of Year Levels > Edit Year Level
Breadcrumbs::for('settings.year-levels.edit', function (BreadcrumbTrail $trail, $yearLevel) {
    $trail->parent('settings.year-levels');
    $trail->push(__('global.edit') . " " . __('cruds.yearLevel.title_singular'), route('settings.year-levels.edit', $yearLevel));
});


// System Configuration and Maintenance > List of Semesters
Breadcrumbs::for('settings.semesters', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(__('global.list_of', ['attribute' => __('cruds.semester.title')]), route('settings.semesters.index'));
});

// System Configuration and Maintenance > List of Semesters > Add Semester
Breadcrumbs::for('settings.semesters.create', function (BreadcrumbTrail $trail) {
    $trail->parent('settings.semesters');
    $trail->push(__('global.add') . " " . __('cruds.semester.title_singular'), route('settings.semesters.create'));
});

// System Configuration and Maintenance > List of Semesters > Edit Semester
Breadcrumbs::for('settings.semesters.edit', function (BreadcrumbTrail $trail, $semester) {
    $trail->parent('settings.semesters');
    $trail->push(__('global.edit') . " " . __('cruds.semester.title_singular'), route('settings.semesters.edit', $semester));
});


// System Configuration and Maintenance > List of School Years
Breadcrumbs::for('settings.school-years', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(__('global.list_of', ['attribute' => __('cruds.schoolYear.title')]), route('settings.school-years.index'));
});

// System Configuration and Maintenance > List of School Years > Add School Year
Breadcrumbs::for('settings.school-years.create', function (BreadcrumbTrail $trail) {
    $trail->parent('settings.school-years');
    $trail->push(__('global.add') . " " . __('cruds.schoolYear.title_singular'), route('settings.school-years.create'));
});


// System Configuration and Maintenance > List of Backups
Breadcrumbs::for('settings.backups', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(__('global.list_of', ['attribute' => __('Backups')]), route('settings.backups.index'));
});
