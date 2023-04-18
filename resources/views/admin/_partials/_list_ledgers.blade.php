<div class="block-content block-content-full">
    <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination" id="example">
        <thead>
        <tr>
            <th>ID</th>
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
                    <td class="font-w600 font-size-sm">{{$data->shop_id ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{$data->payable_amount ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{$data->amount_paid ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{$data->remaining_amount ?? ''}}</td>
                    <td>
{{--                        <a class="d-inline btn btn-sm btn-alt-info" href="{{ route('get.update.product',Crypt::encrypt($data->id) ) }}"><i class="bi bi-pencil" aria-hidden="true" style="color:green" ></i></a>--}}
{{--                        <button class="d-inline btn btn-sm btn-alt-info" onclick="deleteRecord('product/delete',{{ $data->id ?? ''}})"><i class="bi bi-trash" aria-hidden="true" style="color:red"></i></button>--}}
                    </td>
                </tr>
            @endforeach

        @endif
        </tbody>
    </table>
</div>
