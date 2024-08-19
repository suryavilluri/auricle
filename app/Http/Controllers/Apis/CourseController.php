<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Course;
use Validator;

class CourseController extends Controller
{
    public function createCourse(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|unique:courses',
                'description' => 'required',
                'instructor' => 'required',
                'duration' => 'required',
            ]);

            if ($validator->fails()) {
                $messages = $validator->messages();
                return response()->json(['status' => 400, 'message' => $messages->first()], 400);
            }

            $course = Course::create($request->all());
            return response()->json(['status' => 201, 'message' => 'Course created successfully!', 'course' => $course], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    public function allCourses()
    {
        // $courses = Course::all();
        $courses = Course::with('lessons')->paginate(10);
        if($courses->isEmpty()) {
            return response()->json(['status' => 404, 'message' => 'No courses found'], 404);
        } else {
            return response()->json(['status' => 200, 'message' => 'Courses', 'courses' => $courses], 200);
        }
    }

    public function getCourse($id)
    {
        $course = Course::where(['course_id' => $id])->with('lessons')->first();
        if($course) {
            return response()->json(['status' => 200, 'message' => 'Course', 'course' => $course], 200);
        } else {
            return response()->json(['status' => 404, 'message' => 'No course found'], 404);
        }
    }

    public function updateCourse(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|unique:courses,title,' . $id . ',course_id',
                'description' => 'required',
                'instructor' => 'required',
                'duration' => 'required',
            ]);

            if ($validator->fails()) {
                $messages = $validator->messages();
                return response()->json(['status' => 400, 'message' => $messages->first()], 400);
            }

            $course = Course::findOrFail($id);
            $course->update($request->all());

            return response()->json(['status' => 200, 'message' => 'Course update successfully!', 'course' => $course], 200);

        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteCourse($id = null)
    {
        try {
            $course = Course::findOrFail($id);
            $course->delete();
            return response()->json(['status' => 200, 'message' => 'Course deleted successfully!'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            response()->json(['status' => 404, 'message' => 'Course not found'], 404);
        }
    }

}
