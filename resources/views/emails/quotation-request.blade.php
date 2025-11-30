<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>New Quotation Request - {{ $product->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
        }

        .product-info {
            background-color: #e8f5e9;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #4CAF50;
        }

        .form-data {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
        }

        .field {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .field:last-child {
            border-bottom: none;
        }

        .label {
            font-weight: bold;
            color: #555;
            display: block;
            margin-bottom: 5px;
        }

        .value {
            color: #333;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #777;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>New Quotation Request</h2>
    </div>

    <div class="content">
        <div class="product-info">
            <h3 style="margin-top: 0;">Product: {{ $product->title }}</h3>
            <p style="margin: 5px 0;"><strong>Type:</strong> {{ $product->type_name }}</p>
        </div>

        <h3>Customer Information:</h3>
        <div class="form-data">
            @foreach ($formData as $key => $value)
                <div class="field">
                    <span class="label">{{ ucwords(str_replace('_', ' ', $key)) }}:</span>
                    <span class="value">{{ $value }}</span>
                </div>
            @endforeach
        </div>

        <div class="footer">
            <p>This is an automated email from your insurance management system.</p>
            <p>Please respond to this quotation request at your earliest convenience.</p>
        </div>
    </div>
</body>

</html>
