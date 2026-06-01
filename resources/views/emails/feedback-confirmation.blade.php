<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>फीडबैक पुष्टिकरण</title>
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
            background: linear-gradient(135deg, #0d6efd, #6d1b1b);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header p {
            margin: 10px 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #1e293b;
            margin-bottom: 20px;
        }
        .message {
            background: #f8f9ff;
            border-left: 4px solid #0d6efd;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .details {
            background: #f7f7f7;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .details h3 {
            color: #6d1b1b;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .detail-item {
            margin: 10px 0;
            padding: 5px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .detail-label {
            font-weight: bold;
            color: #4a5568;
            display: inline-block;
            width: 80px;
        }
        .footer {
            background: #f1f5f9;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 5px 0;
            color: #64748b;
            font-size: 14px;
        }
        .contact-info {
            background: #fff7f7;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .contact-info h4 {
            color: #6d1b1b;
            margin-bottom: 10px;
        }
        .emoji {
            font-size: 24px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🙏 धन्यवाद!</h1>
            <p>चातुर्मास आवास निवास</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                नमस्कार <strong>{{ $feedback->name }}</strong> जी,
            </div>
            
            <div class="message">
                <p>🎉 <strong>आपका फीडबैक सफलतापूर्वक प्राप्त हो गया है!</strong></p>
                <p>हमें आपकी प्रतिक्रिया मिली है और हम इसकी बहुत सराहना करते हैं। आपके सुझाव हमारे लिए अत्यंत महत्वपूर्ण हैं।</p>
            </div>
            
            <div class="details">
                <h3>📋 आपके फीडबैक का विवरण:</h3>
                <div class="detail-item">
                    <span class="detail-label">नाम:</span> {{ $feedback->name }}
                </div>
                <div class="detail-item">
                    <span class="detail-label">ईमेल:</span> {{ $feedback->email }}
                </div>
                <div class="detail-item">
                    <span class="detail-label">फोन:</span> {{ $feedback->phone }}
                </div>
                <div class="detail-item">
                    <span class="detail-label">संदेश:</span><br>
                    <div style="margin-top: 8px; padding: 10px; background: white; border-radius: 4px;">
                        {{ $feedback->message }}
                    </div>
                </div>
                <div class="detail-item">
                    <span class="detail-label">समय:</span> {{ $feedback->created_at->format('d M Y, h:i A') }}
                </div>
            </div>
            
            <div class="contact-info">
                <h4>📞 संपर्क जानकारी</h4>
                <p>यदि आपको कोई और सहायता चाहिए, तो कृपया हमसे संपर्क करें।</p>
                <p><strong>हेल्पलाइन:</strong> +91-XXXXXXXXXX</p>
                <p><strong>ईमेल:</strong> info@chaturmas.com</p>
            </div>
            
            <p style="color: #6d1b1b; font-style: italic; text-align: center; margin-top: 30px;">
                🌟 "आपकी संतुष्टि ही हमारी प्राथमिकता है"
            </p>
        </div>
        
        <div class="footer">
            <p><strong>चातुर्मास आवास निवास</strong></p>
            <p>यह एक स्वचालित ईमेल है, कृपया इसका उत्तर न दें।</p>
            <p>© {{ date('Y') }} चातुर्मास आवास निवास। सभी अधिकार सुरक्षित।</p>
        </div>
    </div>
</body>
</html>