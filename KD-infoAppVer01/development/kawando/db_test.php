<!-- http://localhost/PBI1-B-Humble/KD-infoAppVer01/development/kawando/db_test.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログインページ</title>
    <!-- CSSの反映 -->
    <!-- <link rel="stylesheet" href="styles.css"> -->
</head>

<body>
    <form method="POST" action="insert_test.php">
        <div class="login-container">
            <h2>データ登録のテスト</h2>
            <form>
                <input type="text" id="input_text" name="input_text" placeholder="メールアドレス">
                <input type="text" id="userId" name="userId" placeholder="ユーザー名" required>
                <input type="password" id="userPass" name="userPass" placeholder="パスワード" required>
                <button type="submit">送る</button>
            </form>
        </div>
        <?php
        $dsn = 'mysql:host=localhost;dbname=prosite;charset=utf8'; // データベースの接続情報（prositeに接続）
        $user = 'kobe'; // ユーザー名
        $password = 'denshi'; // パスワード

        // select文を変数に代入し実行
        try {
            $pdo = new PDO($dsn, $user, $password); // データベースに接続
            $sql = 'select * from users'; // SQL文を変数に代入
            $stmt = $pdo->query($sql); // SQL文を実行
            $results = $stmt->fetchALL(); // 実行結果を取得
            foreach ($results as $result) {
                echo $result['user_id'] . ' ,' . $result['email_address'] . ' ,' . $result['user_name'] . ' ,' . $result['user_pass'] . ' ,'; // sql文の結果を出力
                echo $result['profile_title'] . ' ,' . $result['profile_text'];
                echo '<br>';
            }
        } catch (PDOException $e) {
            echo '接続に失敗しました'; // 失敗した場合に表示
            var_dump($e->getMessage()); // エラー内容を出力
            exit; // プログラムを終了
            die();
        }
        $pdo = null;    // データベース接続を切断
        ?>

        <!-- delete文を変数に代入し実行 -->
    </form>
    <form method="POST" action="delete_test.php">
        <div class="login-container">
            <h2>データ削除のテスト</h2>
            <form>
                <input type="text" id="userId" name="userId" placeholder="ユーザー名" required>
                <input type="password" id="userPass" name="userPass" placeholder="パスワード" required>
                <button type="submit">送る</button>
            </form>
        </div>
    </form>
</body>

</html>