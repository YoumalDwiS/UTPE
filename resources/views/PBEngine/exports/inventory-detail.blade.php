<table>
    <thead>
        <tr>
            <th style="font-weight: bold">Part Number</th>
            <th>{{ $component->pn_component }}</th>
        </tr>
        <tr>
            <th style="font-weight: bold">Component Name</th>
            <th>{{ $component->component_name }}</th>
        </tr>
        <tr>
            <th style="font-weight: bold">Stock</th>
            <th>{{ $component->quantity }}</th>
        </tr>
        <tr></tr>
        <tr>
            <th style="font-weight: bold">Created At</th>
            <th style="font-weight: bold">Quantity</th>
            <th style="font-weight: bold">In/Out</th>
            <th style="font-weight: bold">Memo Number</th>
            <th style="font-weight: bold">Created By</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($semifinishLog as $sl)
            <tr>
                <td>{{ date('d/m/Y H:i', strtotime($sl->created_at)) }}</td>
                <td>{{ $sl->quantity }}</td>
                <td style="text-align:center">{{ $sl->type }}</td>
                <td>{{ $sl->memo_number }}</td>
                <td>{{ $sl->created_by }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
