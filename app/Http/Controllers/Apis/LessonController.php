<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Lesson;
use Validator;

class LessonController extends Controller
{
    public function createLesson(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|unique:lessons',
                'content' => 'required',
                'course_id' => 'required|exists:courses,course_id',
            ]);

            if ($validator->fails()) {
                $messages = $validator->messages();
                return response()->json(['status' => 400, 'message' => $messages->first()], 400);
            }

            $course = Lesson::create($request->all());
            return response()->json(['status' => 201, 'message' => 'Lesson created successfully!', 'lesson' => $course], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    public function allLessons()
    {
        // $courses = Course::all();
        $lesson = Lesson::with('course')->paginate(10);
        if($lesson->isEmpty()) {
            return response()->json(['status' => 404, 'message' => 'No lessons found'], 404);
        } else {
            return response()->json(['status' => 200, 'message' => 'Lesson', 'Lessons' => $lesson], 200);
        }
    }

    public function getLesson($id)
    {
        $lesson = Lesson::where(['lession_id' => $id])->with('course')->first();
        if($lesson) {
            return response()->json(['status' => 200, 'message' => 'Lesson', 'lesson' => $lesson], 200);
        } else {
            return response()->json(['status' => 404, 'message' => 'No lesson found'], 404);
        }
    }

    public function updateLesson(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|unique:lessons,title,' . $id . ',lession_id',
                'content' => 'required',
                'course_id' => 'required|exists:courses,course_id',
            ]);

            if ($validator->fails()) {
                $messages = $validator->messages();
                return response()->json(['status' => 400, 'message' => $messages->first()], 400);
            }

            $lesson = Lesson::findOrFail($id);
            $lesson->update($request->all());

            return response()->json(['status' => 200, 'message' => 'Lesson update successfully!', 'lesson' => $lesson], 200);

        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteLesson($id = null)
    {
        try {
            $lesson = Lesson::findOrFail($id);
            $lesson->delete();
            return response()->json(['status' => 200, 'message' => 'Lesson deleted successfully!'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            response()->json(['status' => 404, 'message' => 'Lesson not found'], 404);
        }
    }
}
