<!-- メモ用のファイルです -->

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイプロフィール</title>
</head>

<body>
    <?php
    // フォームから送信されたデータを受け取り、変数に保存する
    $userName = $_POST['userName'];
    $userPass = $_POST['userPass'];

    // データベースへの接続確認
    try {
        $dsn = 'mysql:host=localhost;dbname=prosite;charset=utf8'; // データベースの接続情報(db&serverName)
        $user = 'kobe'; // ユーザー名
        $password = 'denshi'; // パスワード
        $pdo = new PDO($dsn, $user, $password); // データベースに接続
        echo '接続に成功しました'; // 成功した場合に表示
        echo "<br>";
    } catch (PDOException $e) {
        echo '接続に失敗しました'; // 失敗した場合に表示
        var_dump($e->getMessage()); // エラー内容を出力
        exit; // プログラムを終了
        die();
    }

    // Postで受け取ったデータからユーザーの主キーを取得
    $sql = 'select user_id from users where user_name = :userName and user_pass = :userPass'; // SQL文を変数に代入
    $stmt = $pdo->prepare($sql); // SQL文実行する前にprepareメソッドを実行
    $stmt->execute(['userName' => $userName, 'userPass' => $userPass]); // 値を代入してSQL文を実行
    // 結果を取得
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $userId = $row['user_id'];  // ユーザーのuser_idがあれば取得
        echo 'ユーザーの主キーは: ' . $userId;
        echo "<br>";
    } else {
        echo 'ユーザーが見つかりませんでした';
    }

    // postsテーブルからユーザーIDに紐づくデータを取得
    $sql = 'select * from posts where user_id = :userId'; // SQL文を変数に代入
    $stmt = $pdo->prepare($sql); // SQL文実行する前にprepareメソッドを実行
    $stmt->execute(['userId' => $userId]); // 値を代入してSQL文を実行
    // 結果を連想配列で取得
    $results = $stmt->fetchALL(PDO::FETCH_ASSOC);
    if ($results) {
        echo '<br>' . 'postが見つかりました' . '<br>';
        foreach ($results as $result) {
            echo $result['post_id'] . ' ,' . $result['user_id'] . ' ,' . $result['post_title'] . ' ,' . $result['post_text'] . ' ,'; // sql文の結果を出力
            echo $result['post_good'] . ' ,' . $result['post_code'] . ' ,' . $result['post_image_name'] . ' ,' . $result['post_image'] . ' ,' . $result['post_time'];
            echo '<br>';
        }
    } else {
        echo '<br>' . 'postが見つかりませんでした' . '<br>';
    }

    // questionsテーブルからユーザーIDに紐づくデータを取得
    $sql = 'select * from questions where user_id = :userId'; // SQL文を変数に代入
    $stmt = $pdo->prepare($sql); // SQL文実行する前にprepareメソッドを実行
    $stmt->execute(['userId' => $userId]); // 値を代入してSQL文を実行
    // 結果を連想配列で取得
    $results = $stmt->fetchALL(PDO::FETCH_ASSOC);
    if ($results) {
        echo '<br>' . 'questionが見つかりました' . '<br>';
        foreach ($results as $result) {
            echo $result['question_id'] . ' ,' . $result['user_id'] . ' ,' . $result['question_title'] . ' ,' . $result['question_text'] . ' ,'; // sql文の結果を出力
            echo $result['question_good'] . ' ,' . $result['question_code'] . ' ,' . $result['question_image_name'] . ' ,' . $result['question_image'] . ' ,' . $result['question_time'];
            echo '<br>';
        }
    } else {
        echo 'questionが見つかりませんでした' . '<br>';
    }
    ?>

    <?php
    $pdo = null;    // データベース接続を切断
    ?>
</body>

</html>