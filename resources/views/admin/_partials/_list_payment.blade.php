<div class="block-content block-content-full">
    <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination" id="example">
        <thead>
        <tr>
            <th>Shop Name</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Credit</th>
            <th>Debit</th>
            <th>Closing Account</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($ledgers) && !empty($ledgers))
            @foreach($ledgers as $data)
                <tr>
                    <td class="font-w600 font-size-sm">{{$data->shop->name ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{ $data->product->name ?? 'Amount Received' }}</td>
                    <td class="font-w600 font-size-sm">{{$data->price ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{$data->quantity ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{$data->status == '0' ? 'Credit' : 'Debit' }}</td>
                    <td class="font-w600 font-size-sm">{{$data->credit ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{$data->debit ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{$data->closing_account ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{date("F d, Y h:i:s",$data->created_at->timestamp) ?? ''}}</td>
                </tr>
            @endforeach

        @endif
        </tbody>
    </table>
</div>

