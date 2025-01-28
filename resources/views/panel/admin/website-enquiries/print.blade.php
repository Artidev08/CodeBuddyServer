@extends('layouts.empty')
@section('title', 'Website Enquiries')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="table-responsive">
                        @include('panel.admin.website-enquiries.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
