<?php

namespace App\Http\Resources;

use App\Models\Addresses;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SubjectsResource;

class TeachersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = User::select('id')->where('teacher_id', $this->id)->first();
        return [
            'id' => $this->id,
            'dateHired' => $this->date_hired,
            'idNumber' => $this->id_number,
            'userDetails' => User::where(['teacher_id' => $this->id])->get([
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
                'gender',
                'is_email_verified as isEmailVerified'
            ])->first(),
            'addressDetails' => Addresses::where(['user_id' => $user->id])->get([
                'id',
                'house_no as houseNo',
                'barangay',
                'country',
                'province',
                'city',
                'zip_code as zipCode',
                'user_id as userId',
            ])->first(),
            'subjects' => SubjectsResource::collection($this->whenLoaded('subjects'))
        ];
    }
}
