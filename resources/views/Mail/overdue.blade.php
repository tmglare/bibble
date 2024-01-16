<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
	<div>
		Dear {{ $loan->borrower->style }} {{ $loan->borrower->surname }}<br>
		Please note that your loan item {{ $loan->inventoryItem->book->title }} is now overdue.
		Please return this item as soon as possible. Fines will be applied to any items more
		then seven days overdue. Thankyou.
		<p>
		Our Library
	</div>
</body>
</html>
