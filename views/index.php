<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>
        Home Page
    </body>
    <br/>
    <form action="/transactions/upload" method="post" enctype="multipart/form-data" id="receipt">
        <input type="file" name="receipt[]" multiple>
        <button type="submit" name="receipt">Upload</button>
    </form>
</html>
