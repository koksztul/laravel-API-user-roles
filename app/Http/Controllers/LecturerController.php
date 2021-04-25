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
        $lecturers = User::with('lecturer')->get();
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
        $lecturer = User::with('lecturer')->find($id);
        if (is_null($lecturer)) {
            return response()->json(['message' => 'Lecturer not found'], 404);
        }
        return response()->json($lecturer, 200);
    }
}
