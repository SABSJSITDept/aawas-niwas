<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>नया फीडबैक प्राप्त</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #dc3545, #6d1b1b);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: bold;
        }
        .content {
            padding: 30px;
        }
        .alert {
            background: #fff3cd;
            border: 1px solid #ffecb5;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
        }
        .details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .detail-item {
            margin: 12px 0;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .detail-label {
            font-weight: bold;
            color: #495057;
            display: inline-block;
            width: 100px;
        }
        .message-box {
            background: #e7f3ff;
            border: 1px solid #b3d4fc;
            border-radius: 6px;
            padding: 15px;
            margin: 15px 0;
        }
        .footer {
            background: #f1f3f4;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .action-btn {
            background: #6d1b1b;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 6px;
            display: inline-block;
            margin: 15px 0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔔 नया फीडबैक प्राप्त</h1>
            <p>चातुर्मास आवास निवास - Admin Panel</p>
        </div>
        
        <div class="content">
            <div class="alert">
                <strong>📧 Alert:</strong> आपको एक नया फीडबैक प्राप्त हुआ है। कृपया जल्दी जवाब दें।
            </div>
            
            <div class="details">
                <h3 style="color: #6d1b1b; margin-bottom: 15px;">📋 फीडबैक का विवरण:</h3>
                
                <div class="detail-item">
                    <span class="detail-label">नाम:</span> 
                    <strong>{{ $feedback->name }}</strong>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">ईमेल:</span> 
                    <a href="mailto:{{ $feedback->email }}">{{ $feedback->email }}</a>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">फोन:</span> 
                    <a href="tel:{{ $feedback->phone }}">{{ $feedback->phone }}</a>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">समय:</span> 
                    {{ $feedback->created_at->format('d M Y, h:i A') }}
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">संदेश:</span>
                    <div class="message-box">
                        {{ $feedback->message }}
                    </div>
                </div>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ config('app.url') }}/admin/feedback" class="action-btn">
                    📊 Admin Panel में देखें
                </a>
            </div>
            
            <div style="background: #f8f9fa; padding: 15px; border-radius: 6px; text-align: center;">
                <p style="margin: 0; color: #6c757d;">
                    <strong>Quick Response:</strong> 
                    <a href="mailto:{{ $feedback->email }}">Reply via Email</a> | 
                    <a href="tel:{{ $feedback->phone }}">Call Now</a>
                </p>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>चातुर्मास आवास निवास - Admin Notification</strong></p>
            <p>यह एक स्वचालित email है। कृपया user को जल्दी response करें।</p>
        </div>
    </div>
</body>
</html>