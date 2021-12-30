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
        <td colspan="5" style="text-align:center;"><p class="p1">fresh2arrive</p>
            <p style="font-size:20px;font-weight:700;"> Ecoveggy Private Limited.</p>
            <p>Commorcial Shop,Hosiyarpur,Sec-52 Noida,Opp - Piller - No 232<br/>
                9990194488,8800808954
                <br/>(GSTIN : 09AAGCE6115M1ZD)
            </p></td>
    </tr>

    <tr><td colspan="5"><hr/></td></tr>

    <tr>
        <td  colspan="5" style="text-align: center"><p><B>[RETAIL INVOICE]</B></p></td>
    </tr>

    <tr>
        <td colspan="3"><p>OrderID: {{$order_data->refrerid ?? 0}}</p></td>
        <td><p style="border:1px solid;text-align: center;padding: 5px">Bags : {{$order_data->bags_no ?? 1}}</p></td>
    </tr>

<!--
    <tr>
        <td colspan="5">


            <p><b>{{$order_data->refrerid ?? 0}}</b></p>

        </td>
    </tr>
-->

    <tr>
        <td colspan="5"><br/></td>
    </tr>

    <tr>
        <td colspan="5"><p>Customer : {{$order_data->name}} ({{$order_data->mobile}})</p></td>
    </tr>

    <tr>
        <td colspan="5"><p>Address &nbsp;:{{$order_data->house_no}},{{$order_data->building}},{{$order_data->street}},{{$order_data->city}},{{$order_data->pincode}},({{$order_data->area}})</p>
        </td>
    </tr>

    <tr>
        <td colspan="5"><hr/></td>
    </tr>

    <tr>
        <td colspan="5"><p>Delivery Owner :{{$order_data->pname}} ({{$order_data->pmobile}})</p></td>
    </tr>

    <tr>
        <td colspan="5">  <p>Address &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;:{{$order_data->phouse_no}},{{$order_data->pcity}},{{$order_data->pstate}},{{$order_data->ppincode}} {{$order_data->address}}</p></td>
    </tr>

    <tr><td colspan="5"><br/></td></tr>


    <tr>
        <td colspan="1"><p>Order Date &nbsp;&nbsp;&nbsp;:  {{ date('d/M/y', strtotime($order_data->created_at)) }}<br/>
        
        Placed At  &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;:  {{ date('h:i A', strtotime($order_data->created_at)) }}</p></td>

{{--                Wed 27 oct 2021--}}
<!--
    </tr>
    <tr>
-->
        <td colspan="3"><p>Delivery Date : {{ date('d/M/y', strtotime($order_data->delivery_date)) }} {{ $order_data->delivery_time }} </p></td>
{{--                Thu 28 oct 2021--}}
<!--
    </tr>
    <tr>
-->
        <td colspan="1"><p>Crate No &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;: {{$order_data->crate_no}}</p></td>
    </tr>


    <tr><td colspan="5"><br/></td></tr>


    <tr class="trborder1">
        <td>Description</td>
        <td>Size</td>
        <td>Qty</td>
        <td>Rate</td>
        <td>Amt</td>
    </tr>

    <tr><td colspan="5"><hr/></td></tr>
    <?php  $total =0;
           $orderdetails=0;
           $grandtotal=0;
          // $order_data=$order_data->echo_charges;
    ?>
    @foreach($order_detail as $orderdetails)

    <tr class="trborder">
        <p>
        <td >{{$orderdetails->name}}</td>
        <td >{{$orderdetails->display_pack_size}}</td>
        <td>{{$orderdetails->packet_count}}</td>
        <td>{{$orderdetails->packet_price}}</td>
        <td>{{$orderdetails->packet_count * $orderdetails->packet_price}}</td>
        </p>
    </tr>   <?php $amt=$orderdetails->packet_count * $orderdetails->packet_price?>
             <?php $total+=$amt; ?>
    @endforeach
    <tr><td><br/></td></tr>

    <tr class="trborder">
        <td></td>
        <td></td>
        <td></td>
        <td>Total :-</td>
        <td>{{$total}}</td>
    </tr>

    <tr class="trborder">
        <td></td>
        <td></td>
        <td></td>
        <td>GST exempted :-</td>
        <td>0</td>
    </tr>

    <tr class="trborder">
        <td></td>
        <td></td>
        <td></td>
        <td>Discount :-</td>
        <td>0</td>
    </tr>

    <tr class="trborder">
        <td></td>
        <td></td>
        <td></td>
        <td>Eco Friendly Packaging:-</td>
        <td>{{$order_data->echo_charges}}</td>
    </tr>

    <tr class="trborder">
        <td></td>
        <td></td>
        <td></td>
        <td><b>Grand Total :-</b></td>
        <td><b>{{$total + $order_data->echo_charges}}</b></td>
    </tr>
</table>
<table style="width:100%">
    <tr>
        <td><p>Thanks for buying from fresh2arrive<br/> HAVE A NICE DAY <br/>follow us on Instagram,Facebook,Twitter.</p></td>
        <td>
            <p>Visit on : www.fresh2arrive.com</p>
            <p>
                All disputes are subject to <br/>Gautam Budh Nagar Jurisdiction Only
            </p>
        </td>
    </tr>
</table>



</tr>
</body>
</html>
