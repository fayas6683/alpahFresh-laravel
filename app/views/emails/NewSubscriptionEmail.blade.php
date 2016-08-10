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
                <table class="main" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="content-wrap">
                            <table cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <img class="img-responsive" src = "{{ $logo }}"/>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <h3>{{ $title }} </h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">

                                        Hi {{ $first_name }}
                                        <br/><br/>
                                        {{ $p1 }}
                                        <br/><br/>
                                        {{ $p2 }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <br/>
                                        Kind Regards,
                                        <br/><br/>
                                        {{ $sender_info }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

            </div>
        </td>
        <td></td>
    </tr>
</table>

</body>
</html>
