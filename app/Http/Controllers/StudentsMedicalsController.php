<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentsMedicalsResources;
use App\Models\StudentsMedicals;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Image;

class StudentsMedicalsController extends Controller
{
    private $status = 200;

    public function list(Request $request) {
        $studentsMedicals = StudentsMedicals::select('*')
                            ->orderBy("created_at", "DESC")
                            ->get();
        return response([
            'data' => StudentsMedicalsResources::collection($studentsMedicals),
            'status' => $this->status
        ]);
    }

    public function store(Request $request) {
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();
        $medicals = StudentsMedicals::where('created_at', '>' , $startOfYear)->where('created_at', '<', $endOfYear)->get();
        $count = count($medicals) + 1;
        $medicalNumber = 'M'.Carbon::now()->toDateString().'-'.$count;

        $studentsMedicals = new StudentsMedicals();
        $studentsMedicals->parent_id = $request->parentId;
        $studentsMedicals->teacher_id = $request->teacherId;
        $studentsMedicals->children_id = $request->studentId;
        $studentsMedicals->subject_id = $request->subjectId;
        $studentsMedicals->note = $request->note;
        $studentsMedicals->status = 'P';
        $studentsMedicals->medical_number = $medicalNumber;
        $studentsMedicals->image = $count.'_'.$request->image;
        $studentsMedicals->save();

        $response = [
            'message' => 'Medical Saved',
            'status' => $this->status,
            'parentId' => $request->parentId
        ];
        return response($response, $this->status);
    }

    public function upload(Request $request)
    {
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();
        $medicals = StudentsMedicals::where('created_at', '>' , $startOfYear)->where('created_at', '<', $endOfYear)->get();
        $count = count($medicals) + 1;
        $fileName = $count.'_'.$request->file->getClientOriginalName();
        $request->file('file')->move('uploads/medicals', $fileName, 'public');
        $this->fileName = $fileName;

        $response = [
            'message' => 'Medical Uploaded',
            'status' => $this->status
        ];
        return response($response, $this->status);
    }
}
