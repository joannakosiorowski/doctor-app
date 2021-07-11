<?php

namespace App\Http\Controllers;
use App\{User};
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doctors = User::where('role_id','!=',3)->get();
        return view('admin.doctor.index', ['doctors'=> $doctors]); // compact('doctors')
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.doctor.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateStore($request);
        $data = $request->all();

        //zapisywanie obrazka w db
        $image = $request->file('image');
        $name = $image->hashName();
        $destination = public_path('/images');
        $image->move($destination, $name);

        $data['image'] = $name;
        $data['password'] = bcrypt($request->password);
        User::create($data);

        return redirect()->back()->with('message', 'doctor added successfully');

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $doctor = User::find($id);
       return view('admin.doctor.delete', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.doctor.edit', compact('user'));
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
        $this->validateStore($request, $id);
        $data = $request->all();
        $user = User::find($id);
        $imageName = $user->image;
        $userPassword = $user->password;
        if($request->hasFile('image'))
        {   
        //zapisywanie obrazka w db
        $image = $request->file('image');
        $imageName = $image->hashName();
        $destination = public_path('/images');
        $image->move($destination, $imageName);
        }

        $data['image'] = $imageName;

        if($request->password)
        {
            $data['password'] = bcrypt($request->password);
        }
        else {
            $data['password'] = $userPassword;
        }

        $user->update($data);
  
        return redirect()->back()->with('message', 'Edition complete');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Auth::id() == $id){
            abort(401);
       }
        $doctor = User::find($id);
        $doctorDelete = $doctor->delete();

        if($doctorDelete)
        {
            unlink(public_path('images/'.$doctor->image));
        }

        return redirect()->route('doctor.index')->with('message','Doctor deleted successfully');
    }

    public function validateStore($request, $id=null)
    {
      return  $this->validate($request, [
            'name'=>'required',
            'email'=>'required|unique:users,email',
            'password'=>'required|min:4',
            'gender'=>'required',
            'education'=>'required',
            'address'=>'required',
            'department'=>'required',
            'phone_number'=>'required|numeric',
            'image'=>'required|mimes:jpeg,jpg,png',
            'role_id'=>'required',


        ]);
    }


}
