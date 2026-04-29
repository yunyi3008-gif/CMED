<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }
    .header {
        font-style: oblique;
        background-color: #c9b4f5;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 16px 0;
        width: 100%; 
        margin: 0 0 10px;
        flex-direction: row;  
        gap: 12px;
    }
    .header-logo img{ 
        width: 100px;
        height: 100px;
        object-fit: contain;
    }
    .header h1 {
        font-size: 70px;
        color: #2d1b5e;
        font-weight: bold;
        letter-spacing: 2px;
    }
    body {
        padding: 10px;
        background-color: #f7f2fe;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .Registration {
        max-width: 700px;
        width: 100%;
        background: white;
        border-radius: 5px;
        padding: 20px 30px;
        justify-content: center;
        align-items: center;
    }
    .title {
        font-size: 25px;
        font-weight: 500;
        position: relative;
    }
    .Registration .UserDetailsForm {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }
    .Registration .inputBox {
        width: 48%;
        margin: 20px 0 12px 0;
    }
    .Registration .inputBox input,
    .Registration .inputBox select {
        height: 45px;
        width: 100%;
        box-sizing: border-box;
        outline: none;
        border-radius: 5px;
        padding-left: 15px;
        border: 1px solid #ccc;
        font-size: 13px;
        border-bottom-width: 2px;
    }
    .Registration .inputBox input:hover,
    .Registration .inputBox select:hover {
        border-color: #2d1b5e;
    }
    .Registration .inputBox label {
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
    }
    select:invalid {
        color: gray;
    }
    select option {
        color: black;
    }
    .YearAdmission {
        display: flex;
        gap: 10px;
    }
    #rules-box {
        display: none;
        margin-top: 8px;
        padding: 12px 16px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        background-color: #fafafa;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
    .password-rules p {
        font-size: 12px;
        font-weight: 600;
        color: #555;
        margin: 0 0 8px 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .rule {
        font-size: 13px;
        padding: 4px 0;
        color: #aaa;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: color 0.2s;
    }
    .rule.pass {
        color: green;
    }
    .rule.fail {
        color: red;
    }
    .error-msg {
        display: none;
        color: red;
        font-size: 12px;
    }
    .error-msg.show {
        display: block;
    }
    .err {
        border-color: red !important;
    }
    .genderCategory {
        display: flex;
        gap: 30px;
    }
    .genderCategory input[type="radio"] {
        width: auto;
        height: auto;
    }
    .submit-btn {
        background-color: #f7f2fe;
        color: black;
        border: 2px solid #8c7eb1;
        padding: 12px 30px;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        display: inline-block;
        width: 100%;
        cursor: pointer;
        font-size: 14px;
    }
    .submit-btn:hover {
        border-color: #2d1b5e;
    }

    @media (max-width: 680px) {
        .header-logo img {
            width: 90px;
            height: 90px;
        }
        .header h1 {
            font-size: 60px;
        }
    }
    @media (max-width: 584px) {
        .header-logo img {
            width: 80px;
            height: 80px;
        }
        .header h1 {
            font-size: 50px;
        }
        .Registration {
            max-width: 100%;
        }
        .Registration .inputBox {
            width: 100%;
            margin: 20px 0 12px 0;
        }
    }
    @media (max-width: 490px) {
        .header-logo img {
            width: 80px;
            height: 80px;
            margin-left: 8px;
        }
        .header h1 {
            font-size: 40px;
            letter-spacing: 1px;
            margin-right: 8px;
        }
    }
    @media (max-width: 380px) {
        .header-logo img {
            width: 70px;
            height: 70px;
            padding-left: 6px;
        }
        .header h1 {
            font-size: 35px;
            letter-spacing: 1px;
            padding-right: 6px;
        }
    }
</style>
<body>
    <div class="header">
        <div class="header-logo">
            <img src="Logo.png">
        </div>
        <h1>Memo Campus</h1>
    </div>

    <div class="Registration">
        <div class="Title">
            <h1>Student Registration Form</h1>
        </div>
        <form action="InsertDatabase.php" method="post" id="RegistrationForm">
            <div class="UserDetailsForm">

                <!-- Full Name -->
                <div class="inputBox">
                    <label for="StudentName">Full Name:</label>
                    <input type="text" name="StudentName" id="StudentName"
                        placeholder="Enter your name"
                        onkeypress="validateAlphabets(event)" required>
                </div>

                <!-- Email -->
                <div class="inputBox">
                    <label for="StudentEmail">Email:</label>
                    <input type="email" name="StudentEmail" id="StudentEmail"
                        placeholder="Enter your email" required>
                </div>

                <!-- Phone Number -->
                <div class="inputBox">
                    <label for="StudentPhoneNumber">Phone Number:</label>
                    <input type="text" name="StudentPhoneNumber" id="StudentPhoneNumber"
                        placeholder="Enter your phone number"
                        onkeypress="validateNumber(event)" required>
                </div>

                <!-- Programme -->
                <div class="inputBox">
                    <label for="StudentProgramme">Programme:</label>
                    <select name="StudentProgramme" id="StudentProgramme" required>
                        <option value="" disabled selected>Select your programme</option>
                        <option value="Information Technology">Information Technology</option>
                        <option value="International Business">International Business</option>
                    </select>
                </div>

                <!-- Education Level -->
                <div class="inputBox">
                    <label for="StudentEducationLevel">Education Level:</label>
                    <select name="StudentEducationLevel" id="StudentEducationLevel" required>
                        <option value="" disabled selected>Select your education level</option>
                        <option value="Diploma Level">Diploma Level</option>
                        <option value="Degree Level">Degree Level</option>
                    </select>
                </div>

                <!-- Institution -->
                <div class="inputBox">
                    <label for="StudentInstitution">Institution:</label>
                    <select name="StudentInstitution" id="StudentInstitution" required>
                        <option value="" disabled selected>Select your institution</option>
                        <option value="Sunway University">Sunway University</option>
                        <option value="Sunway College Johor Bahru">Sunway College Johor Bahru</option>
                    </select>
                </div>

                <!-- Year Admission -->
                <div class="inputBox">
                    <label for="StudentYearAdmission">Year Admission:</label>
                    <div style="display: flex; gap: 10px;">
                        <select name="StudentMonthAdmission" id="StudentMonthAdmission" required>
                            <option value="" disabled selected>Month</option>
                            <option value="Jan">Jan</option>
                            <option value="Feb">Feb</option>
                            <option value="Mar">Mar</option>
                            <option value="Apr">Apr</option>
                            <option value="May">May</option>
                            <option value="Jun">Jun</option>
                            <option value="Jul">Jul</option>
                            <option value="Aug">Aug</option>
                            <option value="Sep">Sep</option>
                            <option value="Oct">Oct</option>
                            <option value="Nov">Nov</option>
                            <option value="Dec">Dec</option>
                        </select>
                        <select name="StudentYearAdmission" id="StudentYearAdmission" required>
                            <option value="" disabled selected>Year</option>
                            <option value="2026">2026</option>
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                        </select>
                    </div>
                </div>

                <!-- Password -->
                <div class="inputBox">
                    <label for="StudentPassword">Password:</label>
                    <input type="password" name="StudentPassword" id="StudentPassword"
                        placeholder="Create your password"
                        oninput="checkRules()"
                        onfocus="document.getElementById('rules-box').style.display='block'"
                        onblur="document.getElementById('rules-box').style.display='none'" required>
                    <span class="error-msg" id="newpw-error"></span>
                    <div class="password-rules" id="rules-box">
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
                </div>

                <!-- Confirm Password -->
                <div class="inputBox">
                    <label for="StudentConfirmPassword">Confirm Password:</label>
                    <input type="password" id="StudentConfirmPassword"
                        placeholder="Confirm your password"
                        oninput="checkConfirm()" required>
                    <span class="error-msg" id="confirmpw-error"></span>
                </div>

                <!-- Gender -->
                <div class="inputBox" style="padding-left: 14px;">
                    <label>Gender:</label><br>
                    <div class="genderCategory">
                        <span>
                            <input type="radio" name="StudentGender" id="GenderFemale" value="Female" required> Female
                        </span>
                        <span>
                            <input type="radio" name="StudentGender" id="GenderMale" value="Male"> Male
                        </span>
                    </div>
                </div>

                <br>
                <!-- Hidden Student ID -->
                <input type="hidden" name="StudentId" id="StudentId">

                <!-- Submit Button -->
                <button type="button" class="submit-btn" onclick="handleSubmit()">Submit</button>

            </div>
        </form>
    </div>

<script>
    function validateAlphabets(event) {
        const char = String.fromCharCode(event.which);
        // Allow letters and space (for names like "John Doe")
        if (!/^[A-Za-z ]$/.test(char)) {
            event.preventDefault();
        }
    }

    function validateNumber(event) {
        const char = String.fromCharCode(event.which);
        if (!/^[0-9]$/.test(char)) {
            event.preventDefault();
        }
    }

    function checkRules() {
        var password = document.getElementById('StudentPassword').value;
        setRule('rule-length', password.length >= 8);
        setRule('rule-upper',  /[A-Z]/.test(password));
        setRule('rule-number', /[0-9]/.test(password));
        setRule('rule-symbol', /[^A-Za-z0-9]/.test(password));
        if (document.getElementById('StudentConfirmPassword').value) checkConfirm();
    }

    function setRule(ruleId, isPassing) {
        var el = document.getElementById(ruleId);
        el.classList.remove("pass", "fail", "idle");
        el.classList.add(isPassing ? "pass" : "fail");
    }

    function checkConfirm() {
        var password = document.getElementById('StudentPassword').value;
        var confirmPassword = document.getElementById('StudentConfirmPassword').value;
        var errEl = document.getElementById('confirmpw-error');
        var input = document.getElementById('StudentConfirmPassword');
        if (confirmPassword && confirmPassword !== password) {
            errEl.textContent = 'Passwords do not match. Please try again.';
            errEl.classList.add('show');
            input.classList.add('err');
        } else {
            errEl.classList.remove('show');
            input.classList.remove('err');
        }
    }

    function isPasswordValid(password) {
        return (
            password.length >= 8 &&
            /[A-Z]/.test(password) &&
            /[0-9]/.test(password) &&
            /[^A-Za-z0-9]/.test(password)
        );
    }

    function handleSubmit() {
        var password = document.getElementById('StudentPassword').value;
        var confirmPassword = document.getElementById('StudentConfirmPassword').value;
        var passwordInput = document.getElementById('StudentPassword');
        var confirmInput = document.getElementById('StudentConfirmPassword');
        var passwordErr = document.getElementById('newpw-error');
        var confirmErr = document.getElementById('confirmpw-error');

        // Reset errors
        [passwordInput, confirmInput].forEach(function(el) { el.classList.remove('err'); });
        [passwordErr, confirmErr].forEach(function(el) { el.classList.remove('show'); });

        var isValid = true;

        // Validate password
        if (!password) {
            passwordErr.textContent = 'Please enter a password.';
            passwordErr.classList.add('show');
            passwordInput.classList.add('err');
            isValid = false;
        } else if (!isPasswordValid(password)) {
            passwordErr.textContent = 'Password does not meet all requirements above.';
            passwordErr.classList.add('show');
            passwordInput.classList.add('err');
            isValid = false;
        }

        // Validate confirm password
        if (!confirmPassword) {
            confirmErr.textContent = 'Please confirm your password.';
            confirmErr.classList.add('show');
            confirmInput.classList.add('err');
            isValid = false;
        } else if (confirmPassword !== password) {
            confirmErr.textContent = 'Passwords do not match. Please try again.';
            confirmErr.classList.add('show');
            confirmInput.classList.add('err');
            isValid = false;
        }

        if (isValid) {
            var userId = generateUserId();
            document.getElementById("StudentId").value = userId;
            alert('Registration Successful!\nYour User ID: ' + userId + '\n\nPlease save this ID for future login.');
            document.getElementById("RegistrationForm").submit(); // Submit only when valid
        }
    }

    function generateUserId() {
        var id = '';
        for (var i = 0; i < 8; i++) {
            id += Math.floor(Math.random() * 10);
        }
        return id;
    }
</script>
</body>
</html>