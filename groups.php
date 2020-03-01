<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage groups</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/main.css">

    <script src="js/jquery-3.4.1.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            $('form').submit(function(event) {
                event.preventDefault();
                $.post('createGroup.php', $('form').serialize());
                this.reset();
            });
        });
    </script>

</head>

<body>
    <div class="container">
        <?php include "navbar.php" ?>
        <form method="POST">
            <div class="label"><label>Enter name for new group</label></div>
            <input type="name" name="name">
            <input class="ui-button" type="submit" value="Create new group">
        </form>
    </div>
</body>