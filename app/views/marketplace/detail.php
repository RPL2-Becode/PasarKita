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
                    
                    <?php if(isset($data['ratingStats']['review_count']) && $data['ratingStats']['review_count'] > 0) : ?>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="flex text-yellow-400 text-sm">
                            <?php 
                            $avg = $data['ratingStats']['avg_rating'];
                            for($i=1; $i<=5; $i++) {
                                if($i <= $avg) echo '<i class="fas fa-star"></i>';
                                else if($i - 0.5 <= $avg) echo '<i class="fas fa-star-half-alt"></i>';
                                else echo '<i class="far fa-star"></i>';
                            }
                            ?>
                        </div>
                        <span class="text-sm font-bold text-gray-700"><?php echo $avg; ?>/5</span>
                        <span class="text-sm text-gray-400">(<?php echo $data['ratingStats']['review_count']; ?> Ulasan)</span>
                    </div>
                    <?php endif; ?>
                    
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
                    <div class="flex gap-2">
                        <a href="/marketplace" class="flex-1 block w-full text-center border-2 border-gray-200 text-gray-600 font-bold py-3 rounded-xl hover:border-primary hover:text-primary transition">
                            <i class="fas fa-arrow-left mr-2"></i> Lainnya
                        </a>
                        <?php if(isset($data['in_wishlist']) && $data['in_wishlist']) : ?>
                        <form action="/wishlist/remove/<?php echo $p->id; ?>" method="POST" class="flex-1 flex block w-full">
                            <button type="submit" class="w-full text-center border-2 border-red-500 text-red-500 bg-red-50 font-bold py-3 rounded-xl hover:bg-red-100 transition">
                                <i class="fas fa-heart mr-2"></i> Tersimpan
                            </button>
                        </form>
                        <?php else : ?>
                        <form action="/wishlist/add/<?php echo $p->id; ?>" method="POST" class="flex-1 flex block w-full">
                            <button type="submit" class="w-full text-center border-2 border-gray-200 text-gray-600 font-bold py-3 rounded-xl hover:border-red-500 hover:text-red-500 transition">
                                <i class="far fa-heart mr-2"></i> Wishlist
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
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

        <!-- Store Information Section -->
        <?php if(isset($data['seller']) && $data['seller']) : $seller = $data['seller']; ?>
        <div class="border-t border-gray-100 p-8 bg-gray-50">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <!-- Store Profile -->
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white rounded-full border-2 border-gray-200 overflow-hidden flex items-center justify-center shrink-0">
                        <?php if(!empty($seller->profile_picture)): ?>
                            <img src="/uploads/profile/<?php echo $seller->profile_picture; ?>" alt="<?php echo $seller->store_name ?? $seller->username; ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i class="fas fa-store text-2xl text-gray-400"></i>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-800"><?php echo !empty($seller->store_name) ? $seller->store_name : $seller->username; ?></h3>
                        <p class="text-sm text-green-500 mb-2"><i class="fas fa-circle text-[10px] mr-1"></i> Online</p>
                        <div class="flex gap-2">
                            <button class="px-3 py-1.5 border border-primary text-primary text-xs font-semibold rounded hover:bg-orange-50 transition">
                                <i class="fas fa-comment-dots mr-1"></i> Chat Sekarang
                            </button>
                            <a href="/toko/<?php echo $seller->username; ?>" class="px-3 py-1.5 border border-gray-300 text-gray-600 text-xs font-semibold rounded hover:bg-gray-100 transition">
                                <i class="fas fa-store mr-1"></i> Kunjungi Toko
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Store Stats -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-x-8 gap-y-2 text-sm text-gray-600 border-l border-gray-200 pl-6 w-full md:w-auto">
                    <div class="flex justify-between md:flex-col gap-2 md:gap-1">
                        <span>Penilaian</span>
                        <span class="font-bold text-primary"><?php echo $data['seller_stats']['avg_rating']; ?></span>
                    </div>
                    <div class="flex justify-between md:flex-col gap-2 md:gap-1">
                        <span>Produk</span>
                        <span class="font-bold text-primary"><?php echo $data['seller_stats']['total_products']; ?></span>
                    </div>
                    <div class="flex justify-between md:flex-col gap-2 md:gap-1">
                        <span>Bergabung</span>
                        <span class="font-bold text-primary"><?php echo date('Y', strtotime($seller->created_at)); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Reviews Section -->
        <div class="border-t border-gray-100 p-8">
            <h2 class="text-lg font-bold text-gray-800 mb-6"><i class="fas fa-star mr-2 text-yellow-400"></i>Ulasan Pembeli</h2>
            
            <?php if(empty($data['reviews'])) : ?>
                <div class="text-center py-8 bg-gray-50 rounded-xl">
                    <i class="far fa-comment-dots text-4xl text-gray-300 mb-2"></i>
                    <p class="text-gray-500">Belum ada ulasan untuk produk ini.</p>
                </div>
            <?php else : ?>
                <div class="space-y-6">
                    <?php foreach($data['reviews'] as $review) : ?>
                        <div class="border-b border-gray-50 pb-6 last:border-0 last:pb-0">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center text-primary font-bold">
                                    <?php echo strtoupper(substr($review->username, 0, 1)); ?>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-sm"><?php echo $review->username; ?></p>
                                    <div class="flex text-yellow-400 text-xs">
                                        <?php for($i=1; $i<=5; $i++) : ?>
                                            <i class="<?php echo $i <= $review->rating ? 'fas' : 'far'; ?> fa-star"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="ml-auto text-xs text-gray-400">
                                    <?php echo date('d M Y', strtotime($review->created_at)); ?>
                                </div>
                            </div>
                            <?php if(!empty($review->comment)) : ?>
                                <p class="text-gray-600 text-sm ml-13 pl-13"><?php echo htmlspecialchars($review->comment); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
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
