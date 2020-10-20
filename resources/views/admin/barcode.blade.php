<!DOCTYPE html>
<html>
<head>
	<title>Barcode Generator</title>
	<style type="text/css">
		.m-0{
			margin: 0;
		}
		.fs-12{
			font-size: 12px;
		}
		.text-center{
			text-align: center;
		}
	</style>
</head>
<body>
     <table style="width: 100%; height: auto;">
     	<?php for($i=0;$i<ceil($qty/4);$i++){ ?>
     	<tr>
     		<?php for($j=0;$j<4;$j++){ ?>
     		<td style="text-align: center;">
     			<p class="m-0 fs-12">{{$product_name}}</p>
		        <img src="{{asset('public')}}/{{DNS1D::getBarcodePNGPath('$'.$inventory_id.'$', 'C39',1,33)}}" alt="barcode" />
		         <p class="m-0 fs-12">MRP: {{$price}}</p>
     		</td>
     		<?php } ?>
     	</tr>
     <?php } ?>

     </table>
</body>
</html>