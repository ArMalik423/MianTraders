<div class="block-content block-content-full overflow-class">
    <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination" id="example">
        <thead>
        <tr>
            <th>Shop Name</th>
            <th>Payable Amount</th>
            <th>Amount Paid</th>
            <th>Remaining Amount</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($accounts) && !empty($accounts))
            @foreach($accounts as $data)
                <tr>
                    <td class="font-w600 font-size-sm">{{$data->shop->name ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{$data->payable_amount ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{$data->amount_paid ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{$data->remaining_amount ?? ''}}</td>
                    <td>
                        <a class="d-inline btn btn-sm btn-alt-info" href="{{route('viewer.shop.payment', Crypt::encrypt($data->shop_id) )}}"><i class="bi bi-card-list" aria-hidden="true" style="" title="Shop All Payments"></i></a>
                    </td>
                </tr>
            @endforeach

        @endif
        </tbody>
    </table>
</div>
