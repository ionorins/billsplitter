<!DOCTYPE html>
<html lang="en">

<head>
    <title>Split bill</title>

    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/main.css">

    <script src="js/jquery-3.4.1.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/main.js"></script>

</head>

<body>
    <div class="container">
        <?php include "navbar.php" ?>
        <div id="accordion">
            <h3>Split equaly</h3>
            <div>
                <form method="POST">
                    <div class="label"><label>Ammount (&#163;)</label></div>
                    <input type="number" name="number">
                    <input class="ui-button" type="submit" value="Split">
                </form>
            </div>
            <h3>Split using percentages</h3>
            <div>
                <form method="POST">
                    <div class="label"><label>Ammount (&#163;)</label></div>
                    <input type="number" name="number">
                    <input class="ui-button" type="submit" value="Split">
                </form>
            </div>
            <h3>Split using flat ammounts</h3>
            <div>
                <form method="POST">
                    <div class="label"><label>Ammount (&#163;)</label></div>
                    <input type="number" name="number">
                    <input class="ui-button" type="submit" value="Split">
                </form>
            </div>
        </div>
</body>