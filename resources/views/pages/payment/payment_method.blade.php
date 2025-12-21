@extends('app')

@section('head_title',trans('words.payment_method').' | '.getcong('site_name') )

@section('head_url', Request::url())
@section('use_hero_header', true)
@section('hero_title', trans('words.payment'))
@section('hero_breadcrumbs', json_encode([['label' => trans('words.home'), 'url' => '/'], ['label' => trans('words.payment'), 'url' => '']]))

@section("content")

<style type="text/css">
#loading {
    background: url('{{ URL::asset('assets/images/LoaderIcon.gif') }}') no-repeat center center;
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    z-index: 9999999;
    opacity: 1;
}  
.payment_loading{
  opacity: 0.5;
}
</style>  

<div id="loading" style="display: none;"></div>

@include('common.page-hero-header')
    </div>    
</section>
<!-- ================================
     End Breadcrumb Area Area
================================= --> 

<!-- ================================
     Start Payment Method Area
================================= -->
<section class="user-details bg-gray section_item_padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                
            @if(Session::has('flash_message'))
                <div class="alert alert-success">
                
                    {{ Session::get('flash_message') }}
                </div>
                @endif
        
        
            @if(Session::has('error_flash_message'))
            <div class="alert alert-danger">
            
                {{ Session::get('error_flash_message') }}
            </div>
            @endif   

                <div class="payment-details-area">
                    <h3>{{trans('words.payment_method')}}</h3>
                    <div class="select-plan-text">{{trans('words.you_have_selected')}}<span>{{$plan_info->plan_name}}</span></div>
                    <p>{{trans('words.logged_in_as')}} <a href="#">{{Auth::User()->email}}</a>  
                    <div class="mt-3"><a href="{{ URL::to('pricing') }}" class="primary_item_btn border-0">{{trans('words.change_plan')}}</a></div>
                </div>
            </div>
        </div>
    @if(Auth::User()->mobile!='')
        <div class="row">
        @if(getPaymentGatewayInfo(1)->status)
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="select-payment-method">
                    <h1>{{getPaymentGatewayInfo(1)->gateway_name}}</h1>
                    <h4>{{trans('words.pay_with')}} {{getPaymentGatewayInfo(1)->gateway_name}}</h4>
                    
                    <form action="{{ url('paypal/pay') }}" class="" id="" role="form" method="POST">
                        @csrf
                        <input id="plan_id" type="hidden" class="form-control" name="plan_id" value="{{$plan_info->id}}">
                        <input id="amount" type="hidden" class="form-control" name="amount" value="{{$plan_info->plan_price}}">
                        <input id="plan_name" type="hidden" class="form-control" name="plan_name" value="{{$plan_info->plan_name}}">
                        
                        <button type="submit" class="primary_item_btn border-0">{{trans('words.pay_now')}}</button>
                    </form>

                 </div>
            </div>
        @endif

        @if(getPaymentGatewayInfo(2)->status)    
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="select-payment-method">
                    <h1>{{getPaymentGatewayInfo(2)->gateway_name}}</h1>
                    <h4>{{trans('words.pay_with')}} {{getPaymentGatewayInfo(2)->gateway_name}}</h4>
                    <a href="{{ URL::to('stripe/pay') }}" class="primary_item_btn border-0" title="{{trans('words.pay_now')}}">{{trans('words.pay_now')}}</a>
                 </div>
            </div>
        @endif
        
        @if(getPaymentGatewayInfo(3)->status)    
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="select-payment-method">
                    <h1>{{getPaymentGatewayInfo(3)->gateway_name}}</h1>
                    <h4>{{trans('words.pay_with')}} {{getPaymentGatewayInfo(3)->gateway_name}}</h4>
                    <button type="button" id="razorpayId" class="primary_item_btn border-0" data-bs-toggle="modal">{{trans('words.pay_now')}}</button>
                 </div>
            </div>
        @endif

        @if(getPaymentGatewayInfo(4)->status)    
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="select-payment-method">
                    <h1>{{getPaymentGatewayInfo(4)->gateway_name}}</h1>
                    <h4>{{trans('words.pay_with')}} {{getPaymentGatewayInfo(4)->gateway_name}}</h4>
                    <form action="{{ url('pay') }}" class="" id="" role="form" method="POST">         
                        @csrf
                    <input type="hidden" name="amount" value="{{$plan_info->plan_price}}">

                    <button type="submit" class="primary_item_btn border-0">{{trans('words.pay_now')}}</button>
                    </form>
                 </div>
            </div>
        @endif             
             
        </div>
    @else

        <div class="row">
            @if(getPaymentGatewayInfo(1)->status)
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="select-payment-method">
                        <h1>{{getPaymentGatewayInfo(1)->gateway_name}}</h1>
                        <h4>{{trans('words.pay_with')}} {{getPaymentGatewayInfo(1)->gateway_name}}</h4>
                        
                        <a href="Javascript:void(0);" data-toggle="modal" data-target="#phone_update" class="primary_item_btn border-0" title="{{trans('words.pay_now')}}">{{trans('words.pay_now')}}</a>

                    </div>
                </div>
            @endif

            @if(getPaymentGatewayInfo(2)->status)    
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="select-payment-method">
                        <h1>{{getPaymentGatewayInfo(2)->gateway_name}}</h1>
                        <h4>{{trans('words.pay_with')}} {{getPaymentGatewayInfo(2)->gateway_name}}</h4>
                        <a href="Javascript:void(0);" data-toggle="modal" data-target="#phone_update" class="primary_item_btn border-0" title="{{trans('words.pay_now')}}">{{trans('words.pay_now')}}</a>
                    </div>
                </div>
            @endif
            
            @if(getPaymentGatewayInfo(3)->status)    
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="select-payment-method">
                        <h1>{{getPaymentGatewayInfo(3)->gateway_name}}</h1>
                        <h4>{{trans('words.pay_with')}} {{getPaymentGatewayInfo(3)->gateway_name}}</h4>
                        <a href="Javascript:void(0);" data-toggle="modal" data-target="#phone_update" class="primary_item_btn border-0" title="{{trans('words.pay_now')}}">{{trans('words.pay_now')}}</a>
                    </div>
                </div>
            @endif

            @if(getPaymentGatewayInfo(4)->status)    
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="select-payment-method">
                        <h1>{{getPaymentGatewayInfo(4)->gateway_name}}</h1>
                        <h4>{{trans('words.pay_with')}} {{getPaymentGatewayInfo(4)->gateway_name}}</h4>
                        <a href="Javascript:void(0);" data-toggle="modal" data-target="#phone_update" class="primary_item_btn border-0" title="{{trans('words.pay_now')}}">{{trans('words.pay_now')}}</a>
                    </div>
                </div>
            @endif             
             
        </div>
    
    @endif
    </div>
