<?php

namespace App\Http\Resources;

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
        return [
            'id' => $this->id,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'email' => $this->email,
            'username' => $this->username,
            'userType' => $this->user_type,
            'addressId' => $this->address_id,
            'status' => $this->status,
            'statusVerification' => $this->status_verification,
            'childrenId' => $this->children_id,
            'mobile' => $this->mobile,
            'age' => $this->age,
            'teacherId' => $this->teacher_id,
        ];
    }
}
