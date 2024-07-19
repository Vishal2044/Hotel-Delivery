<?php

session_start();
include('confi.php');
include('function.php');

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $join_date = mysqli_real_escape_string($conn, $_POST['join_date']);
    $hotel_name = mysqli_real_escape_string($conn, $_POST['hotel_name']);
    $logo_img = mysqli_real_escape_string($conn, $_POST['logo_img']);
    $open_time = mysqli_real_escape_string($conn, $_POST['open_time']);
    $close_time = mysqli_real_escape_string($conn, $_POST['close_time']);

    // Attempt insert query execution
    $sql = "INSERT INTO admin_ragister (username, contac_number, email, addres, password, join_date, hotel_name, logo_img, open_time, close_time) 
            VALUES ('$username', '$contact_number', '$email', '$address', '$password', '$join_date', '$hotel_name', '$logo_img', '$open_time', '$close_time')";
    
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
</head>
<body>
    <h2>Admin Registration Form</h2>
    <form action="" method="POST">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="contact_number">Contact Number:</label><br>
        <input type="text" id="contact_number" name="contact_number" required><br><br>
        
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="address">Address:</label><br>
        <input type="text" id="address" name="address" required><br><br>
        
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        
        <label for="join_date">Join Date:</label><br>
        <input type="datetime-local" id="join_date" name="join_date" required><br><br>
        
        <label for="hotel_name">Hotel Name:</label><br>
        <input type="text" id="hotel_name" name="hotel_name" required><br><br>
        
        <label for="logo_img">Logo Image:</label><br>
        <input type="file" id="logo_img" name="logo_img" required><br><br>
        
        <label for="open_time">Open Time:</label><br>
        <input type="time" id="open_time" name="open_time" required><br><br>
        
        <label for="close_time">Close Time:</label><br>
        <input type="time" id="close_time" name="close_time" required><br><br>
        
        <input type="submit" value="Register">
    </form>
    <a href="login.php">Login</a>
</body>
</html>