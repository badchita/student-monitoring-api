<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TeachersController;
use App\Models\Addresses;
use App\Models\Teachers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserVerify;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Passport\Client as OClient;

class ApiAuthController
{
    private $status = 200;
    public function register (Request $request) {
        // $validator = Validator::make($request->all(), [
        //     'first_name' => 'required|string|max:255',
        //     'last_name' => 'required|string|max:255',
        //     'username' => 'required|string|max:255|unique:users',
        //     'email' => 'required|string|email|max:255|unique:users',
        //     'password' => 'required|string|min:8',
        //     'mobile' => 'required|string|unique:users',
        //     'user_type' => 'string|max:255',
        //     'date_of_joined' => 'string|max:255',
        //     'date_of_birth' => 'string|max:255',
        //     'age' => 'integer',
        // ]);

        // if ($validator->fails())
        // {
        //     return response(['errors'=>$validator->errors()->all()], 405);
        // }
        if ($request->user_type == 'teacher') {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'mobile' => 'required|string|unique:users',
                'user_type' => 'string|max:255',
                'date_of_joined' => 'string|max:255',
                'date_of_birth' => 'string|max:255',
                'age' => 'integer',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 405);
            } else {
                $teachers = new Teachers();
                $teachers->save();
                $request['password']=Hash::make($request['password']);
                $request['remember_token'] = Str::random(10);
                $request->request->add(['teacher_id' => $teachers->id]);
                $user = User::create($request->toArray());
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                UserVerify::create([
                    'user_id' => $user->id,
                    'token'   => $token
                ]);
                Mail::send('email.emailVerificationEmail', ['token' => $token], function($message) use($request){
                    $message->to($request->email);
                    $message->subject('Email Verification Mail');
                });
                // $response = ['token' => $token];
                $response = [
                    'message' => 'Teacher Added!',
                    'status' => $this->status
                ];
                return response($response, $this->status);
            }
        } else {
            return response(['errors'=> 'error'], 405);
        }
    }

    public function loginAdmin (Request $request) {
        if ($request->email == '') {
            $user = User::where('email', $request->email)->first();
        }
        $user = User::where('email', $request->email)->orwhere('username', $request->username)->first();
        if ($user) {
            if ($user->user_type !== 'admin') {
                $response = ["message" => "This Account does not have access to this site. Please Login as Admin"];
                return response($response, 403);
            }
            if (Hash::check($request->password, $user->password)) {
                $is_email_verified = $user->is_email_verified;
                if ($is_email_verified == 0) {
                    $response = ["message" => "Email Not Verified!"];
                    return response($response, 402);
                } else {
                    $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                    $response = [
                        'remember_token' => $user->remember_token,
                        'token'          => $token,
                        'id'             => $user->id,
                        'status' => 200
                    ];
                return response($response, 200);
                }
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" =>'User does not exist'];
            return response($response, 422);
        }
    }

    public function loginTeacher (Request $request) {
        if ($request->email == '') {
            $user = User::where('email', $request->email)->first();
        }
        $user = User::where('email', $request->email)->orwhere('username', $request->username)->first();
        if ($user) {
            if ($user->user_type !== 'teacher') {
                $response = ["message" => "This Account does not have access to this site. Please Login as Teacher"];
                return response($response, 403);
            }
            if (Hash::check($request->password, $user->password)) {
                $is_email_verified = $user->is_email_verified;
                if ($is_email_verified == 0) {
                    $response = ["message" => "Email Not Verified!"];
                    return response($response, 402);
                } else {
                    $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                    $response = [
                        'remember_token' => $user->remember_token,
                        'token'          => $token,
                        'id'             => $user->id,
                        'status' => 200
                    ];
                return response($response, 200);
                }
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" =>'User does not exist'];
            return response($response, 422);
        }
    }

    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }

    public function verifyAccount($token)
    {
        $verifyUser = UserVerify::where('token', $token)->first();
        $message = 'Sorry your email cannot be identified.';
        if(!is_null($verifyUser) ){
            $user = $verifyUser->user;
            if(!$user->is_email_verified) {
                $verifyUser->user->is_email_verified = 1;
                $verifyUser->user->save();
                $message = "Your e-mail is verified. You can now login.";
                $response = ['message' => $message];
                // return Redirect::to('http://localhost:4200/login/teacher');
                return Redirect::to('https://studentmonitoring.herokuapp.com/login/teacher');
                // return response($response, 200);
            } else {
                $message  = "Your e-mail is already verified. You can now login.";
                $response = ['message' => $message];
                return response($response, 401);
            }
        }
    }

    public function getTokenAndRefreshToken(OClient $oClient, $email, $password) {
        $oClient = OClient::where('password_client', 1)->first();
        $http = new Client();
        $response = $http->request('POST', 'http://mylemp-nginx/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'username' => $email,
                'password' => $password,
                'scope' => '*',
            ],
        ]);
        $result = json_decode((string) $response->getBody(), true);
        return response()->json($result, $this->successStatus);
    }

    public function generatePassword() {
        $randomPassword = Str::random(10);
        return response([
            'data' => $randomPassword,
            'status' => $this->status
        ]);
    }

    public function updatePasswordByAdmin(Request $request) {
        $password = $request->password;
        User::where(['id' => $request->id])->update([
            'password' => Hash::make($password),
        ]);
        Mail::send('email.emailChangePasswordEmail', ['password' => $password], function($message) use($request){
            $message->to($request->email);
            $message->subject('Change Password Mail');
        });
        return response([
            'status' => $this->status
        ]);
    }

    public function sendEmailVerification(Request $request) {
        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        UserVerify::create([
            'user_id' => $request->id,
            'token'   => $token
        ]);
        Mail::send('email.emailVerificationEmail', ['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Email Verification Mail');
        });
        $response = [
            'message' => 'Email Sent',
            'status' => $this->status
        ];
        return response($response, $this->status);
    }
}
