<!DOCTYPE html>
<html>

<head>
	<title>Receipt</title>
	<style type="text/css">
		body {
			font-family: arial;
			font-size: 9px;
			min-height: 4.8in;
			width: 3.1in;
			margin: 0px !important;
			padding: 0px !important;
		}

		body,
		html {
			margin: 0;
			padding: 0;
		}

		#invoice-POS {
			/* box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5); */
			padding: 2mm;
			margin: 0px;
			width: 3in;
			background: #fff;
			border: 1px solid black;
		}

		#top,
		#mid,
		#bot {
			border-bottom: 1px solid #eee;
		}

		#top {
			min-height: 100px;
		}

		#mid {
			min-height: 80px;
		}

		#bot {
			min-height: 50px;
		}

		#legalcopy {
			margin-top: 5mm;
			text-align: center;
		}

		#table table {
			width: 100%;
			border-collapse: collapse;
		}

		td {
			/* padding: 0 0 3px 0; */
			border: 1px solid #eee;
		}

		.text-center{
			text-align: center;
		}

		@media print {

			body,
			html {
				margin: -10px;
				padding: 0;
			}

			body {
				margin: 0;
				padding: 0px;
				color: #000;
				background-color: #fff;
				/* height: auto; */
				width: 3in;
				min-height: 4.8in;

				font-family: arial;
				font-size: 9px;
			}

			@page {
				margin: 0;
			}


		}
	</style>
</head>

<body onload="window.print()">

	<div id="invoice-POS">

		<center id="top">
			<div class="logo"></div>
			<div class="info">
				<p style="font-weight: bold;font-size: 15px;">Collectorate Super Shop</p>
				<p style="margin-top: -10px;">DC Road, Narsingdi Sadar, Narsingdi</p>
				<p style="margin-top: -10px;">01670-480954,01814-857361</p>
				<p>
					<hr>
				</p>
				<p style="margin-top: 2px;"><span style="font-weight: bold;">User</span>: {{Auth::user()->username}}
					<span style="font-weight: bold;">Customer:</span>
					{{ !empty($order->client_name)?$order->client_name:'Walking Customer' }} </p>
				<p style="margin-top: 2px;"><span style="font-weight: bold;">Invoice No:</span> {{ $order->order_id }}
					<span style="font-weight: bold;">Date:</span>
					{{ date('d-m-Y H:i: s',strtotime(!empty($order->created_at)?$order->created_at:now())) }} </p>
				<p>
					<hr>
				</p>
				<p style="font-weight: bold;">Thank you</p>
				<p>
					<hr>
				</p>
			</div>
			<!--End Info-->
		</center>
		<!--End InvoiceTop-->
		<div id="bot">

			<div id="table">
				<table>
					<thead>
						<th>Sl</th>
						<th width="150px">Product</th>
						<th>MRP</th>
						<th>Qty</th>
						<th>Total</th>
					</thead>
					<tbody>
						@php
						$count = 0;
						$total = 0;
						@endphp
						@foreach ($orderedProducts as $product)
						@php
						$count++;
						$total = $total+($product->selling_price * $product->debit);
						@endphp
						<tr>
							<td>{{ $count }}</td>
							<td width="150px">{{ $product->Product->name}}</td>
							<td class="text-center"> {{ $product->selling_price }}</td>
							<td class="text-center">{{ $product->debit }}</td>
							<td class="text-center">{{ $product->selling_price * $product->debit }}</td>
						</tr>
						@endforeach
					</tbody>
					<tfoot>
						<tr>
							<td colspan="4" style="text-align: right;">SubTotal</td>
							<td class="text-center">{{ $total }}</td>
						</tr>
						<tr>
							<td colspan="4" style="text-align: right;">Discount</td>
							<td class="text-center">{{$order->discount}}</td>
						</tr>
						<tr>
							<th colspan="4" style="text-align: right;">Net Amount</th>
							<th class="text-center">{{ $total - $order->discount }}</th>
						</tr>
						<tr>
							<td colspan="4" style="text-align: right;">Cash Paid</td>
							<td class="text-center">{{ $total - $order->discount }}</td>
						</tr>
					</tfoot>

				</table>
			</div>
			<!--End Table-->

			<div id="legalcopy">
				<center>
					<p class="legal"><strong>Developed By</strong> Innovation IT</p>
				</center>
			</div>

		</div>
		<!--End InvoiceBot-->
	</div>
	<!--End Invoice-->

</body>

</html>