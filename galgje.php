<!DOCTYPE html>
<html>
<head>
    <title>Galgje</title>
    <link rel="shortcut icon" href="C:\xampp\htdocs\School\favicon.ico">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Ubuntu&display=swap" rel="stylesheet">
<body>
<div class="container">
<?php
session_start();
if (!empty($_POST)) {
    $_SESSION['status'] = "ingame";
}
if (!isset($_SESSION['status'])) {
    ?>
    <h1>Galgje</h1>
    <form method="POST" action="galgje.php">
        <label id="typeaWord" for="word">Type a word to play with</label> <br>
        <input name="word" type="text">
    </form>
    <form method="POST" action="galgje.php">
        <br><button name="randomWord" value="true" type="text">Random Word!</button>
    </form>
	<?php
}
if (isset($_SESSION['status']) && $_SESSION['status'] == "ingame") {
    ?>
    <form action="kill.php">
        <button type="submit">Give up</button>
    </form>
    <?php
}
if (isset($_SESSION['status']) && $_SESSION['status'] == "ingame") {
    if (isset($_SESSION['lifes']) && $_SESSION['lifes'] == 6) {
        ?><h1>YOU LOST NOOB</h1>
        <form action="kill.php">
            <button type="submit">Try again</button>
        </form>
        <style> body {background-color: red} </style>
        <?php
        exit();
    }
    if (!isset($_SESSION['word']) && isset($_POST['word'])) {
        $_SESSION['word'] = $_POST['word'];
        unset($_POST['word']);
        $_SESSION['lifes'] = 0;
    }
    if (!isset($_SESSION['word']) && isset($_POST['randomWord'])) {
        $_SESSION['word'] = file('random.php')[rand(0, count(file('random.php')) - 1)];
        $_SESSION['word'] = preg_replace('/\s+/', '', $_SESSION['word']);
        unset($_POST['randomWord']);
        $_SESSION['lifes'] = 0;
    }
    ?>
    <h2>Select a letter</h2>
    <form method="POST" action="galgje.php">
    <?php
    $letters = range("A", "Z");
    array_unshift($letters, "");
    unset($letters[0]);
    if ($_SESSION['lifes'] <= 6 && isset($_POST['letter'])) {
        $_SESSION['letter'][] = $_POST['letter'];
        $_SESSION['guessed'][] = end($_SESSION['letter']);
        ?><span class="guessed">Already guessed: </span><?php
foreach ($_SESSION['guessed'] as $key => $value) {
    ?><span class="guessed"><?=$value . ", "?></span><?php
}?><br><?php
    }
    if (isset($_SESSION['guessed'])) {
        $lastGuessed = end($_SESSION['guessed']);
        $_SESSION['letterPos'][] = ord(strtoupper($lastGuessed)) - ord('A') + 1;
    }
    if (isset($_SESSION['letterPos'])) {
        foreach ($_SESSION['letterPos'] as $letterPos) {
            unset($letters[$letterPos]);
        }
    }
    foreach ($letters as $letter) {
        ?>
        <button id="<?=$letter?>" name="letter" type="submit" value="<?=$letter?>"><?=$letter?></button>
        <?php
    }
    ?></form><br><?php
$singleLettersFromWord = str_split($_SESSION['word']);
if ($_SESSION['lifes'] <= 6 && isset($_POST['letter'])) {
    foreach ($_SESSION['letter'] as $letterFromSession) {
        $result = (str_contains(strtoupper($_SESSION['word']), $letterFromSession));
    }
    if ($result == true) {
        $_SESSION['correct'][] = end($_SESSION['letter']);
    } else {
        $_SESSION['lifes']++;
        $_SESSION['wrong'][] = end($_SESSION['letter']);
    }
}
?><br><?php
$_SESSION['neededForCorrect'] = count($singleLettersFromWord);
?><div class="dashes"><?php
foreach ($singleLettersFromWord as $key => $value) {
    $value = strtoupper($value);
    if (!isset($_SESSION['guessed']) || !in_array($value, $_SESSION['guessed'])) {
        ?><span class="letters"> _</span><?php
    } else {
        ?><span class="letters"> <?=strtolower($value)?></span><?php
if (!isset($tempCorrect)) {
                $tempCorrect = 0;
}
            $tempCorrect++;
            $_SESSION['amountCorrect'] = $tempCorrect;
    }
}
?></div><?php

if (isset($_SESSION['amountCorrect']) && $_SESSION['neededForCorrect'] == $_SESSION['amountCorrect']) {
    ob_end_clean();
    ?><h1>You guessed the word "<?=$_SESSION['word']?>"! Congrats.</h1>
    <style> body {background-color: green} </style>
    <form action="kill.php">
        <button type="submit">Play again?</button>
    </form>
    <?php
    exit();
}
if (isset($_SESSION['lifes'])) {
    switch ($_SESSION['lifes']) {
        case null:
            ?><img class="galgje" src="images/00.png" width=35%><?php
            break;
        case '1':
            ?><img class="galgje" src="images/01.png" width=35%><?php
            break;
        case '2':
            ?><img class="galgje" src="images/02.png" width=35%><?php
            break;
        case '3':
            ?><img class="galgje" src="images/03.png" width=35%><?php
            break;
        case '4':
            ?><img class="galgje" src="images/04.png" width=35%><?php
            break;
        case '5':
            ?><img class="galgje" src="images/05.png" width=35%><?php
            break;
        case '6':
            ?><img class="galgje" src="images/06.png" width=35%><?php
            break;
        case '0':
            ?><img class="galgje" src="images/00.png" width=35%><?php
            break;
        default:
            break;
    }
}
}
?>
</div>
</body>
</html>