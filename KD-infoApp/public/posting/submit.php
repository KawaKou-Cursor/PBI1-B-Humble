<?php
session_start();

// データベース接続
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "projectDB";
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続エラーの確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// フォームからのデータを取得
$title = $_POST['title'];
$code = $_POST['code'];
$description = $_POST['description'];
$language = $_POST['language']; // 追加

// SQL文を作成して実行
$sql = "INSERT INTO projects (title, code, description, language) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $title, $code, $description, $language);
$stmt->execute();

// 実行結果を確認
if ($stmt->affected_rows > 0) {
    $_SESSION['post_success'] = true;
} else {
    $_SESSION['post_success'] = false;
}

// 接続を閉じる
$stmt->close();
$conn->close();

// index.php にリダイレクト
header("Location: index.php");
exit;
?>
