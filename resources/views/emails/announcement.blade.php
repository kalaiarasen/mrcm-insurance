<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $announcement->title }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .email-header .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .email-body {
            padding: 30px;
        }
        .announcement-title {
            font-size: 22px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        .announcement-description {
            font-size: 16px;
            color: #555;
            line-height: 1.8;
            white-space: pre-line;
        }
        .announcement-date {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            font-size: 14px;
            color: #888;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: 600;
            font-color: #fff;
        }
        .button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <div class="icon">📢</div>
            <h1>New Announcement</h1>
        </div>
        
        <div class="email-body">
            <div class="announcement-title">
                {{ $announcement->title }}
            </div>
            
            <div class="announcement-description">
                {{ $announcement->description }}
            </div>
            
            <div class="announcement-date">
                <strong>Posted on:</strong> {{ $announcement->created_at->format('F d, Y \a\t h:i A') }}
            </div>
            
            <center>
                <a href="{{ route('dashboard') }}" class="button">View Dashboard</a>
            </center>
        </div>
        
        <div class="email-footer">
            <p>This is an automated announcement from MRCM Insurance.</p>
            <p>Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} MRCM Services. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
