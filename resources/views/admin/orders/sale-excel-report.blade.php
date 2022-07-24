 
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Total Quantity</th>
                                        <th>Packet Size</th>
                                        <th>Price</th>
                                        <th>Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($quantities as $quantity)
                                    <?php $amount=$quantity->packet_count*$quantity->product->packet_price;?>
                                        <tr>
                                            <td>{{$quantity->product->name??''}}</td>
                                            <td>{{$quantity->packet_count}}</td>                                           
                                            <td>{{$quantity->product->display_pack_size??''}}</td>
                                            <td>{{$quantity->product->packet_price??''}}</td>
                                            <td>{{$amount??''}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                               