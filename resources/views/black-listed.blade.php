<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blocked</title>
    <style>
        body {
            /* font-family: Arial, sans-serif; */
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-weight: 400;
            font-family: "Nunito Sans", sans-serif;
        }

        .container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #d9534f;
        }

        p {
            color: #555;
        }

        .contact {
            margin-top: 20px;
        }

        .contact a {
            color: #337ab7;
            text-decoration: none;
        }

        .contact a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your IP Address is Blocked</h1>
        <p>We're sorry, but access from your IP address has been blocked. If you believe this is in error or need further assistance, please contact the administrator.</p>
        <div class="contact">
            <p>Contact the administrator at <a href="mailto:admin@example.com">{{getSetting('admin_email')}}</a></p>
        </div>
    </div>
</body>
</html>
