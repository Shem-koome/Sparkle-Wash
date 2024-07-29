<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: auto;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="email"],
        input[type="text"] {
            width: calc(100% - 10px);
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        #math_question {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
        }
        .error {
            color: red;
            margin: 10px 0;
        }
        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Responsive Design */
        @media only screen and (max-width: 600px) {
            form {
                padding: 10px;
            }
            input[type="email"],
            input[type="text"] {
                width: 100%;
            }
            button[type="submit"] {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <h2>Forget Password</h2>
    <?php
        session_start();
        
        // Function to generate a new math question
        function generateMathQuestion() {
            $num1 = rand(1, 100);
            $num2 = rand(1, 100);
            $operation = rand(0, 1) ? '+' : '-';
            $math_question = "$num1 $operation $num2";
            $answer = $operation == '+' ? $num1 + $num2 : $num1 - $num2;
            
            // Store math question and answer in session
            $_SESSION['math_question'] = $math_question;
            $_SESSION['math_answer'] = $answer;
            
            return $math_question;
        }
        
        // Always generate a new math question on page load
        generateMathQuestion();
        
        // Check for error message from process_forget_password.php
        if (isset($_SESSION['error_message'])) {
            echo '<p class="error">' . $_SESSION['error_message'] . '</p>';
            unset($_SESSION['error_message']); // Clear error message
        }
    ?>
    <form action="process_forget_password.php" method="POST">
        <label for="email">Email:</label>
        <?php
            $email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
        ?>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
        
        <br><br>
        
        <label for="math_question">Answer the quiz below:</label>
        <p id="math_question"><?php echo $_SESSION['math_question']; ?></p>
        
        <label for="math_answer">Answer:</label>
        <input type="text" id="math_answer" name="math_answer" <?php if (isset($_SESSION['error_message'])) echo 'style="border-color: red;"'; ?> required>
        
        <br><br>
        
        <button type="submit">Submit</button>
    </form>
</body>
</html>
