<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubjectsResource;
use App\Models\Grades;
use App\Models\Subjects;
use App\Models\Teachers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubjectsController extends Controller
{
    private $status = 200;

    public function list(Request $request) {
        $code = $request->code;
        $teacherId = $request->teacherId;
        if ($teacherId) {
            $subjects = Subjects::select('*')
                        ->where('teacher_id', $teacherId)
                        ->where('code', 'LIKE', $code . '%')
                        ->orderBy("created_at", "DESC")
                        ->get();
        } else {
            $subjects = Subjects::select('*')
                        ->where('code', 'LIKE', $code . '%')
                        ->orderBy("created_at", "DESC")
                        ->get();
        }
        return response([
            'data' => SubjectsResource::collection($subjects->loadMissing('grades')),
            'status' => $this->status
        ]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255',
            'units' => 'required|integer|max:255',
        ]);

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 405);
        } else {
            $subjects = new Subjects();
            $subjects->description = $request->description;
            $subjects->code = $request->code;
            $subjects->units = $request->units;
            $subjects->schedule = $request->schedule;
            $subjects->save();

            $response = [
                'message' => 'Subject Added!',
                'status' => $this->status
            ];
            return response($response, $this->status);
        }
    }

    public function show($id) {
        $subjects = Subjects::find($id);
        $data = New SubjectsResource($subjects);
        return response($data, $this->status);
    }

    public function update(Request $request) {
        Subjects::where(['id' => $request->id])->update([
            'description' => $request->description,
            'code' => $request->code,
            'units' => $request->units,
            'schedule' => $request->schedule,
        ]);
        $response = [
            'message' => 'Subject Information Saved',
            'status' => $this->status
        ];
        return response($response, $this->status);
    }

    public function assignTeacher(Request $request) {
        Subjects::where(['id' => $request->id])->update([
            'teacher_id' => $request->teacherId,
        ]);
        Grades::where(['subject_id' => $request->id])->update([
            'teacher_id' => $request->teacherId,
        ]);
        $response = [
            'message' => 'Teacher Assigned',
            'status' => $this->status
        ];
        return response($response, $this->status);
    }

    public function assignStudents(Request $request) {
        foreach($request->grades as $key) {
            $grades = new Grades();
            $grades->student_id = $key['studentId'];
            $grades->subject_id = $key['subjectId'];
            $grades->teacher_id = $key['teacherId'];
            $grades->save();
        }
        $response = [
            'message' => 'Subject Assigned',
            'status' => $this->status
        ];
        return response($response, $this->status);
    }
}
