<div class="block-content block-content-full overflow-class">
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
                        <a class="d-inline btn btn-sm btn-alt-info" href="{{route('product.viewer.detail.view', Crypt::encrypt($data->id) )}}"><i class="bi bi-card-list" aria-hidden="true" style="" title="Shop All Payments"></i></a>
                    </td>
                </tr>
            @endforeach

        @endif
        </tbody>
    </table>
</div>
