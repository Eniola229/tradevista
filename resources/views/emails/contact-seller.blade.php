<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Message from TradeVista Hub Support Team</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f5f8fa; margin: 0; padding: 0;">

    <table align="center" width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
        <tr style="background: #0d6efd;">
            <td style="padding: 20px; text-align: center; color: #ffffff; font-size: 20px; font-weight: bold;">
                TradeVista Hub
                <div style="font-size: 13px; font-weight: normal;">Connecting Buyers and Sellers</div>
            </td>
        </tr>

        <tr>
            <td style="padding: 25px; color: #333333; font-size: 15px; line-height: 1.6;">
                <p>Hello {{ $sellerName ?? 'Seller' }},</p>

                <p>This is a message from the <strong>TradeVista Hub Support Team</strong> regarding your product: 
                <strong style="color: #0d6efd;">{{ $product }}</strong>.</p>

                <p style="margin-top: 15px; font-weight: bold;">Message from Support:</p>
                <p style="background: #f0f4ff; padding: 12px; border-left: 3px solid #0d6efd; border-radius: 4px;">
                    {{ $bodyMessage }}
                </p>

                <p style="margin-top: 20px;">Please take any necessary action as advised by the support team.</p>
            </td>
        </tr>

        <tr>
            <td style="background: #f5f8fa; padding: 15px; text-align: center; font-size: 12px; color: #666;">
                © {{ date('Y') }} TradeVista Hub – Connecting Buyers and Sellers
            </td>
        </tr>
    </table>

</body>
</html>
