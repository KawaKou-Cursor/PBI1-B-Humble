<?php
session_start();
// gestユーザーの場合loginフォームに誘導する。
if (empty($_SESSION['session_user_name'])) {
    header('Location: ../user/login.php'); // Redirect to login if not logged in
    exit;
}

// DB接続
$dbname = "prosite";
$servername = "localhost";
$username = "kobe";
$password = "denshi";

$conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



// フォームが送信された時の処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $text = $_POST['text'];

    // セッションからユーザー名を取得して、DBからuser_idを検索
    $user_name = $_SESSION['session_user_name'];
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE user_name = :user_name");
    $stmt->bindParam(':user_name', $user_name);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        $user_id = $user['user_id'];

        // 質問をDBに登録
        $stmt = $conn->prepare("INSERT INTO questions (user_id, question_title, question_text, question_time, question_good) VALUES (:user_id, :title, :text, NOW(), 0)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':text', $text);
        $stmt->execute();

        $_SESSION["question_post_success"] = true; // 質問投稿成功のフラグを設定
        header("Location: index.php"); // 投稿後は質問一覧ページへリダイレクト
        exit;
    } else {
        echo "ユーザーIDが見つかりません。ログイン情報を確認してください。";
    }
}

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $title = $_POST['title'];
//     $text = $_POST['text'];
//     $user_id = $_SESSION['session_user_id']; // Assume user_id is stored in session

//     $stmt = $conn->prepare("INSERT INTO questions (user_id, question_title, question_text, question_time, question_good) VALUES (:user_id, :title, :text, NOW(), 0)");
//     $stmt->bindParam(':user_id', $user_id);
//     $stmt->bindParam(':title', $title);
//     $stmt->bindParam(':text', $text);
//     $stmt->execute();

//     header("Location: index.php");
//     exit;
// }

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Question KD-info</title>
    <!-- TailwindCSSに必要なリンク -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome CSSを追加 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- タブのアイコン設定(相対パスは非表示になるバグがあるので絶対パスで指定中) -->
    <link rel="icon" type="image/png" href="\PBI1-B-Humble\KD-infoApp\public\Components\static\AppIcon\KD-info2.png">
    <style>
        body { background-color: #111; }
    </style>
</head>

<?php
    // ヘッダーのインポート
    include '../Components/src/renderHeader.php';
    renderHeader('question');
?>

<body>
    <div class="container mx-auto p-4">
        <h1 class="text-white text-xl">新しい質問を投稿</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="space-y-4">
            <div class="input-field">
                <label class="text-white">タイトル:</label>
                <input type="text" name="title" required class="block w-full px-3 py-2 rounded-md">
            </div>
            <div class="input-field">
                <label class="text-white">詳細:</label>
                <textarea name="text" required class="block w-full px-3 py-2 rounded-md"></textarea>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">投稿</button>
        </form>
    </div>
</body>

</html>
