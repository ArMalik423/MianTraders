@extends('layouts.admin')
@section('content')
    <main class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Shop payments</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Payments</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">All Payments</h4>
                    </div>
                    <div>
                        <h5>Quantity: {{ $quantity ?? 0 }}</h5> <br>
                        <h5>Total Cost: {{ $total_cost ?? 0 }}</h5>
                    </div>
                </div>

                <hr/>
               <div class="mb-5">
                   <a class="btn btn-primary" style="float: right" href="{{ route('download.ledger',$shopId) }}">Download Excel</a>
               </div>
                {!! $list_payments ?? '' !!}

            </div>
        </div>
    </main>
@endsection
