<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($data['title']) ? $data['title'] : 'PasarKita'; ?></title>
    
    <?php
    // Ensure profile picture is in session if not set
    if (isset($_SESSION['user_id']) && !array_key_exists('user_profile_picture', $_SESSION)) {
        $db = new Database();
        $db->query("SELECT profile_picture FROM users WHERE id = :id");
        $db->bind(':id', $_SESSION['user_id']);
        $userRow = $db->single();
        $_SESSION['user_profile_picture'] = $userRow ? $userRow->profile_picture : null;
    }
    ?>

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
                    <?php
                    // Get unread chat count
                    $chatModel = clone (new Controller())->model('Chat_model');
                    $unread_chat = $chatModel->getUnreadCount($_SESSION['user_id']);
                    ?>
                    <?php if($_SESSION['user_role'] == 'admin') : ?>
                        <a href="/admin/dashboard" class="text-sm font-semibold text-gray-600 hover:text-primary transition hidden md:inline"><i class="fas fa-chart-bar mr-1"></i> Dashboard</a>
                    <?php elseif($_SESSION['user_role'] == 'operator') : ?>
                        <a href="/admin/orders" class="text-sm font-semibold text-gray-600 hover:text-primary transition hidden md:inline"><i class="fas fa-list-alt mr-1"></i> Monitoring</a>
                    <?php elseif(strtolower($_SESSION['user_role']) == 'pelapak') : ?>
                        <a href="/insight" class="text-sm font-semibold text-gray-600 hover:text-primary transition hidden md:inline"><i class="fas fa-chart-line mr-1 text-orange-500"></i> Insight</a>
                        <a href="/products" class="text-sm font-semibold text-gray-600 hover:text-primary transition hidden md:inline ml-2"><i class="fas fa-store mr-1"></i> Produk Saya</a>
                        <a href="/products/orders" class="text-sm font-semibold text-gray-600 hover:text-primary transition hidden md:inline ml-2"><i class="fas fa-receipt mr-1 text-orange-500"></i> Pesanan Masuk</a>
                    <?php elseif(strtolower($_SESSION['user_role']) == 'consumen') : ?>
                        <a href="/pesanan" class="text-sm font-semibold text-gray-600 hover:text-primary transition hidden md:inline"><i class="fas fa-receipt mr-1"></i> Pesanan Saya</a>
                    <?php endif; ?>
                    
                    <a href="/chat" class="relative p-2 text-gray-600 hover:text-primary transition ml-2" title="Pesan">
                        <i class="fas fa-comment-dots text-xl"></i>
                        <?php if($unread_chat > 0) : ?>
                            <span class="absolute top-0 right-0 bg-red-500 text-white text-[10px] font-bold px-1.5 rounded-full border-2 border-white"><?php echo $unread_chat; ?></span>
                        <?php endif; ?>
                    </a>
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
                    <?php if(isset($_SESSION['user_balance'])) : ?>
                        <div class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-full border border-blue-100" title="Saldo SmartBank">
                            <i class="fas fa-wallet"></i>
                            <span class="text-sm font-bold">Rp <?php echo number_format($_SESSION['user_balance'], 0, ',', '.'); ?></span>
                        </div>
                    <?php endif; ?>
                    <a href="/profile" class="text-sm font-bold bg-orange-100 text-orange-600 px-4 py-1.5 rounded-full hover:bg-orange-200 transition flex items-center gap-2" title="Profil Saya">
                        <?php if(!empty($_SESSION['user_profile_picture'])) : ?>
                            <img src="/uploads/profile/<?php echo $_SESSION['user_profile_picture']; ?>" alt="Profile" class="w-6 h-6 rounded-full object-cover border border-orange-200">
                        <?php else : ?>
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['user_username']); ?>&background=random&color=fff&rounded=true&size=64" alt="Profile" class="w-6 h-6 rounded-full object-cover border border-orange-200">
                        <?php endif; ?>
                        <span><?php echo $_SESSION['user_username']; ?></span>
                    </a>
                    <a href="/users/logout" class="text-gray-500 hover:text-red-500 ml-2" title="Keluar"><i class="fas fa-sign-out-alt text-xl"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <main>
