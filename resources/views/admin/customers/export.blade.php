 


                                <table>
                                    <thead>
                                <tr>
                                    <td>name</td>
                                    <td>mobile</td>                                      
                                    <td>Balance</td>    
                                    <td>Orders</td>   
                                    <td>Orders Total</td>  
                                    <td>assigned_partner</td>
                                    <td>reffered_by</td>
                                    <td>reffered_by_partner</td>                                
                                    <td>Address</td>
                                    <td>Society/Sector</td>
                                    <td>Registered On</td>
                                    <td>Registered Date</td>
                                                                        
                                    <!-- <td>lat</td>
                                    <td>lang</td> -->
                                    <!-- <td>map_address</td> -->
                                                                                                   
                             </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($customer as $quantity)
                                    <tr>
                                    <td>{{$quantity->name}}</td>
                                    <td>{{$quantity->mobile}}  </td>   
                                    <td>{{\App\Models\Wallet::balance($quantity->id)}}</td> 
                                    <td>{{$quantity->orders_count}}</td>    
                                    <td>{{\App\Models\Customer::total_order($quantity->id)}}</td>                                      
                                    <td>{{$quantity->partner->name??'Not Alloted'}}</td>
                                    <td>{{$quantity->refferer->name ?? ''}}</td>
                                    <td>{{$quantity->partnerRefferer->name ?? ''}}</td>                            
                                    <td>House No/Flat No.{{$quantity->house_no}} {{$quantity->building}} {{$quantity->street}}</td>                                    
                                    <td>{{$quantity->street}}</td>     
                                    <td>{{date('d/m/Y',strtotime($quantity->created_at))}}</td>
                                    <td>{{date('h:i:a',strtotime($quantity->created_at))}}</td>                               
                                    <!-- <td>{{$quantity->lat}}</td>
                                    <td>{{$quantity->lang}}</td> -->
                                    <!-- <td>{{$quantity->map_address}}</td> -->
                                   
                                    
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                