<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Profile KD-info</title>
  <!-- TailwindCSSに必要なリンク -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <!-- Font Awesome CSSを追加 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- タブのアイコン設定(相対パスは非表示になるバグがあるので絶対パスで指定中) -->
  <link rel="icon" type="image/png" href="\PBI1-B-Humble\KD-infoApp\public\Components\static\AppIcon\KD-info2.png">
  <link rel="stylesheet" href="../Components/static/background.css">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #111;
      margin: 0;
      padding: 0;
      color: #fff;
    }

    .profile-container {
      max-width: 900px;
      margin: 50px auto;
      background-color: #111;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(255, 255, 255, 0.1);
      position: relative;
      /* コンテナ内での絶対位置指定のために必要 */
    }

    .profile-image {
      width: 180px;
      height: 180px;
      border-radius: 50%;
      object-fit: cover;
      margin: 0 auto 20px;
      display: block;
      border: 2px solid #000080;
    }

    .profile-name-container {
      text-align: center;
      /* 名前を中央揃えにする親要素 */
    }

    .profile-name {
      font-size: 30px;
      font-weight: bold;
      color: #fff;
      background-color: #2c2c2c;
      padding: 10px;
      border-radius: 10px;
      display: inline-block;
    }

    .profile-title {
      font-size: 20px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 15px;
    }

    .profile-details {
      font-size: 18px;
      margin-bottom: 10px;
      line-height: 1.6;
      text-align: center;
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
      color: #fff;
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

    .data-title {
      font-size: 16px;
      color: #4169e1;
      margin-bottom: 10px;
    }

    .date,
    .work-date,
    .like-count {
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
      position: absolute;
      /* ページに対しての相対位置 */
      top: 10px;
      /* プロファイルコンテナの上から10pxの位置 */
      right: 0;
      /* 右端に配置 */
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

<body class="custom-gradient2">
  <?php
  session_start(); // セッション開始
  include '..\Components\src\renderHeader.php';
  renderHeader('question');
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
  // session_user_nameがセットされていれば取得
  if (isset($_SESSION['session_user_name'])) {
    $user_name = $_SESSION['session_user_name'];
  }

  // prositeに接続
  $answer_conn = new mysqli($answer_servername, $answer_username, $answer_password, $answer_dbname);
  // 接続をチェック
  if ($answer_conn->connect_error) {
    die("データベースへの接続失敗: " . $answer_conn->connect_error);
  }

  // ユーザー名からユーザー情報を取得
  $sql = 'SELECT * FROM users WHERE user_name = ?'; // SQL文を変数に代入
  $stmt = $answer_conn->prepare($sql); // SQL文実行する前にprepareメソッドを実行
  $stmt->bind_param('s', $user_name); // プレースホルダーに値をバインド
  $stmt->execute(); // クエリを実行
  $result = $stmt->get_result(); // 結果を取得
  if ($result === false) {
    die("結果の取得に失敗しました: " . $stmt->error);
  }

  $row = $result->fetch_assoc(); // 結果を連想配列で取得
  if ($row) {
    $user_name = $row['user_name'];  // ユーザー名があれば取得
    $user_id = $row['user_id'];  // ユーザーのuser_idがあれば取得
    $profile_title = $row['profile_title'];   // ユーザーのプロフィールタイトルがあれば取得
    $profile_text = $row['profile_text'];   // ユーザーのプロフィールテキストがあれば取得
  } else {
    echo 'ユーザーが見つかりませんでした';
  }
  ?>

  <div class="profile-container border-2 border-white">
    <!-- <button class="edit-profile-button" onclick="location.href='edit_profile.html'">プロフィール編集</button> -->
    <img src="../Components/static/image/aikon.png" alt="Profile Image" class="profile-image">
    <?php
    echo '<div class="profile-name-container">'; // 名前を中央揃えにする親要素
    echo '<div class="profile-name">';
    echo $user_name;
    echo '</div>';
    echo '</div>';

    echo '<div class="profile-title">';
    echo $profile_title;
    echo '</div>';
    echo '<div class="profile-details">';
    echo $profile_text;
    echo '</div>';

    echo "<div class='section'>";
    echo "<div class='section-title'>投稿作品</div>";
    echo "<div class='section-text'>あなたが投稿した作品の投稿一覧です。</div>";
    echo "<div class='list-container'>";
    echo "<ul class='works-list'>";

    $answer_conn->close(); // 接続を閉じる

    // projectDBに接続
    $post_conn = new mysqli($post_servername, $post_username, $post_password, $post_dbname);
    // 接続をチェック
    if ($post_conn->connect_error) {
      die("データベースへの接続失敗: " . $post_conn->connect_error);
    }

    // projectDBからデータを取得
    $sql = "SELECT * FROM projects WHERE user_id = ?";
    $stmt = $post_conn->prepare($sql); // SQL文実行する前にprepareメソッドを実行
    $stmt->bind_param('s', $user_id); // プレースホルダーに値をバインド
    $stmt->execute(); // クエリを実行
    $result = $stmt->get_result(); // 結果を取得
    if ($result === false) {
      die("結果の取得に失敗しました: " . $stmt->error);
    }

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo "<li>";
        echo "<a href='#'><i class='fas fa-globe'></i> " . htmlspecialchars($row["title"]) . "</a>";
        echo "<span class='work-date'>投稿日：" . htmlspecialchars($row["created_at"]) . "</span>";
        echo "<a href='delete.php?id=" . $row['id'] . "' class='delete-link' onclick=\"return confirm('本当にこの投稿を削除しますか？');\">削除</a>";
        echo "</li>";
      }
    } else {
      echo "<li>投稿がありません。</li>";
    }

    echo "</ul>";
    echo "</div>";
    echo "</div>";

    // projectDBの接続を閉じる
    $post_conn->close();

    // prositeに再接続
    $answer_conn = new mysqli($answer_servername, $answer_username, $answer_password, $answer_dbname);
    // 接続をチェック
    if ($answer_conn->connect_error) {
      die("データベースへの接続失敗: " . $answer_conn->connect_error);
    }

    echo "<div class='section'>";
    echo "<div class='section-title'>質問</div>";
    echo "<div class='section-text'>あなたの質問一覧です。</div>";
    echo "<div class='list-container'>";
    echo "<ul class='likes-list'>";


    // 他のユーザー名からユーザー情報を取得
    $sql = 'SELECT * FROM questions WHERE user_id = ?'; // SQL文を変数に代入
    $stmt = $answer_conn->prepare($sql); // SQL文実行する前にprepareメソッドを実行
    $stmt->bind_param('s', $user_id); // プレースホルダーに値をバインド
    $stmt->execute(); // クエリを実行
    $result = $stmt->get_result(); // 結果を取得
    if ($result === false) {
      die("結果の取得に失敗しました: " . $stmt->error);
    }

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo "<li>";
        echo "<a href='#'><i class='fas fa-question-circle'></i> " . htmlspecialchars($row["question_title"]) . "</a>";
        echo "<span class='like-count'><i class='fas fa-heart'></i> " . htmlspecialchars($row["question_good"]) . "</span>";
        echo "<span class='date'>" . htmlspecialchars($row["question_time"]) . "</span>";
        echo "<a href='delete_question.php?id=" . $row['question_id'] . "' class='delete-link'' onclick=\"return confirm('本当にこの質問を削除しますか？');\">削除</a>";
        echo "</li>";
      }
    } else {
      echo "<li>質問がありません。</li>";
    }

    echo "</ul>";
    echo "</div>";
    echo "</div>";

    echo "<div class='section'>";
    echo "<div class='section-title'>回答</div>";
    echo "<div class='section-text'>あなたの回答一覧です。</div>";
    echo "<div class='list-container'>";
    echo "<ul class='replies-list'>";

    // repliesテーブルからデータを取得
    $sql = "SELECT * FROM replies WHERE user_id = ?"; // SQL文を変数に代入
    $stmt = $answer_conn->prepare($sql); // SQL文実行する前にprepareメソッドを実行
    $stmt->bind_param('s', $user_id); // プレースホルダーに値をバインド
    $stmt->execute(); // クエリを実行
    $result = $stmt->get_result(); // 結果を取得

    // 質問に対する回答元の質問タイトルを取得するためのSQL文
    $sql_second = "SELECT question_title FROM questions WHERE question_id = ? ";
    $stmt = $answer_conn->prepare($sql_second); // SQL文実行する前にprepareメソッドを実行

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $stmt->bind_param('s', $row["question_id"]); // プレースホルダーに値をバインド
        $stmt->execute(); // クエリを実行
        $result_second = $stmt->get_result(); // 結果を取得
        $row_second = $result_second->fetch_assoc();
        echo "<li>";
        echo "<div class='data-title'>回答元：" . $row_second["question_title"] . "</div>";
        echo "<p>" . htmlspecialchars($row["reply_text"]) . "</p>";
        echo "<span class='date'>" . htmlspecialchars($row["reply_time"]) . "</span>";
        echo "<a href='delete_reply.php?id=" . $row['reply_id'] . "' class='delete-link'' onclick=\"return confirm('本当にこの回答を削除しますか？');\">削除</a>";
        echo "</li>";
      }
    } else {
      echo "<li>回答がありません。</li>";
    }

    echo "</ul>";
    echo "</div>";
    echo "</div>";

    // prositeの接続を閉じる
    $answer_conn->close();
    ?>
  </div>
</body>

</html>