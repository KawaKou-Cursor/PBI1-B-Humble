<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts Details KD-info</title>
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

        .returnButton,
        .replyButton {
            position: fixed;
            bottom: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: 1px solid #fff;
            border-radius: 5px;
            cursor: pointer;
        }

        .replyButton {
            left: 48%;
            bottom : 1px;
        }

        .replyForm {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .replyForm textarea {
            width: 100%;
            height: 100px;
            resize: none;
            margin-bottom: 10px;
        }

        .replyForm button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }

        .cancelButton {
            background-color: #dc3545;
        }

        .replyList ul {
            list-style-type: none;
            padding: 0;
        }

        .replyList li {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .code-container {
            display: flex;
            margin-bottom: 20px;
        }

        .code-language {
            background-color: #f1f1f1;
            padding: 10px;
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
            margin-right: 5px;
        }

        .code-content {
            background-color: #f9f9f9;
            padding: 10px;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
            overflow-x: auto;
        }

        pre {
            margin: 0;
        }
    </style>
</head>

<?php
// ヘッダーのインポート
include '../Components/src/renderHeader.php';
renderHeader('question');
?>

<body>
    <!-- 投稿一覧ボタン -->
    <form action="index.php" class="pl-1 pt-1">
        <input type="submit" value="投稿一覧へ" class="text-white bg-black border border-white hover:bg-white hover:text-black rounded px-2 py-1">
    </form>

    <div class="max-w-5xl mx-auto pt-0 px-4">
        <h2 class="text-white text-xl pb-4">投稿詳細</h2>
        <?php
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

        // URLパラメータから投稿IDを取得
        $id = $_GET['id'];

        // 該当の投稿をデータベースから取得
        $sql = "SELECT * FROM projects WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false || $result->num_rows === 0) {
            echo "<p class='text-white'>投稿が見つかりませんでした</p>";
        } else {
            $row = $result->fetch_assoc();
            echo "<div class='bg-gray-800 p-5 rounded-md shadow'>";
            echo "<h1 class='text-2xl font-bold text-white mb-2'>" . htmlspecialchars($row["title"]) . "</h1>";
            echo "<p class='text-sm text-gray-500'>投稿日時: " . date('Y-m-d', strtotime($row["created_at"])) . "<p>";
            // if ($row["average_rating"] !== null) {
            //     echo "評価: " . number_format($row["average_rating"], 1) . " / 5.0 <br>";
            // }
            echo "<div class='code-container'>";
            echo "<div class='code-language'>" . htmlspecialchars($row["language"]) . "</div>";
            echo "<div class='code-content'><pre>" . htmlspecialchars($row["code"]) . "</pre></div>";
            echo "</div>";
            echo "<p class='text-white'>作品の説明:</p>";
            echo "<p class='text-white'>" . htmlspecialchars($row["description"]) . "</p>";

            echo '<div class="text-white">評価: ';
            for ($i = 1; $i <= 5; $i++) {
                echo "<label><input type='radio' name='rating' value='$i'/> $i </label>";
            }
            echo '<button onclick="submitRating()" class="ml-2 bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded">評価送信</button>';
            echo '</div>';

            // 返信一覧を取得して表示
            $sql = "SELECT * FROM replies WHERE project_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $replies = $stmt->get_result();

            echo "<div class='replyList'>";
            if ($replies->num_rows > 0) {
                echo "<h2 class='text-white text-xl mt-4'>返信一覧</h2>";
                echo "<ul>";
                while ($reply = $replies->fetch_assoc()) {
                    echo "<li class='bg-gray-800 text-white p-4 rounded mb-3 shadow'>" . htmlspecialchars($reply["content"]) . " <button class='ml-2 bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded' onclick='deleteReply(" . $reply["id"] . ")'>削除</button></li>";
                }
                echo "</ul>";
            } else {
                echo "<p class='text-white'>返信はありません</p>";
            }
            echo "</div>";
            echo "</div>";
        }

        $conn->close();
        ?>

        <!-- 返信フォーム -->
        <div class="replyForm" id="replyForm">
            <textarea id="replyContent" placeholder="返信内容を入力してください"></textarea>
            <button onclick="submitReply()">返信する</button>
            <button class="cancelButton" onclick="closeReplyForm()">キャンセル</button>
        </div>

        <!-- 返信ボタン -->
        <div>
            <button class="replyButton bg-black border border-white hover:bg-blue-900 hover:border-white text-white py-2 px-4 rounded mt-4 transition-colors duration-200" onclick="openReplyForm()">返信する</button>
        </div>

    </div>

    <!-- JavaScript -->
    <script>
        function returnHome() {
            window.location.href = "index.php";
        }

        function submitRating() {
            var radios = document.getElementsByName('rating');
            var ratingValue;
            for (var i = 0; i < radios.length; i++) {
                if (radios[i].checked) {
                    ratingValue = radios[i].value;
                    break;
                }
            }
            if (ratingValue) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "submit_rating.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if (this.status == 200) {
                        console.log('Rating submitted successfully');
                    }
                }
                xhr.send("id=<?php echo $id; ?>&rating=" + ratingValue);
            }
        }

        function openReplyForm() {
            document.getElementById('replyForm').style.display = 'block';
        }

        function closeReplyForm() {
            document.getElementById('replyForm').style.display = 'none';
        }

        function submitReply() {
            var content = document.getElementById('replyContent').value;
            if (content.trim() !== "") {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "submit_reply.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if (this.status == 200) {
                        console.log('Reply submitted successfully');
                        // ページをリロードして返信を表示
                        location.reload();
                    }
                }
                xhr.send("id=<?php echo $id; ?>&content=" + encodeURIComponent(content));
            }
        }

        function deleteReply(replyId) {
            if (confirm("本当に削除しますか？")) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "delete_reply.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if (this.status == 200) {
                        console.log('Reply deleted successfully');
                        // ページをリロードして返信を更新
                        location.reload();
                    }
                }
                xhr.send("replyId=" + replyId);
            }
        }
    </script>
</body>

</html>