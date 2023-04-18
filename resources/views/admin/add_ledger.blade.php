@extends('layouts.admin')
@section('content')
    <main class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Ledgers</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Add Ledger</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <h4 class="mb-0">Add Ledger</h4>
                <hr/>
                <form method="POST" action="/ledger/add" id="public-holiday-form-id"data-modal-id="add-viewer-id">
                    @csrf
                    <div class="row">
                        <div class="col-md-2">
                            <label>Products :</label>
                        </div>
                        <div class="col-md-10">
                            <select class="form-select mb-3" name="product_id" aria-label="Default select example">
                                <option >Select Product</option>
                                @if($products->count() > 0)
                                    @foreach($products as $product)
                                        <option value="{{ $product->id  }}">{{ $product->name ?? "" }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-md-2">
                            <label>Shops :</label>
                        </div>
                        <div class="col-md-10">
                            <select class="form-select mb-3" name="shop_id" aria-label="Default select example">
                                <option >Select Shop</option>
                                @if($shops->count() > 0)
                                    @foreach($shops as $shop)
                                        <option value="{{ $shop->id }}">{{ $shop->name ?? "" }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="row pt-3">
                        <div class="col-md-2">
                            <label>Quantity :</label>
                        </div>
                        <div class="col-md-10">
                            <input name="quantity" type="integer" class="form-control" placeholder="10">
                        </div>
                    </div>

                    <div class="pt-3">
                        <button class="btn btn-primary" onclick="validateFieldsByFormId(this)"
                                data-validation="validation-span-id"
                                id="validation-span-id" style="float:right;">
                            Add Ledger
                        </button>
                    </div>
                </form>


            </div>
        </div>
    </main>
    <div>

    </div>
@endsection
