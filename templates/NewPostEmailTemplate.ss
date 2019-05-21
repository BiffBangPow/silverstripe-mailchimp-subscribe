<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Demystifying Email Design</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="margin: 0; padding: 0;">
    <table border="1" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td>
                <h2>$Title</h2>
                <h4>$PublishDate</h4>
            </td>
        </tr>
        <% if $FeaturedImage %>
            <tr>
                <td>
                    <img src="$FeaturedImage.ScaleMaxWidth(795).Fill(795,530).Link" style="max-width: 100%;" />
                </td>
            </tr>
        <% end_if %>
        <tr>
            <td>
                $Summary.RAW
            </td>
        </tr>
    </table>
</body>
</html>