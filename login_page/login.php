<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #3a7bd5, #3a6073);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0px 0px 20px rgba(0,0,0,0.2);
            text-align: center;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 25px;
            box-sizing: border-box;
        }
        button {
            background-color: #4e73df;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        button:hover {
            background-color: #3b5dc3;
        }
        .link {
            margin-top: 15px;
        }
        .link a {
            text-decoration: none;
            color: #4e73df;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Selamat Datang!</h2>
        <p>Silakan login untuk melanjutkan</p>
        <?php
        if (isset($_GET['pesan']) && $_GET['pesan'] == "logout") {
            echo "<p style='color: green;'>Anda berhasil logout.</p>";
        }
        ?>

        <?php
        if (isset($_GET['pesan'])) {
            if ($_GET['pesan'] == "gagal") {
                echo "<p style='color: red;'>Username atau Password salah!</p>";
            } elseif ($_GET['pesan'] == "signup_berhasil") {
                echo "<p style='color: green;'>Registrasi berhasil! Silakan login.</p>";
            }
        }  
        ?>
        
        <form method="POST" action="cek_login.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="link">
            <p><a href="../signup_page/signup.php">Buat Akun!</a></p>
        </div>
    </div>
</body>
</html>
