<?php
session_start();

if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

$error_message = '';
$username = 'admin'; 
$password = '1234'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
    $input_user = $_POST['username'];
    $input_pass = $_POST['password'];


    if ($input_user === $username && $input_pass === $password) {
     
        $_SESSION['username'] = $input_user;
 
        $_SESSION['login_success'] = true; 
        
    
        header("Location: dashboard.php");
        exit();
    } else {
  
        $error_message = 'Username atau password salah!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - POLGAN MART</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    
    <div class="login-container">
        <div class="login-card">
            <h1>POLGAN MART</h1>
            


            <form action="index.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type
="text" id="username" name="username" value="" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-login">Login</button>
                <button type="reset" class="btn btn-cancel">Batal</button>
            </form>
            
        </div>
    </div>

</body>
</html>