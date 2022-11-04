<?php

namespace App\Http\Controllers;

use App\Http\Resources\GradesResource;
use App\Models\Grades;
use Illuminate\Http\Request;

class GradesController extends Controller
{
    private $status = 200;

    public function list(Request $request) {
        $subjectId = $request->subjectId;
        $grades = Grades::select('*')
                    ->where('subject_id', $subjectId)
                    ->orderBy("created_at", "DESC")
                    ->get();
        return response([
            'data' => GradesResource::collection($grades),
            'status' => $this->status
        ]);
    }

    public function show($studentId, $subjectId) {
        $grades = Grades::where('student_id', $studentId)->where('subject_id', $subjectId)->first();
        $data = New GradesResource($grades);
        return response($data, $this->status);
    }

    public function update(Request $request) {
        Grades::where(['id' => $request->id])->update([
            'student_id' => $request->studentId,
            'subject_id' => $request->subjectId,
            'teacher_id' => $request->teacherId,
            'prelim' => $request->prelim,
            'midterm' => $request->midterm,
            'endterm' => $request->endterm,
            'final_grade' => $request->finalGrade,
            'student_status' => $request->studentStatus,
            'absent' => $request->absent,
        ]);
        $response = [
            'message' => 'Grade Information Saved',
            'status' => $this->status
        ];
        return response($response, $this->status);
    }
}
