<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms & Conditions</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: #1E1E2D;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
            padding: 32px 24px;
        }

        .logo {
            display: flex;
            justify-content: center;
            margin-bottom: 32px;
        }

        .logo img {
            max-width: 180px;
            height: auto;
        }

        h1 {
            text-align: center;
            color: #0D2451;
            margin-bottom: 24px;
        }

        .terms-content {
            color: #333;
            line-height: 1.7;
            font-size: 1.08rem;
        }
        .logo {
	background: #1E1E2D;
	border-radius: 12px;
	box-shadow: 2px 2px 3px 2px #1E1E2D;
	padding: 16px;
	border: 1px solid #1E1E2D;
	display: flex;
	justify-content: center;
	align-items: center;
	width: fit-content;
	margin: 0 auto 32px auto;
}

        @media (max-width: 600px) {
            .container {
                padding: 18px 6px;
            }



            .logo img {
                max-width: 120px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">
            <img src="{{ get_file(setting()->logo_header) }}" alt="" height="100">
        </div>
        <h1>Terms & Conditions</h1>
        <div class="terms-content">
            <p><strong>Welcome to our platform!</strong></p>
            <p>This app is dedicated to football news, match predictions, talent discovery, and more. We aim to provide
                a vibrant community for football enthusiasts, aspiring talents, and fans to connect, learn, and stay
                updated.</p>
            <ul>
                <li>You must provide accurate, complete information during registration.</li>
                <li>You are responsible for maintaining the confidentiality of your account credentials.</li>
                <li>You are responsible for all activity that occurs under your account.</li>
                <li>You must be at least 13 years old (or the minimum legal age in your jurisdiction) to use the app. By
                    using the app, you represent that you meet this requirement.</li>
            </ul>
            <p>By using our app, you agree to abide by these terms and any applicable laws. We reserve the right to
                update these terms as needed. Please check back regularly for updates.</p>
            <p>If you have any questions, please contact our support team.</p>
        </div>
    </div>
</body>

</html>
