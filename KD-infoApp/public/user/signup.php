<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up for KD-info</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome CSSを追加 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/png" href="\PBI1-B-Humble\KD-infoApp\public\Components\static\AppIcon\KD-info2.png">
    <link rel="stylesheet" href="../Components/static/background.css">
    <style>
        body {
            background-color: #111;
        }

        .modal-content {
            background-color: #111;
            color: #fff;
            border: 1px solid #444;
        }

        .input-field input {
            background-color: #222;
            color: #ccc;
            border-color: #555;
        }

        .input-field input:focus {
            border-color: #777;
            outline: none;
        }

        .toggle-password {
            cursor: pointer;
        }
    </style>
</head>
<body class="custom-gradient flex justify-center items-center h-screen">
    <div class="modal-content p-8 rounded-lg w-96">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="space-y-4">
            <h1 class="text-lg font-bold text-center mb-4">Sign up</h1>
            <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $servername = "localhost";
                    $username = "kobe";
                    $password = "denshi";
                    $dbname = "prosite";

                    $input_user_name = $_POST["user_name"];
                    $input_email = $_POST["email"];
                    $input_password = $_POST["password"];
                    $confirm_password = $_POST["confirm_password"];

                    try {
                        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $stmt = $conn->prepare("SELECT * FROM users WHERE user_name = :user_name OR email_address = :email");
                        $stmt->bindParam(':user_name', $input_user_name);
                        $stmt->bindParam(':email', $input_email);
                        $stmt->execute();
                        $exists = $stmt->fetch();

                        if ($exists) {
                            if ($exists['user_name'] === $input_user_name) {
                                echo "<p class='text-red-500'>既に存在するユーザーネームです。</p>";
                            }
                            if ($exists['email_address'] === $input_email) {
                                echo "<p class='text-red-500'>既に存在するメールアドレスです。</p>";
                            }
                        } elseif (strlen($input_password) < 8) {
                            echo "<p class='text-red-500'>パスワードは8文字以上でなければなりません。</p>";
                        } elseif ($input_password !== $confirm_password) {
                            echo "<p class='text-red-500'>パスワードが一致しません。</p>";
                        } else {
                            $hashed_password = password_hash($input_password, PASSWORD_DEFAULT);
                            $stmt = $conn->prepare("INSERT INTO users (user_name, user_pass, email_address) VALUES (:user_name, :user_pass, :email)");
                            $stmt->bindParam(':user_name', $input_user_name);
                            $stmt->bindParam(':user_pass', $hashed_password);
                            $stmt->bindParam(':email', $input_email);
                            $stmt->execute();
                            session_start();
                            $_SESSION['signup_success'] = "新規登録が完了しました。ログインしてください。";
                            header("Location: login.php");
                            exit();
                        }
                    } catch (PDOException $e) {
                        echo "<p class='text-red-500'>エラー: " . $e->getMessage() . "</p>";
                    }
                }
            ?>
            <div class="input-field">
                <input type="text" id="user_id" name="user_name" placeholder="ユーザー名" required class="mt-1 block w-full px-3 py-2 rounded-md">
            </div>
            <div class="input-field">
                <input type="email" id="email" name="email" placeholder="メールアドレス" required class="mt-1 block w-full px-3 py-2 rounded-md">
            </div>
            <div class="input-field relative">
                <input type="password" id="password" name="password" placeholder="パスワード" required class="mt-1 block w-full px-3 py-2 rounded-md">
                <span class="toggle-password absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                    <i class="fa fa-eye-slash" aria-hidden="true" onclick="togglePasswordVisibility('password')"></i>
                </span>
            </div>
            <div class="input-field relative">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="パスワード確認" required class="mt-1 block w-full px-3 py-2 rounded-md">
                <span class="toggle-password absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                    <i class="fa fa-eye-slash" aria-hidden="true" onclick="togglePasswordVisibility('confirm_password')"></i>
                </span>
            </div>
            <button type="submit" class="bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 w-full rounded focus:outline-none focus:shadow-outline">新規登録</button>
            <p class="text-xs text-gray-400 mt-3">すでにアカウントをお持ちですか？ <a href="login.php" class="text-red-500 hover:text-red-700">ログインはこちら</a></p>
        </form>
    </div>

    <!-- JavaScript for Modal, etc -->
    <script>
        function togglePasswordVisibility(id) {
            var passwordInput = document.getElementById(id);
            var toggleIcon = passwordInput.nextElementSibling.children[0];
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.add('fa-eye');
                toggleIcon.classList.remove('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.add('fa-eye-slash');
                toggleIcon.classList.remove('fa-eye');
            }
        }
    </script>
</body>
</html>
