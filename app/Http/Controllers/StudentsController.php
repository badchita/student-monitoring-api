<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentsResource;
use App\Models\Addresses;
use App\Models\Students;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentsController extends Controller
{
    private $status = 200;

    public function list(Request $request) {
        $idNumber = $request->idNumber;
        $students = Students::select('*')
                    ->where('id_number', 'LIKE', $idNumber . '%')
                    ->orderBy("created_at", "DESC")
                    ->get();
        return response([
            'data' => StudentsResource::collection($students->loadMissing('grades')),
            'status' => $this->status
        ]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'course' => 'required|string|max:255',
            'year' => 'required|string|max:255',
            'section' => 'required|string|max:255',
            'idNumber' => 'required|string|max:255',
            'dateEnrolled' => 'string|max:255',
            'totalTuitionFee' => 'integer|max:255',
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
            $students = new Students();
            $students->course = $request->course;
            $students->id_number = $request->idNumber;
            $students->year = $request->year;
            $students->section = $request->section;
            $students->date_enrolled = $request->dateEnrolled;
            $students->total_tuition_fee = $request->totalTuitionFee;
            $students->save();

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
            $users->user_type = 'student';
            $users->children_id = $students->id;
            $users->save();

            $addresses = new Addresses;
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

            $response = [
                'message' => 'Student Added!',
                'status' => $this->status
            ];
            return response($response, $this->status);
        }
    }

    public function show($id) {
        $students = Students::find($id);
        $data = New StudentsResource($students->loadMissing('grades'));
        return response($data, $this->status);
    }

    public function update(Request $request) {
        Students::where(['id' => $request->id])->update([
            'course' => $request->course,
            'id_number' => $request->idNumber,
            'year' => $request->year,
            'section' => $request->section,
            'date_enrolled' => $request->dateEnrolled,
            'total_tuition_fee' => $request->totalTuitionFee,
        ]);


        if ($request->addressDetails['id']) {
            Addresses::where(['user_id' => $request->userDetails['id']])->update([
                'house_no' => $request->addressDetails['houseNo'],
                'barangay' => $request->addressDetails['barangay'],
                'country' => $request->addressDetails['country'],
                'province' => $request->addressDetails['province'],
                'zip_code' => $request->addressDetails['zipCode'],
            ]);
        } else {
            $addresses = new Addresses;
            $addresses->house_no = $request->addressDetails['houseNo'];
            $addresses->barangay = $request->addressDetails['barangay'];
            $addresses->country = json_encode($request->addressDetails['country']);
            $addresses->province = json_encode($request->addressDetails['province']);
            $addresses->city = $request->addressDetails['city'];
            $addresses->zip_code = $request->addressDetails['zipCode'];
            $addresses->save();
            User::where(['children_id' => $request->id])->update([
                'address_id' => $addresses->id,
            ]);
        }

        User::where(['children_id' => $request->id])->update([
            'first_name' => $request->userDetails['firstName'],
            'last_name' => $request->userDetails['lastName'],
            'email' => $request->userDetails['email'],
            'mobile' => $request->userDetails['mobile'],
            'date_of_birth' => $request->userDetails['dateOfBirth'],
            'age' => $request->userDetails['age'],
            'gender' => $request->userDetails['gender'],
        ]);

        $response = [
            'message' => 'Teacher Information Saved',
            'status' => $this->status
        ];
        return response($response, $this->status);
    }

    public function generateIdNumber() {
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();
        $students = Students::where('created_at', '>' , $startOfYear)->where('created_at', '<', $endOfYear)->get();
        $count = count($students) + 1;
        $idNumber = 'S'.Carbon::now()->toDateString().'-'.$count;

        return response([
            'data' => $idNumber,
            'status' => $this->status
        ]);
    }
}
