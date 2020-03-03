<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage account</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/main.css">

    <script src="js/jquery-3.4.1.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            // set up name change request
            $('#nameForm').submit(function(event) {
                $('.warning').html('');
                event.preventDefault();
                $.post('changeName.php', $('form').serialize(), function(data) {
                    data = JSON.parse(data);
                    if (data === "success")
                        $('.warning').html('Name successfully changed.');
                    else {
                        $('.warning').html('⚠️ ' + data);
                    }
                });
                this.reset();
            });

            // set up password change request
            $('#passForm').submit(function(event) {
                event.preventDefault();
                $('.warning').html('');
                data = $('form').serializeArray();
                // check if passwords match
                if (data[2]['value'] != data[3]['value']) {
                    $('#repeat')[0].setCustomValidity('Passwords do not match.');
                    return;
                }
                $.post('changePass.php', $('form').serialize(), function(data) {
                    data = JSON.parse(data);
                    if (data === "success")
                        $('.warning').html('Password successfully changed.');
                    else {
                        $('.warning').html('⚠️ ' + data);
                    }
                });
                this.reset();
            });
        });
    </script>

</head>

<body>
    <div class="container">
        <?php include "navbar.php" ?>
        <div class="warning"></div>
        <form id="nameForm" method="POST">
            <div class="label"><label>Name</label></div>
            <input type="name" name="name">
            <input class="ui-button" type="submit" value="Change name">
        </form>
        <form id="passForm" method="POST">
            <div class="label"><label>Old password</label></div>
            <input type="password" name="password">
            <div class="label"><label>New password</label></div>
            <input type="password" name="newPassword">
            <div class="label"><label>Repeat new password</label></div>
            <input type="password" name="passwordRepeat" id="repeat">
            <input class="ui-button" type="submit" value="Change password">
        </form>
    </div>
</body>