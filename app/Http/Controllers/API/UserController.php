<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        if (\Gate::allows('isAdmin')) {
            return User::latest()->paginate(10);
        }
        else{
            $categories = collect();
            $categories->push(auth('api')->user());
            
            return $this->paginates($categories, $perPage = 10, $page = null, $options = []);
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
            'email'=>'required|string|email|max:191|unique:users',
            'name'=>'required|string|max:191',
            'password'=>'required|string|min:6'
        ]);

        return User::create([
            'name'=>$request['name'],
            'email'=>$request['email'],
            'type'=>$request['type'],
            'password' =>Hash::make($request['password']),
            'photo'=>$request['photo']
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
        return User::findOrFail($id);
    }
    public function profile()
    {
        return auth('api')->user();
    }
    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
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
        $user = User::findOrFail($id);

        $this->validate($request,[            
            'email'=>'required|string|email|max:191|unique:users,email,'.$user->id,
            'name'=>'required|string|max:191',
            'password'=>'sometimes|string|min:6'
        ]);

        $user->update($request->all());
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
        $user = User::findOrFail($id);
        $user->delete();
        return ['message'=>'Deleted'];
    }
    public function updateProfile(Request $request,$id)
    {
        $user = User::findOrFail($id);//auth('api')->user();


        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:6'
        ]);


        $currentPhoto = $user->photo;


        if($request->photo != $currentPhoto){
            $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];

            \Image::make($request->photo)->save(public_path('img/').$name);
            $request->merge(['photo' => $name]);

            $userPhoto = public_path('img/').$currentPhoto;
            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }

        }
        if(!empty($request->password)){
            $request->merge(['password' => Hash::make($request['password'])]);
        }


        $user->update($request->all());
        return ['message' => "Success"];
    }
}
