<?php

namespace App\Http\Controllers;

use App\Http\Resources\UsersResource;
use App\Models\Addresses;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    private $status = 200;
    public function show($id)
    {
        $user = User::find($id);
        $data = New UsersResource($user->loadMissing('students'));
        return response($data, $this->status);
    }

    public function update(Request $request) {
        User::where(['id' => $request->id])->update([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'date_of_birth' => $request->dateOfBirth,
            'age' => $request->age,
            'gender' => $request->gender,
        ]);

        if ($request->addressDetails['id']) {
            Addresses::where(['user_id' => $request->id])->update([
                'house_no' => $request->addressDetails['houseNo'],
                'barangay' => $request->addressDetails['barangay'],
                'country' => $request->addressDetails['country'],
                'province' => $request->addressDetails['province'],
                'zip_code' => $request->addressDetails['zipCode'],
            ]);
        } else {
            $addresses = new Addresses();
            $addresses->house_no = $request->addressDetails['houseNo'];
            $addresses->barangay = $request->addressDetails['barangay'];
            $addresses->country = json_encode($request->addressDetails['country']);
            $addresses->province = json_encode($request->addressDetails['province']);
            $addresses->city = $request->addressDetails['city'];
            $addresses->zip_code = $request->addressDetails['zipCode'];
            $addresses->save();
            User::where(['id' => $request->id])->update([
                'address_id' => $addresses->id,
            ]);
            Addresses::where(['id' => $addresses->id])->update([
                'user_id' => $request->id,
            ]);
            //test
        }

        $response = [
            'message' => 'User Information Saved',
            'status' => $this->status
        ];
        return response($response, $this->status);
    }

    public function updatePassword(Request $request) {
        $currentPassword = User::select('password')->where('id', $request->id)->first();
        $oldPassword = $request->oldPassword;
        $current = $currentPassword->password;
        $newPassword = $request->newPassword;
        if (Hash::check($oldPassword, $currentPassword->password) ) {
            User::where(['id' => $request->id])->update([
                'password' => Hash::make($newPassword),
            ]);
            $response = [
                'message' => 'Password the same',
                'current' => $current,
                'oldPassword' => $oldPassword,
                'status' => $this->status
            ];
        } else {
            $this->status = 400;
            $response = [
                'message' => 'Current password does not match',
                'status' => $this->status
            ];
        }
        return response($response, $this->status);
    }
}
