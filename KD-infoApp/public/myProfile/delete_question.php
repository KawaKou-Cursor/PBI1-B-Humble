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
    $question_id = $_GET['id'];

    // トランザクションを開始
    $conn->begin_transaction();

    try {
        // 関連する返信を削除するSQLクエリ
        $sql_delete_replies = "DELETE FROM replies WHERE question_id = ?";
        $stmt_replies = $conn->prepare($sql_delete_replies);
        $stmt_replies->bind_param("i", $question_id);
        $stmt_replies->execute();
        $stmt_replies->close();

        // 質問を削除するSQLクエリ
        $sql_delete_question = "DELETE FROM questions WHERE question_id = ?";
        $stmt_question = $conn->prepare($sql_delete_question);
        $stmt_question->bind_param("i", $question_id);
        $stmt_question->execute();
        $stmt_question->close();

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
?>
