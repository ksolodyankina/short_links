<html>
<head>
	<title>Сокращение ссылок</title>
	<meta charset="utf-8">
	<?php
	function make_short_link($long_link) 
	{
		$digits='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$short_link='';
		$link_hash= hexdec(hash("crc32", $long_link, false));
		do {
			$dig=$link_hash%62;
			$short_link=$digits[$dig].$short_link;
			$link_hash=floor($link_hash/62);
		} while($link_hash!=0);
		return $short_link;
	}

	function correct_short_link($short_link, $long_link)
	{
		$connect = mysqli_connect("localhost", "id2981647_admin", "mlt32ktbR1", "id2981647_test_task_db");
		$query = "SELECT * FROM Links WHERE ShortLink = '$short_link'";
		$result = mysqli_query($connect, $query);
		if (mysqli_num_rows($result) == 0) {
			$query = "INSERT INTO Links (LongLink, ShortLink) VALuES ('$long_link','$short_link')";
			$result = mysqli_real_query ($connect, $query );
			return $short_link;
		}
		else {
			$row = mysqli_fetch_row($result);
			if ($row[1] == $long_link) {
				return $short_link;
			}
			elseif ($_POST['user_value']) {
				return false;
			}
			else {
				correct_short_link(make_short_link($long_link.'1'),$long_link);
			}
		}
	}
	?>
</head>

<body>
	<div style="margin: 20px 30%; background: #e9edf5; padding: 10px 25px; height: 250px">
		<form action="index.php" method="post">
			<p>Вставьте длинную ссылку:</p>
			<input type="text" size=60 name="orig_link" value="<?php if ($_POST) {echo $_POST['orig_link'];} ?>"/>
			<p>Укажите желаемое имя для короткой ссылки (Необязательно):</p>
			<input type="text" size=60 name="user_value" value="<?php if ($_POST) {echo $_POST['user_value'];} ?>"/>
			<p style="margin: 20px 35%"><input type="submit" value="Сгененрировать"/></p>
		</form>

	<?php
	if ($_POST) {
		$long_link = $_POST['orig_link'];
		if ($_POST['user_value']) {
			$short_link = $_POST['user_value'];
		} else {
			$short_link = make_short_link($long_link);
		}
		$short_link = (correct_short_link($short_link, $long_link));
		if ($short_link) {
			$short_link = 'https://katokey000.000webhostapp.com/'.$short_link;
			echo "<a href='$short_link'>$short_link</a>";
		} else {
			echo "Ссылка $short_link уже существует. Введите другое название.";
		}
	}
	?>

	</div>
</body>
</html>