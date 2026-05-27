<?php require_once '../app/views/templates/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 py-8 fade-in">
    <?php flash('product_message'); ?>

    <!-- Seller Dashboard Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <?php if(!empty($data['seller']->store_banner)): ?>
            <div class="h-24 bg-gray-200">
                <img src="/uploads/banner/<?php echo $data['seller']->store_banner; ?>" alt="Banner Toko" class="w-full h-full object-cover">
            </div>
        <?php else: ?>
            <div class="h-24 bg-gradient-to-r from-orange-400 to-primary"></div>
        <?php endif; ?>
        <div class="px-8 pb-8 relative flex flex-col md:flex-row gap-6 items-start md:items-center justify-between">
            <div class="flex items-center gap-6">
                <!-- Profile Picture -->
                <div class="-mt-12 w-24 h-24 rounded-full border-4 border-white bg-white shadow-md overflow-hidden shrink-0 flex items-center justify-center relative">
                    <?php if(!empty($data['seller']->profile_picture)): ?>
                        <img src="/uploads/profile/<?php echo $data['seller']->profile_picture; ?>" alt="Store" class="w-full h-full object-cover">
                    <?php else: ?>
                        <i class="fas fa-store text-4xl text-gray-400"></i>
                    <?php endif; ?>
                </div>
                <!-- Info -->
                <div class="mt-2 md:mt-0">
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <?php echo !empty($data['seller']->store_name) ? $data['seller']->store_name : $data['seller']->username; ?>
                        <span class="bg-primary text-white text-[10px] px-2 py-0.5 rounded font-bold uppercase tracking-wide shadow-sm">Pelapak</span>
                    </h1>
                    <p class="text-gray-500 text-sm mb-2"><i class="fas fa-map-marker-alt mr-1"></i> <?php echo !empty($data['seller']->address) ? $data['seller']->address : 'Alamat belum diatur'; ?></p>
                </div>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="/profile/edit" class="px-5 py-2.5 border border-primary text-primary font-bold rounded-lg hover:bg-orange-50 transition flex items-center gap-2">
                    <i class="fas fa-user-edit"></i> Edit Profil Toko
                </a>
            </div>
        </div>
    </div>

    <!-- Products List Section -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Daftar Produk Saya</h2>
        <div class="flex gap-2">
            <a href="/products/orders" class="px-5 py-2.5 border border-primary text-primary rounded-lg font-bold hover:bg-orange-50 transition shadow-sm flex items-center gap-2 text-sm">
                <i class="fas fa-receipt text-orange-500"></i> Kelola Pesanan Masuk
            </a>
            <a href="/products/add" class="bg-primary text-white px-5 py-2.5 rounded-lg font-bold hover:bg-orange-700 transition shadow-sm flex items-center gap-2 text-sm">
                <i class="fas fa-plus"></i> Tambah Produk Baru
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach($data['products'] as $product) : ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col overflow-hidden hover:shadow-md transition">
                <div class="flex items-center gap-4 p-4 border-b border-gray-50">
                    <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden shrink-0 flex items-center justify-center">
                        <?php if($product->image_url): ?>
                            <img src="<?php echo $product->image_url; ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i class="fas fa-image text-2xl text-gray-300"></i>
                        <?php endif; ?>
                    </div>
                    <div class="flex-grow min-w-0">
                        <h3 class="text-md font-bold text-gray-900 truncate"><?php echo $product->name; ?></h3>
                        <p class="text-primary font-extrabold text-sm mb-1">Rp <?php echo number_format($product->price, 0, ',', '.'); ?></p>
                        <div class="flex items-center gap-2 text-xs">
                            <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded font-medium">Stok: <?php echo $product->stock; ?></span>
                            <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded font-medium">Terjual: <?php echo $product->sold_count ?? 0; ?></span>
                        </div>
                    </div>
                </div>
                <div class="flex p-3 bg-gray-50 gap-2">
                    <a href="/marketplace/detail/<?php echo $product->id; ?>" class="flex-1 text-center py-2 text-sm font-semibold text-gray-600 border border-gray-200 rounded hover:bg-gray-100 transition"><i class="fas fa-eye mr-1"></i> Lihat</a>
                    <a href="/products/edit/<?php echo $product->id; ?>" class="flex-1 text-center py-2 text-sm font-semibold text-blue-600 border border-blue-200 bg-blue-50 rounded hover:bg-blue-100 transition"><i class="fas fa-edit mr-1"></i> Edit</a>
                    <form action="/products/delete/<?php echo $product->id; ?>" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                        <button type="submit" class="w-full h-full py-2 text-sm font-semibold text-red-600 border border-red-200 bg-red-50 rounded hover:bg-red-100 transition"><i class="fas fa-trash mr-1"></i> Hapus</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
        
        <?php if(empty($data['products'])) : ?>
            <div class="col-span-full text-center py-20 bg-white rounded-xl border-2 border-dashed border-gray-200 shadow-sm">
                <i class="fas fa-box-open text-5xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 font-medium">Belum ada produk. Silakan tambah produk pertama Anda!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>
