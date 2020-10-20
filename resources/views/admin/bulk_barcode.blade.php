<!DOCTYPE html>
<html>
<head>
	<title>Bulk Barcode Generator</title>
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
     	<?php 
     	for($i=0;$i<count($data);$i+=4){ 
     	?>
     	<tr>

     		<?php
     			for ($j=$i; $j < $i+4; $j++) { 
                         if($j == count($data)){break;}
                    ?>
     		
     		<td style="text-align: center;">
     			<p class="m-0 fs-12">{{$data[$j]['name']}}</p>
		        <img src="{{asset('public')}}/{{DNS1D::getBarcodePNGPath('$'.$data[$j]['inventory_id'].'$', 'C39',1,33)}}" alt="barcode" />
		         <p class="m-0 fs-12">MRP: {{$data[$j]['price']}}</p>
     		</td>

     		<?php } ?>

     	</tr>
     	<?php  } ?>

     </table>
</body>
</html>