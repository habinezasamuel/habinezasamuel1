<?php
$conn=mysqli_connect('localhost','root','','itb');
$delete=$_GET['id'];
$sql="DELETE FROM tumba WHERE id='$delete'";
if(mysqli_query($conn,$sql)){
	header('location:display.php');
}else{
	echo "delete is failed";
}
?>