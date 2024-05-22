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
    <title>Question Creation KD-info</title>
    <!-- TailwindCSSに必要なリンク -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome CSSを追加 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- タブのアイコン設定(相対パスは非表示になるバグがあるので絶対パスで指定中) -->
    <link rel="icon" type="image/png" href="\PBI1-B-Humble\KD-infoApp\public\Components\static\AppIcon\KD-info2.png">
    <style>
        body {
            background-color: #111;
        }
    </style>
</head>

<?php
// ヘッダーのインポート
include '../Components/src/renderHeader.php';
renderHeader('question');
?>

<body class="bg-black text-white">
    <!-- 投稿一覧ボタン -->
    <form action="index.php" class="pl-1 pt-1">
        <input type="submit" value="質問一覧へ" class="bg-black border border-white hover:bg-white hover:text-black rounded px-2 py-1">
    </form>

    <div class="text-xl max-w-5xl mx-auto px-4 pt-0">新しい質問投稿フォーム</div>

    <!-- form バックグランド設定 -->
    <div class="max-w-5xl mx-auto px-4 pt-5 pb-5">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="space-y-4">

            <!-- Question Title Entry -->
            <div class="pb-1">
                <label class="text-white">質問のタイトル</label>
                <input type="text" name="title" required class="w-full bg-black rounded border-2 border-white text-3xl px-2 py-2">
            </div>

            <div class="input-field">
                <label class="text-white">質問の詳細内容</label>
                <textarea name="text" rows="16" required class="w-full bg-black rounded border-2 border-white"></textarea>
            </div>

            <!-- Posting Description Entry -->
            <!-- <label for="description" class="pl-0">作品の詳細説明</label>
        <textarea id="description" name="description" rows="4" required class="w-full bg-black rounded border-2 border-white"></textarea> -->

            <!-- <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">投稿</button> -->
            <!-- 投稿ボタン -->
            <div class="flex justify-center pt-2">
                <input type="submit" value="質問を投稿" class="bg-black border border-white hover:bg-white hover:text-black rounded px-4 py-2">
            </div>
        </form>
    </div>

</body>

</html>