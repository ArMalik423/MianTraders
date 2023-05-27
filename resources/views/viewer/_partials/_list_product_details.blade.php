<div class="block-content block-content-full overflow-class">
    <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination" id="example">
        <thead>
        <tr>
            <th>Shop Name</th>
            <th>Quantity</th>
            <th>Profit</th>
            <th>Status</th>
            <th>Credit</th>
            <th>Debit</th>
            <th>Expense</th>
            <th>Profit After Expense</th>
            <th>Closing Account</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($productDetails) && !empty($productDetails))
            @foreach($productDetails as $data)
                <tr>
                    <td class="font-w600 font-size-sm">{{ $data->product->name ?? 'Amount Payed' }}</td>
                    <td class="font-w600 font-size-sm">{{$data->quantity ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{$data->profit ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{($data->status == '0') ? 'Credit' : 'Debit Cash Paid'}}</td>
                    <td class="font-w600 font-size-sm">{{$data->credit ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{$data->debit ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{$data->expense ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{$data->calculate_expense ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{$data->closing_account ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{date("F d, Y h:i:s",$data->created_at->timestamp) ?? ''}}</td>
                </tr>
            @endforeach

        @endif
        </tbody>
    </table>
</div>

