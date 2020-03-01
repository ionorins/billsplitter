<!DOCTYPE html>
<html lang="en">

<head>
    <title>Billsplitter</title>
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
                $('.warning').html('');
                event.preventDefault();

                $.post('login.php', $('form').serialize(), function(data) {
                    data =  JSON.parse(data);
                    if (data === "success")
                        window.location.href = 'bills.php';
                    else {
                        $('.warning').html('⚠️ ' + data);
                    }
                })

            });
        });
    </script>

</head>

<body>
    <div class="container">
        <div class="warning"></div>
        <form method="POST">
            <div class="label"><label>Email</label></div>
            <input type="email" name="email">
            <div class="label"><label>Password</label></div>
            <input type="password" name="password">
            <input class="ui-button" type="submit" value="Log In">
        </form>
        <br>
        <button class="ui-button" onclick="window.location.href = 'register.php'">Register Here</button>
    </div>
</body>