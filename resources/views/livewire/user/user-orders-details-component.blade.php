<div>
    <div class="container" style="padding: 30px 0;">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @if (Session::has('order_message'))
                            <div class="alert alert-success" role="alert">{{Session::get('order_message')}}</div>
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                Ordered Details
                            </div>
                            <div class="col-md-6">
                                <a href="{{route('user.orders')}}" class="btn btn-success pull-right">My Orders</a>
                                @if ($Order->status == 'ordered')
                                    <a href="" wire:click.prevent="cancel({{$Order->id}})" style="margin-right:20px" class="btn btn-warning pull-right">Cancel Order</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table">
                            <tr>
                                <th>Order Id</th>
                                <td>{{$Order->id}}</td>
                                <th>Order Date</th>
                                <td>{{$Order->created_at}}</td>
                                <th>Order Status</th>
                                <td>{{$Order->status}}</td>
                                @if ($Order->status == 'delivered')
                                    <th>Delivery Date</th>
                                    <td>{{$Order->delivered_date}}</td>
                                @elseif($Order->status == 'canceled')
                                    <th>Cancellation Date</th>
                                    <td>{{$Order->canceled_date}}</td>
                                @endif
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Ordered Items
                    </div>
                    <div class="panel-body">
                        <div class="wrap-iten-in-cart">
                            <h3 class="box-title">Products Name</h3>
                            <ul class="products-cart">
                                @foreach ($Order->orderItems as $item)
                                    <li class="pr-cart-item">
                                        <div class="product-image">
                                            <figure><img src="{{asset('assets/images/products')}}/{{$item->product->image}}" alt="{{$item->product->name}}"></figure>
                                        </div>
                                        <div class="product-name">
                                            <a class="link-to-product" href="{{route('product.details',['slug' => $item->product->slug])}}">{{$item->product->name}}</a>
                                        </div>
                                        <div class="price-field produtc-price"><p class="price">{{$item->price}}</p></div>
                                        <div class="quantity">
                                            <div class="text-center">
                                                <h5>{{$item->quantity}}</h5>
                                            </div>
                                        </div>
                                        <div class="price-field sub-total"><p class="price">{{$item->price * $item->quantity}}</p></div>
                                        @if ($Order->status == 'delivered' && $item->review_status == false)
                                            <div class="price-field sub-total"><p class="price"><a href="{{route('user.review',['id'=>$item->id])}}">Write Review</a></p></div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="summary">
                            <div class="order-summary">
                                <h4 class="title-box">Order Summary</h4>
                                <p class="summary-info"><span class="title">Subtotal</span><b class="index">${{$Order->subtotal}}</b></p>
                                <p class="summary-info"><span class="title">Tax</span><b class="index">${{$Order->tax}}</b></p>
                                <p class="summary-info"><span class="title">Shipping</span><b class="index">Free Shipping </b></p>
                                <p class="summary-info"><span class="title">Total</span><b class="index">${{$Order->total}}</b></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Billing Details
                    </div>
                    <div class="panel-body">
                        <table class="table">
                            <tr>
                                <th>First Name</th>
                                <td>{{$Order->firstname}}</td>
                                <th>Last Name</th>
                                <td>{{$Order->lastname}}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{$Order->mobile}}</td>
                                <th>Email</th>
                                <td>{{$Order->email}}</td>
                            </tr>
                            <tr>
                                <th>Line1</th>
                                <td>{{$Order->line1}}</td>
                                <th>Line2</th>
                                <td>{{is_null($Order->line2) ? '' : $Order->line2}}</td>
                            </tr>
                            <tr>
                                <th>City</th>
                                <td>{{$Order->city}}</td>
                                <th>Province</th>
                                <td>{{$Order->province}}</td>
                            </tr>
                            <tr>
                                <th>County</th>
                                <td>{{$Order->country}}</td>
                                <th>PostCode</th>
                                <td>{{$Order->zipcode}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    
        @if ($Order->is_shipping_different)
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Shipping Details
                        </div>
                        <div class="panel-body">
                            <table class="table">
                                <tr>
                                    <th>First Name</th>
                                    <td>{{$Order->shipping->firstname}}</td>
                                    <th>Last Name</th>
                                    <td>{{$Order->shipping->lastname}}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{$Order->shipping->mobile}}</td>
                                    <th>Email</th>
                                    <td>{{$Order->shipping->email}}</td>
                                </tr>
                                <tr>
                                    <th>Line1</th>
                                    <td>{{$Order->shipping->line1}}</td>
                                    <th>Line2</th>
                                    <td>{{is_null($Order->shipping->line2) ? '' : $Order->shipping->line2}}</td>
                                </tr>
                                <tr>
                                    <th>City</th>
                                    <td>{{$Order->shipping->city}}</td>
                                    <th>Province</th>
                                    <td>{{$Order->shipping->province}}</td>
                                </tr>
                                <tr>
                                    <th>County</th>
                                    <td>{{$Order->shipping->country}}</td>
                                    <th>PostCode</th>
                                    <td>{{$Order->shipping->zipcode}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    
        @if ($Order->transaction)
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Transaction
                        </div>
                        <div class="panel-body">
                            <table class="table">
                                <tr>
                                    <th>Transaction Mode</th>
                                    <td>{{$Order->transaction->mode}}</td>
                                </tr>
            
                                <tr>
                                    <th>Status</th>
                                    <td>{{$Order->transaction->status}}</td>
                                </tr>
    
                                <tr>
                                    <th>Transaction Date</th>
                                    <td>{{$Order->transaction->created_at}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
        @endif
    </div>
</div>
