// User
// document.addEventListener('DOMContentLoaded', (event) => {
//     console.log("DOM fully loaded and parsed"); // デバッグログ
//     checkLoginStatus();
// });

// function checkLoginStatus() {
//     console.log("Checking login status..."); // デバッグログ
//     fetch('/PBI1-B-Humble/KD-infoApp/public/Components/src/notice/checkLoginStatus.php')
//         .then(response => {
//             console.log("Response received from checkLoginStatus.php");
//             return response.json();
//         })
//         .then(data => {
//             console.log("Data received:", data); // デバッグログ
//             if (data.loggedIn) {
//                 console.log("Login status detected"); // デバッグログ
//                 showNotification();
//             }
//         })
//         .catch(error => console.error('Error checking login status:', error));
// }

// ログイン時の通知プログラム
function showNotification() {
    // console.log("Showing notification"); // デバッグログ
    const notification = document.createElement('div');
    notification.id = 'login-notification';
    notification.innerHTML = `
        <div class="fixed top-0 left-1/2 transform -translate-x-1/2 p-4 bg-green-500 text-white flex flex-col items-center justify-center rounded-lg shadow-lg animate-slide-down max-w-lg w-full">
            <span class="text-lg">ログインしました</span>
            <button id="notification-close" class="mt-4 bg-green-700 hover:bg-green-900 text-white font-bold py-2 px-6 rounded">OK</button>
        </div>
    `;
    document.body.appendChild(notification);

    document.getElementById('notification-close').addEventListener('click', () => {
        notification.remove();
    });

    setTimeout(() => {
        notification.remove();
    }, 3000);
}