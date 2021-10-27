<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Invoice</title>

<style>
    .table{
        width:100%;
        border:1px solid;
    }
    .table p{
        margin: 2px;
        padding: 0px;
        font-weight: 550;
    }
    .p1{
        font-size:30px;
        font-weight: 700;
    }
    .trborder td{
        width:20%;
        text-align:center;


    }
    .trborder1 td{
        font-size:16px;
        font-weight:799;
        width:20%;
        text-align:center;

    }
</style>
</head>

<body>

<table class="table">
    <tr>
        <td colspan="4" style="text-align:center;">
            <p class="p1">Fresh2arrive</p>
            <p style="font-size:20px;font-weight:700;"> Ecoveggy Private Limited.</p>
            <p>Commercial Shop,Hosiyarpur,Sec-52 Noida,Opp - Piller - No 232<br/>
                9990194488,8800808954
                <br/>(GSTIN : 09AAGCE6115M1ZD)
            </p>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <hr/>
        </td>
    </tr>
    <tr>
        <td  colspan="4" style="text-align: center">
            <p><B>[RETAIL INVOICE]</B></p>
        </td>
    </tr>
    <tr>
        <td>
            <p>OrderID</p>
        </td>
        <td></td>
        <td></td>
        <td>
            <p style="border:1px solid;text-align: center;padding: 5px">Bags : 1</p>
        </td>
    </tr>
    <tr>
        <td>
            <p><b>{{$order_data->oid}}</b></p>
        </td>
        <td></td>
        <td></td>
        <td></td>
    </tr>

    <tr>
        <td colspan="4"><br/></td>
    </tr>

    <tr>
        <td colspan="4"><p>Customer : {{$order_data->name}} ({{$order_data->mobile}})</p>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <p>Address &nbsp;:{{$order_data->house_no}},{{$order_data->area}},{{$order_data->city}},{{$order_data->state}},{{$order_data->pincode}}</p>
        </td>
    </tr>

    <tr>
        <td colspan="4"><hr/></td>
    </tr>
    <tr>
        <td colspan="4"><p>Delivery Owner :{{$order_data->pname}} ({{$order_data->pmobile}})</p></td>

    </tr>

    <tr>
        <td colspan="4">  <p>Address &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;:{{$order_data->phouse_no}},{{$order_data->pcity}},{{$order_data->pstate}},{{$order_data->ppincode}} {{$order_data->address}}</p></td>


    </tr>

    <tr>
        <td><br/></td>
        <td><br/></td>
        <td><br/></td>
        <td><br/></td>
    </tr>



    <tr>
        <td colspan="4">
            <p>Order Date &nbsp;&nbsp;&nbsp;:  {{ date('d/M/y', strtotime($order_data->created_at)) }}</p>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <p>Delivery Date : {{ date('d/M/y', strtotime($order_data->delivery_date)) }}  </p>
        </td>
    </tr>
    <tr>
        <td colspan="4"><p>Crate No &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;: f2a0001</p></td>
    </tr>


    <tr><td colspan="4"><br/></td></tr>


    <tr class="trborder1">
        <td>Description</td>
        <td>Qty</td>
        <td>Rate</td>
        <td>Amt</td>
    </tr>

    <tr>
        <td colspan="4"><hr/>
        </td>
    </tr>

    <?php  $total =0;
           $orderdetails=0;
           $grandtotal=0;
          // $order_data=$order_data->echo_charges;
    ?>
    @foreach($order_detail as $orderdetails)

    <tr class="trborder">
        <p>
        <td >{{$orderdetails->name}}</td>
        <td>{{$orderdetails->quantity}}</td>
        <td>{{$orderdetails->price}}</td>
        <td>{{$orderdetails->quantity * $orderdetails->price}}</td>
        </p>
    </tr>
        <?php $amt=$orderdetails->quantity * $orderdetails->price?>
        <?php $total+=$amt; ?>
    @endforeach
    <tr>
        <td><br/></td>
        <td><br/></td>
        <td><br/></td>
        <td><br/></td>
    </tr>
    <tr class="trborder">
        <td></td>
        <td></td>
        <td>Total :-</td>
        <td>{{$total}}</td>
    </tr>

    <tr class="trborder">
        <td></td>
        <td></td>
        <td>GST exempted :-</td>
        <td>0</td>
    </tr>

    <tr class="trborder">
        <td></td>
        <td></td>
        <td>Discount :-</td>
        <td>0</td>
    </tr>

    <tr class="trborder">
        <td></td>
        <td></td>
        <td>Eco Friendly Packaging:-</td>
        <td>{{$order_data->echo_charges}}</td>

    </tr>

    <tr class="trborder">
        <td></td>
        <td></td>
        <td>Grand Total :-</td>
        <td> {{$total + $order_data->echo_charges}}</td>
    </tr>

</table>
<br/>
<B>Thanks for buying from fresh2arrive<br/> HAVE A NICE DAY <br/>follow us on Instagram,Facebook,Twitter.</B><br/><br/>
<b>Visit on : www.fresh2arrive.com</b><br/><br/>
<b>
    All disputes are subject to <br/>Gautam Budh Nagar Jurisdiction Only
</b>

</tr>
</body>
</html>
