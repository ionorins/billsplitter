<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage bills</title>
    <meta charset="utf-8">
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
        });
        jQuery.ajaxSetup({
            async: true
        });

        function confirmBill(id) {
            $.get('confirmBill.php?billId=' + id);
            window.location.reload(true);
        }

        // function adapted from stackoverflow.com/questions/18749591/encode-html-entities-in-javascript
        function escape(str) {
            return str.replace(/[\u00A0-\u9999<>\&]/gim, function(i) {
                return '&#' + i.charCodeAt(0) + ';';
            });
        }

        function displayBills() {
            $.get('getBills.php', function(data) {
                $("#accordion").html('');
                data = JSON.parse(data);
                data.forEach(element => {
                    if (element['payer'] == window.email) {
                        if (element['confirmedPayee'] != 0)
                            message = 'Confirmed by payee.';
                        else
                            message = 'Not yet confirmed by payee.';
                        if (element['confirmedPayer'] == 0)
                            button = '<button class="ui-button" onclick="confirmBill(\'' + element['id'] + '\')">Confirm payment of bill</button>';
                        else
                            button = '<button class="ui-button disabled" disabled>Confirmed</button>';
                        $('#accordion').prepend(
                            '<h3 class="billTitle">You need to pay £' + element['ammount'] + ' to ' + element['payer'] + '</h3>' +
                            '<div class="bill">' + escape(element['description']) + '<br>' + message +
                            button +
                            '</div>');
                    }
                    if (element['payee'] == window.email) {
                        if (element['confirmedPayer'] != 0)
                            message = 'Confirmed by payer.';
                        else
                            message = 'Not yet confirmed by payer.';
                        if (element['confirmedPayee'] == 0)
                            button = '<button class="ui-button" onclick="confirmBill(\'' + element['id'] + '\')">Confirm payment of bill</button>';
                        else
                            button = '<button class="ui-button disabled" disabled>Confirmed</button>';
                        $('#accordion').prepend(
                            '<h3 class="billTitle">You need to receive £' + element['ammount'] + ' from ' + element['payee'] + '</h3>' +
                            '<div class="bill">' + escape(element['description']) + '<br>' + message +
                            button +
                            '</div>');
                    }
                });
                $("#accordion").accordion({
                    heightStyle: "content",
                });
            });
        }
        $(document).ready(function() {
            $.get('getMoneyYouOwe.php', function(data) {
                data = JSON.parse(data);
                $('#youOwe').html(data);
                if (data == null)
                    $('#youOwe').html(0);
            });
            $.get('getMoneyOwedToYou.php', function(data) {
                data = JSON.parse(data);
                $('#owedToYou').html(data);
                if (data == null)
                    $('#owedToYou').html(0);
            });
            displayBills();
            $('form').submit(function(event) {
                $('.warning').html('');
                event.preventDefault();
                $.post('addBill.php', $('form').serialize(), function(data) {
                    data = JSON.parse(data);
                    if (data === "success")
                        window.location.href = 'bills.php';
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
        <div class="money">
            Money you owe: &#163;<span id="youOwe"></span>
            <br>
            Money owed to you: &#163;<span id="owedToYou"></span></div>
        <div class="warning"></div>
        <div id="accordion"></div>
        <form method="POST">
            <div class="label"><label>Ammount (&#163;)</label></div>
            <input type="number" name="ammount">
            <div class="label"><label>Description</label></div>
            <input type="text" name="description">
            <div class="label"><label>Payer's email</label></div>
            <input type="email" name="payer">
            <input class="ui-button" type="submit" value="Add new bill">
        </form>
    </div>
</body>