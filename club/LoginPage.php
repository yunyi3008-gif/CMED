<?php
session_start();
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentID   = trim($_POST["id"]);
    $passwordVal = $_POST["password"];

    if (empty($studentID) || empty($passwordVal)) {
        $_SESSION["login_error"] = "Please fill in all fields.";
    } elseif (!preg_match('/^\d{8}$/', $studentID)) {
        $_SESSION["login_error"] = "Invalid Student ID format.";
    } else {
        $x = $conn->prepare("SELECT Student_Password FROM student WHERE Student_Id = ?");
        $x->bind_param("s", $studentID);
        $x->execute();
        $x->store_result();

        if ($x->num_rows === 0) {
            $_SESSION["login_error"] = "Student ID or password is incorrect.";
        } else {
            $x->bind_result($hashedPassword);
            $x->fetch();

            if (password_verify($passwordVal, $hashedPassword)) {
                $_SESSION["student_id"] = $studentID;
                $x->close();
                $conn->close();
                header("Location: CampusSystemHomePage.php");
                exit();
            } else {
                $_SESSION["login_error"] = "Student ID or password is incorrect.";
            }
        }
        $x->close();
    }
    $conn->close();


    header("Location: LoginPage.php");
    exit();
}


$loginError = "";
if (isset($_SESSION["login_error"])) {
    $loginError = $_SESSION["login_error"];
    unset($_SESSION["login_error"]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In — Memo Campus</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            background-color: #f7f2fe;
            min-height: 100vh;
        }
        .header {
        font-style: oblique;
        background-color: #c9b4f5;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: row; 
        gap: 15px;     
        padding: 25px 0;       
        }

        .header h1 {
            font-size: 26px;
            color: #2d1b5e;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .page-content {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 80px);
            padding: 40px 20px;
        }
        .login-box {
            background-color: white;
            border-radius: 20px;
            padding: 48px 40px;
            width: 100%;
            max-width: 420px;
        }
        .login-box h2 {
            font-size: 24px;
            color: #2d1b5e;
            margin-bottom: 6px;
        }
        .login-box .subtitle {
            font-size: 14px;
            color: #999;
            margin-bottom: 30px;
        }
        .input-wrapper { margin-bottom: 16px; }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 13px 18px;
            border: 2px solid #c9b4f5;
            border-radius: 30px;
            font-size: 14px;
            color: #555;
            background-color: #f7f2fe;
            outline: none;
        }
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #7c4dff;
            background-color: white;
        }
        input.input-error {
            border-color: #e53935 !important;
            background-color: #fff5f5;
        }
        .error-msg {
            display: none;
            color: #e53935;
            font-size: 12px;
            margin-top: 6px;
            margin-left: 18px;
        }
        .error-msg.show { display: block; }
        .login-error-banner {
            background-color: #fff0f0;
            border: 1px solid #e53935;
            color: #e53935;
            border-radius: 10px;
            padding: 10px 16px;
            font-size: 13px;
            margin-bottom: 20px;
        }
        .success-banner {
            background-color: #f0fff4;
            border: 1px solid #43a047;
            color: #2e7d32;
            border-radius: 10px;
            padding: 10px 16px;
            font-size: 13px;
            margin-bottom: 20px;
        }
        button {
            width: 100%;
            padding: 14px;
            background-color: #7c4dff;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 4px;
            margin-bottom: 20px;
        }
        button:hover { background-color: #5e35b1; }
        a {
            display: block;
            color: #7c4dff;
            font-size: 13px;
            text-decoration: none;
            margin-bottom: 10px;
        }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

   <div class="header">
    <img src="MemoCampusLogo.png" alt="Memo Campus Logo" style="width:80px;">
    <h1>MEMO CAMPUS</h1>
</div>

    <div class="page-content">
        <div class="login-box">

            <h2>Welcome Back!</h2>
            <p class="subtitle">Please log in to your account</p>

            <?php if (!empty($loginError)): ?>
                <div class="login-error-banner">⚠️ <?= htmlspecialchars($loginError) ?></div>
            <?php endif; ?>

            <?php if (isset($_GET["reset"]) && $_GET["reset"] === "success"): ?>
                <div class="success-banner">✅ Password reset successfully! Please log in with your new password.</div>
            <?php endif; ?>

            <?php if (isset($_GET["signup"]) && $_GET["signup"] === "success"): ?>
                <div class="success-banner">✅ Account created! Please log in.</div>
            <?php endif; ?>

            <form action="LoginPage.php" method="post" onsubmit="return login()">

                <div class="input-wrapper">
                    <input type="text" id="id" name="id" placeholder="Student ID" maxlength="8"
                           value="<?= htmlspecialchars($_POST['id'] ?? '') ?>">
                    <span class="error-msg" id="id-error"></span>
                </div>

                <div class="input-wrapper">
                    <input type="password" id="password" name="password" placeholder="Password">
                    <span class="error-msg" id="password-error"></span>
                </div>

                <button type="submit">Log In</button>

                <a href="ResetPassword.php">Forgot Password?</a>
                <a href="RegistrationForm.php">Don't have an account? Sign Up</a>

            </form>

        </div>
    </div>

    <script>
        function login() {
            var idValue       = document.getElementById("id").value;
            var passwordValue = document.getElementById("password").value;
            var idInput       = document.getElementById("id");
            var passwordInput = document.getElementById("password");
            var idError       = document.getElementById("id-error");
            var passwordError = document.getElementById("password-error");

            idInput.classList.remove("input-error");
            passwordInput.classList.remove("input-error");
            idError.classList.remove("show");
            passwordError.classList.remove("show");

            var isValid = true;

            if (idValue === "") {
                idError.textContent = "⚠️ Please enter your Student ID.";
                idError.classList.add("show");
                idInput.classList.add("input-error");
                isValid = false;
           

            if (passwordValue === "") {
                passwordError.textContent = "⚠️ Please enter your password.";
                passwordError.classList.add("show");
                passwordInput.classList.add("input-error");
                isValid = false;
           
            
            }

            return isValid;
        }
            }
    </script>

</body>
</html>