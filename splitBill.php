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
    <script>
        jQuery.ajaxSetup({
            async: false
        });
        $.get('getEmail.php', function(data) {
            window.email = JSON.parse(data);
            console.log(window.email);
        });
        jQuery.ajaxSetup({
            async: true
        });

        $(document).ready(function() {
            $("#accordion").accordion({
                heightStyle: "content"
            });
            const params = new URLSearchParams(window.location.search);
            $('#equally').submit(function(event) {
                $('.warning').html('');
                event.preventDefault();
                number = $('#equally').serializeArray()[0]['value'];
                desc = $('#equally').serializeArray()[0]['description'];

                $.get('getUsers.php?groupId=' + params.get('groupId'), function(data) {
                    data = JSON.parse(data);
                    ammount = number / data.length;
                    console.log()
                    console.log(data.length);
                    data.forEach(element => {
                        if (element['email'] != window.email)
                            $.post('addBill.php', {
                                'ammount': ammount,
                                'description': description,
                                'payer': element['email']
                            });
                    });
                });
                $('.warning').html('Bill split.');
                this.reset();
            });
        });
    </script>

</head>

<body>
    <div class="container">
        <?php include "navbar.php" ?>
        <div class="warning"></div>
        <div id="accordion">
            <h3>Split equally</h3>
            <div>
                <form id="equally" method="POST">
                    <div class="label"><label>Ammount (&#163;)</label></div>
                    <input type="number" name="number">
                    <div class="label"><label>Description</label></div>
                    <input type="text" name="description">
                    <input class="ui-button" type="submit" value="Split">
                </form>
            </div>
        </div>
</body>