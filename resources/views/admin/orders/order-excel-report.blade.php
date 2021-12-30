
                                <table>
                                    <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Total Quantity</th>
                                        <th>Packet Size</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($quantities as $quantity)
                                        <tr>
                                            <td>{{$quantity->product->name??''}}</td>
                                            <td>{{$quantity->packet_count}}</td>
                                            <td>{{$quantity->product->display_pack_size??''}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                