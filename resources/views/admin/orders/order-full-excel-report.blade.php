<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Mobile</th>
        <th>OR.No</th>
        <th>Total</th>
        <th>Status</th>
        <th>Is_Paid</th>
        <th>Delivery Date</th>
        <th>Delivery Time</th>
        <th>Delivery Partner</th>
        <th>Address Of Customer</th>
        <th>Society Name</th>
        <th>Customer Registered Date</th>
        <th>Last Order Date</th>
        <th>Last Order No</th>

        <th>Action</th>

    </tr>
    </thead>
    <tbody>
    @foreach($orders as $Order)
        <tr>
            <td>{{$Order->id}}</td>
            <td>{{$Order->customer->name}}</td>
            <td>{{$Order->customer->mobile}}</td>
            <td>{{$Order->customer->orders_count}}</td>
            <td>{{$Order->order_total}}</td>
            <td>{{$Order->status}}</td>
            <td>{{$Order->is_paid==1 ? 'Yes':'No'}}</td>
            <td>{{date('d-m-Y', strtotime($Order->delivery_date));}}</td>
            <td>{{$Order->delivery_time}}</td>
            <td>{{$Order->partner->name}}</td>
            <td>
                {{$Order->customer->building??''}},
                {{$Order->customer->street??''}},
                {{$Order->customer->city??''}},
                {{$Order->customer->state??''}},
                {{$Order->customer->pincode??''}},
                {{$Order->customer->house_no??''}}
            </td>
            <td>{{$Order->customer->building??''}}</td>
            <td>{{date('d-m-Y', strtotime($Order->customer->created_at));}}</td>
            <td>{{date('d-m-Y', strtotime($Order->created_at));}}</td>
            <td>{{$Order->id}}</td>
            <td><a href="{{route('orders.edit',['id'=>$Order->id])}}">Edit</a> <a href="{{route('invoice',['orderid'=>$Order->id])}}" class="btn btn-success">Print</a>
            </td>
        </tr>





    @endforeach
    </tbody>
</table>
