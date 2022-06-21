<?php

namespace App\Http\Controllers\JWT;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // COURSE ENROLLMENT - POST
    public function courseEnrollment(Request $request){
        $request->validate([
            'title' => 'required',
            'description'=> 'required',
            'total_videos'=> 'required',
        ]);

        $course = new Course();

        $course->user_id = auth()->user()->id;
        $course->title = $request->title;
        $course->description = $request->description;
        $course->total_videos = $request->total_videos;

        $course->save();

        return response()->json([
            'status' => 1,
            'message' => 'Course Enrollment Successfully',
            'course info' => $course
        ], 201);
    }

    // TOTAL COURSES - GET
    public function totalCourses(){
       $courses = auth()->user()->courses;
        return response()->json([
            'status' => 1,
            'message' => 'Total Courses Enrollment',
            'data' => [
                'number of courses' => sizeof($courses),
                'courses' => $courses
            ]
        ], 201);
    }

    // DELETE COURSE - GET
    public function deleteCourse($id){
        if(Course::where([
            'id' => $id,
            'user_id' => auth()->user()->id
        ])->exists()){
            $course = Course::find($id);
            $course->delete();
            return response()->json([
                'status' => 1,
                'message' => 'Course deleted',
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'message' => 'Course not found',
            ], 404);
        }
    }
}
