<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
	@page { size: 210mm 297mm; margin: 14mm 10mm; }
  body { font-family: DejaVu Sans, sans-serif; }
</style>
</head>
<body>
	<table style="width:100%; margin-left:0px">
		@foreach ($inventoryItems as $inventoryItem)
			@if ( $loop->index > 0 and $loop->index % 21 == 0)
				<tr style="page-break-after:always">
			@elseif ( $loop->index % 3 == 0)
				<tr>
			@endif
					<td style="height:37.5mm; max-width:60mm;padding: 0px 5px">
						<div style="margin-top: 18px; margin-bottom:02px; outline:1px solid #000000; padding: 10px 20px">
							{{-- print_r($inventoryItem,true) --}}
							<img src="data:image/png;base64,{{ $inventoryItem['barcodeImage'] }}" />
							<div style="font-size:xx-small">
								{{ $inventoryItem["barcode"] }}
							</div>
							<div style="height:4em; width:45mm; font-size:xx-small">
								{{ $inventoryItem["title"] }}
							</div>
							<div style="font-size:xx-small">
								Copy no: {{ $inventoryItem["copyNo"] }}
							</div>
						</div>
					</td>
			@if ( $loop->last )
				</tr>
			</table>
			@elseif ( $loop->index % 3 == 2)
				</tr>
			@endif
		@endforeach
	<table>
</body>
</html>
