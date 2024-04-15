<!DOCTYPE Html>
<html lang="it">
<head>
    <title>Page Under Construction</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./styles/form.css">
    <link rel="stylesheet" type="text/css" href= "./styles/nav.css">
    <link rel="stylesheet" type="text/css" href= "./styles/home.css">
</head>
<body>
<?php
    require_once "nav.php";
    echo <<<p
            <article> 
                <h1 class="under_construction">Page under construction</h1>
            </article>
        p;
    require_once "footer.php";
?>
</body>
</html>