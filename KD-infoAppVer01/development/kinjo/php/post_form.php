<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プログラム作品投稿フォーム</title>
</head>
<body>
    <h1>プログラム作品投稿フォーム</h1>
    <form action="submit.php" method="post">
        <label for="title">作品の題名:</label><br>
        <input type="text" id="title" name="title" required><br><br>
        
        <label for="code">プログラムコード:</label><br>
        <textarea id="code" name="code" rows="10" required></textarea><br><br>
        
        <label for="description">作品の説明:</label><br>
        <textarea id="description" name="description" rows="5" required></textarea><br><br>
        
        <input type="submit" value="投稿">
    </form>
</body>
</html>
