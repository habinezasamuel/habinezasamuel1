

<?php

$conn=mysqli_connect('localhost','root','','itb');
if(isset($_POST['create'])){
	$user=$_POST['username'];
    $pass=$_POST['password'];
    $em=$_POST['email'];
    $sql="INSERT INTO `tumba` (`username`, `password`, `email`) VALUES ('$user', '$pass', '$em')";
    $result=mysqli_query($conn,$sql);
    if($result){
    	//echo "create is successfully";
    	header('location:login.php');
    }else{
    	echo "create is failed";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>createform</title>
</head>
<body>
 <h2>create_user</h2><br>
 <form action=" " method="POST">
 <label>username</label><br>
 <input type="text" name="username" placeholder="enter username" required><br>
  <label>password</label><br>
  <input type="text" name="password" placeholder="enter password" required><br>
  <label>email</label><br>
  <input type="text" name="email" placeholder="enter email" required><br><br>
  <input type="submit" name="create" value="create">
</body>
</html>