<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale())}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>File upload to s3</title>
</head>
<body>
    <form method="POST" action="/save-image" enctype="multipart/form-data">
        <input type="file" name="image">
        <button type="submit">upload</button>

    </form>
</body>