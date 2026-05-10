<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - PasarKita</title>
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
<body class="bg-gray-50 font-sans min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl overflow-hidden fade-in">
        <div class="p-8 text-center border-b border-gray-100">
            <div class="w-16 h-16 bg-orange-100 text-primary rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl shadow-inner">
                <i class="fas fa-unlock-alt"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900">Reset Password</h1>
            <p class="text-gray-500 text-sm mt-2">Buat password baru untuk akun Anda</p>
        </div>

        <div class="p-8">
            <form action="/users/forgotpassword" method="POST" class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Username</label>
                    <input type="text" name="username" class="w-full px-4 py-3 rounded-xl border <?php echo (!empty($data['username_err'])) ? 'border-red-500' : 'border-gray-200'; ?> outline-none focus:border-primary transition" placeholder="Masukkan username Anda" value="<?php echo $data['username']; ?>">
                    <?php if(!empty($data['username_err'])) : ?>
                        <span class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle"></i> <?php echo $data['username_err']; ?></span>
                    <?php endif; ?>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Password Baru</label>
                    <input type="password" name="new_password" class="w-full px-4 py-3 rounded-xl border <?php echo (!empty($data['new_password_err'])) ? 'border-red-500' : 'border-gray-200'; ?> outline-none focus:border-primary transition" placeholder="Minimal 6 karakter" value="<?php echo $data['new_password']; ?>">
                    <?php if(!empty($data['new_password_err'])) : ?>
                        <span class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle"></i> <?php echo $data['new_password_err']; ?></span>
                    <?php endif; ?>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Konfirmasi Password Baru</label>
                    <input type="password" name="confirm_password" class="w-full px-4 py-3 rounded-xl border <?php echo (!empty($data['confirm_password_err'])) ? 'border-red-500' : 'border-gray-200'; ?> outline-none focus:border-primary transition" placeholder="Ulangi password baru" value="<?php echo $data['confirm_password']; ?>">
                    <?php if(!empty($data['confirm_password_err'])) : ?>
                        <span class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle"></i> <?php echo $data['confirm_password_err']; ?></span>
                    <?php endif; ?>
                </div>

                <button type="submit" class="w-full bg-primary text-white font-bold py-4 rounded-xl hover:bg-orange-600 transition shadow-lg shadow-orange-200 mt-4">
                    Simpan Password
                </button>
            </form>
        </div>

        <div class="bg-gray-50 p-6 text-center text-sm border-t border-gray-100">
            Kembali ke <a href="/users/login" class="text-primary font-bold hover:underline">Login</a>
        </div>
    </div>
</body>
</html>
