<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Contact Us</title>
    <link href="styles.css" media="all" rel="stylesheet" type="text/css" />

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
                            <table  cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <img class="img-responsive" src="http://www.nero4me.com/images/logo.png"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        
                                        <br/>
                                    </td>
                                </tr>
                                   <tr>
                                    <td class="content-block">
                                         send By :  {{ $userType }}
                                        <br/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                         subject :  {{ $subject }}
                                        <br/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                       {{ $body }}
                                        <br/>
                                        <br/>
                                        <br/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Regards,
                                        <br/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <a href="mailto:{{ $emails }}"  style="margin: 0;padding: 0;font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;color: #2ba6cb;">{{$name}}</a>
                                        <br/>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <div class="footer">
                    <table width="100%">
                        <tr>
                            <!--                            <td class="aligncenter content-block">Follow <a href="#">@Company</a> on Twitter.</td>-->
                        </tr>
                    </table>
                </div></div>
        </td>
        <td></td>
    </tr>
</table>

</body>
</html>