<html>

<head>
    <title> Iframe</title>
</head>

<body>
    <center>
        <iframe src="{{$production_url}}" id="paymentFrame" width="482" height="450" frameborder="0"
            scrolling="No"></iframe>

        <script type="text/javascript" src="jquery-1.7.2.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                window.addEventListener('message', function(e) {
                    $("#paymentFrame").css("height", e.data['newHeight'] + 'px');
                }, false);

            });
        </script>
    </center>
</body>

</html>
