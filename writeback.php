<?php
session_start();

if (!isset($_SESSION['playerId'])) {
    echo "玩家 ID 未提供";
    exit;
}

$playerId = $_SESSION['playerId'];

if (isset($_GET['reset'])) {
    session_unset();
    header("Location: play.php");
    exit;
}

if (!empty($_SESSION['history'])) {
    $history = implode(" ", $_SESSION['history']);

    $conn = mysqli_connect("localhost", "root", "", "final");

    if (!$conn) {
        die("資料庫連接失敗" . mysqli_connect_error());
    }

    $query = "INSERT INTO guess (userID, history) VALUES ('$playerId', '$history')";
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>資料庫寫入</title>
    </head>
    <body>
        <h1 style='text-align: center; font-size: 36px;'>
        <?php
            if (mysqli_query($conn, $query)) {
                echo "資料已記錄回資料庫了喲！";
            } else {
                echo "資料插入失敗！";
            }
        ?>
        </h1>
        <div style='text-align: center; width: 500px; margin: 0 auto;'>
        <a href="play.php?reset=true">返回遊戲</a>
        </div>
    </body>
    </html>
<?php
    mysqli_close($conn);
}

$_SESSION['history'] = array();
?>
