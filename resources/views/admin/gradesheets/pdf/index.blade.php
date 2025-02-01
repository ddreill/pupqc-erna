<!DOCTYPE html>
<html>

<head>
    <title>GRADESHEET</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="{{ public_path('assets/plugins/custom/bootstrap-4.6.2/css/bootstrap.min.css') }}" rel="stylesheet"
        type="text/css" />

    <style>
        div.breakNow {
            page-break-inside: avoid;
            page-break-after: always;
        }

        *,
        ::after,
        ::before {
            box-sizing: border-box;
        }

        body {
            font-size: 13px;
        }

        .table thead th {
            border-bottom-color: #a7a7a7;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #7c7c7c
        }

        .table-xs td,
        .table-xs th {
            padding: 2px;
            padding-left: 0.3rem;
            padding-right: 0.3rem;
        }
    </style>
</head>

<body>
    <p class="font-weight-bold pb-0 text-center" style="font-size: 1rem">GRADE SHEET</p>

    <div>
        <table class="table table-bordered table-sm">
            <thead>
                <tr class="table-active">
                    <th scope="col" width="15%"
                        class="text-center font-weight-normal small align-middle font-weight-bold">
                        STUDENT NO.
                    </th>
                    <th scope="col" width="5%" class="font-weight-normal small align-middle font-weight-bold">
                    </th>
                    <th scope="col" width="45%"
                        class="text-center font-weight-normal small align-middle font-weight-bold">
                        STUDENT'S NAME <br /> (Alphabetical Order)
                    </th>
                    <th scope="col" width="5%"
                        class="text-center font-weight-normal small align-middle font-weight-bold">
                        FIRST GRADING
                    </th>
                    <th scope="col" width="5%"
                        class="text-center font-weight-normal small align-middle font-weight-bold">
                        SECOND GRADING
                    </th>
                    <th scope="col" width="5%"
                        class="text-center font-weight-normal small align-middle font-weight-bold">
                        FINAL RATING
                    </th>
                    <th scope="col" width="10%"
                        class="text-center font-weight-normal small align-middle font-weight-bold">
                        REMARKS
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($gradesheet['students'] as $key => $student)
                    <tr>
                        <td class="align-middle text-center">{{ $student->stud_studentNo }}</td>
                        <td class="align-middle text-center">{{ $key + 1 }}</td>
                        <td class="align-middle">{{ $student->stud_lastName }}, {{ $student->stud_firstName }}
                            {{ $student->stud_middleName }}</td>
                        <td class="align-middle text-center">{{ $student->midterm_grade }}</td>
                        <td class="align-middle text-center">{{ $student->final_grade }}</td>
                        <td class="align-middle text-center">{{ $student->final_rating }}</td>
                        <td class="align-middle text-center">{{ $student->grade_status }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="7" class="text-center"><b>* * * NOTHING FOLLOWS * * *</b></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div>
        <table class="table table-borderless table-sm mb-0">
            <tr>
                <td class="align-bottom">Subject Code:</td>
                <th class="align-bottom">{{ $gradesheet['class_subj_code'] }}</th>
                <td class="align-bottom">Description:</td>
                <th class="align-bottom" colspan="5">{{ $gradesheet['class_subj_name'] }}</th>
                <td class="align-bottom">Units:</td>
                <th class="align-bottom">{{ $gradesheet['class_subj_units'] }}</th>
            </tr>
        </table>

        <table class="table table-borderless table-sm">
            <tr>
                <td class="align-bottom">Section:</td>
                <th class="align-bottom">{{ $gradesheet['class_section'] }}</th>
                <td class="align-bottom ">Schedule:</td>
                <th class="align-bottom">{{ $gradesheet['class_day_time'] }}</th>
                <td class="align-bottom">Room:</td>
                <th class="align-bottom">{{ $gradesheet['class_room'] }}</th>
                <td class="align-bottom">Semester:</td>
                <th class="align-bottom">{{ $gradesheet['class_semester'] }}</th>
                <td class="align-bottom">School Year :</td>
                <th class="align-bottom">{{ $gradesheet['class_sy'] }}</th>
            </tr>
        </table>
    </div>
</body>

</html>
