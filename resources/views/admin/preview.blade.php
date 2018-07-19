<!DOCTYPE HTML>
<html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title></title>
<style>
 img{width: 100%;}
</style>

<body>
{!!  base64_decode(\Illuminate\Support\Facades\Request::input('htmlContent')) !!}
</body>
</html>