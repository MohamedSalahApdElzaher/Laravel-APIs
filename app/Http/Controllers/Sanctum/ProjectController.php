<?php

namespace App\Http\Controllers\Sanctum;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProjectController extends Controller
{
    /**
     *  ALL APIs NEED AUTHENTICATION
     */

    // CREATE API
    public function createProject(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'discription' => 'required',
            'duration' => 'required',
        ]);

        $student_id = auth()->user()->id;

        $project = new Project();

        $project->student_id = $student_id;
        $project->name = $request->name;
        $project->discription = $request->discription;
        $project->duration = $request->duration;

        $project->save();

        return response()->json([
            'status' => 1,
            'message' => 'project created successfully'
        ]);
    }

    // LIST DETAILS API
    public function listProject()
    {
        $projects = Project::where('student_id', auth()->user()->id)
            ->orderBy('id', 'DESC')->get();

        return response()->json([
            'status' => 1,
            'message' => 'List projects',
            'data' => $projects
        ]);
    }

    // SINGLE PROJECT API
    public function singleProject($id)
    {
        $student_id = auth()->user()->id;

        if(Project::where([
            'student_id' => $student_id,
            'id' => $id
        ])->exists()){

            $project = Project::where([
                'student_id' => $student_id,
                'id' => $id
                ])->first();
    
            return response()->json([
                'status' => 1,
                'message' => 'project info',
                'data' => $project
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'message' => 'project not found',
            ], 404);
        }
       
    }

    // UPDATE PROJECT API
    public function updateProject(Request $request, $id){
       $student_id = auth()->user()->id;
       if(Project::where([
           'student_id' => $student_id,
           'id' => $id
       ])->exists()){

        Project::where([
            'student_id' => $student_id,
            'id' => $id
        ])->update([
            'name' =>  $request->name,
            'discription' =>  $request->discription  ,
            'duration' => $request->duration 
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'project updated successfully',
        ], 200);

       }
       else{
           return response()->json([
              'status' => 0,
              'message' => 'project not found'
           ], 404);
       }
    }

    // DELETE API
    public function delete($id)
    {
        $student_id = auth()->user()->id;

        if(Project::where([
            'student_id' => $student_id,
            'id' => $id
        ])->exists()){

            $project = Project::where([
                'student_id' => $student_id,
                'id' => $id
                ])->delete();
    
            return response()->json([
                'status' => 1,
                'message' => 'project deleted successfully',
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'message' => 'project not found',
            ], 404);
        }
    }
}
