<?php
// データベース接続情報
$servername = "localhost";
$username = "kobe";
$password = "denshi";
$dbname = "prosite";

// データベース接続の確立
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続エラーチェック
if ($conn->connect_error) {
    die("データベースへの接続失敗: " . $conn->connect_error);
}

// URLパラメータからquestion_idを取得
if (isset($_GET['id'])) {
    $reply_id = $_GET['id'];

    // トランザクションを開始
    $conn->begin_transaction();

    try {
        // 返信を削除するSQLクエリ
        $sql_delete_replies = "DELETE FROM replies WHERE reply_id = ?";
        $stmt_replies = $conn->prepare($sql_delete_replies);
        $stmt_replies->bind_param("i", $reply_id);
        $stmt_replies->execute();
        $stmt_replies->close();

        // コミット
        $conn->commit();

        // 成功したらindex.phpにリダイレクト
        header("Location: index.php");
        exit;
    } catch (mysqli_sql_exception $exception) {
        // エラーが発生した場合はロールバック
        $conn->rollback();

        // エラーメッセージを表示
        echo "エラー: " . $exception->getMessage();
    }
} else {
    echo "エラー: 無効なIDです。";
}

// データベース接続を閉じる
$conn->close();
