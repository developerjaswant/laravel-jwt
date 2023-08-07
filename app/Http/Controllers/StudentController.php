<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class StudentController extends Controller
{


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

  
     public function showOrSearch(Request $request, $id = null)
     {
        if (!Auth::check() || !Auth::user()) {
            return response()->json(['message' => 'Not authorized.'], 401);
        }
         $searchCriteria = $request->input('name');
         $searchValue = $request->input('value');
 
         // If an id is provided, show the student record
         if ($id !== null) {
             $student = Student::find($id);
             if (!$student) {
                 return response()->json([
                     'message' => 'Student not found.',
                 ], 404);
             }
 
             return response()->json([
                 'data' => $student,
             ], 200);
         }
 
         // If no id is provided, search for student records based on search criteria and value
         if ($searchCriteria && $searchValue) {
             $validator = Validator::make($request->all(), [
                 'name' => 'required|string|in:name,description,mobile',
                 'value' => 'required|string',
             ]);
 
             if ($validator->fails()) {
                 return response()->json($validator->errors(), 422);
             }
 
             $students = Student::where($searchCriteria, 'like', "%$searchValue%")->get();
             if ($students->isEmpty()) {
                 return response()->json([
                     'message' => 'No students found.',
                 ], 404);
             }
 
             return response()->json([
                 'data' => $students,
             ], 200);
         }
 
         $students = Student::all();

         if ($students->isEmpty()) {
             return response()->json([
                 'message' => 'No students found.',
             ], 404);
         }
 
         return response()->json([
             'data' => $students,
         ], 200);
     }
 
     
     
    public function storeOrUpdate(Request $request, $id = null)
    {
        // Check if the user is authenticated and has a valid token
        if (!Auth::check() || !Auth::user()) {
            return response()->json(['message' => 'Not authorized.'], 401);
        }
    
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'mobile' => 'required|integer'
        ]);
    
        // If validation fails, return the errors
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        // Check if an ID is provided for updating
        if ($id) {
            $student = Student::findOrFail($id);
            $student->update($validator->validated());
        } else {
            // Create a new student record
            $student = Student::create($validator->validated());
        }
    
        // Check if the student record was updated or created successfully
        if ($student) {
            return response()->json([
                'message' => $id ? 'Student updated successfully.' : 'Student created successfully.',
                'student' => $student
            ], $id ? 200 : 201);
        }
    
        // If updating or creating the student record failed, return an error message
        return response()->json([
            'message' => $id ? 'Failed to update student.' : 'Failed to create student.',
        ], 500);
    }
 

   
    public function show($id)
    {
        if (!Auth::check() || !Auth::user()) {
            return response()->json(['message' => 'Not authorized.'], 401);
        }
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'message' => 'Student not found.',
            ], 404);
        }

        return response()->json([
            'data' => $student,
        ], 200);
    }


    
   
    public function destroy($id)
    {
        if (!Auth::check() || !Auth::user()) {
            return response()->json(['message' => 'Not authorized.'], 401);
        }
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'message' => 'Student not found.',
            ], 404);
        }

        if ($student->delete()) {
            return response()->json([
                'message' => 'Student deleted successfully.',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to delete student.',
            ], 500);
        }
    }
}
