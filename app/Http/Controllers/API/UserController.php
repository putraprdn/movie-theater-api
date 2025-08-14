<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;
use Symfony\Component\HttpFoundation\Response;

class UserController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $input['role_id'] = Role::where('name', 'user')->first()->id;
        if (!$input['role_id']) {
            return $this->sendError('Failed to create user. Please check the user roles', [], Response::HTTP_INTERNAL_SERVER_ERROR);
        };

        $user = User::create($input);
        $success['token'] =  $user->createToken('MovieTheater')->plainTextToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User register successfully');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return $this->sendError('Unauthorized.', ['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();
        $success['token'] =  $user->createToken('MovieTheater')->plainTextToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User login successfully.');
    }
}
