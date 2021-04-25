<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Lecturer;
use App\Role;
use App\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
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
     * @OA\Post(
     * path="/api/users",
     * summary="Create users who is empoloyee and lecturer",
     * description="Login by email, password",
     * operationId="authLogin",
     * tags={"user"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass users who is empoloyee and lecturer<br>",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="fname", type="string", example="Adam"),
     *       @OA\Property(property="lname", type="string", example="Kowalski"),
     *       @OA\Property(property="login", type="string", example="aKowlaski"),
     *       @OA\Property(property="email", type="string", format="email", example="akowalski@gmail.com"),
     *       @OA\Property(property="type", type="string", example="lecturer_and_employee"),
     *       @OA\Property(property="password", type="string", example="password1234"),
     *       @OA\Property(property="phone_number", type="string", example="666333222"),
     *       @OA\Property(property="education", type="string", example="education"),
     *       @OA\Property(property="mail_voivodship", type="string", example="Wielkopolskie"),
     *       @OA\Property(property="mail_city", type="string", example="Poznań"),
     *       @OA\Property(property="mail_postcode", type="string", example="60-300"),
     *       @OA\Property(property="mail_street", type="string", example="Piątkowska"),
     *       @OA\Property(property="mail_number", type="integer", example="32"),
     *       @OA\Property(property="addr_voivodship", type="string", example="Mazowiecki"),
     *       @OA\Property(property="addr_city", type="string", example="Warszawa"),
     *       @OA\Property(property="addr_postcode", type="string", example="30-123"),
     *       @OA\Property(property="addr_street", type="string", example="Warszawska"),
     *       @OA\Property(property="addr_number", type="integer", example="30"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="User who is empoloyee and lecturer has beed created",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="employee lecturer created!")
     *        )
     *     )
     * )
     */

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
        $response .= ' created!';
        return response()->json(['message' => $response], 200);
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
        $response .= ' updated!';
        return response()->json(['message' => $response], 200);
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
