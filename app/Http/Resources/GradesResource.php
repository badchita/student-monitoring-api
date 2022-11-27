<?php

namespace App\Http\Resources;

use App\Models\Addresses;
use App\Models\Students;
use App\Models\Subjects;
use App\Models\Teachers;
use App\Models\User;
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
            'studentStatus' => $this->student_status,
            'absent' => $this->absent,
            'subjectDetails' => Subjects::where(['id' => $this->subject_id])->get([
                'id',
                'description',
                'code',
                'teacher_id as teacherId',
                'units',
                'schedule'
            ])->first(),
            'studentDetails' => Students::where(['id' => $this->student_id])->get([
                'id',
                'course',
                'section',
                'id_number as idNumber',
                'date_enrolled as dateEnrolled',
                'total_tuition_fee as totalTuitionFee',
                'year',
            ])->first(),
            'userDetails' => User::where(['children_id' => $this->student_id])->get([
                'id',
                'first_name as firstName',
                'last_name as lastName',
                'email',
                'email_verified_at as emailVerifiedAt',
                'username',
                'password',
                'user_type as userType',
                'date_of_birth as dateOfBirth',
                'date_of_joined as dateOfJoined',
                'address_id as addressId',
                'status',
                'status_verification as statusVerification',
                'children_id as childrenId',
                'mobile',
                'age',
                'teacher_id as teacherId',
                'address_id as addressId',
                'gender'
            ])->first(),
            'userTeacherDetails' => User::where(['teacher_id' => $this->teacher_id])->get([
                'id',
                'first_name as firstName',
                'last_name as lastName',
                'email',
                'email_verified_at as emailVerifiedAt',
                'username',
                'password',
                'user_type as userType',
                'date_of_birth as dateOfBirth',
                'date_of_joined as dateOfJoined',
                'address_id as addressId',
                'status',
                'status_verification as statusVerification',
                'children_id as childrenId',
                'mobile',
                'age',
                'teacher_id as teacherId',
                'address_id as addressId',
                'gender'
            ])->first(),
            'teacherDetails' => Teachers::where(['id' => $this->teacher_id])->get([
                'id',
                'date_hired as dateHired',
                'id_number as idNumber',
            ])->first(),
            'addressDetails' => Addresses::where(['user_id' => $this->student_id])->get([
                'id',
                'house_no as houseNo',
                'barangay',
                'country',
                'province',
                'city',
                'zip_code as zipCode',
                'user_id as userId',
            ])->first(),
        ];
    }
}
