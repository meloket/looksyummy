
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>CIT2ADM</title>
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700,700i" rel="stylesheet">
<link id="switcher" href="{{ asset("/bower_components/Apex/css/theme-color/orange-theme.css") }}" rel="stylesheet">


<!-- Main Style -->
<link href="{{ asset("/bower_components/Apex/style.css") }}" rel="stylesheet">


<style>
body {
	background: #f7f7f9;
	margin: 0;
	padding: 0;
	font-family: arial, sans-serif;
	color: #000000;
	font-size: 14px;
	font-weight: 400;
	line-height: 22px;
}
.footer_social tr td {
	padding-right: 5px !important;
}
.footer_social tr td:last-child {
	padding-right: 0 !important;
}
.container {
	max-width:640px; 
	margin:0 auto; 
	background:#FFF;
}
.container h1 {
	font-size: 20px;
}
.container table tr:nth-child(2) ul li {
	padding-bottom:10px;
}
</style>


</head>

<body>
<div class="container">
  <table width="100%" style="border:0px;" cellspacing="5" cellpadding="15">
  <tr>
    <td>
		<table width="100%" style="border:0px; background-color:#FAA152" cellspacing="0" cellpadding="0">
		  <tr>
			<td style="text-align:center;padding-top:10px;padding-bottom:10px;"><a href="{{ url('/') }}"><img src="{{ asset('/bower_components/Apex/images/logo.png') }}" height="80"></a></td>
			
		  </tr>
		</table>
	</td>
  </tr>


<tr><td>{{$slot}}</td></tr>

<tr>
    <td style="background:#f7f7f9; text-align: center; padding:0 15px; padding-bottom: 25px;">
    <table width="100%" style="border:0px;" cellspacing="0" cellpadding="0">

		<tr>
			<td height="10"></td>
		</tr>
		
		
		<tr>
        <td height="10" colspan="2" style="text-align: center; width: 98%;">This is an automatically generated email. Replies sent to this email goes to an unmonitored mailbox. For any quesion please contact <a href="mailto:{{ env('CONTACT_EMAIL') }}">{{ env('CONTACT_EMAIL') }}</a>. | <a href="{{ url('/') }}">Visit our website</a></td>
      </tr>
      <tr>
        <td height="10"></td>
      </tr>
      <tr>
        <td colspan="2"><p  style="text-align: center; width: 98%;"> Go Green! Please think about the environment before printing this email. </p></td>
      </tr>
      <tr>
		
		<tr>
			<td height="10"></td>
		</tr>
		
		
		

	</table>
	</td>
</tr>

</table>
</body>
</html>
