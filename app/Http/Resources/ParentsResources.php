<?php

namespace App\Http\Resources;

use App\Models\Addresses;
use App\Models\Parents;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ParentsResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = User::select('id')->where('parent_id', $this->id)->first();
        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'userDetails' => User::where(['parent_id' => $this->id])->get([
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
                'mobile',
                'age',
                'parent_id as parentId',
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
            'children' => StudentsResource::collection($this->whenLoaded('students'))
        ];
    }
}
