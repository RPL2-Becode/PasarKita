<?php require_once '../app/views/templates/header.php'; ?>

<div class="max-w-6xl mx-auto px-4 py-8 fade-in">
    <?php if(isset($data['product']) && $data['product']) : $p = $data['product']; ?>
    <a href="/marketplace" class="text-gray-500 hover:text-primary text-sm mb-6 inline-flex items-center gap-1">
        <i class="fas fa-arrow-left"></i> Kembali ke Katalog
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mt-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
            <!-- Product Image -->
            <div class="bg-gray-50 flex items-center justify-center p-8 min-h-[400px]">
                <?php if(!empty($p->image_url)) : ?>
                    <img src="<?php echo $p->image_url; ?>" alt="<?php echo $p->name; ?>" class="max-h-96 object-contain rounded-xl">
                <?php else : ?>
                    <div class="text-center text-gray-300">
                        <i class="fas fa-image text-8xl mb-4"></i>
                        <p class="text-sm">Tidak ada gambar</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Product Info -->
            <div class="p-8 flex flex-col justify-between">
                <div>
                    <?php if(!empty($p->category_name)) : ?>
                        <span class="badge badge-info mb-3"><?php echo $p->category_name; ?></span>
                    <?php endif; ?>

                    <h1 class="text-3xl font-extrabold text-gray-900 mb-2"><?php echo $p->name; ?></h1>
                    
                    <p class="text-sm text-gray-400 mb-4">
                        <i class="fas fa-store mr-1"></i> Dijual oleh <span class="font-semibold text-gray-600"><?php echo $p->seller_name ?? 'Penjual'; ?></span>
                    </p>

                    <div class="bg-orange-50 rounded-xl p-4 mb-6">
                        <p class="text-3xl font-extrabold text-primary">Rp <?php echo number_format($p->price, 0, ',', '.'); ?></p>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-center gap-3 text-sm">
                            <i class="fas fa-box text-gray-400 w-5"></i>
                            <span class="text-gray-600">Stok: <strong class="<?php echo $p->stock > 0 ? 'text-green-600' : 'text-red-500'; ?>"><?php echo $p->stock > 0 ? $p->stock . ' unit' : 'Habis'; ?></strong></span>
                        </div>
                        <?php if(!empty($p->category_name)) : ?>
                        <div class="flex items-center gap-3 text-sm">
                            <i class="fas fa-tag text-gray-400 w-5"></i>
                            <span class="text-gray-600">Kategori: <strong><?php echo $p->category_name; ?></strong></span>
                        </div>
                        <?php endif; ?>
                        <div class="flex items-center gap-3 text-sm">
                            <i class="fas fa-clock text-gray-400 w-5"></i>
                            <span class="text-gray-600">Ditambahkan: <?php echo date('d M Y', strtotime($p->created_at)); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <?php if($p->stock > 0) : ?>
                        <a href="/cart/add/<?php echo $p->id; ?>" class="block w-full text-center bg-primary text-white font-bold py-4 rounded-xl hover:bg-orange-700 transition shadow-lg shadow-orange-200">
                            <i class="fas fa-cart-plus mr-2"></i> Tambah ke Keranjang
                        </a>
                    <?php else : ?>
                        <button disabled class="w-full bg-gray-300 text-gray-500 font-bold py-4 rounded-xl cursor-not-allowed">
                            <i class="fas fa-ban mr-2"></i> Stok Habis
                        </button>
                    <?php endif; ?>
                    <a href="/marketplace" class="block w-full text-center border-2 border-gray-200 text-gray-600 font-bold py-3 rounded-xl hover:border-primary hover:text-primary transition">
                        <i class="fas fa-arrow-left mr-2"></i> Lihat Produk Lain
                    </a>
                </div>
            </div>
        </div>

        <!-- Description Section -->
        <?php if(!empty($p->description)) : ?>
        <div class="border-t border-gray-100 p-8">
            <h2 class="text-lg font-bold text-gray-800 mb-3"><i class="fas fa-info-circle mr-2 text-primary"></i>Deskripsi Produk</h2>
            <p class="text-gray-600 leading-relaxed whitespace-pre-line"><?php echo $p->description; ?></p>
        </div>
        <?php endif; ?>
    </div>

    <?php else : ?>
    <div class="text-center py-20">
        <i class="fas fa-exclamation-triangle text-6xl text-gray-300 mb-4"></i>
        <h2 class="text-xl font-bold text-gray-500">Produk tidak ditemukan</h2>
        <a href="/marketplace" class="text-primary font-bold mt-4 inline-block hover:underline">Kembali ke Katalog</a>
    </div>
    <?php endif; ?>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>
