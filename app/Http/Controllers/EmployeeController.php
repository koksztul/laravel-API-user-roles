<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = User::has('employee')->with('employee')->get();
        if (is_null($employees)) {
            return response()->json(['message' => 'Employees not found'], 404);
        }
        return response()->json($employees, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = User::has('employee')->with('employee')->find($id);
        if (is_null($employee)) {
            return response()->json(['message' => 'Employee not found'], 404);
        }
        return response()->json(['user' => $employee], 200);
    }
}
