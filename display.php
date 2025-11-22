<?php
session_start();

if(!isset($_SESSION['username'])){
    header("Location:login.php");
    exit();
}

$conn = mysqli_connect('localhost','root','','itb');
if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Display</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        table th, table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            font-size: 16px;
        }

        table th {
            background: #007bff;
            color: white;
            font-weight: bold;
        }

        tr:hover {
            background: #eaf3ff;
            transition: 0.3s;
        }

        a {
            text-decoration: none;
            color: white;
            background: #007bff;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 14px;
        }

        a:hover {
            background: #0056b3;
        }

        .logout {
            background: red;
            display: inline-block;
            margin-top: 15px;
        }

        .container {
            text-align: center;
        }
    </style>
</head>
<body>

<h2>Welcome! User: <?php echo $_SESSION['username']; ?></h2>

<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Password</th>
        <th>Email</th>
        <th colspan="2">Actions</th>
    </tr>

    <?php
    $sql = mysqli_query($conn,"SELECT * FROM tumba");
    while($row = mysqli_fetch_array($sql)){
    ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['username']; ?></td>
        <td><?php echo $row['password']; ?></td>
        <td><?php echo $row['email']; ?></td>
        <td><a href="delete.php?id=<?php echo $row['id']; ?>">Delete</a></td>
        <td><a href="update.php?id=<?php echo $row['id']; ?>">Update</a></td>
    </tr>
    <?php } ?>
</table>

<div class="container">
    <a class="logout" href="logout.php">Logout</a>
</div>

</body>
</html>
