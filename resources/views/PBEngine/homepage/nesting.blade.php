@extends('PBEngine/template/horizontal', [
    'title' => 'Homepage',
    'breadcrumbs' => ['Home', 'Homepage'],
])
@section('content')


    {{-- <div class="card">
        <div class="card-header"><span class="title">Selamat Datang</span></div>
        <div class="card-body">
            <div class="d-flex">
                <div class="flex-column align-self-center">
                   
                </div>
                <div class="flex-column ml-4">
                    <h2 class="fw-bold">Nama Operator</h2>
                    <h5>90001 - Operator</h5>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="user-display">
        {{-- public\assets\img\backgorund\bg-op.jpg --}}
        <div class="user-display-bg"><img src="{{ asset('public\assets\img\background\bg-np-edit.jpg') }}"
                alt="Profile Background" class="img-fluid "></div>
        <div class="user-display-bottom">
            <div class="user-display-avatar">
                <img src="{{ asset('public\assets\img\user\user-nesting.jpg') }}" alt="Avatar">
                {{-- <div class="avatar" style="width: 5vw; height: 5vw;">
                <span class="avatar-title rounded-circle border border-white" style="font-size: 2vw;">CF</span>
            </div> --}}
            </div>
            <!-- <a class="float-right" href="https://storyset.com/" target="_blank">Illustrations by Storyset</a> -->
            <div class="user-display-info">
                <div class="name">{{ Auth::user()->name }}</div>
                <div class="nick"><span class="mdi mdi-account"></span> {{ Auth::user()->title }}</div>
            </div>
            <div class="row user-display-details my-8 text-center">
                <div class="col-4">
                    <div class="title">On Going Job</div>
                    <div class="counter">{{ $ongoingJobsCount }}</div>
                </div>
                <div class="col-4">
                    <div class="title">Finished Job</div>
                    <div class="counter">{{ $ForFinish }}</div>
                </div>
                <div class="col-4">
                    <div class="title">Machine</div>
                    <div class="counter">{{ $machineCount }}</div>
                </div>
            </div>
        </div>
    </div>
   
@endsection


