<?php

namespace App\Http\Controllers\Api;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class studentController extends Controller
{
    // POST method to create a new student
    public function createStudent(Request $request)
    {

        $validateData = $request->validate([
            'firstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'phoneNumber' => 'required|string|min:11|max:11',
        ]);
        

        $student = Student::create($validateData);
        if ($student) {
            return response()->json([
                'message' => 'Student created successfully',
                'data' => $student
            ], 201);
        } else {
            return response()->json([
                'message' => 'Something went wrong',
            ], 500);
        }
    }

    // GET method to retrieve all students
    public function getAllStudents()
    {
        $students = Student::all();
        return response()->json($students);
    }

    // GET method to retrieve a specific student by ID
    public function getStudent($id)
    {
        $student = Student::find($id);
        if ($student) {
            return response()->json($student);
        } else {
            return response()->json(['message' => 'Student not found'], 404);
        }
    }

    // PUT method to update a student's details
    public function updateStudent(Request $request, $id)
    {
        $student = Student::find($id);
        $student->update($request->all());
        if ($student) {
            return response()->json([
                'message' => 'Student updated successfully',
                'data' => $student
            ]);
        } else {
            return response()->json(['message' => 'Student not found'], 404);
        }
    }

    // DELETE method to remove a student from the database
    public function deleteStudent($id)
    {
        $student = Student::find($id);
        $student->delete();
        if ($student) {
           
            return response()->json(['message' => 'Student deleted successfully']);
        } else {
            return response()->json(['message' => 'Student not found'], 404);
        }
    }
}

