<?php

namespace App\Http\Controllers;

use App\Http\Resources\ParentsResources;
use App\Models\Addresses;
use App\Models\Parents;
use App\Models\Students;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ParentsController extends Controller
{
    private $status = 200;

    public function list(Request $request) {
        $idNumber = $request->idNumber;
        $parents = Parents::select('*')
                    // ->where('id_number', 'LIKE', $idNumber . '%')
                    ->orderBy("created_at", "DESC")
                    ->get();
        return response([
            'data' => ParentsResources::collection($parents),
            'status' => $this->status
        ]);
    }

    public function show($id) {
        $parents = Parents::find($id);
        $data = New ParentsResources($parents->loadMissing('students'));
        return response($data, $this->status);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'userDetails.firstName' => 'required|string|max:255',
            'userDetails.lastName' => 'required|string|max:255',
            'userDetails.email' => 'required|string|email|max:255|unique:users,email',
            'userDetails.mobile' => 'required|string|unique:users,mobile',
            'userDetails.dateOfBirth' => 'string|max:255',
            'userDetails.age' => 'integer',
            'addressDetails.country' => 'required|max:255',
            'addressDetails.province' => 'required|max:255',
            'addressDetails.city' => 'required|string|max:255',
            'addressDetails.zipCode' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 405);
        } else {
            $parents = new Parents();
            $parents->save();

            $users = new User();
            $users->first_name = $request->userDetails['firstName'];
            $users->last_name = $request->userDetails['lastName'];
            $users->email = $request->userDetails['email'];
            $users->mobile = $request->userDetails['mobile'];
            $users->date_of_birth = $request->userDetails['dateOfBirth'];
            $users->date_of_joined = $request->userDetails['dateOfJoined'];
            $users->age = $request->userDetails['age'];
            $users->gender = $request->userDetails['gender'];
            $users->status = 'V';
            $users->user_type = 'parent';
            $users->parent_id = $parents->id;
            $users->save();

            $addresses = new Addresses();
            $addresses->house_no = $request->addressDetails['houseNo'];
            $addresses->barangay = $request->addressDetails['barangay'];
            $addresses->country = json_encode($request->addressDetails['country']);
            $addresses->province = json_encode($request->addressDetails['province']);
            $addresses->city = $request->addressDetails['city'];
            $addresses->zip_code = $request->addressDetails['zipCode'];
            $addresses->save();

            User::where(['id' => $users->id])->update([
                'address_id' => $addresses->id,
            ]);

            Addresses::where(['id' => $addresses->id])->update([
                'user_id' => $users->id,
            ]);

            Parents::where(['id' => $users->parent_id])->update([
                'user_id' => $users->id,
            ]);

            $response = [
                'message' => 'Parent Added!',
                'status' => $this->status
            ];
            return response($response, $this->status);
        }
    }

    public function assignStudents(Request $request) {
        foreach ($request->children as $key) {
            Students::where(['id' => $key['studentId']])->update([
                'parent_id' => $request->parentId,
            ]);
        }
        $response = [
            'message' => 'Children Assigned!',
            'status' => $this->status
        ];
        return response($response, $this->status);
    }
}
