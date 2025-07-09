<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Order ID</th>
            <th>Vehicle ID</th>
            <th>Requested On</th>
            <th>Accepted On</th>
            <th>Status</th>
            <th>Accepted By</th>
            <th>Rejected Reason</th>
        </tr>
    </thead>
    <tbody>
        @foreach($requests as $index => $request)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>#{{ $request->order->order_number ?? '-' }}</td>
                <td>{{ $request->stock->vehicle_number ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($request->request_date)->format('d M Y h:i A') }}</td>
                <td>{{ \Carbon\Carbon::parse($request->accepted_date)->format('d M Y h:i A') }}</td>
                <td>{{ ucfirst($request->type) }}</td>
                <td>{{ $request->admin->email ?? '-' }}</td>
                <td>{{ $request->rejected_reason ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
