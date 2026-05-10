<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PasarKita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-10 rounded-2xl shadow-xl w-full max-w-md border border-gray-100">
        <div class="text-center mb-10">
            <div class="text-orange-600 text-5xl mb-4">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900">Masuk ke <span class="text-orange-600">PasarKita</span></h1>
            <p class="text-gray-500 text-sm mt-2">Silakan masukkan akun Anda</p>
        </div>

        <?php flash('login_errors'); ?>

        <form action="/users/login" method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Username</label>
                <input type="text" name="username" class="w-full px-4 py-3 rounded-xl border <?php echo (!empty($data['username_err'])) ? 'border-red-500' : 'border-gray-200'; ?> outline-none focus:border-orange-600 transition" value="<?php echo $data['username']; ?>">
                <span class="text-red-500 text-xs mt-1"><?php echo $data['username_err']; ?></span>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                <input type="password" name="password" class="w-full px-4 py-3 rounded-xl border <?php echo (!empty($data['password_err'])) ? 'border-red-500' : 'border-gray-200'; ?> outline-none focus:border-orange-600 transition">
                <span class="text-red-500 text-xs mt-1 block"><?php echo $data['password_err']; ?></span>
                <div class="text-right mt-2">
                    <a href="/users/forgotpassword" class="text-xs text-orange-600 hover:underline font-semibold">Lupa Password?</a>
                </div>
            </div>
            <button type="submit" class="w-full bg-orange-600 text-white font-bold py-4 rounded-xl hover:bg-orange-700 transition shadow-lg shadow-orange-200">
                Masuk Sekarang
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-gray-500 text-sm">Belum punya akun? <a href="/users/register" class="text-orange-600 font-bold hover:underline">Daftar</a></p>
        </div>
    </div>
</body>
</html>
