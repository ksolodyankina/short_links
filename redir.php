<html>
<?php
$link = $_GET['link'];
$connect = mysqli_connect("localhost", "id2981647_admin", "mlt32ktbR1", "id2981647_test_task_db");
$query = "SELECT * FROM Links WHERE ShortLink = '$link'";
$result = mysqli_query($connect, $query);
if (mysqli_num_rows($result) > 0){
	$row = mysqli_fetch_row($result);
	$long_link = $row[1];
	echo '<meta http-equiv="Refresh" content="0; url='.htmlspecialchars($long_link).'">';
}
?>
</html>