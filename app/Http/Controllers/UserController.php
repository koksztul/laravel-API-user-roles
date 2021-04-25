<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Employee;
use App\Lecturer;
use App\Role;
use App\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Validation\Rules\Exists;
use App\Enums\UserType;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with(['employee', 'lecturer'])->get();
        return response()->json($users, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $user = new User($request->validated());
        $user->save();
        $response = '';
        if ($request->type === UserType::Lecturer || $request->type === UserType::Both) {
            $lecturer = new Lecturer($request->all());
            $lecturer->user_id = $user->id;
            $lecturer->save();
            $response .= 'lecturer';
        }
        if ($request->type === UserType::Employee || $request->type === UserType::Both) {
            $employee = new Employee($request->all());
            $employee->user_id = $user->id;
            $employee->save();
            $response .= ' employee';
        }
        return $response .= ' created!';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UpdateUserRequest  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        User::find($user)->first()->fill($request->validated())->save();
        $response = '';
        if ($request->type === UserType::Lecturer || $request->type === UserType::Both) {
            $lecturer = User::find($user->id)->lecturer;
            $lecturer->update($request->all());
            $response .= 'lecturer';
        }
        if ($request->type === UserType::Employee || $request->type === UserType::Both) {
            $employee = User::find($user->id)->employee;
            $employee->update($request->all());
            $response .= ' employee';
        }
        return $response .= ' updated!';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (User::where('id', $id)->exists()) {
            $book = User::find($id);
            $book->delete();
            return response()->json(["message" => "User deleted"], 200);
        } else {
            return response()->json(["message" => "User not found"], 404);
        }
    }
}
