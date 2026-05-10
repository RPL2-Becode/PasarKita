<?php require_once '../app/views/templates/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 fade-in">
    <!-- Main Promo Banner -->
    <div class="py-6">
        <div class="relative rounded-2xl overflow-hidden shadow-xl h-[300px] lg:h-[400px]">
            <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=1200" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-black/60 to-transparent flex items-center p-12">
                <div class="max-w-md text-white">
                    <span class="bg-yellow-400 text-black px-3 py-1 rounded-full text-xs font-bold uppercase mb-4 inline-block">Promo Spesial</span>
                    <h2 class="text-4xl lg:text-5xl font-extrabold mb-4">Produk UMKM Terbaik</h2>
                    <p class="text-lg mb-6 opacity-90">Dukung produk lokal, nikmati kualitas terbaik dari UMKM Indonesia.</p>
                    <a href="/marketplace" class="bg-white text-primary px-8 py-3 rounded-xl font-bold hover:scale-105 transition shadow-lg inline-block">Belanja Sekarang</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Quick Links -->
    <div class="py-8 grid grid-cols-4 md:grid-cols-4 gap-4 text-center">
        <?php 
        $cats = ['Makanan', 'Minuman', 'Pakaian', 'Kerajinan'];
        $icons = ['fa-utensils', 'fa-coffee', 'fa-shirt', 'fa-palette'];
        foreach($cats as $i => $cat) : ?>
            <a href="/marketplace?category=<?php echo $i + 1; ?>" class="group cursor-pointer block">
                <div class="w-14 h-14 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center mx-auto mb-2 group-hover:shadow-md group-hover:border-primary transition-all">
                    <i class="fas <?php echo $icons[$i]; ?> text-gray-600 group-hover:text-primary"></i>
                </div>
                <span class="text-xs font-medium text-gray-600 group-hover:text-primary"><?php echo $cat; ?></span>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Recommendation Section -->
    <div class="py-4">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-extrabold">Inspirasi Belanja Untukmu</h3>
            <a href="/marketplace" class="text-primary text-sm font-bold hover:underline">Lihat Semua →</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            <?php foreach($data['products'] as $p) : ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all hover:-translate-y-1 cursor-pointer group">
                    <a href="/marketplace/detail/<?php echo $p->id; ?>">
                        <div class="h-40 overflow-hidden relative">
                            <img src="<?php echo $p->image_url ? $p->image_url : 'https://via.placeholder.com/150'; ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <?php if(isset($p->category_name) && !empty($p->category_name)) : ?>
                            <div class="absolute top-2 left-2 bg-white/90 backdrop-blur px-2 py-1 rounded text-[10px] font-bold text-gray-600">
                                <?php echo $p->category_name; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </a>
                    <div class="p-3">
                        <a href="/marketplace/detail/<?php echo $p->id; ?>">
                            <h4 class="text-xs text-gray-800 font-medium line-clamp-2 h-8 mb-2 group-hover:text-primary transition-colors"><?php echo $p->name; ?></h4>
                        </a>
                        <p class="text-primary font-bold text-base mb-1">Rp <?php echo number_format($p->price, 0, ',', '.'); ?></p>
                        <div class="flex items-center gap-1 mb-2">
                            <?php if($p->stock > 0) : ?>
                            <span class="text-[10px] bg-green-100 text-green-600 px-1 rounded font-bold">Tersedia</span>
                            <?php else : ?>
                            <span class="text-[10px] bg-red-100 text-red-600 px-1 rounded font-bold">Habis</span>
                            <?php endif; ?>
                            <span class="text-[10px] bg-blue-100 text-blue-600 px-1 rounded font-bold">UMKM</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>
