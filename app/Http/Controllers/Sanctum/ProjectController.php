<?php

namespace App\Http\Controllers\Sanctum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * ALL APIs NEED AUTHENTICATION
     */

     // CREATE API
     public function createProject(Request $request){}

     // LIST DETAILS API
     public function listProject(){}

     // SINGLE PROJECT API
     public function singleProject($id){}

     // DELETE API
     public function delete($id){}

}
