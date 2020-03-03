<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/main.css">

    <script src="js/jquery-3.4.1.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            // make registration request go through ajax
            $('form').submit(function(event) {
                event.preventDefault();
                $('.warning').html('');
                data = $('form').serializeArray();
                // check if passwords match
                if (data[2]['value'] != data[3]['value']) {
                    $('#repeat')[0].setCustomValidity('Passwords do not match.');
                } else {
                    // send user creation request
                    $.post('createUser.php', $('form').serialize(), function(data) {
                        data = JSON.parse(data);
                        if (data === "success")
                            // if user registration was successful, log the user in
                            $.post('login.php', $('form').serialize(), function(data) {
                                data = JSON.parse(data);
                                if (data === "success")
                                    window.location.href = 'bills.php';
                                else {
                                    $('.warning').html('⚠️ ' + data);
                                }
                            })
                        else {
                            $('.warning').html('⚠️ ' + data);
                        }

                    })
                }

            });

        });
    </script>
</head>

<body>
    <div class="container">
        <div class="warning"></div>
        <form>
            <div class="label"><label>Email</label></div><input type="email" name="email">
            <div class="label"><label>Name</label></div><input type="name" name="name">
            <div class="label"><label>Password</label></div><input type="password" name="password">
            <div class="label"><label>Repeat Password</label></div><input id="repeat" type="password" name="passwordRepeat">
            <input class="ui-button" type="submit" value="Register">
        </form>
    </div>
</body>