@extends("admin.admin_app")

@section("content")
 
  <!-- Page Header -->
                <div class="content bg-gray-lighter">
                    <div class="row items-push">
                        <div class="col-sm-7">
                            <h1 class="page-heading">
                            {{trans('words.transactions')}}  
                            </h1>
                        </div>
                        <div class="col-sm-5 text-right hidden-xs">
                            <ol class="breadcrumb push-10-t">
                                <li><a href="{{ URL::to('admin/dashboard') }}">Dashboard</a></li>
                                <li><a class="link-effect" href="">{{trans('words.transactions')}}</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- END Page Header -->

                <!-- Page Content -->
                <div class="content">
                    <!-- Dynamic Table Full -->
                    <div class="block">
                         
                        <div class="block-content">
                            <div class="block-header" style="padding-left:0;padding-right:0;">
                            <div class="col-lg-3 col-md-3" style="padding-left:0">
                                <select name="gateway_select" id="gateway_select" class="form-control">
                                    <option value="?">{{trans('words.filter_by_gateway')}}</option>
                                    @foreach($gateway_list as $gateway_data)
                                    <?php $gateway_name=$gateway_data->gateway_name;?>
                                    <option value="?gateway={{$gateway_name}}" @if(isset($_GET['gateway']) && $_GET['gateway']==$gateway_name ) selected @endif>{{$gateway_data->gateway_name}}</option>
                                    @endforeach
                                 </select>
                            </div> 
                            <div class="col-lg-5 col-md-6" style="padding-left:0;display: flex;">
                                <form action="{{ url('admin/transaction') }}" class="" id="search" role="form" method="GET">
                                            <input type="text" class="span8 form-control" name="s" value="{{ request('s') }}" placeholder="{{trans('words.search_by_payment_id_email')}}" style="float: left;display: inline;width: 68%;">
                                            <button type="submit" class="btn btn-primary">{{trans('words.search')}}</button>
                                </form>
                            </div>
                             
                        </div>
                            <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>

                                         <th>{{trans('words.email')}}</th>
                                        <th>Transaction ID</th>
                                        <th>{{trans('words.plan')}}</th>
                                        <th>{{trans('words.payment_gateway')}}</th>
                                        <th>{{trans('words.amount')}}</th>
                                        <th>{{trans('words.payment_date')}}</th>
                                         <!--<th class="text-center" style="width: 10%;">Actions</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                     @foreach($transaction as $i => $trans)
                                    <tr>                                         
                                        <td>{{ $trans->email }}</td>
                                        <td>{{ $trans->payment_id }}</td>
                                        <td>{{ \App\Models\SubscriptionPlan::getSubscriptionPlanInfo($trans->plan_id)->plan_name}}</td>
                                        <td>{{ $trans->gateway }}</td>
                                         
                                        <td>{{html_entity_decode(getCurrencySymbols(getcong('currency_code')))}}{{$trans->payment_amount}}</td>
                                         
                                        <td>{{ date('M,  jS, Y',$trans->date) }}</td>
                                          
                                        
                                    </tr>
                                   @endforeach
                                    
                                </tbody>
                            </table>
                            </div>
                            @include('admin.pagination', ['paginator' => $transaction]) 
                        </div>
                    </div>
                    <!-- END Dynamic Table Full -->

                    
                </div>
                <!-- END Page Content -->

@endsection