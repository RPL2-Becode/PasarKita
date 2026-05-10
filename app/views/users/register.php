<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - PasarKita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: { primary: '#ee4d2d', secondary: '#ff7337' }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-10 rounded-2xl shadow-xl w-full max-w-md border border-gray-100 fade-in">
        <div class="text-center mb-10">
            <div class="text-orange-600 text-5xl mb-4">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900">Daftar di <span class="text-orange-600">PasarKita</span></h1>
            <p class="text-gray-500 text-sm mt-2">Buat akun baru untuk mulai berbelanja atau berjualan</p>
        </div>

        <?php flash('register_errors'); ?>

        <form action="/users/register" method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Username</label>
                <input type="text" name="username" class="w-full px-4 py-3 rounded-xl border <?php echo (!empty($data['username_err'])) ? 'border-red-500' : 'border-gray-200'; ?> outline-none focus:border-orange-600 transition" value="<?php echo $data['username']; ?>" placeholder="Masukkan username">
                <span class="text-red-500 text-xs mt-1"><?php echo $data['username_err']; ?></span>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                <input type="password" name="password" class="w-full px-4 py-3 rounded-xl border <?php echo (!empty($data['password_err'])) ? 'border-red-500' : 'border-gray-200'; ?> outline-none focus:border-orange-600 transition" placeholder="Minimal 6 karakter">
                <span class="text-red-500 text-xs mt-1"><?php echo $data['password_err']; ?></span>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Password</label>
                <input type="password" name="confirm_password" class="w-full px-4 py-3 rounded-xl border <?php echo (!empty($data['confirm_password_err'])) ? 'border-red-500' : 'border-gray-200'; ?> outline-none focus:border-orange-600 transition" placeholder="Ulangi password">
                <span class="text-red-500 text-xs mt-1"><?php echo $data['confirm_password_err']; ?></span>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Daftar Sebagai</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-2 border rounded-xl px-4 py-3 cursor-pointer hover:border-orange-400 transition <?php echo (isset($data['role']) && $data['role'] == 'consumen') ? 'border-orange-500 bg-orange-50' : 'border-gray-200'; ?>">
                        <input type="radio" name="role" value="consumen" class="accent-orange-600" <?php echo (!isset($data['role']) || $data['role'] == 'consumen') ? 'checked' : ''; ?>>
                        <div>
                            <i class="fas fa-shopping-cart text-orange-500 mr-1"></i>
                            <span class="text-sm font-semibold">Pembeli</span>
                        </div>
                    </label>
                    <label class="flex items-center gap-2 border rounded-xl px-4 py-3 cursor-pointer hover:border-orange-400 transition <?php echo (isset($data['role']) && $data['role'] == 'pelapak') ? 'border-orange-500 bg-orange-50' : 'border-gray-200'; ?>">
                        <input type="radio" name="role" value="pelapak" class="accent-orange-600" <?php echo (isset($data['role']) && $data['role'] == 'pelapak') ? 'checked' : ''; ?>>
                        <div>
                            <i class="fas fa-store text-orange-500 mr-1"></i>
                            <span class="text-sm font-semibold">Pelapak</span>
                        </div>
                    </label>
                </div>
            </div>
            <button type="submit" class="w-full bg-orange-600 text-white font-bold py-4 rounded-xl hover:bg-orange-700 transition shadow-lg shadow-orange-200 mt-2">
                <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-gray-500 text-sm">Sudah punya akun? <a href="/users/login" class="text-orange-600 font-bold hover:underline">Masuk</a></p>
        </div>
    </div>
    <script src="/js/main.js"></script>
</body>
</html>
