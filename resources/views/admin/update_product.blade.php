@extends('layouts.admin')
@section('content')
<main class="page-content">

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Product</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Update Product</li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->

<div class="card">
    <div class="card-body">
        <h4 class="mb-0">Update Product</h4>
        <hr/>
        <form method="POST" action="/product/update" id="public-holiday-form-id"data-modal-id="add-viewer-id">
            @csrf
            <div class="row">
                <input type="hidden" name="product_id" value="{{ $product->id ?? ''}}">
                <div class="col-md-2">
                <label>Name :</label>
                </div>
                <div class="col-md-10">
                    <input name="name" type="text" class="form-control" value="{{ $product->name ?? '' }}" placeholder="Product 1">
                </div>
            </div>
            <div class="row pt-3">
                <div class="col-md-2">
                <label>Product Code :</label>
                </div>
                <div class="col-md-10">
                    <input name="product_code" type="text" class="form-control" value="{{ $product->product_code ?? 0 }}" placeholder="PD12345">
                </div>
            </div>

            <div class="row pt-3">
                <div class="col-md-2">
                <label>Purchase Price :</label>
                </div>
                <div class="col-md-10">
                    <input name="purchase_price" type="number" class="form-control" placeholder="10" value="{{ $product->purchase_price ?? 0 }}">
                </div>
            </div>
            <div class="row pt-3">
                <div class="col-md-2">
                <label>Sale Price :</label>
                </div>
                <div class="col-md-10">
                    <input name="sale_price" type="number" class="form-control" placeholder="15" value="{{ $product->sale_price ?? 0 }}">
                </div>
            </div>
            <div class="row pt-3">
                <div class="col-md-2">
                    <label>Quantity :</label>
                </div>
                <div class="col-md-10">
                    <input name="quantity" type="number" class="form-control" placeholder="100" value="{{ $product->quantity ?? 0 }}">
                </div>
            </div>


            <div class="pt-3">
                <button class="btn btn-primary" onclick="validateFieldsByFormId(this)"
                            data-validation="validation-span-id"
                            id="validation-span-id" style="float:right;">
                            Update Product
                </button>
            </div>
        </form>


    </div>
</div>
</main>
    <div>

    </div>
@endsection
