<div class="block-content block-content-full">
    <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination" id="example">
        <thead>
            <tr>
                <th style="width: 15%;">Name</th>
                <th style="width: 15%;">Email</th>
                <th style="width: 10%;">Action</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($viewers) && !empty($viewers))
                @foreach($viewers as $data)
                <tr>
                    <td class="font-w600 font-size-sm">{{$data->name ?? ''}}</td>
                    <td class="font-w600 font-size-sm">{{$data->email ?? ''}}</td>
                    <td>


                    </td>
                </tr>
                @endforeach

            @endif
        </tbody>
    </table>
</div>