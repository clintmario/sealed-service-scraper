<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>HTML Email</title>
    <style type="text/css">
        * {-webkit-text-adjust:none;}
        ul[class="UnorderedList"] li {margin-bottom:4px;}
        ul[class="UnorderedList"] li:last-child {margin-bottom:0;}
        @media all and (max-device-width: 1024px) {
            table[class="marginfix"] {position:relative; top:0; left:0; right:0;}
        }
        @media all and (max-width: 600px) {
            html, body {margin:0; padding:0;}
            *[class*="mobile-only"] {display:block !important; width:auto !important; max-height:inherit !important; overflow:visible !important;}
            *[class="hide-mobile"] {display: none !important;}
            *[class="mobile-clear"] {display: block !important; clear:both !important;}
            *[class="mobile-gray-header"] {background-color:#f2f2f2 !important;}
            *[class="no-margin"] {margin:0 !important;}
            table[class="plainTextTable"] {width: 100%!important; clear:both !important; float:none !important; padding:16px !important;}
            table[class="table"] {width: 100%!important; clear:both !important; float:none !important;}
            table[class="contenttable"], td[class="contentblock"] {clear:both !important; float:none !important;}
            table[class="contenttable"] {width: 95% !important;}
            td[class="contentblock"] {width: 100% !important; display:block !important;}
            td[class="logo"] {height:36px !important; text-align: center !important;}
            *[class="mobile-mb8"] {display:block !important; margin-bottom:8px !important;}
            h1 {font-size:22px !important; line-height:30px !important;}
            h2 {font-size:18px !important; line-height:26px !important;}
            h3, h4, p, ul, strong {font-size:16px !important; line-height:24px !important;}
            small {font-size:12px !important; line-height:18px !important;}
            ul[class="UnorderedList"] {margin-left:16px !important;}
            img[class="logo-img"] {height: 36px !important;}
            img[class="full-width"] {width: 100% !important;}
            a[class="mainCTA"] {width: 85% !important; margin:0 auto !important; padding:16px 20px !important; border:0 !important; line-height:1.5 !important;}
            img[class="statBoxIcon"] {width:16px !important;height:16px !important;margin-top:7px !important;}
            td[class="statBoxIconWrap"] {width:24px !important;}
            span[class="statBoxLabel"] {font-size:12px !important;line-height:18px !important;}
            table[class="statBox"] {padding:0px !important;}
            tr[class="valignBaseline"] {vertical-align:baseline !important;}
            td[class="NPSScoreOneThird"] {width:33% !important;}
        }
    </style>
    <meta name = "viewport" content = "width = device-width, initial-scale=1.0, user-scalable = no" />
</head>

<body style="margin:0;padding:0;background-color:#f2f2f2;">
<table width="100%" class="marginfix" border="0" cellpadding="0" cellspacing="0" style="font-family:Helvetica,Arial,sans-serif;color:#222222;font-size:13px;line-height:21px;background-color:#f2f2f2;">

    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" width="600" align="center" bgcolor="#f2f2f2" class="table">
                <tr>
                    <td>
                        <!-- Header -->
                        <table cellpadding="0" cellspacing="0" width="600" align="center" bgcolor="#f2f2f2" class="contenttable">
                            <tr><td height="16"></td></tr>
                            <tr>
                                <td height="36" width="" class="logo">
                                    <a href="{{ Config::get('app.url') }}">
                                        <img class="logo-img" src="{{ Config::get('app.url') }}/fortuna/images/classesmasses-logo.png" alt="Bogex" height="36" border="0"/>
                                    </a>
                                </td>
                            </tr>
                            <tr><td height="8"></td></tr>
                        </table>
                        <!-- End header -->
                        <table cellpadding="0" cellspacing="0" width="600" align="center" bgcolor="#ffffff" class="contenttable" style="border:1px solid #ddd;">
                            <tr>
                                <td>
                                    <table cellpadding="16" cellspacing="0" width="600" align="center" bgcolor="#ffffff" class="table">
                                        <tr>
                                            <td>
                                                @yield('email-content')
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <!-- Footer -->
            <table cellpadding="0" cellspacing="0" width="600" align="center" bgcolor="#f2f2f2" class="table">
                <tr>
                    <td height="8"></td>
                </tr>
            </table>
            <table cellpadding="8" cellspacing="0" width="600" align="center" bgcolor="#f2f2f2" class="table">
                <tr>
                    <td align="center">
                        <small style="font-family:Helvetica,Arial,sans-serif;font-size:11px;line-height:17px;color:#666;">Bogex LLC | Bogex Pvt Ltd</small>
                    </td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" width="600" align="center" bgcolor="#f2f2f2" class="table">
                <tr>
                    <td height="16"></td>
                </tr>
            </table>
            <!-- End footer -->
        </td>
    </tr>
</table>
</body>
</html>
