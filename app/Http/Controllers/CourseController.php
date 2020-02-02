<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;
use App\User;
use JWTAuth;

use App\Http\Resources\CourseResource;
use App\Jobs\CreateCourses;
use App\Exports\CourseExport;
use Maatwebsite\Excel\Facades\Excel;

class CourseController extends Controller
{
    public function addCourses(){

        $courses = new CreateCourses();
        dispatch($courses);
        if($courses){
            return $this->response('success', '50 courses created', $courses);
        }else{
            return $this->response('error', 'courses not added', '');
        }
        
    }

    public function listCourses(){
        //$userid = JWTAuth::parseToken()->toUser()->id;
        //return $user;
        $course = Course::with(['user'=> function($query){
            $query->wherePivot('user_id','=', JWTAuth::parseToken()->toUser()->id);
            }])->get();
    
        $courses = CourseResource::collection($course);
        return $this->response('success', 'courses list fetched successfully', $courses);
    }

    public function registerCourses(Request $request){
        $auth = JWTAuth::parseToken()->toUser();
        $user = User::find($auth->id);
        $added = $user->course()->sync([$request->id],false);
        
        if($added){
            return $this->response('success', 'courses(s) added', $courses);
        }else{
            return $this->response('error', 'unable to register course', '');
        }

    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function export() 
    {
        return Excel::download(new CourseExport, 'Courses.xlsx');
    }
    
}