</section>

<div class="modal fade modal-container" id="phone_update" tabindex="-1" role="dialog" aria-labelledby="phone_update" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header align-items-center">
                <h5 class="modal-title" id="shareModalTitle">{{trans('words.update_phone')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="fal fa-times"></span>
                </button>
            </div>
            <div class="modal-body">
                 <div class="edit-profile-form">  
              @if (count($errors) > 0)
                <div class="alert alert-danger">
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if(Session::has('flash_message'))
                      <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                          {{ Session::get('flash_message') }}
                      </div>
                @endif

                        <form action="{{ url('phone_update') }}" class="row" name="phone_update" id="phone_update" role="form" method="POST" enctype="multipart/form-data">  
                                @csrf
                            <input name="" value="" type="hidden">
              
              <div class="col-lg-12 col-md-12">
                       <label class="label-text">{{trans('words.phone')}}</label>
                       <div class="form-group">
                                                     <input class="form-control form--control" type="number" name="phone" value="{{ old('phone', Auth::user()->mobile) }}" placeholder="" required>
                       </div>
              </div>               
             
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group d-flex align-items-end flex-column mt-30">
                  <button type="submit" class="primary_item_btn mt-2 border-0">{{trans('words.update')}}</button>
                </div>   
              </div>           
              
                        </form>

             
                </div>  
            </div>
        </div>
    </div>
</div>

 

<!-- ================================
     End Payment Method Area
================================= -->

<script src="{{ URL::asset('assets/js/jquery-3.4.1.min.js') }}"></script>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>


<script type="text/javascript">
  $("#razorpayId").click(function(e) {
    e.preventDefault();

    $('.vfx-item-ptb').addClass('payment_loading');
    $("#loading").show();

    $.ajax({
        type: "POST",
        url: "{{ URL::to('razorpay_get_order_id') }}",
        data: { 
            id: $(this).val(), // < note use of 'this' here
            _token: "{{ csrf_token() }}" 
        },
        success: function(result) {
            //$('#paymentWidget').attr("data-order_id",'111');
            
            //alert(result);
            $('.vfx-item-ptb').removeClass('payment_loading');
            $("#loading").hide();

            var options = {
                      "key": "{{getcong('razorpay_key')}}", // Enter the Key ID generated from the Dashboard
                      "amount": "{{$plan_info->plan_price}}", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
                      "currency": "INR",
                      "name": "{{getcong('site_name')}}",
                      "description": "{{$plan_info->plan_name}}",
                      "image": "{{ URL::asset('/'.getcong('site_logo')) }}",
                      "order_id": result, //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
                      "callback_url": "{{ URL::to('razorpay-success') }}",
                      "prefill": {
                          "name": "{{Auth::user()->name}}",
                          "email": "{{Auth::user()->email}}",
                          "contact": "{{Auth::user()->phone}}"
                      },                       
                      "theme": {
                          "color": "#3399cc"
                      }
                  };

            var rzp1 = new Razorpay(options);

            rzp1.open();  

            //alert(result);
        },
        error: function(result) {
            alert('error');
        }
    });
});
</script>

<script type="text/javascript">
 
 $('#open_phone_update').on('click', function(e) {    
    $('#phone_update').modal('show');
 }); 

</script>

 
@endsection