<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Employee;
use App\Lecturer;
use App\Role;
use App\User;
use Illuminate\Http\Request;


class UserController extends Controller
{

    protected function validator($data)
    {
        return Validator::make($data, [
            'fname' => 'required|min:10|max:255',
            'lname' => 'required|max:255',
            'login' => 'required|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'type' => 'required|max:255',
            'password' => 'required|string|min:8',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $this->validator($request->all())->validate();
            $user = new User();
            $user->fname = $request->fname;
            $user->lname = $request->lname;
            $user->login = $request->login;
            $user->email = $request->email;
            $user->type = $request->type;
            $user->password = $request->password;
            $user->save();
        } catch (\Exception $e) {
            $content = array(
                'success' => false,
                'data' => 'something went wrong.',
                'message' => 'There was an error while processing your request: ' .
                $e->getMessage()
            );
            return response($content)->setStatusCode(500);
        }
        $respone = '';
        if ($request->type == 'lecturer' || $request->type == 'lecturer_and_employee') {
            Lecturer::create([
                'user_id' => $user->id,
                'phone_number' => $request->phone_number,
                'education' => $request->education
            ])->save();
            $respone .= 'lecturer ';
        }
        if ($request->type == 'employee' || $request->type == 'lecturer_and_employee') {
            Employee::create([
                'user_id' => $user->id,
                'mail_voivodship' => $request->mail_voivodship,
                'mail_city' => $request->mail_city,
                'mail_postcode' => $request->mail_postcode,
                'mail_street' => $request->mail_street,
                'mail_number' => $request->mail_number,
                'addr_voivodship' => $request->addr_voivodship,
                'addr_city' => $request->addr_city,
                'addr_postcode' => $request->addr_postcode,
                'addr_street' => $request->addr_street,
                'addr_number' => $request->addr_number
            ])->save();
            $respone .= ' employee ';
        }
        return $respone .= ' created!';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json(['message' => 'User not found'], 404);
        }
        try {
            $this->validator($request->all())->validate();
            $new_user_data = array(
                'fname' => $request->fname,
                'lname' => $request->lname,
                'login' => $request->login,
                'email' => $request->email,
                'type' => $request->type,
                'password' => $request->password
            );
            $user->fill($new_user_data)->save();
        } catch (\Exception $e) {
            $content = array(
                'success' => false,
                'data' => 'something went wrong.',
                'message' => 'There was an error while processing your request: ' .
                $e->getMessage()
            );
            return response($content)->setStatusCode(500);
        }

        $response = '';
        if ($request->type == 'lecturer' || $request->type == 'lecturer_and_employee') {
            $lecturer = User::find($user->id)->lecturer;
            $new_lecturer_data = array(
                'phone_number' => $request->phone_number,
                'education' => $request->education,
            );
            $lecturer->fill($new_lecturer_data)->save();
            $response .= 'lecturer';
        }
        if ($request->type == 'employee' || $request->type == 'lecturer_and_employee') {
            $employee = User::find($user->id)->employee;
            $new_employee_data = array(
                'user_id' => $user->id,
                'mail_voivodship' => $request->mail_voivodship,
                'mail_city' => $request->mail_city,
                'mail_postcode' => $request->mail_postcode,
                'mail_street' => $request->mail_street,
                'mail_number' => $request->mail_number,
                'addr_voivodship' => $request->addr_voivodship,
                'addr_city' => $request->addr_city,
                'addr_postcode' => $request->addr_postcode,
                'addr_street' => $request->addr_street,
                'addr_number' => $request->addr_number
            );
            $employee->fill($new_employee_data)->save();
            $response .= ' employee';
        }
        return $response .= ' updated!';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return User::destroy($id);
    }
}
