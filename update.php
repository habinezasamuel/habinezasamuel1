<?php
// Connect to database
$conn = mysqli_connect('localhost','root','','itb');
if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}

// Get user data if ID is provided
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $result = mysqli_query($conn,"SELECT * FROM tumba WHERE id='$id'");
    $row = mysqli_fetch_assoc($result);
}

// Update user
if(isset($_POST['update'])){
    $id   = $_POST['id']; // get id from hidden input
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $em   = $_POST['email'];

    $sql = "UPDATE tumba SET username='$user', password='$pass', email='$em' WHERE id='$id'";

    if(mysqli_query($conn,$sql)){
        header('Location: display.php');
        exit();
    } else {
        echo "Update failed: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update User</title>
</head>
<body>

<h2>Update User</h2>
<form action="" method="POST">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

    <label>Username</label><br>
    <input type="text" name="username" value="<?php echo $row['username']; ?>" required><br>

    <label>Password</label><br>
    <input type="password" name="password" value="<?php echo $row['password']; ?>" required><br>

    <label>Email</label><br>
    <input type="email" name="email" value="<?php echo $row['email']; ?>" required><br><br>

    <input type="submit" name="update" value="Update">
</form>

</body>
</html>
