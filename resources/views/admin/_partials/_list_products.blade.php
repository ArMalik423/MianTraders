<div class="block-content block-content-full">
    <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination" id="example">
        <thead>
            <tr>
                <th>Name</th>
                <th>Product Code</th>
                <th>Purchase Price</th>
                <th>Sale Price</th>
                <th>Discount</th>
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
                    <td class="font-w600 font-size-sm">{{$data->discount ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{$data->quantity ?? ''}}</td>
                    <td>
                        <a class="d-inline btn btn-sm btn-alt-info" href="{{ route('get.update.product',Crypt::encrypt($data->id) ) }}"><i class="bi bi-pencil" aria-hidden="true" style="color:green" ></i></a>
                        <button class="d-inline btn btn-sm btn-alt-info" onclick="deleteRecord('product/delete',{{ $data->id ?? ''}})"><i class="bi bi-trash" aria-hidden="true" style="color:red"></i></button>
                    </td>
                </tr>
                @endforeach

            @endif
        </tbody>
    </table>
</div>
