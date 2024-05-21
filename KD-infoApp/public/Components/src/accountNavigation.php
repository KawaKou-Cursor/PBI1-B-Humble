<div class="relative text-white">
    <!-- アカウントボタン -->
    <!-- <button id="account-btn" class="fas fa-user"> -->
    <!-- <button id="account-btn" class="fas fa-user-graduate"> -->
    <!-- <button id="account-btn" class="fas fa-user-tie"> -->
    <button id="account-btn" class="fas fa-user-circle pl-2 pr-2" style="color: white; font-size: 24px;">
    </button>
    <!-- アカウントメニュー -->
    <div id="account-menu" class="hidden absolute right-0 w-48 border border-gray-400 rounded-lg text-left" style="background-color: #111;">
        <!-- ユーザー名表示。セッションにユーザー名があれば表示、なければGuest表示 -->
        <?php $username = isset($_SESSION['session_user_name']) ? $_SESSION['session_user_name'] : 'Guest'; ?>
        <div class="p-2 text-left"><?php echo $username; ?></div>
        <hr class="border-gray-400 mx-auto" style="width: 180px;">
        <?php if ($username !== 'Guest') : ?>
            <!-- ログイン時のメニュー項目 -->
            <a href="../myProfile/index.php" class="block p-0.5 pl-2 text-lg text-left hover hover:bg-gray-900">My Profile</a>
            <a href="../setting/index.php" class="block p-0.5 pl-2 text-lg text-left hover hover:bg-gray-900">Settings</a>
            <hr class="border-gray-400 mx-auto" style="width: 180px;">
            <button id="logout-btn" class="block p-2 pt-1 text-lg text-left hover hover:bg-red-600">Logout 　　　　 　　 </button>
        <?php else : ?>
            <!-- ゲスト時のメニュー項目 -->
            <div class="p-2 text-left">
                <a href="../user/login.php" class="block text-lg hover hover:bg-gray-900">Login</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ログアウト用フォーム -->
<form id="logout-form" action="../user/logout.php" method="post" class="hidden">
    <input type="hidden" name="logout" value="true">
</form>

<!-- ログアウト用モーダル -->
<div id="logout-modal" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-gray-900 flex justify-center items-center hidden">
    <div class="bg-gray-333 p-16 rounded-lg text-white text-2xl text-center border border-#444" style="background-color: #111;">
        <p class="text-3xl mb-6 text-left">ログアウトしてもよろしいでしょうか？</p>
        <div class="flex justify-center">
            <button id="logout-confirm" class="bg-red-600 text-white px-6 py-3 rounded mr-2 text-lg text-left hover hover:bg-red-700">Yes</button>
            <button id="logout-cancel" class="bg-gray-700 text-gray-200 px-6 py-3 rounded text-lg text-left hover hover:bg-gray-900">No</button>
        </div>
    </div>
</div>

<script>
    // アカウントボタンをクリックした時にメニューの表示/非表示を切り替える
    document.getElementById('account-btn').addEventListener('click', function() {
        var menu = document.getElementById('account-menu');
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    });

    // ウィンドウ全体にクリックイベントを設定し、メニュー外をクリックした場合にメニューを閉じる
    window.addEventListener('click', function(e) {
        if (!document.getElementById('account-btn').contains(e.target)) {
            document.getElementById('account-menu').style.display = 'none';
        }
    });

    <?php if (isset($_SESSION['session_user_name'])) : ?>
        // ログアウトボタンをクリックした時にログアウトモーダルを表示する
        document.getElementById('logout-btn').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('logout-modal').style.display = 'flex';
        });

        // モーダル内のNoボタンをクリックした時にモーダルを非表示にする
        document.getElementById('logout-cancel').addEventListener('click', function() {
            document.getElementById('logout-modal').style.display = 'none';
        });

        // モーダル内のYesボタンをクリックした時にログアウト処理を行う
        document.getElementById('logout-confirm').addEventListener('click', function() {
            // フォームをサブミットする
            document.getElementById('logout-form').submit();
        });
    <?php endif; ?>
</script>