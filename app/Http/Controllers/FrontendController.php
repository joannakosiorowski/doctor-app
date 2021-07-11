<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Appointment, User, Time, Booking, Prescription};
use App\Mail\AppointmentMail;
use Facade\Ignition\DumpRecorder\Dump;
use Illuminate\Support\Facades\App;

class FrontendController extends Controller
{

    //wyszukiwanie dostepnego doktora 
    public function index(Request $request)
    {
    	date_default_timezone_set('Europe/Paris');
        if(request('date')){
            $doctors = $this->findDoctorsBasedOnDate($request);
            return view('welcome',compact('doctors'));
        }
        $doctors = Appointment::where('date',date('Y-m-d'))->get();
    	return view('welcome',compact('doctors'));
    }

    public function findDoctorsBasedOnDate( $request)
    {
       $doctors = Appointment::with('doctor')->whereDate('date',date('Y-m-d'))->get();
       return $doctors;

    }

    public function show($doctorId, $date)
    {
        $appointment = Appointment::where('user_id', $doctorId)->where('date', $date)->first();
        $times = Time::where('appointment_id', $appointment->id)->where('status', 0)->get();
        $doctor = User::where('id', $doctorId)->first();
        $doctor_id = $doctorId;
        return view('appointment', compact('times', 'date', 'doctor', 'doctor_id'));
    }

    public function store(Request $request)
    {
        $request->validate(['time'=>'required']);
        $date = $request->date;
     
        $check = $this->checkBookingTimeInterval($date);

        

        if($check)
        {
            return redirect()->back()->with('errmessage','You have just booked an appointment on this day');
        }

        Booking::create([

            'user_id'=> auth()->user()->id,
            'doctor_id'=> $request->doctorId,
            'time'=> $request->time,
            'date'=>$request->date,
            'status'=>0
        ]);

        Time::where('appointment_id', $request->appointmentId)->where('time', $request->time)->update(['status'=>1]);

        //wysyłanie maili z powiadomieniem

        $doctorName = User::where('id', $request->doctorId)->first();
        $mailData = [
            'name' => auth()->user()->name,
            'time' => $request->time,
            'date' => $request->date,
            'doctorName' => $doctorName->name
        ];

        try{
            \Mail::to(auth()->user()->email)->send(new AppointmentMail($mailData));
        }
        catch(\Exception $e)
        {

        }

        return redirect()->back()->with('message','Your appointment is made');

    }

    public function checkBookingTimeInterval($date)
    {
       
       
        return Booking::orderby('id','desc')->where('user_id',auth()->user()->id)->where('date', $date)->exists();
        
    }

    public function myBookings(Request $request)
    {
        $appointments = Booking::latest()->where('user_id',auth()->user()->id)->get();
        return view('booking.index',compact('appointments'));
    }

    public function doctorToday(Request $request)
    {
        $doctors = Appointment::with('doctor')->whereDate('date',date('Y-m-d'))->get();
        return $doctors;
    }

    public function findDoctors(Request $request)
    {
       $doctors = Appointment::with('doctor')->whereDate('date',$request->date)->get();
        return $doctors;
    }

    public function myPrescription()
    {
        $prescriptions = Prescription::where('user_id', auth()->user()->id)->get();
        return view('my-prescription', compact('prescriptions'));

    }
}
