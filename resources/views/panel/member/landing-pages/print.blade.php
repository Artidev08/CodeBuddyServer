{{--
* Project: Landing Page
* 
* @category ZStarter
* @ref zCRUD GENERATOR 
* 
* @license Proprietary - Unauthorized copying, use, or distribution is strictly prohibited.
* License details: https://www.defenzelite.com/license
* 
* (c) Defenzelite. All rights reserved.
* @contact hq@defenzelite.com
* 
* @version zStarter: 1.1.2
--}}
@extends('layouts.empty')
@section('title', 'Landing Pages')
@section('content')
@php
@endphp


<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    @include('panel.member.landing-pages.table')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
