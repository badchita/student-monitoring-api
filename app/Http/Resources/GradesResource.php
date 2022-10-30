<?php

namespace App\Http\Resources;

use App\Models\Subjects;
use Illuminate\Http\Resources\Json\JsonResource;

class GradesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'studentId' => $this->student_id,
            'subjectId' => $this->subject_id,
            'teacherId' => $this->teacher_id,
            'prelim' => $this->prelim,
            'midterm' => $this->midterm,
            'endterm' => $this->endterm,
            'finalGrade' => $this->final_grade,
            'subjectDetails' => Subjects::where(['id' =>$this->subject_id])->get([
                'id',
                'description',
                'code',
                'teacher_id as teacherId',
                'units',
                'schedule'
            ])->first(),
        ];
    }
}
