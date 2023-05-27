<div class="block-content block-content-full overflow-class">
    <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination" id="example">
        <thead>
            <tr>
                <th>Name</th>
                <th>Address</th>
                <th>Phone Number</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($shops) && !empty($shops))
                @foreach($shops as $data)
                <tr>
                    <td class="font-w600 font-size-sm">{{$data->name ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{$data->address ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{$data->phone_number ?? ''}}</td>
                    <td>
                        <a class="d-inline btn btn-sm btn-alt-info" href="{{ route('get.update.shop',Crypt::encrypt($data->id) ) }}"><i class="bi bi-pencil" aria-hidden="true" style="color:green" ></i></a>
                        <button class="d-inline btn btn-sm btn-alt-info" onclick="deleteRecord('shop/delete',{{ $data->id ?? ''}})"><i class="bi bi-trash" aria-hidden="true" style="color:red"></i></button>
                    </td>
                </tr>
                @endforeach

            @endif
        </tbody>
    </table>
</div>
