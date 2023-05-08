<div class="block-content block-content-full">
    <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination" id="example">
        <thead>
            <tr>
                <th>Name</th>
                <th>Product Code</th>
                <th>Purchase Price</th>
                <th>Sale Price</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($products) && !empty($products))
                @foreach($products as $data)
                <tr>
                    <td class="font-w600 font-size-sm">{{$data->name ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{$data->product_code ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{$data->purchase_price ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{$data->sale_price ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{$data->quantity ?? ''}}</td>
                    <td>
                        <a class="d-inline btn btn-sm btn-alt-info" href="{{ route('get.update.product',Crypt::encrypt($data->id) ) }}"><i class="bi bi-pencil" aria-hidden="true" style="color:green" ></i></a>
                        <button class="d-inline btn btn-sm btn-alt-info" onclick="deleteRecord('product/delete',{{ $data->id ?? ''}})"><i class="bi bi-trash" aria-hidden="true" style="color:red"></i></button>
                        <a class="d-inline btn btn-sm btn-alt-info" onclick="productPayemnt('/product/pay',{{ $data->id }})"><i class="bi bi-box-arrow-in-down" aria-hidden="true" style="" title="Add Payment"></i></a>
                        <a class="d-inline btn btn-sm btn-alt-info" href="{{route('product.detail.view', Crypt::encrypt($data->id) )}}"><i class="bi bi-card-list" aria-hidden="true" style="" title="Shop All Payments"></i></a>
                    </td>
                </tr>
                @endforeach

            @endif
        </tbody>
    </table>
</div>
