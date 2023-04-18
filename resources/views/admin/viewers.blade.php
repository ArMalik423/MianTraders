@extends('layouts.admin')
@section('content')
<main class="page-content">

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Viewers</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Add Viewer</li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->

<div class="card">
    <div class="card-body">
        <h4 class="mb-0">All Viewers</h4>
        <hr/>
        {!! $list_all_viewers ?? '' !!}
       
    </div>
</div>
</main>
@endsection