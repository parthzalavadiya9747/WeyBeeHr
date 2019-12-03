<!DOCTYPE html>
<html>
<head>
	<title>Erorr</title>
</head>
<body>
	<h1>Error occur in Luzon</h1>
	
		<table width="60%" border="1">
			<thead>
				<tr>
					<th>Module</th>
					<th>Description</th>
					<th>Level</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td align="center">{{ $module }}</td>
					<td align="center">{{ $errordesc }}</td>
					<td align="center"><span style="color: red;">{{ $level }}</span></td>
				</tr>
			</tbody>
		</table>
	

</body>
</html>