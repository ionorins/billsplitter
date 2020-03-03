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
        // get user's email synchronously (before the page loads)
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

        // function adapted from stackoverflow.com/questions/18749591/encode-html-entities-in-javascript
        // escapes strings
        function escape(str) {
            return str.replace(/[\u00A0-\u9999<>\&]/gim, function(i) {
                return '&#' + i.charCodeAt(0) + ';';
            });
        }

        // makes remove user from group requests go through ajax
        function removeUser(user, group) {
            $.post('deleteUserFromGroup.php', {
                'email': user,
                'groupId': group
            }, function(data) {
                data = JSON.parse(data);
                if (data === "success")
                    window.location.reload(true);
                else {
                    $('.warning').html('⚠️ ' + data);
                }
            });
        }

        // makes leave group requests go through ajax
        function leaveGroup(group) {
            $.post('leaveGroup.php', {
                'groupId': group
            }, function(data) {
                data = JSON.parse(data);
                if (data === "success")
                    window.location.reload(true);
                else {
                    $('.warning').html('⚠️ ' + data);
                }
            });
        }

        // makes accept request requests go through ajax
        function accept(id) {
            $.post('acceptRequest.php', {
                'requestId': id
            }, function(data) {
                data = JSON.parse(data);
                if (data === "success")
                    window.location.reload(true);
                else {
                    $('.warning').html('⚠️ ' + data);
                }
            });
        }

        // makes reject request requests go through ajax
        function reject(id) {
            $.post('deleteRequest.php', {
                'requestId': id
            }, function(data) {
                data = JSON.parse(data);
                if (data === "success")
                    window.location.reload(true);
                else {
                    $('.warning').html('⚠️ ' + data);
                }
            });
        }

        $(document).ready(function() {
            // makes create group requests go through ajax
            $('#createGroup').submit(function(event) {
                event.preventDefault();
                $.post('createGroup.php', $('form').serialize());
                window.location.reload(true);
            });

            // displays groups
            $.get('getGroups.php', function(data) {
                data = JSON.parse(data);
                // iterates though each group
                data.forEach(element => {
                    $('#accordion').append(
                        '<h3>' + escape(element['name']) + '</h3><div class="users" id="' + element['id'] + '"></div>'
                    );
                    $.get('getUsers.php?groupId=' + element['id'], function(data) {
                        data = JSON.parse(data);
                        // configures add user, kick user and leave group buttons depending on context
                        button = '';
                        add = '<button class="ui-button" onclick="leaveGroup(\'' + element['id'] + '\')">Leave group</button>';
                        if (window.email === element['leader']) {
                            button = '<button class="x">❌</button> ';
                            add =
                                '<form id="add' + element['id'] + '" method="POST"><input name="groupId" value="' + element['id'] + '" type="hidden"><input type="email" name="email"><input class="ui-button" type="submit" value="Add user"></form>';
                        }
                        // iterates though each member
                        data.forEach(user => {
                            $('#' + element['id']).append(button + escape(user['name']) + ' (' + user['email'] + ')<br>');
                            $('#' + element['id'] + '>button').click(function(event) {
                                event.preventDefault();
                                removeUser(user['email'], element['id']);
                            });
                        });
                        $('#' + element['id']).append(add);
                        // add split bill button
                        $('#' + element['id']).append('<button class="ui-button" onclick="window.location.href = \'splitBill.php?groupId=' + element['id'] + '\'">Split bill</button>');
                        $('#add' + element['id']).submit(function(event) {
                            $('.warning').html('');
                            event.preventDefault();
                            $.post('addUserToGroup.php', $('#add' + element['id']).serialize(), function(data) {
                                data = JSON.parse(data);
                                if (data === "success") {
                                    $('.warning').html('Request sent.');
                                } else {
                                    $('.warning').html('⚠️ ' + data);
                                }
                            });
                            this.reset();
                        });
                    });
                });
                $("#accordion").accordion({
                    heightStyle: "content"
                });
                // display requests to join group
                $.get('getRequests.php', function(data) {
                    console.log(data);
                    data = JSON.parse(data);
                    data.forEach(element => {
                        $('.requests').prepend(
                            '<div class="request">You have been invited by ' + element['leader'] + ' to join group ' + escape(element['name']) +
                            '<button class="ui-button" onclick="accept(\'' + element['id'] + '\')">Accept</button>' +
                            '<button class="ui-button" onclick="reject(\'' + element['id'] + '\')">Reject</button></div>'
                        );
                    });
                });
            });

        });
    </script>

</head>

<body>
    <div class="container">
        <?php include "navbar.php" ?>
        <div class="requests"></div>
        <div class="warning"></div>
        <div id="accordion"></div>
        <form id="createGroup" method="POST">
            <div class="label"><label>Enter name for new group</label></div>
            <input type="name" name="name">
            <input class="ui-button" type="submit" value="Create new group">
        </form>
    </div>
</body>