<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-lg mx-auto mt-12 p-8 bg-white rounded-lg shadow-lg">
        <!-- Header -->
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-900">Reset Your Password</h1>
            <p class="mt-2 text-gray-600">We received a request to reset your password. If you didn’t request this, please ignore this email.</p>
        </div>

        <!-- Main Content -->
        <div class="mt-6">
            <p class="text-gray-700">To reset your password, click the button below:</p>
            <a href="{{ url('http://localhost:5173/reset-password?email=' . $email . '&token=' . $token) }}" class="mt-4 inline-block w-full py-3 bg-blue-600 text-white text-center rounded-lg font-semibold hover:bg-blue-700">
                Reset Password
            </a>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center text-gray-500 text-sm">
            <p>If you did not request a password reset, please disregard this email.</p>
            <p class="mt-2">Thank you,<br>WarehouseTeam</p>
        </div>
    </div>
</body>
</html>
