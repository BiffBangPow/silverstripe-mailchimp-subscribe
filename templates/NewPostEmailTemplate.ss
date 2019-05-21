<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>New post from $SiteTitle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,600&display=swap" rel="stylesheet">
</head>


<style>
    body {
        font-family: 'Source Sans Pro', sans-serif;
    }

    .btn {
        background-color: black;
        padding: 10px;
        color: white;
        text-transform: uppercase;
        text-decoration: none;
        font-weight: 300;
    }

</style>
<body style="margin: 0;padding: 0;font-family: 'Source Sans Pro', sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; margin-left: auto; margin-right: auto;">
        <tr>
            <td style="padding: 20px; background-color: black; color: white;">
                <h1 style="margin: 0; text-align: center; font-weight: 300;">New post from $SiteTitle</h1>
            </td>
        </tr>
        <% if $FeaturedImage %>
            <tr>
                <td>
                    <img src="$FeaturedImage.ScaleMaxWidth(795).Fill(795,530).Link" style="max-width: 100%;">
                </td>
            </tr>
        <% end_if %>
        <tr>
            <td style="padding: 20px; text-align: center;">
                <h2 style="margin-top: 0">$Title</h2>
                <h4 style="margin: 0; font-weight: 300;">$PublishDate</h4>
                $Summary.RAW
            </td>
        </tr>
        <tr>
            <td style="padding: 20px; text-align: center;">
                <a href="$Link" class="btn" style="background-color: black;padding: 10px;color: white;text-transform: uppercase;text-decoration: none;font-weight: 300;">Read post</a>
            </td>
        </tr>
    </table>
</body>
</html>