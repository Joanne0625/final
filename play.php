<?php
session_start();

function num_rand($min, $max, $num) {
    $range = range($min, $max);
    $count = count($range);
    $return = array();
    
    for ($i = 0; $i < $num; $i++) {
        $rand = rand(0, $count - 1);
        $return[] = $range[$rand];
        unset($range[$rand]);
        $range = array_values($range);
        $count--;
    }
    
    return $return;
}


if (isset($_GET['reset'])) {
    session_unset();
    header("Location: play.php");
    exit;
}

if (!isset($_SESSION['number'])) {
    $_SESSION['number'] = implode('', num_rand(0, 9, 4));
}

if (!isset($_SESSION['history'])) {
    $_SESSION['history'] = array();
}

if (isset($_POST['playerId'])) {
    $playerId = $_POST['playerId'];
} elseif (isset($_SESSION['playerId'])) {
    $playerId = $_SESSION['playerId'];
} else {
    $playerId = '';
}

$error = '';

if (isset($_POST['submit'])) {
    $_SESSION['playerId'] = $playerId;
    $guess = $_POST['guess'];

    if (empty($guess)) {
        $error = '請輸入數字';
    } else {
        $result = '';
        $a = 0;
        $b = 0;
        for ($i = 0; $i < 4; $i++) {
            if ($_SESSION['number'][$i] == $guess[$i]) {
                $result .= 'A';
                $a++;
            } elseif (strpos($_SESSION['number'], $guess[$i]) !== false) {
                $result .= 'B';
                $b++;
            }
        }
        $_SESSION['result'] = $result;
        $_SESSION['history'][] = $guess . '==>' . $a . 'A' . $b . 'B';
    }
}

if (isset($_SESSION['result'])) {
    $result = $_SESSION['result'];
    $a = substr_count($result, 'A');
    $b = substr_count($result, 'B');
    unset($_SESSION['result']);
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>猜數字</title>
</head>
<body>
<h1 style='text-align: center; font-size: 36px; color: red;'>猜數字遊戲</h1>
<div style='text-align: center; width: 800px; margin: 0 auto; background-color: #f7ba7d;'>
<form action="play.php" method="post">
    玩家 ID:
    <input type="text" id="playerId" name="playerId" value="<?php echo $playerId; ?>"><br>
    已使用歷程:
    <input type="text" id="guess" name="guess">
    <input type="submit" name="submit" value="猜猜看">
    <span style="color: red;"><?php echo $error; ?></span> <!-- Display error message -->
</form>
<br>
<textarea id="history" rows="24" cols="80">
<?php
$guesstext = $_SESSION['history'];
$guesscount = count($guesstext);

for ($i = 0; $i < $guesscount; $i++) {
    echo $guesstext[$i] . "\n";
}
?>
</textarea>
<br>
<a href="writeback.php">寫回資料庫</a>
<a href="play.php?reset=true">重設</a>
</div>
<br>
<div style='text-align: center; width: 500px; margin: 0 auto;'>
    <?php
    $num = str_split($_SESSION['number']);
    for ($i = 0; $i < 4; $i++) {
        echo '<img src="imgs/' . $num[$i] . '.png">';
    }
    ?>
</div>
</body>
</html>
