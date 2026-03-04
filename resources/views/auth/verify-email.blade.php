{{-- resources/views/auth/verify-email.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Email - KingFisher Methodist Church</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .verify-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 550px;
            width: 100%;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .verify-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 35px 30px;
            text-align: center;
            color: white;
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .icon-wrapper i {
            font-size: 2.5rem;
            color: #667eea;
        }

        .verify-header h1 {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .verify-header p {
            font-size: 13px;
            opacity: 0.95;
        }

        .verify-body {
            padding: 35px 30px;
        }

        .info-text {
            font-size: 14px;
            color: #555;
            line-height: 1.7;
            margin-bottom: 25px;
            text-align: center;
        }

        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }

        .button-group {
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: center;
            margin-top: 25px;
        }

        .btn-primary {
            padding: 12px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            padding: 12px 24px;
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        @media (max-width: 480px) {
            .verify-container {
                border-radius: 0;
            }

            .verify-header {
                padding: 30px 20px;
            }

            .verify-body {
                padding: 30px 20px;
            }

            .button-group {
                flex-direction: column;
                width: 100%;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="verify-container">
        <div class="verify-header">
            <div class="icon-wrapper">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <h1>Verify Your Email</h1>
            <p>KingFisher Methodist Church</p>
        </div>

        <div class="verify-body">
            <p class="info-text">
                Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just sent to you. If you didn't receive the email, we'll gladly send you another.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="success-message">
                    A new verification link has been sent to your email address.
                </div>
            @endif

            <div class="button-group">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn-primary">
                        Resend Verification Email
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-secondary">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>