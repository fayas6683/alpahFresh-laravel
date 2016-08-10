<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Actionable emails e.g. reset password</title>
    <link href="styles.css" media="all" rel="stylesheet" type="text/css"/>

    <style>
        body {
            background-color: linen;
        }

        h1 {
            color: maroon;
            margin-left: 40px;
        }
    </style>

</head>

<body>

<table class="body-wrap">
    <tr>
        <td></td>
        <td class="container" width="600">
            <div class="content">

                <p>

                <h3>Dear {{ $consumer_name }} </h3>
                </p>
                <p>
                    You have been charged ${{ $amount }} for {{ $plan }} plan has been successful.
                </p>

                <p>
                    Your account is now ready to be used.If you have any complaints about the product you received
                    please don't hesitate to email us.
                </p>

                <p>
                    Kind Regards,
                    </br>
                    {{ $sender_info }}
                </p>

            </div>
        </td>
        <td></td>
    </tr>
</table>

</body>
</html>
