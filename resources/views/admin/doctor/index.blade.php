@extends('admin.layouts.master')

@section('content')
<div class="page-header">
<div class="row align-items-end">
<div class="col-lg-8">
<div class="page-header-title">
    <i class="ik ik-inbox bg-blue"></i>
    <div class="d-inline">
        <h5>All Doctors</h5>
        <span>Doctor information</span>
    </div>
</div>
</div>
<div class="col-lg-4">
<nav class="breadcrumb-container" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../index.html"><i class="ik ik-home"></i></a>
        </li>
        <li class="breadcrumb-item">
            <a href="#">Doctors</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Doctors </li>
    </ol>
</nav>
</div>
</div>
</div>


<div class="row">
<div class="col-md-12">
<div class="card">
<div class="card-header"><h3>Data Table</h3></div>
<div class="card-body">
    <table id="data_table" class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th class="nosort">Avatar</th>
                <th>Email</th>
                <th>Address</th>
                <th>Phone number</th>
                <th>Department</th>
                <th></th>
                <th></th>

            </tr>
        </thead>
        <tbody>
            @if(count($doctors)>0)
            @foreach($doctors as $doctor)
            <tr>
                <td>{{$doctor->name}}</td>
                <td><img src="{{ asset('images')}}/{{$doctor->image}}" width="50px" class="rounded-circle" alt=""></td>
                <td>{{$doctor->email}}</td>
                <td>{{$doctor->address}}<i class="ik ik-eye table-actions " ></i> </td>
                <th>{{$doctor->phone_number}}</th>
                <th>{{$doctor->department}}</th>
                <td>
                    <div class="table-actions">
                        <a href="#" data-toggle="modal" data-target="#exampleModal{{$doctor->id}}"><i class="ik ik-eye btn btn-primary" ></i></a>
                        <a href="{{ route('doctor.edit', [$doctor->id])}}" ><i class="ik ik-edit-2"></i></a>
                        <a href="{{route('doctor.show', [$doctor->id])}}" ><i class="ik ik-trash-2"></i></a>
                    </div>
                </td>
                <td>x</td>
            </tr>
            @include('admin.doctor.modal');
        @endforeach
           @else 
           <td>No user to display</td>
           @endif
        </tbody>
    </table>
</div>
</div>
</div>
</div>
@endsection