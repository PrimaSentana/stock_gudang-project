<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
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
        <h2>Buat Akun</h2>
        <form method="POST" action="proses_signup.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Daftar</button>
        </form>
        <div class="link">
            <p><a href="../login_page/login.php">Sudah punya akun? Masuk!</a></p>
        </div>
    </div>
</body>
</html>
