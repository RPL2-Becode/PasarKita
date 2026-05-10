<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk - PasarKita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm py-4 mb-8">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <a href="/home" class="text-orange-600 font-bold text-2xl flex items-center gap-2">
                <i class="fas fa-shopping-bag"></i> PasarKita
            </a>
            <div class="flex items-center gap-4">
                <span class="text-sm font-bold bg-orange-100 text-orange-600 px-3 py-1 rounded-full uppercase"><?php echo $_SESSION['user_role']; ?></span>
                <a href="/users/logout" class="text-gray-500 hover:text-red-500"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4">
        <?php flash('product_message'); ?>

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Daftar Produk Saya</h1>
            <a href="/products/add" class="bg-orange-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-orange-700 transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Produk Baru
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <?php foreach($data['products'] as $product) : ?>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-6">
                    <img src="<?php echo $product->image_url ? $product->image_url : 'https://via.placeholder.com/150'; ?>" class="w-24 h-24 rounded-lg object-cover bg-gray-100">
                    <div class="flex-grow">
                        <h3 class="text-lg font-bold text-gray-900"><?php echo $product->name; ?></h3>
                        <p class="text-orange-600 font-extrabold">Rp <?php echo number_format($product->price, 0, ',', '.'); ?></p>
                        <p class="text-sm text-gray-500">Stok: <?php echo $product->stock; ?> unit</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="/products/edit/<?php echo $product->id; ?>" class="p-3 text-blue-500 hover:bg-blue-50 rounded-xl transition"><i class="fas fa-edit"></i></a>
                        <form action="/products/delete/<?php echo $product->id; ?>" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                            <button type="submit" class="p-3 text-red-500 hover:bg-red-50 rounded-xl transition"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <?php if(empty($data['products'])) : ?>
                <div class="text-center py-20 bg-white rounded-xl border-2 border-dashed border-gray-200">
                    <p class="text-gray-400">Belum ada produk. Silakan tambah produk pertama Anda!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
