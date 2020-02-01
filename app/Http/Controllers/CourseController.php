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
            return response()
            ->json(['status' => 'success', 
                    'message' => '50 courses created',
                    'data' => $courses ],200);
        }else{
            return response()
                    ->json(['status' => 'error', 
                    'message' => 'courses not added',
                    'data' => '']);
        }
        
    }

    public function listCourses(){
        $user = JWTAuth::parseToken()->toUser();
        $courses = Course::with(['user' => function($q) {
            $q->where('user.id', $user->id)->select('created_at');
        }])->get();
        //$courses = CourseResource::collection($course);
        //$courses = $course->user;
        return response()
                ->json(['status' => 'success', 
                        'message' => 'courses listed successfully',
                        'data' => $courses],200);
       
    }

    public function registerCourses(Request $request){
        $auth = JWTAuth::parseToken()->toUser();
        $user = User::find($auth->id);
        $added = $user->course()->sync([$request->id],false);
        if($added){
            return response()
                    ->json(['status' => 'success', 'message' => 'courses added'],200);
        }else{
            return response()
                    ->json(['status' => 'error', 'data' => 'unable to register course'],400);
        }

    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function export() 
    {
        // $courses = new CourseExport;
        // $headers =[
        //     'content-type' => 'application/pdf'
        // ];
        // return  response()->file($courses,['Courses.xlsx']);
        return Excel::download(new CourseExport, 'Courses.xlsx');
    }
    
}
