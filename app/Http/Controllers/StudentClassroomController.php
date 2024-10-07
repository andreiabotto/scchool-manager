<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentClassroom\StoreStudentClassroomRequest;
use App\Http\Requests\StudentClassroom\UpdateStudentClassroomRequest;
use App\Http\Responses\Student\StudentClassroomResponse;
use App\Models\StudentClassroom;
use App\Services\Interfaces\StudentClassroomServiceInterface;
use Illuminate\Http\Request;

class StudentClassroomController extends Controller
{
    protected $service;

    public function __construct(StudentClassroomServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        try {
            $params = $request->all();
            if($params) {
                $params = $params['values'];

                if (is_null($params[1])) {
                    $params = [];
                }
            }

            $students = $this->service->getAll($params);
            return StudentClassroomResponse::collection($students);
        }
        catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(),'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $students = $this->service->getById($id);

            if ($students) {
                return new StudentClassroomResponse($students);
            }

            return response()->json(['message' => 'Registration not found'], 404);
        }
        catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(),'message' => $e->getMessage()], 500);
        }
    }

    public function store(StoreStudentClassroomRequest $request)
    {
        try {
            if($request->validated()){
                $params = $request->all();
                $registration = StudentClassroom::where('student_id', $params['student_id'])->where('classroom_id', $params['classroom_id'])->first();

                if (!$registration) {
                    $classroom = $this->service->create($request->validated());
                    return new StudentClassroomResponse($classroom);
                }
                else {
                    return response()->json(['error' => 'Registration error'], 422);
                }
            }
            else {
                return response()->json(['error' => 'Registration error'], 422);
            }

        }
        catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(),'message' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateStudentClassroomRequest $request, $id)
    {
        try{
            $updateStudent = $this->service->update($id, $request->validated());

            if ($updateStudent) {
                $student = $this->service->getById($id);
                return new StudentClassroomResponse($student);
            }
            return response()->json(['message' => 'Registration not found'], 404);
        }
        catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(),'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $deleteStudent = $this->service->delete($id);
            if ($deleteStudent) {
                return response()->json(['message' => 'Registration deleted successfully'], 200);
            }
            return response()->json(['message' => 'Registration not found'], 404);
        }
        catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(),'message' => $e->getMessage()], 500);
        }
    }
}
