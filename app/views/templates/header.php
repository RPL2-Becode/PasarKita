<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($data['title']) ? $data['title'] : 'PasarKita'; ?></title>
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
    <script src="/js/main.js" defer></script>
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased">
    <!-- Top Search Bar (Tokopedia Style) -->
    <header class="bg-white sticky top-0 z-50 py-3 shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 flex items-center gap-6">
            <a href="/" class="flex items-center text-primary font-bold text-2xl shrink-0">
                <i class="fas fa-shopping-bag mr-2"></i> PasarKita
            </a>
            <form action="/marketplace" method="GET" class="relative flex-grow hidden md:block">
                <input type="text" name="search" placeholder="Cari kripik tempe, kain batik, atau sambal..." class="w-full border border-gray-200 rounded-lg py-2 px-10 focus:border-primary outline-none text-sm transition-all" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-sm"></i>
            </form>
            <div class="flex items-center gap-4 shrink-0">
                <?php if(isset($_SESSION['user_id'])) : ?>
                    <?php if($_SESSION['user_role'] == 'admin') : ?>
                        <a href="/admin/dashboard" class="text-sm font-semibold text-gray-600 hover:text-primary transition hidden md:inline"><i class="fas fa-chart-bar mr-1"></i> Dashboard</a>
                    <?php elseif($_SESSION['user_role'] == 'operator') : ?>
                        <a href="/admin/orders" class="text-sm font-semibold text-gray-600 hover:text-primary transition hidden md:inline"><i class="fas fa-list-alt mr-1"></i> Monitoring</a>
                    <?php elseif($_SESSION['user_role'] == 'pelapak') : ?>
                        <a href="/products" class="text-sm font-semibold text-gray-600 hover:text-primary transition hidden md:inline"><i class="fas fa-store mr-1"></i> Produk Saya</a>
                    <?php elseif($_SESSION['user_role'] == 'consumen') : ?>
                        <a href="/pesanan" class="text-sm font-semibold text-gray-600 hover:text-primary transition hidden md:inline"><i class="fas fa-receipt mr-1"></i> Pesanan Saya</a>
                    <?php endif; ?>
                <?php endif; ?>
                <a href="/wishlist" class="relative p-2 text-gray-600 hover:text-red-500 transition" title="Wishlist">
                    <i class="fas fa-heart text-xl"></i>
                </a>
                <a href="/cart" class="relative p-2 text-gray-600 hover:text-primary transition">
                    <i class="fas fa-shopping-cart text-xl"></i>
                    <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) : ?>
                        <span class="absolute top-0 right-0 bg-primary text-white text-[10px] font-bold px-1.5 rounded-full border-2 border-white"><?php echo count($_SESSION['cart']); ?></span>
                    <?php endif; ?>
                </a>
                <div class="h-6 w-px bg-gray-200"></div>
                <?php if(!isset($_SESSION['user_id'])) : ?>
                    <a href="/users/login" class="px-4 py-2 text-sm font-bold border border-primary text-primary rounded-lg hover:bg-orange-50 transition">Masuk</a>
                    <a href="/users/register" class="px-4 py-2 text-sm font-bold bg-primary text-white rounded-lg hover:bg-orange-600 shadow-md">Daftar</a>
                <?php else : ?>
                    <span class="text-sm font-bold bg-orange-100 text-orange-600 px-3 py-1 rounded-full uppercase"><?php echo $_SESSION['user_role']; ?></span>
                    <a href="/users/logout" class="text-gray-500 hover:text-red-500" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <main>
