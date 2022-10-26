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
}
