<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="google-site-verification" content="XRS-DlqvCSDD76UDmBelZK-oG16XL20KyEUnDFdgCo4" />
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-WYTVNWXJG6"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-WYTVNWXJG6');
    </script>
    <link rel="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" />
    <title>Database error details</title>
</head>

<body>
    <div class="row justify-content-center mt-5 m-5">
        <div id="taskParent" class="col-md-6 border">
            <h1>Database error details</h1>
            <p><b>Msg: </b><?php
                            echo $eArr["\0*\0message"];
                            ?></p><br>
            <p><b>Time of request: </b>
                <?php echo date("Y-m-d H:i:s", $_SERVER["REQUEST_TIME"]); ?>
            </p>
        </div>
    </div>
    <script src="vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
</body>

</html>