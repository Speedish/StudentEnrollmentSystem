<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enrollment;
use App\User;
use App\Course;
use \Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Gate::allows('isAdmin')) {
            $categories = collect();
            foreach ( Enrollment::with('user', 'course')->get() as $key=> $category) {
                $categories->push($category);
            }

            return $this->paginates($categories, $perPage = 10, $page = null, $options = []);

        }
        else{
            $user = auth('api')->user();
            $categories = collect();
            foreach ( Enrollment::with('user', 'course')->get()->where('user_id',$user->id) as $key=> $category) {
                $categories->push($category);
            }

            return $this->paginates($categories, $perPage = 1, $page = null, $options = []);
        } 
    }
    public function paginates($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[            
            'user_id'=>'required',
            'course_id'=>'required'
        ]);

        return Enrollment::create([
            'user_id'=>$request['user_id'],
            'course_id'=>$request['course_id']
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    public function countbyid()
    {
        $user = auth('api')->user(); 
        return Enrollment::with('user')->get()->where('user_id',$user->id)->count();
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $enrollment = Enrollment::findOrFail($id);

        $this->validate($request,[            
            'user_id'=>'required',
            'course_id'=>'required'
        ]);

        $enrollment->update($request->all());
        return ['message'=>'Updated'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->delete();
        return ['message'=>'Deleted'];
    }
}
