<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT分野情報共有サイト</title>
    <style>
        /* CSSスタイル */
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }

        .container {
            text-align: center
        }

        .title {
            font-size: 60px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .button {
            padding: 10px 20px;
            font-size: 18px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }

        /* モーダルスタイル */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 5px;
            position: relative;
        }

        .close {
            color: #aaa;
            position: absolute;
            right: 10px;
            top: 5px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .input-field {
            margin: 10px 0;
            text-align: left;
        }

        .input-field label {
            display: block;
            margin-bottom: 5px;
        }

        .input-field input {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="title">IT分野情報共有サイト</div>
        <button class="button" onclick="openModal('loginModal')">ログイン</button>
        <a href="signup.php" class="button">アカウント新規作成</a>
    </div>

    <!-- ログインモーダル -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('loginModal')">&times;</span>
            <!-- PHPコード -->
            <?php
            session_start();

            // もし既にログインしている場合、ポップアップを表示して終了
            if (isset($_SESSION['username'])) {
                echo "<script>alert('既にログインしています'); window.location.href='home.php';</script>";
                exit();
            }

            // POSTリクエストがあるかどうかをチェック
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // データベース接続情報
                $servername = "localhost"; // データベースのホスト名
                $username = "kobe"; // データベースのユーザー名
                $password = "denshi"; // データベースのパスワード
                $dbname = "prosite"; // データベース名

                // ユーザーからの入力を取得
                $input_username = $_POST["user_name"];
                $input_password = $_POST["user_pass"];

                try {
                    // データベースに接続
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // 入力されたユーザー名とパスワードを検証
                    $stmt = $conn->prepare("SELECT * FROM users WHERE user_name = :username AND user_pass = :password");
                    $stmt->bindParam(':username', $input_username);
                    $stmt->bindParam(':password', $input_password);
                    $stmt->execute();

                    // 結果を取得
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);

                    // ログイン成功時の処理
                    if ($result) {
                        $_SESSION["username"] = $input_username;
                        // ログイン成功したらhome.phpにリダイレクト
                        echo "<script>window.location.href='home.php';</script>";
                        exit();
                    } else {
                        // ログイン失敗時のポップアップ表示
                        echo "<script>alert('ユーザー名またはパスワードが違います');</script>";
                    }
                } catch (PDOException $e) {
                    echo "<p>エラー: " . $e->getMessage() . "</p>";
                }
            }
            ?>

            <!-- ログインフォーム -->
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="input-field">
                    <label for="username">ユーザー名:</label>
                    <input type="text" id="username" name="user_name" required>
                </div>
                <div class="input-field">
                    <label for="password">パスワード:</label>
                    <input type="password" id="password" name="user_pass" required>
                </div>
                <button type="submit" class="button">ログイン</button>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // JavaScript関数
        function openModal(modalId) {
            var modal = document.getElementById(modalId);
            modal.style.display = "block";
        }

        function closeModal(modalId) {
            var modal = document.getElementById(modalId);
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            var modals = document.getElementsByClassName('modal');
            for (var i = 0; i < modals.length; i++) {
                var modal = modals[i];
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        }
    </script>
</body>

</html>