<?php
session_start();

// DB connect
$dbname = "prosite";
$servername = "localhost";
$username = "kobe";
$password = "denshi";

$conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// パラメータ検証と質問の詳細の取得
if (!isset($_GET['id']) || empty($_SESSION['session_user_name'])) {
    header('Location: ../user/login.php'); // Redirect to login if not logged in or ID is not set
    exit;
}
$question_id = $_GET['id'];
$stmt = $conn->prepare("SELECT q.*, u.user_name FROM questions q JOIN users u ON q.user_id = u.user_id WHERE q.question_id = :question_id");
$stmt->bindParam(':question_id', $question_id);
$stmt->execute();
$question = $stmt->fetch();

if (!$question) {
    echo "<p>質問が見つかりませんでした。</p>";
    exit;
}

// 質問に対するLike機能の処理
if (isset($_POST['like'])) {
    // いいね数の更新処理
    $stmt = $conn->prepare("UPDATE questions SET question_good = question_good + 1 WHERE question_id = :question_id");
    $stmt->bindParam(':question_id', $question_id);
    $stmt->execute();
    header("Refresh:0");
}

// 回答の取得
$reply_stmt = $conn->prepare("SELECT r.*, u.user_name FROM replies r JOIN users u ON r.user_id = u.user_id WHERE r.question_id = :question_id ORDER BY r.reply_time");
$reply_stmt->bindParam(':question_id', $question_id);
$reply_stmt->execute();
$replies = $reply_stmt->fetchAll();

// 回答に対するLike機能の処理
if (isset($_POST['like_reply'])) {
    $reply_id = $_POST['reply_id'];

    // いいね数を更新
    $stmt = $conn->prepare("UPDATE replies SET reply_good = reply_good + 1 WHERE reply_id = :reply_id");
    $stmt->bindParam(':reply_id', $reply_id);
    $stmt->execute();

    // ページを再読み込みして結果を反映
    header("Location: {$_SERVER['PHP_SELF']}?id={$question_id}");
}


// 回答送信の処理
if (isset($_POST['submit_reply'])) {
    $user_name = $_SESSION['session_user_name'];
    $reply_text = $_POST['reply_text'];

    // user_idの取得
    $user_stmt = $conn->prepare("SELECT user_id FROM users WHERE user_name = :user_name");
    $user_stmt->bindParam(':user_name', $user_name);
    $user_stmt->execute();
    $user = $user_stmt->fetch();

    if ($user) {
        $user_id = $user['user_id'];

        // 回答をrepliesテーブルに挿入
        $reply_stmt = $conn->prepare("INSERT INTO replies (user_id, question_id, reply_text, reply_time) VALUES (:user_id, :question_id, :reply_text, NOW())");
        $reply_stmt->bindParam(':user_id', $user_id);
        $reply_stmt->bindParam(':question_id', $question_id);
        $reply_stmt->bindParam(':reply_text', $reply_text);
        $reply_stmt->execute();

        header("Location: questionDetail.php?id=$question_id"); // ページをリフレッシュ
    } else {
        echo "<p>ユーザー情報の取得に失敗しました。</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Details KD-info</title>
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

<body>
    <h2 class="text-white text-xl max-w-5xl mx-auto pt-5 px-4">質問詳細:</h2>
</body>

<body class="bg-black text-white">
    <div class="max-w-5xl mx-auto pt-5 px-4">
        <!-- 質問タイトルとメタデータ -->
        <div class="bg-gray-800 p-5 rounded-md shadow">
            <h1 class="text-2xl font-bold text-white mb-2"><?php echo htmlspecialchars($question['question_title']); ?></h1>
            <!-- 質問者表示とそのユーザーのプロフィールに遷移するボタン表示 -->
            <div>
                <div class="text-sm text-gray-500">質問者:
                    <a href="\PBI1-B-Humble\KD-infoApp\public\myProfile\otherProfile.php?user_id=<?php echo $question['user_id']; ?>" class="text-blue-500 hover:text-blue-700"><?php echo htmlspecialchars($question['user_name']); ?></a>
                </div>
            </div>
            <div class="flex justify-between items-center text-sm text-gray-500">
                <!-- 質問日表示 -->
                <span>
                    質問日: <?php echo date('Y-m-d', strtotime($question['question_time'])); ?>
                </span>
                <div class="flex items-center">
                    <!-- イイネボタンとイイネ数表示 -->
                    <form method="post" class="">
                        <span class="mr-2">
                            <button name="like" class="fas fa-heart"></button>
                            <?php echo $question['question_good']; ?>
                        </span>
                    </form>
                    <!-- コメント数表示 -->
                    <span><i class="fas fa-comment"></i> <?php echo count($replies); ?></span>
                </div>
            </div>
            <!-- 質問詳細内容表示コンテナ -->
            <div class="bg-gray mt-4 p-5 rounded-md shadow">
                <p class="text-white">質問内容</p>
                <p class="text-white"><?php echo nl2br(htmlspecialchars($question['question_text'])); ?></p>
            </div>
        </div>
    </div>
</body>

<body>
    <!-- 回答モーダルウィンドウ起動ボタン -->
    <div class="flex justify-center">
        <button onclick="document.getElementById('replyModal').style.display='block'" class="bg-black border border-white hover:bg-blue-900 hover:border-white text-white py-2 px-4 rounded mt-4 transition-colors duration-200">
            回答する
        </button>
    </div>

    <!-- 回答モーダルウィンドウ -->
    <div id="replyModal" class="hidden fixed inset-0 flex items-center justify-center pt-20 bg-gray-600 bg-opacity-50">
        <div class="relative bg-black border-2 border-white rounded-md overflow-hidden shadow-lg">
            <div class="text-center p-5">
                <h3 class="text-lg leading-6 font-medium text-white">質問に回答する</h3>
                <form method="post" class="mt-2">
                    <textarea name="reply_text" class="w-full p-2 text-white bg-black border border-white rounded-md h-60" placeholder="回答を入力してください"></textarea>
                    <button type="submit" name="submit_reply" class="mt-3 bg-black border border-white hover:bg-blue-900 hover:border-white text-white font-bold py-2 px-4 rounded transition-colors duration-200">送信</button>
                </form>
            </div>
        </div>
    </div>

    <h2 class="text-white text-xl max-w-5xl mx-auto pt-5 px-4">回答:</h2>

    <!-- 回答表示 -->
    <div class="max-w-5xl mx-auto pt-5 px-4">
        <?php if ($replies) : ?>
            <?php foreach ($replies as $reply) : ?>
                <div class="bg-gray-800 text-white p-4 rounded mb-3 shadow">
                    <p><strong><?php echo htmlspecialchars($reply['user_name']); ?></strong> (<?php echo date('Y-m-d H:i', strtotime($reply['reply_time'])); ?>)</p>
                    <p><?php echo htmlspecialchars($reply['reply_text']); ?></p>
                    <!-- 回答に対するいいねボタンといいね数表示 -->
                    <form action="" method="post">
                        <input type="hidden" name="reply_id" value="<?php echo $reply['reply_id']; ?>">
                        <!-- 回答に対するlike表示 -->
                        <button type="submit" name="like_reply" class="fas fa-heart"></button>
                        <span><?php echo $reply['reply_good'] ?: 0; ?></span>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="text-white">まだ回答がありません。</p>
        <?php endif; ?>
    </div>

    </div>
</body>

<script>
    // モーダルウィンドウの外をクリックしたときに閉じる
    window.onclick = function(event) {
        if (event.target == document.getElementById('replyModal')) {
            document.getElementById('replyModal').style.display = "none";
        }
    }
</script>

</html>