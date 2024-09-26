
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <style>
            body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            }

            .container {
                background-color: #ffffff;
                padding: 20px 40px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                text-align: center;
                position: relative;
                transition: background-color 0.3s;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            .container.dragover {
                background-color: #e3f2fd;
            }

            .container h1 {
                color: #333;
                margin-bottom: 20px;
            }

            label {
                display: block;
                margin-bottom: 10px;
                color: #555;
                font-weight: bold;
            }

            input[type="file"] {
                margin-bottom: 20px;
            }

            input[type="submit"] {
                background-color: #4CAF50;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s;
            }

            input[type="submit"]:hover {
                background-color: #45a049;
            }

            .logo {
                width: 50%;
                height: 50%;
            }

            .button {
                display: none;
                margin-top: 20px;
                padding: 10px 20px;
                background-color: #007bff;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s;
            }

            .button:hover {
                background-color: #0056b3;
            }

            .error-message,
            .success-message {
                margin-top: 10px;
                font-size: 16px;
                text-align: center;
            }

            .success-message {
                color: green;
            }

            .error-message {
                color: red;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <img src="LogoInformatika.png" class="logo">
            <p id="message" class="success-message"></p>
        </div>
    </body>
</html>