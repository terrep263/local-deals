<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>
<style>
body {
	font-size: 14px;
	font-family: Arial, Helvetica, sans-serif;
}
</style>
<body>
<table border="0" cellpadding="0" cellspacing="0" width="60%" style="margin:0 auto;border:1px solid rgba(0, 0, 0, 0.2);padding:20px;">
 <tr style="border:0">
  <td style="text-align:center">   
   <a href="{{ url('/') }}" target="_blank"><img src="{{ URL::asset('upload/'.getcong('site_logo')) }}" alt="" style="width: 150px;height: 150px;"></a>
  </td>
 </tr>
  
 <tr>
  <td style="padding: 20px 0 30px 0;line-height:22px;">
    <h2>Deal {{ $deal->auto_approved ? 'Created Successfully' : 'Submitted for Approval' }}</h2>
    <p>Hi <?php echo $user->first_name ?>,</p>
    <p>Your deal "<strong><?php echo $deal->title ?></strong>" has been {{ $deal->auto_approved ? 'created and is now active!' : 'submitted for approval.' }}</p>
    @if(!$deal->auto_approved)
    <p>Our team will review your deal and notify you once it's approved. This usually takes 1-2 business days.</p>
    @endif
    <p><strong>Deal Details:</strong></p>
    <ul>
        <li>Title: <?php echo $deal->title ?></li>
        <li>Price: $<?php echo number_format($deal->deal_price, 2) ?> (Regular: $<?php echo number_format($deal->regular_price, 2) ?>)</li>
        <li>Inventory: <?php echo $deal->inventory_total ?> spots</li>
        <li>Expires: <?php echo $deal->expires_at->format('F d, Y') ?></li>
    </ul>
    <p><a href="{{ url('/vendor/deals') }}" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 10px;">View My Deals</a></p>
  </td>
 </tr>
  
 <tr>
  <td style="line-height:20px">
   Thanks!
   <br />- {{getcong('site_name')}}
  </td>
 </tr>
</table>
 <p style="font-size: 13px;text-align: right;margin-top: 10px;position: relative;right: 40.5%;">&copy; {{getcong('site_name')}}
  </td></p>
</body>
</html>


