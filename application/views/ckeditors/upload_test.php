<!DOCTYPE html>
<html>
	<head>
		<title>테스트</title>
	</head>
	<body>
		<?php echo validation_errors() ?>		
		<form action="/ckeditors/add" method="post" enctype="multipart/form-data">
			<input type="file" name="upload" />
			<input type="submit" value="제출" />
		</form>
	</body>
</html>