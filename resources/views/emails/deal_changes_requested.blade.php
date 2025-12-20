<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Changes Requested for Your Deal</title>
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
    <h2>Changes Requested for Your Deal</h2>
    <p>Hi {{ $vendor_name }},</p>
    <p>An admin has reviewed your deal "<strong>{{ $deal->title }}</strong>" and has requested some changes before it can be approved.</p>
    
    <div style="background: #f5f5f5; padding: 15px; margin: 20px 0; border-left: 4px solid #007bff;">
        <strong>Requested Changes:</strong>
        <p style="margin-top: 10px;">{{ $feedback }}</p>
    </div>
    
    <p>Please review and update your deal to address these concerns. Once you've made the changes, the deal will be reviewed again.</p>
    
    <p style="text-align:center; margin:20px 0;">
        <a href="{{ route('vendor.deals.edit', $deal->id) }}" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Edit Deal</a>
    </p>
  </td>
 </tr>
  
 <tr>
  <td style="line-height:20px">
   Thanks!<br>
   - {{getcong('site_name')}}
  </td>
 </tr>
</table>
<p style="font-size: 13px;text-align: right;margin-top: 10px;position: relative;right: 40.5%;">&copy; {{getcong('site_name')}}</p>
</body>
</html>


