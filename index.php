<!DOCTYPE html>
<!-- Copyright (C) 2013 Alex Reidy -->
<html>
    <head>
        <title>Watch my PC for me</title>
        <link href='http://fonts.googleapis.com/css?family=Source+Code+Pro' rel='stylesheet' type='text/css'>
        <style>
            div {
                padding:5px;
                border-radius:8px;
                color:white;
                margin-bottom:10px;
                background-color:#7E98B2;
                border-color:#456585;
                border-style:solid;
            }
            body { font-family:'Source Code Pro'; }
            h1 { color:#456585; font-size:40px; font-weight:normal; }
            div.simple { width:400px; }
            input {
                background-color:#CBD6E0;
                border-color:#CBD6E0;
                border-radius:8px;
            }
        </style>
    </head>
    <body>
        <center>
            <h1>Watch my PC for me</h1>
            <div class="simple">
                We send you an alert via e-mail if someone tampers with your computer. Make sure your smartphone notifies you when you receive mail.
            </div>
            <div class="simple">
                <b>e-mail:</b> <input id="email-input" type="text"> <button id="arm-button">Arm detection system</button>
            </div>
        </center>
        <center>
            <div id="detector" class="simple">
                We watch for mouse movement and key presses, so keep your cursor on the web page. <u>Hit the <b>X</b> key to disarm the detection system</u> when you return.
            </div>
        </center>
    </body>
    <script type="text/javascript" src="scripts/jquery-1.10.2.min.js"></script>
    <script type="text/javascript">

    var armed = false;
    var repeater;

    function alarm() {
        if (armed) {
            armed = false;
            $('html').css('background-color', 'red');
            $.ajax({
                type: 'POST',
                url: 'scripts/alert.php',
                async: true,
                data: { email: $('#email-input').val() }
            });
        }
    }

    $('document').ready(function() {
        $('#arm-button').click(function() {
            var count = 5;

            $('div').fadeOut(4000);
            $('h1').fadeOut(8000);
            repeater = setInterval(function() {
                if (count == 0) {
                    clearInterval(repeater);
                    document.title = '404';
                    armed = true;
                }
                
                if (!armed) $('h1').html('Arming in ' + count);
                else $('h1').html('A R M E D');

                count--;

            }, 1000);
        });

        $('html').mousemove(alarm);

        window.onbeforeunload = function() {
            if (armed) {
                alarm();
                return 'Stop, thief!';
            }
        }

        $(window).keydown(function(ev) {
            if (armed) {
                if (ev.which == 88) {
                    armed = false;
                    $('html').css('background-color', 'green');
                } else alarm();
            }
        });

    });

    </script>
</html>