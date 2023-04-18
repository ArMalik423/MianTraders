@extends('layouts.admin')
@section('content')
<main class="page-content">

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Shops</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Add Shop</li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->

<div class="card">
    <div class="card-body">
        <h4 class="mb-0">Add Shop</h4>
        <hr/>
        <form method="POST" action="/shop/add" id="public-holiday-form-id"data-modal-id="add-viewer-id">
            @csrf
            <div class="row">
                <div class="col-md-2">
                <label>Name :</label>
                </div>
                <div class="col-md-10">
                    <input name="name" type="text" class="form-control" value="" placeholder="John wick">
                </div>
            </div>
            <div class="row pt-3">
                <div class="col-md-2">
                <label>Email :</label>
                </div>
                <div class="col-md-10">
                    <input name="email" type="email" class="form-control" value="" placeholder="johnwick@gmail.com">
                </div>
            </div>
            <div class="row pt-3">
                <div class="col-md-2">
                <label>Address :</label>
                </div>
                <div class="col-md-10">
                    <input name="address" type="text" class="form-control" value="" placeholder="Shop Adress Can be Null">
                </div>
            </div>
            <div class="row pt-3">
                <div class="col-md-2">
                <label>Phone Number :</label>
                </div>
                <div class="col-md-10">
                    <input name="phone_number" type="text" class="form-control" value="" placeholder="+44 7700 900598">
                </div>
            </div>

            <div class="row pt-3">
                <div class="col-md-2">
                    <label>Post Code :</label>
                </div>
                <div class="col-md-10">
                    <input name="post_code" type="text" class="form-control" value="" placeholder="Post Code Can be Null">
                </div>
            </div>

            <div class="row pt-3">
                <div class="col-md-2">
                    <label>Country :</label>
                </div>
                <div class="col-md-10">
                    <input name="country" type="text" class="form-control" value="" placeholder="Country Can be Null">
                </div>
            </div>


            <div class="pt-3">
                <button class="btn btn-primary" onclick="validateFieldsByFormId(this)"
                        data-validation="validation-span-id"
                        id="validation-span-id" style="float:right;">
                    Add Shop
                </button>
            </div>

        </form>


    </div>
</div>
</main>
    <div>

    </div>
@endsection
