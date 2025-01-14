@extends('layouts.app')

@section('content')
<div>
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="container">
        <div class="card-body">
            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif
            <div class="row justify-content-md-center">
                <div class="col-sm-6 col-xl-4">
                    <div class="card">
                        <div class="card-body p-3 d-flex align-items-center">
                            <i class="cui-energy bg-dark p-3 font-2xl mr-3"></i>
                            <div>
                                <div class="text-value-sm text-dark">Tickets</div>
                                <div class="text-muted text-uppercase font-weight-bold small">Sold</div>
                            </div>
                        </div>
                        <div class="card-footer px-3 py-2">
                            <a class="btn-block text-muted d-flex justify-content-between align-items-center" href="#">
                                <span class="small font-weight-bold">View More</span>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <div class="card">
                        <div class="card-body p-3 d-flex align-items-center">
                            <i class="fas  bg-warning p-3 font-2xl mr-3"></i>
                            <div>
                                <div class="text-value-sm text-warning">Current</div>
                                <div class="text-muted text-uppercase font-weight-bold small">Events</div>
                            </div>
                        </div>
                        <div class="card-footer px-3 py-2">
                            <a class="btn-block text-muted d-flex justify-content-between align-items-center" href="#">
                                <span class="small font-weight-bold">View More</span>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-md-center">
                <div class="col-sm-6 col-xl-4">
                    @can('read-users')
                    <users-count></users-count>
                    @endcan
                </div>
                <div class="col-sm-6 col-xl-4">
                    @can('read-roles')
                    <roles-count></roles-count>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
