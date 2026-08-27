<?php
session_start();
session_unset();
session_destroy();
session_start();
require 'dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $stmt = mysqli_prepare($conn, "SELECT agentID, agentUsername, agentEmployer FROM travel_agents WHERE agentUsername = ? AND agentPassword = ?");
        mysqli_stmt_bind_param($stmt, "ss", $username, $password);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            mysqli_stmt_bind_result($stmt, $agentID, $agentName, $agentEmployer);
            mysqli_stmt_fetch($stmt);

            $_SESSION['agentID'] = $agentID;
            $_SESSION['agentName'] = $agentName;
            $_SESSION['agencyID'] = $agentEmployer;
            $_SESSION['agentUsername'] = $username;

            header("Location: travelagent.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }

        mysqli_stmt_close($stmt);
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Travel Agent Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"> 

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            width: 90%;
            max-width: 400px;
            background: white;
            padding: 5px 30px 40px 30px;
            border-radius: 12px;
            box-shadow: none;
            border: 3px solid #66717bff;
        }
        
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .formGroup {
            margin-bottom: 25px;
        }

        .formGroup label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #444;
        }

        .formGroup i {
            margin-right: 6px;
            color: #66717bff;
        }

        .inputWrapper {
            position: relative;
        }

        .inputWrapper input {
            width: 100%;
            padding: 10px 6px;
            border: none;
            border-bottom: 1.5px solid #949494;
            font-size: 15px;
            background-color: transparent;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .inputWrapper .focusBar {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 2px;
            width: 0;
            background-color: #597fa8ff;
            transition: width 0.4s ease;
        }

        .inputWrapper input:focus {
            border-bottom-color: transparent;
        }

        .inputWrapper input:focus + .focusBar {
            width: 100%;
        }

        .error {
            color: red;
            background-color: #f8d9d9ff;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #455b6eff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #66727bff;
        }

        @media (max-width: 768px) {
            body {
                padding: 20px;
            }

            .login-container {
                margin: 0 auto;
                min-width: 280px;
                padding: 20px;
            }

            h1 {
                font-size: 22px;
            }

            .inputWrapper input {
                font-size: 14px;
            }

            button {
                font-size: 15px;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h1>Travel Agent Login</h1>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="#" autocomplete="off">
            <div class="formGroup">
                <label for="username"><i class="fa-solid fa-user"></i> Username:</label>
                <div class="inputWrapper">
                    <input type="text" id="username" name="username" required
                           value="<?= isset($username) ? htmlspecialchars($username) : '' ?>">
                    <span class="focusBar"></span>
                </div>
            </div>

            <div class="formGroup">
                <label for="password"><i class="fa-solid fa-lock"></i> Password:</label>
                <div class="inputWrapper">
                    <input type="password" id="password" name="password" required autocomplete="new-password">
                    <span class="focusBar"></span>
                </div>
            </div>

            <button type="submit">Login</button>
        </form>
    </div>

</body>
</html>
