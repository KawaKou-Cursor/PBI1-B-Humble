<?php
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "projectDB";
$projectId = $_GET['id'];

// データベース接続
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続エラーの確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// トランザクション開始
$conn->begin_transaction();

try {
    // 返信を削除
    $sql = "DELETE FROM replies WHERE project_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $projectId);
    $stmt->execute();
    $stmt->close();

    // 評価を削除
    $sql = "DELETE FROM ratings WHERE project_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $projectId);
    $stmt->execute();
    $stmt->close();

    // 投稿を削除
    $sql = "DELETE FROM projects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $projectId);
    $stmt->execute();
    $stmt->close();

    // トランザクションをコミット
    $conn->commit();

    // リダイレクト処理（投稿一覧ページへ）
    header("Location: index.php"); // ここで index.php は投稿一覧ページのURLに置き換えてください
    exit;
} catch (Exception $e) {
    // エラーが発生した場合はロールバック
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>
