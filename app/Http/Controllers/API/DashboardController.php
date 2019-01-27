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

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        $data = collect();
        $data->push(User::get()->count());
        $data->push(Course::get()->count());
        $data->push(Enrollment::get()->count());

        return $data;
    }
    
    public function getlatestusers()
    {
        return User::latest()->paginate(8);
    }

    public function getlatestenrollments()
    {
        $data = collect();
        foreach ( Enrollment::with('user', 'course')->get()->sortByDesc('created_at') as $key=> $enrollment) {
            $data->push($enrollment);
        }
        return $this->paginates($data, $perPage = 7, $page = null, $options = []);
    }

    public function paginates($items, $perPage = 7, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
    
    public function destroy($id)
    {

    }
}
