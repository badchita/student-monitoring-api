<?php

namespace App\Http\Resources;

use App\Models\Parents;
use App\Models\Students;
use App\Models\Subjects;
use App\Models\Teachers;
use Doctrine\Inflector\Rules\Substitutions;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentsMedicalsResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $parents = optional(Parents::find($this->parent_id));
        $teachers = optional(Teachers::find($this->teacher_id));
        $child = optional(Students::find($this->children_id));
        $subject = optional(Subjects::find($this->subject_id));
        return [
            'id' => $this->id,
            'parentId' => $this->parent_id,
            'studentId' => $this->children_id,
            'teacherId' => $this->teacher_id,
            'subjectId' => $this->subject_id,
            'note' => $this->note,
            'status' => $this->status,
            'medicalNumber' => $this->medical_number,
            'image' => $this->image,
            'parentDetails' => New ParentsResources($parents->loadMissing('students')),
            'teacherDetails' => New TeachersResource($teachers->loadMissing('subjects')),
            'studentDetails' => New StudentsResource($child->loadMissing('grades')),
            'subjectDetails' => New SubjectsResource($subject->loadMissing('grades')),
        ];
    }
}
