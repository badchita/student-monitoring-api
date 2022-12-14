<?php

namespace App\Http\Resources;

use App\Models\Addresses;
use App\Models\Parents;
use App\Models\Subjects;
use App\Models\Teachers;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $teacher = optional(Teachers::select('id')->where('id', $this->teacher_id)->first());
        $parents = optional(Parents::find($this->parent_id));
        // return ($this->teacher_id);
        return [
            'id' => $this->id,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'email' => $this->email,
            'username' => $this->username,
            'userType' => $this->user_type,
            'addressId' => $this->address_id,
            'gender' => $this->gender,
            'status' => $this->status,
            'statusVerification' => $this->status_verification,
            'childrenId' => $this->children_id,
            'mobile' => $this->mobile,
            'age' => $this->age,
            'dateOfBirth' => $this->date_of_birth,
            'teacherId' => $this->teacher_id,
            'parentId' => $this->parent_id,
            'isEmailVerified' => $this->is_email_verified,
            'addressDetails' => Addresses::where(['user_id' => $this->id])->get([
                'id',
                'house_no as houseNo',
                'barangay',
                'country',
                'province',
                'city',
                'zip_code as zipCode',
                'user_id as userId',
            ])->first(),
            'teacherDetails' => Teachers::where(['id' => $teacher->id])->get([
                'id',
                'date_hired as dateHired',
                'id_number as idNumber',
            ])->first(),
            'parentDetails' => New ParentsResources($parents->loadMissing('students')),
            'children' => StudentsResource::collection($this->whenLoaded('students'))
        ];
    }
}
