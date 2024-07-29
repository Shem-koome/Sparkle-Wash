<?php
session_start();

// Check if email and reset code are set in session
if (!isset($_SESSION['email']) || !isset($_SESSION['reset_code'])) {
    header("Location: forget_password.php");
    exit();
}

$email = $_SESSION['email'];
$reset_code = $_SESSION['reset_code'];

// Check if POST request is received (via AJAX)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the entered code from POST data
    $code = $_POST['code'];

    // Initialize response array
    $response = array('success' => false, 'message' => 'Incorrect code.');

    // Compare entered code with the reset code from session
    if ($code == $reset_code) {
        $response['success'] = true;
        $response['message'] = 'Correct code.';
    }

    // Send JSON response back to JavaScript
    echo json_encode($response);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            max-width: 560px;
            width: 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        .digit-input {
            width: 40px;
            height: 40px;
            text-align: center;
            font-size: 20px;
            margin: 0 5px;
            border: 2px solid #ccc;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .digit-input:focus {
            border-color: #007bff;
        }

        .correct {
            border-color: green;
        }

        .incorrect {
            border-color: red;
        }

        button {
            display: block;
            margin: 20px auto 0;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        @media (max-width: 600px) {
            .digit-input {
                width: 30px;
                height: 30px;
                font-size: 16px;
            }
        }
    </style>
    <script>
        function checkCode() {
            var digits = document.getElementsByClassName('digit-input');
            var code = '';
            for (var i = 0; i < digits.length; i++) {
                code += digits[i].value;
            }

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'verify_code.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    var digitInputs = document.getElementsByClassName('digit-input');
                    if (response.success) {
                        for (var i = 0; i < digitInputs.length; i++) {
                            digitInputs[i].classList.remove('incorrect');
                            digitInputs[i].classList.add('correct');
                        }
                        setTimeout(function() {
                            window.location.href = 'reset_password.php';
                        }, 1000);
                    } else {
                        for (var i = 0; i < digitInputs.length; i++) {
                            digitInputs[i].classList.remove('correct');
                            digitInputs[i].classList.add('incorrect');
                        }
                        document.getElementById('error_message').innerText = response.message;
                    }
                }
            };
            xhr.send('code=' + code);
        }

        function focusNextInput(currentInput) {
            if (currentInput.value.length == 1) {
                if (currentInput.nextElementSibling && currentInput.nextElementSibling.classList.contains('digit-input')) {
                    currentInput.nextElementSibling.focus();
                } else {
                    checkCode();
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var digitInputs = document.getElementsByClassName('digit-input');
            for (var i = 0; i < digitInputs.length; i++) {
                digitInputs[i].addEventListener('input', function() {
                    focusNextInput(this);
                });
            }
        });
    </script>
</head>
<body>
    <div class="container">
        <h2>Enter the 6-digit code sent to your email</h2>
        <p id="error_message" class="error"></p>
        <form onsubmit="return false;">
            <?php for ($i = 0; $i < 6; $i++): ?>
                <input type="text" maxlength="1" class="digit-input" required>
            <?php endfor; ?>
        </form>
    </div>
</body>
</html>


