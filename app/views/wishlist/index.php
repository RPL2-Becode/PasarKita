<div class="max-w-7xl mx-auto px-4 py-8 fade-in">
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900"><i class="fas fa-heart text-red-500 mr-2"></i>Wishlist Saya</h2>
        <p class="text-gray-500 mt-2">Daftar produk favorit yang Anda simpan</p>
    </div>

    <?php flash('wishlist_message'); ?>

    <?php if(empty($data['wishlist'])) : ?>
        <div class="text-center py-20 bg-white rounded-2xl border-2 border-dashed border-gray-100 mt-6">
            <i class="far fa-heart text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Wishlist Anda masih kosong</h3>
            <p class="text-gray-500 mb-4">Cari produk menarik dan tambahkan ke wishlist Anda!</p>
            <a href="/marketplace" class="bg-primary text-white px-6 py-3 rounded-xl font-bold hover:bg-orange-700 transition shadow-lg shadow-orange-200">Mulai Belanja</a>
        </div>
    <?php else : ?>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            <?php foreach($data['wishlist'] as $item) : ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all hover:-translate-y-1 group flex flex-col h-full relative">
                    <a href="/marketplace/detail/<?= $item->id; ?>" class="block">
                        <div class="h-40 overflow-hidden relative bg-gray-50 flex justify-center items-center">
                            <img src="<?= !empty($item->image_url) ? $item->image_url : 'https://via.placeholder.com/300x200?text=No+Image' ?>" alt="<?= $item->name; ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            
                            <?php if(!empty($item->category_name)) : ?>
                            <div class="absolute top-2 left-2 bg-white/90 backdrop-blur px-2 py-1 rounded text-[10px] font-bold text-gray-600">
                                <?= $item->category_name; ?>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Remove from wishlist button -->
                            <div class="absolute top-2 right-2">
                                <form action="/wishlist/remove/<?= $item->id; ?>" method="POST" class="inline" onclick="event.stopPropagation();">
                                    <button type="submit" class="bg-white/90 backdrop-blur w-8 h-8 rounded-full flex items-center justify-center text-red-500 hover:text-white hover:bg-red-500 shadow-sm transition-colors" title="Hapus dari Wishlist" onclick="return confirm('Hapus dari wishlist?');">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </a>
                    
                    <div class="p-3 flex-grow flex flex-col">
                        <a href="/marketplace/detail/<?= $item->id; ?>">
                            <h4 class="text-xs text-gray-800 font-medium line-clamp-2 h-8 mb-2 group-hover:text-primary transition-colors"><?= $item->name; ?></h4>
                        </a>
                        <p class="text-primary font-bold text-base mb-3">Rp <?= number_format($item->price, 0, ',', '.'); ?></p>
                        
                        <div class="mt-auto">
                            <a href="/marketplace/detail/<?= $item->id; ?>" class="w-full bg-orange-50 text-primary border border-primary font-bold py-2 rounded-lg text-xs hover:bg-primary hover:text-white transition-all flex items-center justify-center gap-2">
                                Detail Produk
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
