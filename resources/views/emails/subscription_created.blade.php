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
    <h2>Welcome to {{ getcong('site_name') }}!</h2>
    <p>Hi <?php echo $user_name ?>,</p>
    <p>Your subscription to the <strong><?php echo $package_tier ?> Plan</strong> has been successfully activated!</p>
    <p>You now have access to all features included in your plan. You can start creating deals and managing your business right away.</p>
    @if($monthly_price > 0)
    <p><strong>Monthly Price:</strong> $<?php echo number_format($monthly_price, 2) ?></p>
    @endif
    <p><a href="{{ url('/dashboard') }}" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 10px;">Go to Dashboard</a></p>
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
