<?php
// ══════════════════════════════════════════════════════
// DATABASE CONNECTION
// ══════════════════════════════════════════════════════
$host     = "localhost";
$dbname   = "campus_memory";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$serverError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $emailVal  = trim($_POST["email"]);
    $newPw     = $_POST["new-password"];
    $confirmPw = $_POST["confirm-password"];

    // server side validation
    if (empty($emailVal) || empty($newPw) || empty($confirmPw)) {
        $serverError = "Please fill in all fields.";

    } elseif (!filter_var($emailVal, FILTER_VALIDATE_EMAIL)) {
        $serverError = "Invalid email address.";

    } elseif (strlen($newPw) < 8) {
        $serverError = "Password must be at least 8 characters.";

    } elseif (!preg_match('/[A-Z]/', $newPw)) {
        $serverError = "Password must contain at least 1 uppercase letter.";

    } elseif (!preg_match('/[0-9]/', $newPw)) {
        $serverError = "Password must contain at least 1 number.";

    } elseif (!preg_match('/[^A-Za-z0-9]/', $newPw)) {
        $serverError = "Password must contain at least 1 symbol.";

    } elseif ($newPw !== $confirmPw) {
        $serverError = "Passwords do not match.";

    } else {
        // check if email exists in database
        $check = $conn->prepare("SELECT Student_ID FROM student WHERE Student_Email = ?");
        $check->bind_param("s", $emailVal);
        $check->execute();
        $check->store_result();

        if ($check->num_rows === 0) {
            $serverError = "No account found with this email address.";
        } else {
            // update password as plain text for now，i dont want hash
            $hashedPassword = password_hash($newPw,PASSWORD_DEFAULT); // This is incorrect usage of password_verify
            $update = $conn->prepare("UPDATE student SET Student_Password = ? WHERE Student_Email = ?");
            $update->bind_param("ss", $hashedPassword, $emailVal);

            if ($update->execute()) {
                header("Location: LoginPage.php?reset=success");
                exit();
            } else {
                $serverError = "Something went wrong. Please try again.";
            }
            
            $update->close();
        }
        $check->close();
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<style>
    *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family:Arial,sans-serif;
    }
    body{
        background-color: #f7f2fe;
        min-height:100vh;
    }
    .header{
        font-style: oblique;
        background-color:#c9b4f5;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: row;
        gap: 15px;
        padding: 25px 0;
    }
    .header h1{
        font-size: 26px;
        color: #2d1b5e;
        font-weight:bold;
        letter-spacing:2px;
    }
    .page-content{
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 80px);
        padding:40px 20px;
    }
    .resetpassword-box {
        background-color: white;
        border-radius: 20px;
        padding: 48px 40px;
        width: 100%;
        max-width: 420px;
    }
    .resetpassword-box h2 {
        font-size: 24px;
        color: #2d1b5e;
        margin-bottom: 6px;
    }
    .resetpassword-box .subtitle {
        font-size: 14px;
        color: #999;
        margin-bottom: 30px;
    }
    .input-wrapper {
        margin-bottom: 16px;
    }
    input[type="email"],
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
    input[type="email"]:focus,
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
    .error-msg.show {
        display: block;
    }
    /* server error banner */
    .server-error {
        background-color: #fff0f0;
        border: 1px solid #e53935;
        color: #e53935;
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
    button:hover {
        background-color: #5e35b1;
    }
    a {
        display: block;
        color: #7c4dff;
        font-size: 13px;
        text-decoration: none;
        margin-bottom: 10px;
    }
    a:hover {
        text-decoration: underline;
    }
    .password-rules {
        background-color: #f7f2fe;
        border-left: 3px solid #c9b4f5;
        border-radius: 8px;
        padding: 10px 14px;
        margin-bottom: 16px;
        font-size: 12px;
        color: #666;
        line-height: 1.8;
    }
    .password-rules p {
        margin: 0 0 4px 0;
        font-weight: bold;
        color: #2d1b5e;
    }
    .rule {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .rule-icon { font-size: 13px; }
    .rule.pass .rule-icon::before { content: "✅"; }
    .rule.fail .rule-icon::before { content: "❌"; }
    .rule.idle .rule-icon::before { content: "○"; }
</style>
<body>
    <div class="header">
        <img src="Logo.png" alt="Memo Campus Logo" style="width:80px;">
        <h1>MEMO CAMPUS</h1>
    </div>

    <div class="page-content">
        <div class="resetpassword-box">

            <h2>Reset Your Password</h2>    
            <p class="subtitle">Fill in your details below to reset your password</p>

            <?php if (!empty($serverError)): ?>
                <div class="server-error">⚠️ <?= htmlspecialchars($serverError) ?></div>
            <?php endif; ?>

            <form action="ResetPassword.php" method="post" onsubmit="return resetPassword()">

                <!-- Email -->
                <div class="input-wrapper">
                    <input type="email" id="email" name="email" placeholder="Enter your email address"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    <span class="error-msg" id="email-error"></span>
                </div>

                <!-- New Password -->
                <div class="input-wrapper">
                    <input type="password" id="new-password" name="new-password"
                           placeholder="Enter your new password" oninput="checkRules()">
                    <span class="error-msg" id="newpw-error"></span>
                </div>

                <!-- Live password rules hint -->
                <div class="password-rules" id="rules-box" style="display:none;">
                    <p>Password must have:</p>
                    <div class="rule idle" id="rule-length">
                        <span class="rule-icon"></span> At least 8 characters
                    </div>
                    <div class="rule idle" id="rule-upper">
                        <span class="rule-icon"></span> At least 1 uppercase letter (A-Z)
                    </div>
                    <div class="rule idle" id="rule-number">
                        <span class="rule-icon"></span> At least 1 number (0-9)
                    </div>
                    <div class="rule idle" id="rule-symbol">
                        <span class="rule-icon"></span> At least 1 symbol (!@#$%...)
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="input-wrapper">
                    <input type="password" id="confirm-password" name="confirm-password"
                           placeholder="Confirm your new password">
                    <span class="error-msg" id="confirmpw-error"></span>
                </div>

                <button type="submit">Reset Password</button>
                <a href="LoginPage.php">← Back to Log In</a>

            </form>
        </div>
    </div>

    <script>
        function checkRules() {
            var pw = document.getElementById("new-password").value;
            document.getElementById("rules-box").style.display = pw.length > 0 ? "block" : "none";
            setRule("rule-length", pw.length >= 8);
            setRule("rule-upper",  /[A-Z]/.test(pw));
            setRule("rule-number", /[0-9]/.test(pw));
            setRule("rule-symbol", /[^A-Za-z0-9]/.test(pw));
        }

        function setRule(ruleId, isPassing) {
            var el = document.getElementById(ruleId);
            el.classList.remove("pass", "fail", "idle");
            el.classList.add(isPassing ? "pass" : "fail");
        }

        function resetPassword() {
            var emailValue   = document.getElementById("email").value.trim();
            var newPwValue   = document.getElementById("new-password").value;
            var confirmValue = document.getElementById("confirm-password").value;

            var emailInput   = document.getElementById("email");
            var newPwInput   = document.getElementById("new-password");
            var confirmInput = document.getElementById("confirm-password");
            var emailError   = document.getElementById("email-error");
            var newPwError   = document.getElementById("newpw-error");
            var confirmError = document.getElementById("confirmpw-error");

            [emailInput, newPwInput, confirmInput].forEach(function(el) {
                el.classList.remove("input-error");
            });
            [emailError, newPwError, confirmError].forEach(function(el) {
                el.classList.remove("show");
            });

            var isValid = true;

            if (emailValue === "") {
                emailError.textContent = "⚠️ Please enter your email address.";
                emailError.classList.add("show");
                emailInput.classList.add("input-error");
                isValid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue)) {
                emailError.textContent = "⚠️ Please enter a valid email address (e.g. john@gmail.com).";
                emailError.classList.add("show");
                emailInput.classList.add("input-error");
                isValid = false;
            }

            if (newPwValue === "") {
                newPwError.textContent = "⚠️ Please enter a new password.";
                newPwError.classList.add("show");
                newPwInput.classList.add("input-error");
                isValid = false;
            } else if (newPwValue.length < 8) {
                newPwError.textContent = "⚠️ Password must be at least 8 characters.";
                newPwError.classList.add("show");
                newPwInput.classList.add("input-error");
                isValid = false;
            } else if (!/[A-Z]/.test(newPwValue)) {
                newPwError.textContent = "⚠️ Password must contain at least 1 uppercase letter.";
                newPwError.classList.add("show");
                newPwInput.classList.add("input-error");
                isValid = false;
            } else if (!/[0-9]/.test(newPwValue)) {
                newPwError.textContent = "⚠️ Password must contain at least 1 number.";
                newPwError.classList.add("show");
                newPwInput.classList.add("input-error");
                isValid = false;
            } else if (!/[^A-Za-z0-9]/.test(newPwValue)) {
                newPwError.textContent = "⚠️ Password must contain at least 1 symbol (e.g. !@#$%).";
                newPwError.classList.add("show");
                newPwInput.classList.add("input-error");
                isValid = false;
            }

            if (confirmValue === "") {
                confirmError.textContent = "⚠️ Please confirm your new password.";
                confirmError.classList.add("show");
                confirmInput.classList.add("input-error");
                isValid = false;
            } else if (confirmValue !== newPwValue) {
                confirmError.textContent = "⚠️ Passwords do not match. Please try again.";
                confirmError.classList.add("show");
                confirmInput.classList.add("input-error");
                isValid = false;
            }

            return isValid;
        }
    </script>

</body>
</html>