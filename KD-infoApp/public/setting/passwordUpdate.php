<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PasswordChange KD-info</title>
    <!-- TailwindCSSに必要なリンク -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome CSSを追加 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- タブのアイコン設定(相対パスは非表示になるバグがあるので絶対パスで指定中) -->
    <link rel="icon" type="image/png" href="\PBI1-B-Humble\KD-infoApp\public\Components\static\AppIcon\KD-info2.png">

    <style>
        body {
            background-color: #333;
        }

        .modal-content {
            background-color: #222;
            color: #fff;
            border: 1px solid #444;
        }

        .input-field input {
            background-color: #333;
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

<body>
    <?php
        // セッションの開始とヘッダーのインポート
        session_start();
        include '..\Components\src\header\header.php';
        renderHeader('question');

        // テスト用セッション情報表示
        if (isset($_SESSION['user_name'])) {
            // ログインしているユーザー名を表示
            echo "<h1>Welcome, " . htmlspecialchars($_SESSION['user_name']) . "!</h1>";
        } else {
            // ログイン情報がない場合のメッセージ
            echo "<h1>Welcome to Question Home</h1>";
            echo "<p>Please <a href='\PBI1-B-Humble\KD-infoApp\public\user\login.php'>login</a> to continue.</p>";
        }
    ?>
    <div class="modal-content p-8 rounded-lg w-96">
        <h2 class="text-lg font-bold text-center mb-4">パスワード変更</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="space-y-4">
            <?php
            // もしログインしていない場合、ログインページにリダイレクト
            if (!isset($_SESSION['username'])) {
                header("Location: ../user/login.php");
                exit();
            }

            // データベース接続情報
            $servername = "localhost"; // データベースのホスト名
            $username = "kobe"; // データベースのユーザー名
            $password = "denshi"; // データベースのパスワード
            $dbname = "prosite"; // データベース名

            // POSTリクエストがあるかどうかをチェック
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // 現在のパスワードを取得
                $current_password = $_POST["current_password"];
                // 新しいパスワードを取得
                $new_password = $_POST["new_password"];
                $confirm_password = $_POST["confirm_password"];

                // 新しいパスワードが現在のパスワードと同じかどうかを確認
                if ($current_password === $new_password) {
                    echo "<p class='text-red-500 text-xs italic'>現在のパスワードと新しいパスワードが一緒です。もう一度やり直してください。</p>";
                } else {
                    try {
                        // データベースに接続
                        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // 現在のパスワードを検証
                        $stmt = $conn->prepare("SELECT user_pass FROM users WHERE user_name = :user_name");
                        $stmt->bindParam(':user_name', $_SESSION['user_name']);
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($result) {
                            $hashed_password = $result['user_pass'];
                            if (password_verify($current_password, $hashed_password)) {
                                // パスワードが一致する場合の処理
                                // 新しいパスワードが8文字以上であることを確認
                                if (strlen($new_password) < 8) {
                                    echo "<p class='text-red-500 text-xs italic'>新しいパスワードは8文字以上である必要があります。</p>";
                                } elseif ($new_password !== $confirm_password) {
                                    echo "<p class='text-red-500 text-xs italic'>新しいパスワードが一致しません。もう一度お試しください。</p>";
                                } else {
                                    // パスワードをハッシュ化
                                    $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

                                    // パスワードを更新するクエリ
                                    $stmt = $conn->prepare("UPDATE users SET user_pass = :hashed_password WHERE user_name = :user_name");
                                    $stmt->bindParam(':hashed_password', $hashed_new_password);
                                    $stmt->bindParam(':user_name', $_SESSION['user_name']);
                                    $stmt->execute();

                                    // パスワードが更新されたらホームページにリダイレクト
                                    header("Location: /PBi1-B-Humble/KD-infoApp/public/posting/index.php");
                                    exit();
                                }
                            } else {
                                echo "<p class='text-red-500 text-xs italic'>現在のパスワードが正しくありません。</p>";
                            }
                        }
                    } catch (PDOException $e) {
                        echo "<p>エラー: " . $e->getMessage() . "</p>";
                    }
                }
            }
            ?>
            <div class="input-field relative">
                <input type="password" id="current_password" name="current_password" placeholder="現在のパスワード" required class="mt-1 block w-full px-3 py-2 rounded-md">
                <span class="toggle-password absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                    <i class="fa fa-eye-slash" aria-hidden="true" onclick="togglePasswordVisibility('current_password')"></i>
                </span>
            </div>
            <div class="input-field relative">
                <input type="password" id="new_password" name="new_password" placeholder="新しいパスワード" required class="mt-1 block w-full px-3 py-2 rounded-md">
                <span class="toggle-password absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                    <i class="fa fa-eye-slash" aria-hidden="true" onclick="togglePasswordVisibility('new_password')"></i>
                </span>
            </div>
            <div class="input-field relative">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="新しいパスワード（確認）" required class="mt-1 block w-full px-3 py-2 rounded-md">
                <span class="toggle-password absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                    <i class="fa fa-eye-slash" aria-hidden="true" onclick="togglePasswordVisibility('confirm_password')"></i>
                </span>
            </div>
            <button type="submit" class="bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 w-full rounded focus:outline-none focus:shadow-outline">変更</button>
        </form>
    </div>

    <!-- JavaScript for Password Visibility Toggle -->
    <script>
        function togglePasswordVisibility(id) {
            var passwordInput = document.getElementById(id);
            var toggleIcon = passwordInput.nextElementSibling.children[0];
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            }
        }
    </script>
</body>

</html>