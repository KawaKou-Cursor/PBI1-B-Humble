<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>プロフィール</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #333;
      margin: 0;
      padding: 0;
      color: #fff;
    }

    .profile-container {
      max-width: 900px;
      margin: 50px auto;
      background-color: #222;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(255, 255, 255, 0.1);
      position: relative; /* コンテナ内での絶対位置指定のために必要 */
    }

    .profile-image {
      width: 180px;
      height: 180px;
      border-radius: 50%;
      object-fit: cover;
      margin: 0 auto 20px;
      display: block;
      border: 4px solid #4CAF50;
    }

    .profile-name {
      font-size: 28px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 15px;
      color: #4CAF50;
    }

    .profile-details p {
      font-size: 18px;
      margin-bottom: 10px;
      line-height: 1.6;
    }

    .section {
      margin-top: 40px;
      border-top: 1px solid #444;
      padding-top: 30px;
    }

    .section-title {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 15px;
      color: #4CAF50;
    }

    .section-text {
      font-size: 16px;
      color: #aaa;
      margin-bottom: 25px;
      line-height: 1.6;
    }

    .list-container ul {
      list-style-type: none;
      padding: 0;
    }

    .list-container li {
      margin-bottom: 20px;
      padding: 15px;
      border-radius: 10px;
      background-color: #2c2c2c;
      transition: background-color 0.3s ease;
    }

    .list-container li:hover {
      background-color: #1c1c1c;
    }

    .list-container a {
      text-decoration: none;
      color: #ccc;
      font-weight: bold;
      transition: color 0.3s ease;
    }

    .list-container a:hover {
      color: #4CAF50;
    }

    .date, .work-date, .like-count {
      font-size: 14px;
      color: #888;
      margin-top: 8px;
      display: block;
    }

    .fa-heart {
      color: #ff6b6b;
      margin-right: 5px;
    }

    .edit-profile-button {
      position: absolute; /* ページに対しての相対位置 */
      top: 10px; /* プロファイルコンテナの上から10pxの位置 */
      right: 0; /* 右端に配置 */
      background-color: #555;
      color: #fff;
      padding: 8px 15px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .edit-profile-button:hover {
      background-color: #444;
    }
  </style>
</head>
<body>
  <?php
    include '..\Components\src\renderHeader.php';
    renderHeader('question');
  ?>
  <h1>MyProfile表示・編集画面 SPRINT#4で実装予定</h1>
  <div class="profile-container">
    <button class="edit-profile-button" onclick="location.href='edit_profile.html'">プロフィール編集</button>
    <img src="https://via.placeholder.com/180" alt="Profile Image" class="profile-image">
    <div class="profile-name">山田 太郎</div>
    <div class="profile-details">
      <p>年齢：22歳</p>
      <p>クラス：ソフトⅣ</p>
      <p>趣味：旅行、読書</p>
      <p>好きなIT分野：ウェブ開発、人工知能</p>
      <p>自己紹介：こんにちは、山田太郎です。プログラミングと旅行が大好きで、特にウェブ開発と人工知能に興味があります。</p>
    </div>

    <?php
// 投稿一覧のデータベース接続情報
$post_servername = "localhost";
$post_username = "username";
$post_password = "password";
$post_dbname = "projectDB";

// 回答一覧のデータベース接続情報
$answer_servername = "localhost";
$answer_username = "kobe";
$answer_password = "denshi";
$answer_dbname = "prosite";

// 投稿一覧のデータベースに接続
$post_conn = new mysqli($post_servername, $post_username, $post_password, $post_dbname);

// 接続をチェック
if ($post_conn->connect_error) {
    die("データベースへの接続失敗: " . $post_conn->connect_error);
}

echo "<div class='section'>";
echo "<div class='section-title'>作品投稿一覧</div>";
echo "<div class='section-text'>自分が作成した作品の投稿一覧です。</div>";
echo "<div class='list-container'>";
echo "<ul class='works-list'>";

// projectDBからデータを取得
$sql = "SELECT id, title, created_at FROM projects";
$result = $post_conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      echo "<li>";
      echo "<a href='#'><i class='fas fa-globe'></i> " . htmlspecialchars($row["title"]) . "</a>";
      echo "<span class='work-date'>投稿日：" . htmlspecialchars($row["created_at"]) . "</span>";
      echo "<a href='delete.php?id=" . $row['id'] . "' onclick=\"return confirm('本当にこの投稿を削除しますか？');\">削除</a>";
      echo "</li>";
    }
} else {
    echo "<li>投稿がありません。</li>";
}

echo "</ul>";
echo "</div>";
echo "</div>";

// 投稿一覧の接続を閉じる
$post_conn->close();

// 回答一覧のデータベースに接続
$answer_conn = new mysqli($answer_servername, $answer_username, $answer_password, $answer_dbname);

// 接続をチェック
if ($answer_conn->connect_error) {
    die("データベースへの接続失敗: " . $answer_conn->connect_error);
}

echo "<div class='section'>";
echo "<div class='section-title'>いいねがもらえた質問</div>";
echo "<div class='section-text'>他のユーザーから「いいね」をもらった質問や回答の一覧です。</div>";
echo "<div class='list-container'>";
echo "<ul class='likes-list'>";

// questionsテーブルからデータを取得（question_goodが1以上のもの）
$sql = "SELECT question_id, question_title, question_time, question_good FROM questions WHERE question_good >= 1";

$result = $answer_conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<li>";
        echo "<a href='#'><i class='fas fa-question-circle'></i> " . htmlspecialchars($row["question_title"]) . "</a>";
        echo "<span class='like-count'><i class='fas fa-heart'></i> " . htmlspecialchars($row["question_good"]) . "</span>";
        echo "<span class='date'>" . htmlspecialchars($row["question_time"]) . "</span>";
        echo "<a href='delete_question.php?id=" . $row['question_id'] . "' onclick=\"return confirm('本当にこの質問を削除しますか？');\">削除</a>";
        echo "</li>";
    }
} else {
    echo "<li>質問がありません。</li>";
}

echo "</ul>";
echo "</div>";
echo "</div>";

echo "<div class='section'>";
echo "<div class='section-title'>回答一覧</div>";
echo "<div class='section-text'>質問に対する回答の一覧です。</div>";
echo "<div class='list-container'>";
echo "<ul class='replies-list'>";

// repliesテーブルからデータを取得
$sql = "SELECT reply_id, reply_text, reply_time FROM replies";

$result = $answer_conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<li>";
        echo "<p>" . htmlspecialchars($row["reply_text"]) . "</p>";
        echo "<span class='date'>" . htmlspecialchars($row["reply_time"]) . "</span>";
        echo "<a href='delete_reply.php?id=" . $row['reply_id'] . "' onclick=\"return confirm('本当にこの回答を削除しますか？');\">削除</a>";
        echo "</li>";
    }
} else {
    echo "<li>回答がありません。</li>";
}

echo "</ul>";
echo "</div>";
echo "</div>";
