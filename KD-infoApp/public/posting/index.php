<?php
session_start();

// DB connect(Kawando)
// $dbname = "prosite";
// $servername = "localhost";
// $username = "kobe";
// $password = "denshi";

// DB connect(Kinjo)
// $servername = "localhost";
// $username = "username";
// $password = "password";
// $dbname = "projectDB";

// $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
// $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fetch questions based on sort order
// $sort_order = $_GET['sort'] ?? 'newest'; // Default to newest if not specified
// $sort_query = $sort_order === 'newest' ? "ORDER BY question_time DESC" : ($sort_order === 'oldest' ? "ORDER BY question_time ASC" : "ORDER BY question_good DESC");

// $stmt = $conn->prepare("SELECT q.*, COUNT(r.reply_id) AS comment_count FROM questions q LEFT JOIN replies r ON q.question_id = r.question_id GROUP BY q.question_id $sort_query");
// $stmt->execute();
// $questions = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="ja">
<!-- インポートリンクと表示設定 -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- タブのタイトル -->
    <title>Posts KD-info</title>
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

    <!-- Kinjo-CSS -->
    <style>
        .post {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .post .title {
            flex: 1;
            margin-right: 20px;
            font-size: 18px;
        }

        .post .title a {
            margin-left: 10px;
            text-decoration: none;
            color: #ffffff;
        }

        .post .title a:hover {
            text-decoration: underline;
        }

        .post .info {
            flex: 3;
            font-size: 14px;
        }

        .deleteButton,
        .sortButton {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 5px 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            border-radius: 5px;
            margin-left: 5px;
        }

        .deleteButton {
            background-color: #ff0000;
        }

        /* #postButton {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        } */
        .averageRating {
            color: red;
            font-weight: bold;

        }

        .sortButton.desc:after {
            content: "▲";

        }

        .sortButton.asc:after {
            content: "▼";
        }

        .sortButton.asc {
            background-color: #ff0000;
        }

        /* #searchBar {
            position: absolute;
            top: 10px;
            right: 10px;
        } */
    </style>
</head>

<body>
    <!-- ヘッダーのインポート -->
    <?php
    include '../Components/src/renderHeader.php';
    renderHeader('question');
    ?>

    <!-- 検索バー -->
    <div id="searchBar" class="flex justify-center pt-3 pb-1 pl-60 pr-60">
        <input type="text" id="searchInput" placeholder="投稿を検索..." class="form-input block w-full px-4 py-2 text-black border bg-gray-800 text-gray-300 border-gray-700 focus:border-gray-500 rounded" oninput="filterPosts(this.value)">
    </div>

    <!-- session内で表示順の管理を行うPHPと推測 -->
    <?php
    // ソート状態と言語フィルタの管理
    if (!isset($_SESSION['sort'])) {
        $_SESSION['sort'] = ['field' => 'date', 'order' => 'desc'];
    }
    if (!isset($_SESSION['filter_language'])) {
        $_SESSION['filter_language'] = '';
    }
    if (isset($_GET['change_sort'])) {
        if ($_SESSION['sort']['field'] == $_GET['change_sort']) {
            $_SESSION['sort']['order'] = $_SESSION['sort']['order'] == 'desc' ? 'asc' : 'desc';
        } else {
            $_SESSION['sort'] = ['field' => $_GET['change_sort'], 'order' => 'desc'];
        }
    }
    if (isset($_GET['language'])) {
        $_SESSION['filter_language'] = $_GET['language'];
    }
    ?>

    <!-- 言語ソートのプルダウン表示 -->
    <div class="text-right  text-black">
        <select onchange="window.location.href='?language='+this.value">
            <option value="">すべての言語</option>
            <option value="C" <?php echo $_SESSION['filter_language'] == 'C' ? 'selected' : ''; ?>>C</option>
            <option value="C++" <?php echo $_SESSION['filter_language'] == 'C++' ? 'selected' : ''; ?>>C++</option>
            <option value="Java" <?php echo $_SESSION['filter_language'] == 'Java' ? 'selected' : ''; ?>>Java</option>
            <option value="Python" <?php echo $_SESSION['filter_language'] == 'Python' ? 'selected' : ''; ?>>Python</option>
            <option value="JavaScript" <?php echo $_SESSION['filter_language'] == 'JavaScript' ? 'selected' : ''; ?>>JavaScript</option>
            <option value="HTML" <?php echo $_SESSION['filter_language'] == 'HTML' ? 'selected' : ''; ?>>HTML</option>
            <option value="CSS" <?php echo $_SESSION['filter_language'] == 'CSS' ? 'selected' : ''; ?>>CSS</option>
            <option value="PHP" <?php echo $_SESSION['filter_language'] == 'PHP' ? 'selected' : ''; ?>>PHP</option>
            <option value="Ruby" <?php echo $_SESSION['filter_language'] == 'Ruby' ? 'selected' : ''; ?>>Ruby</option>
            <option value="Swift" <?php echo $_SESSION['filter_language'] == 'Swift' ? 'selected' : ''; ?>>Swift</option>
            <option value="Kotlin" <?php echo $_SESSION['filter_language'] == 'Kotlin' ? 'selected' : ''; ?>>Kotlin</option>
            <option value="Go" <?php echo $_SESSION['filter_language'] == 'Go' ? 'selected' : ''; ?>>Go</option>
            <option value="Rust" <?php echo $_SESSION['filter_language'] == 'Rust' ? 'selected' : ''; ?>>Rust</option>
            <option value="TypeScript" <?php echo $_SESSION['filter_language'] == 'TypeScript' ? 'selected' : ''; ?>>TypeScript</option>
            <option value="SQL" <?php echo $_SESSION['filter_language'] == 'SQL' ? 'selected' : ''; ?>>SQL</option>
            <option value="Perl" <?php echo $_SESSION['filter_language'] == 'Perl' ? 'selected' : ''; ?>>Perl</option>
            <option value="Scala" <?php echo $_SESSION['filter_language'] == 'Scala' ? 'selected' : ''; ?>>Scala</option>
            <option value="Haskell" <?php echo $_SESSION['filter_language'] == 'Haskell' ? 'selected' : ''; ?>>Haskell</option>
            <option value="Erlang" <?php echo $_SESSION['filter_language'] == 'Erlang' ? 'selected' : ''; ?>>Erlang</option>
            <option value="Lua" <?php echo $_SESSION['filter_language'] == 'Lua' ? 'selected' : ''; ?>>Lua</option>
        </select>
        <a href="?change_sort=rating" class="sortButton <?php echo ($_SESSION['sort']['field'] == 'rating' ? $_SESSION['sort']['order'] : 'desc'); ?>">評価順切替</a>
        <a href="?change_sort=date" class="sortButton <?php echo ($_SESSION['sort']['field'] == 'date' ? $_SESSION['sort']['order'] : 'desc'); ?>">更新日切替</a>
    </div>

    <div class="container mx-auto pl-40 pr-40">
        <h1 class="text-white text-xl">投稿一覧</h1>
        <!-- 投稿新規作成画面に遷移するボタン -->
        <a href="post_form.php" class="fixed bottom-10 right-10 bg-gray-500 text-white pr-10 pl-10 pb-2 pt-2 rounded text-lg">
            <i class="fa fa-plus"></i>
        </a>
    </div>

    <div class="container mx-auto pt-3">
        <div id="projectList" class="text-white pl-40 pr-40 ">
            <?php
            $servername = "localhost";
            $username = "username";
            $password = "password";
            $dbname = "projectDB";

            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $field = $_SESSION['sort']['field'];
            $order = $_SESSION['sort']['order'];
            $languageFilter = $_SESSION['filter_language'];
            $languageCondition = $languageFilter ? "WHERE p.language = '$languageFilter'" : '';

            $orderBy = "ORDER BY " . ($field == 'rating' ? "AVG(r.rating)" : "p.created_at") . " $order";

            $sql = "SELECT p.id, LEFT(p.title, 30) AS title, p.created_at, AVG(r.rating) AS average_rating, p.language
                    FROM projects p
                    LEFT JOIN ratings r ON p.id = r.project_id
                    $languageCondition
                    GROUP BY p.id, p.title, p.created_at, p.language
                    $orderBy";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='post bg-gray-800 overflow-hidden shadow-lg rounded flex'>";
                    echo "<div class='title text-white text-2xl font-bold pl-4 flex-grow' style='flex-basis: 70%;'><a href='detail.php?id=" . $row["id"] . "' class='hover:text-blue-400'>" . htmlspecialchars($row["title"]);
                    if (strlen($row["title"]) > 30) {
                        echo "...";
                    }
                    echo "</a></div>";
                    echo "<div class='info text-white text-xs p-4' style='flex-basis: 30%;'>";
                    echo "使用言語: " . htmlspecialchars($row["language"]) . "<br>";
                    echo "投稿日時: " . date('Y-m-d', strtotime($row["created_at"])) . "<br>";
                    if ($row["average_rating"] !== null) {
                        echo "評価: " . number_format($row["average_rating"], 1) . " / 5.0 <br>";
                    }
                    // echo "<button class='deleteButton bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-1 rounded ml-auto' onclick='deleteProject(" . $row["id"] . ")'>削除</button>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>投稿がありません</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>

    <script>
        // 投稿削除用スクリプト
        function deleteProject(id) {
            if (confirm("本当に削除しますか？")) {
                window.location.href = "delete.php?id=" + id;
            }
        }

        // 投稿検索用スクリプト
        function filterPosts(searchTerm) {
            var posts = document.querySelectorAll('.post');
            for (var i = 0; i < posts.length; i++) {
                var title = posts[i].querySelector('.title').textContent.toLowerCase();
                if (title.includes(searchTerm.toLowerCase())) {
                    posts[i].style.display = 'flex';
                } else {
                    posts[i].style.display = 'none';
                }
            }
        }

        // ログイン成功時のダイアログ表示
        <?php if (isset($_SESSION["login_success"]) && $_SESSION["login_success"]) : ?>
            alert("ログインしました");
            <?php unset($_SESSION["login_success"]); ?>
        <?php endif; ?>

        // 投稿完了時のダイアログ表示
        <?php if (isset($_SESSION["post_success"]) && $_SESSION["post_success"]) : ?>
            alert("投稿しました");
            <?php unset($_SESSION["post_success"]); ?>
        <?php endif; ?>

        // パスワード変更成功時のダイアログ表示
        <?php if (isset($_SESSION["password_change_success"])) : ?>
            alert("パスワードが変更されました");
            <?php unset($_SESSION["password_change_success"]); ?>
        <?php endif; ?>
    </script>
</body>

</html>