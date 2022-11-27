<?php

namespace App\Http\Resources;

use App\Models\Parents;
use App\Models\Students;
use App\Models\Teachers;
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
        $parents = Parents::find($this->parent_id);
        $teachers = Teachers::find($this->teacher_id);
        $child = Students::find($this->children_id);
        return [
            'id' => $this->id,
            'parentId' => $this->parent_id,
            'studentId' => $this->children_id,
            'teacherId' => $this->teacher_id,
            'note' => $this->note,
            'status' => $this->status,
            'medical_number' => $this->endterm,
            'image' => $this->image,
            'parentDetails' => New ParentsResources($parents->loadMissing('students')),
            'teacherDetails' => New TeachersResource($teachers->loadMissing('subjects')),
            'studentDetails' => New StudentsResource($child->loadMissing('grades')),
        ];
    }
}
