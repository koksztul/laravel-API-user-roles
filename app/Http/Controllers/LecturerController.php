<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LecturerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lecturers = User::has('lecturer')->with('lecturer')->get();
        if (is_null($lecturers)) {
            return response()->json(['message' => 'Lecturers not found'], 404);
        }
        return response()->json($lecturers, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lecturer = User::has('lecturer')->with('lecturer')->find($id);
        if (is_null($lecturer)) {
            return response()->json(['message' => 'Lecturer not found'], 404);
        }
        return response()->json(['user' => $lecturer], 200);
    }
}
