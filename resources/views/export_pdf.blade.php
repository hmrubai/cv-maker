<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="">
    <style>
        body {
            font-family: Helvetica, sans-serif;
            font-size: 13px;
        }

        .container {
            max-width: 780px;
            margin: 0 auto;
        }

        .logotype {
            background: #000;
            color: #fff;
            width: 175px;
            height: 75px;
            /* line-height: 75px; */
            text-align: center;
            font-size: 18px;
            text-decoration: none;
        }

        .invoice-div {
            background: #93de96;
            color: #000;
            width: 100%;
            height: 75px;
            /* line-height: 75px; */
            text-align: center;
            font-size: 18px;
            text-decoration: none;
        }

        .column-title {
            background: #eee;
            text-transform: uppercase;
            padding: 15px 5px 15px 15px;
            font-size: 11px
        }

        .column-detail {
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }

        .column-header {
            background: #eee;
            text-transform: uppercase;
            padding: 15px;
            font-size: 11px;
            border-right: 1px solid #eee;
        }

        .row {
            padding: 7px 14px;
            border-left: 1px solid #eee;
            border-right: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }

        .alert {
            background: #93de96;
            padding: 20px;
            margin: 20px 0;
            line-height: 22px;
            color: #333
        }

        .socialmedia {
            background: #eee;
            padding: 20px;
            display: inline-block
        }
    </style>
</head>
<body>
    <div class="container">
        <table width="100%">
            <tr>
                <td width="175px">
                    <div class="logotype">
                        <a target="_blank" href="http://aim-high.bacbontutors.com"> <h2 style="color:#fff; line-height: 26px;">AIM HIGH</h2> </a>
                    </div>
                </td>
                <td width="605px">
                    <div class="invoice-div">
                        <a target="_blank" href="#"> <h2 style="color:#000; line-height: 26px;">Invoice No: {{ $data['payment']->invoice_no }}</h2> </a>
                    </div>
                </td>
                <td></td>
            </tr>
        </table>
        <br><br>
        <h3>Your contact details</h3><br />
        <table width="100%" style="border-collapse: collapse;">
            <tr>
                <td widdth="50%" style="background:#eee;padding:20px;">
                    <strong>Date:</strong> {{ $data['payment']->created_at }}<br>
                    <strong>Payment type:</strong> {{ $data['payment']->payment_method }}<br>
                    <strong>Payment Status:</strong> {{ $data['payment']->payment_status }}<br>
                </td>
                <td style="background:#eee;padding:20px;">
                    <strong>Customer:</strong> {{ $data['user']->name }}<br>
                    <strong>E-mail:</strong> {{ $data['user']->email }}<br>
                    <strong>Phone:</strong> {{ $data['user']->contact_no }}<br>
                </td>
            </tr>
        </table><br>
        <div
            style="background: #93de96 url(http://gamers-sensei.bacbontutors.com/public/uploads/cart.png) no-repeat;width: 50px;height: 50px;margin-right: 10px;background-position: center;background-size: 25px;float: left; margin-bottom: 15px;">
        </div>
        <h3>Your Items</h3>
        <table width="100%" style="border-collapse: collapse;border-bottom:1px solid #eee;">
            <tr>
                <td width="20%" class="column-header">Game</td>
                <td width="40%" class="column-header">Item</td>
                <td width="20%" class="column-header">Price</td>
                <td style="text-align:right;width:20%" class="column-header">Total</td>
            </tr>
            <?php $sl = 1; ?>
            @foreach ($data['items'] as $product)
            <tr>
                <td class="row">{{ $product['game_name'] }}</td>
                <td class="row">
                    <span style="color:#777;font-size:11px;">#SL {{ $sl }}</span><br>{{ $product['experience_name'] }}<br/>
                    <span style="color:#777;font-size:11px;"> {{ $product['tire_name'] }}</span>
                </td>
                <td class="row">{{ $product['qty'] }} <span style="color:#777">X</span> {{ $product['price'] }}</td>
                <td class="row" style="text-align:right">$ {{ $product['qty'] * $product['price'] }}</td>
            </tr>
            <?php $sl++; ?>
            @endforeach
        </table><br>
        <table width="100%" style="background:#eee;padding:20px;">
            <tr>
                <td>
                    <table width="300px" style="float:right">
                        <tr>
                            <td><strong>GRAND TOTAL:</strong></td>
                            <td style="text-align:right">$ {{ $data['purchase']->total_price }}</td>
                        </tr>
                    </table>
                    <br/>
                </td>
            </tr>
        </table>
        <div class="alert">This is computer generated invoice no signature required.</div>
        <div class="socialmedia">Follow us online <small>[FB] [INSTA]</small></div>
    </div>
</body>
</html>