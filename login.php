

<?php
session_start();

$conn=mysqli_connect('localhost','root','','itb');
if(isset($_POST['login'])){
    $user=$_POST['username'];
    $pass=$_POST['password'];
    $em=$_POST['email'];
    $sql=mysqli_query($conn,"SELECT * FROM tumba WHERE username='$user' AND password='$pass' AND email='$em'");
    $result=mysqli_num_rows($sql);
    if($result>0){
        //echo "login is done";
        $_SESSION['username']=$user;
        setcookie("username",$user ,time()+3600,'/');
        header('location:display.php');
        exit();
    }else{
        echo "login is failed";
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
  <input type="submit" name="login" value="login">
</body>
</html>