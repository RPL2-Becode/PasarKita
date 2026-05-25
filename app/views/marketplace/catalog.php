<?php require_once '../app/views/templates/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 py-8 fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Jelajahi Produk <span class="text-primary">UMKM</span></h1>
            <p class="text-gray-500">Menampilkan semua produk pilihan terbaik untukmu</p>
        </div>
        
        <!-- Mobile Filter Toggle -->
        <button onclick="document.getElementById('filterSidebar').classList.toggle('hidden')" class="md:hidden bg-primary text-white px-4 py-2 rounded-lg font-semibold text-sm">
            <i class="fas fa-sliders-h mr-2"></i> Filter & Urutkan
        </button>
    </div>

    <?php flash('cart_message'); ?>
    <?php flash('wishlist_message'); ?>

    <!-- Active Filters Indicator -->
    <?php 
        $active_filters = [];
        if(!empty($data['search'])) $active_filters[] = 'Pencarian: "' . htmlspecialchars($data['search']) . '"';
        if(!empty($data['selected_category'])) {
            foreach($data['categories'] as $c) {
                if($c->id == $data['selected_category']) { $active_filters[] = 'Kategori: ' . $c->name; break; }
            }
        }
        if($data['min_price'] !== '') $active_filters[] = 'Min: Rp ' . number_format($data['min_price'], 0, ',', '.');
        if($data['max_price'] !== '') $active_filters[] = 'Max: Rp ' . number_format($data['max_price'], 0, ',', '.');
        if(!empty($data['min_rating'])) $active_filters[] = 'Rating ≥ ' . $data['min_rating'] . '★';
        if($data['sort'] !== 'terbaru') {
            $sort_labels = ['termurah' => 'Harga Terendah', 'termahal' => 'Harga Tertinggi', 'rating' => 'Rating Tertinggi'];
            $active_filters[] = 'Urutan: ' . ($sort_labels[$data['sort']] ?? $data['sort']);
        }
    ?>
    <?php if(!empty($active_filters)) : ?>
        <div class="mb-6 flex flex-wrap items-center gap-2">
            <span class="text-sm text-gray-500"><i class="fas fa-filter mr-1"></i> Filter aktif:</span>
            <?php foreach($active_filters as $af) : ?>
                <span class="bg-orange-50 text-primary text-xs font-semibold px-3 py-1 rounded-full border border-orange-200"><?= $af; ?></span>
            <?php endforeach; ?>
            <a href="/marketplace" class="text-xs text-red-500 hover:underline font-semibold ml-2"><i class="fas fa-times"></i> Reset Semua</a>
        </div>
    <?php endif; ?>

    <div class="flex flex-col md:flex-row gap-6">
        <!-- ===== SIDEBAR FILTER ===== -->
        <aside id="filterSidebar" class="hidden md:block w-full md:w-64 flex-shrink-0">
            <form action="/marketplace" method="GET" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sticky top-24 space-y-6">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-sliders-h text-primary"></i> Filter Produk
                </h3>

                <!-- Search -->
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Kata Kunci</label>
                    <div class="relative">
                        <input type="text" name="search" placeholder="Cari produk..." 
                            class="w-full border border-gray-200 rounded-lg py-2 px-3 pr-8 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition" 
                            value="<?= htmlspecialchars($data['search']); ?>">
                        <i class="fas fa-search absolute right-3 top-2.5 text-gray-400 text-xs"></i>
                    </div>
                </div>

                <!-- Category -->
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Kategori</label>
                    <select name="category" class="w-full border border-gray-200 rounded-lg py-2 px-3 text-sm focus:border-primary outline-none transition bg-white">
                        <option value="">Semua Kategori</option>
                        <?php foreach($data['categories'] as $cat) : ?>
                            <option value="<?= $cat->id; ?>" <?= ($data['selected_category'] == $cat->id) ? 'selected' : ''; ?>>
                                <?= $cat->name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Price Range -->
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Rentang Harga</label>
                    <div class="flex gap-2">
                        <div class="flex-1 relative">
                            <span class="absolute left-2 top-2 text-xs text-gray-400">Rp</span>
                            <input type="number" name="min_price" placeholder="Min" 
                                class="w-full border border-gray-200 rounded-lg py-2 pl-7 pr-2 text-sm focus:border-primary outline-none transition" 
                                value="<?= htmlspecialchars($data['min_price']); ?>" min="0">
                        </div>
                        <span class="text-gray-400 self-center">—</span>
                        <div class="flex-1 relative">
                            <span class="absolute left-2 top-2 text-xs text-gray-400">Rp</span>
                            <input type="number" name="max_price" placeholder="Max" 
                                class="w-full border border-gray-200 rounded-lg py-2 pl-7 pr-2 text-sm focus:border-primary outline-none transition" 
                                value="<?= htmlspecialchars($data['max_price']); ?>" min="0">
                        </div>
                    </div>
                </div>

                <!-- Rating Filter -->
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Rating Minimum</label>
                    <div class="flex gap-1">
                        <?php for($r = 1; $r <= 5; $r++) : ?>
                            <label class="cursor-pointer">
                                <input type="radio" name="min_rating" value="<?= $r; ?>" class="hidden peer" 
                                    <?= ($data['min_rating'] == $r) ? 'checked' : ''; ?>>
                                <div class="flex items-center gap-1 px-3 py-1.5 rounded-lg border border-gray-200 peer-checked:border-yellow-400 peer-checked:bg-yellow-50 transition text-xs font-semibold text-gray-600 peer-checked:text-yellow-600 hover:border-yellow-300">
                                    <?= $r; ?> <i class="fas fa-star text-yellow-400 text-[10px]"></i>
                                </div>
                            </label>
                        <?php endfor; ?>
                    </div>
                    <?php if(!empty($data['min_rating'])) : ?>
                        <a href="javascript:void(0)" onclick="document.querySelectorAll('input[name=min_rating]').forEach(e=>e.checked=false)" class="text-[10px] text-red-400 hover:underline mt-1 inline-block">Reset rating</a>
                    <?php endif; ?>
                </div>

                <!-- Sort -->
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Urutkan</label>
                    <select name="sort" class="w-full border border-gray-200 rounded-lg py-2 px-3 text-sm focus:border-primary outline-none transition bg-white">
                        <option value="terbaru" <?= ($data['sort'] == 'terbaru') ? 'selected' : ''; ?>>Terbaru</option>
                        <option value="termurah" <?= ($data['sort'] == 'termurah') ? 'selected' : ''; ?>>Harga Terendah</option>
                        <option value="termahal" <?= ($data['sort'] == 'termahal') ? 'selected' : ''; ?>>Harga Tertinggi</option>
                        <option value="rating" <?= ($data['sort'] == 'rating') ? 'selected' : ''; ?>>Rating Tertinggi</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="flex-1 bg-primary text-white py-2.5 rounded-xl font-bold text-sm hover:bg-orange-700 transition shadow-lg shadow-orange-200">
                        <i class="fas fa-search mr-1"></i> Terapkan
                    </button>
                    <a href="/marketplace" class="flex-shrink-0 bg-gray-100 text-gray-600 py-2.5 px-4 rounded-xl font-bold text-sm hover:bg-gray-200 transition" title="Reset">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </form>
        </aside>

        <!-- ===== MAIN CONTENT ===== -->
        <div class="flex-1">
            <!-- Category Quick Filter (Pills) -->
            <?php if(isset($data['categories']) && !empty($data['categories'])) : ?>
            <div class="flex flex-wrap gap-2 mb-6">
                <a href="/marketplace" class="px-4 py-2 rounded-full text-sm font-semibold transition <?php echo empty($data['selected_category']) ? 'bg-primary text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:border-primary hover:text-primary'; ?>">
                    <i class="fas fa-th mr-1"></i> Semua
                </a>
                <?php foreach($data['categories'] as $cat) : ?>
                <a href="/marketplace?category=<?php echo $cat->id; ?><?= !empty($data['sort']) && $data['sort'] !== 'terbaru' ? '&sort=' . $data['sort'] : ''; ?>" class="px-4 py-2 rounded-full text-sm font-semibold transition <?php echo ($data['selected_category'] == $cat->id) ? 'bg-primary text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:border-primary hover:text-primary'; ?>">
                    <?php echo $cat->name; ?>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Result Count -->
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm text-gray-500">
                    <span class="font-bold text-gray-800"><?= count($data['products']); ?></span> produk ditemukan
                </p>
            </div>

            <!-- Product Grid -->
            <?php if(!empty($data['products'])) : ?>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                <?php foreach($data['products'] as $p) : ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all hover:-translate-y-1 group flex flex-col h-full">
                        <a href="/marketplace/detail/<?php echo $p->id; ?>" class="block">
                            <div class="h-40 overflow-hidden relative">
                                <img src="<?php echo $p->image_url ? $p->image_url : 'https://via.placeholder.com/300'; ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <?php if(isset($p->category_name) && !empty($p->category_name)) : ?>
                                <div class="absolute top-2 left-2 bg-white/90 backdrop-blur px-2 py-1 rounded text-[10px] font-bold text-gray-600">
                                    <?php echo $p->category_name; ?>
                                </div>
                                <?php endif; ?>
                                <div class="absolute top-2 right-2">
                                    <?php if(isset($p->in_wishlist) && $p->in_wishlist) : ?>
                                    <form action="/wishlist/remove/<?php echo $p->id; ?>" method="POST" class="inline" onclick="event.stopPropagation();">
                                        <button type="submit" class="bg-white/90 backdrop-blur w-8 h-8 rounded-full flex items-center justify-center text-red-500 shadow-sm transition-colors" title="Hapus dari Wishlist">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    </form>
                                    <?php else : ?>
                                    <form action="/wishlist/add/<?php echo $p->id; ?>" method="POST" class="inline" onclick="event.stopPropagation();">
                                        <button type="submit" class="bg-white/90 backdrop-blur w-8 h-8 rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 shadow-sm transition-colors" title="Tambah ke Wishlist">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                                <div class="absolute bottom-2 left-2 bg-white/90 backdrop-blur px-2 py-1 rounded text-[10px] font-bold text-gray-600">
                                    <i class="fas fa-box mr-1"></i> Stok: <?php echo $p->stock; ?>
                                </div>
                            </div>
                        </a>
                        <div class="p-3 flex-grow flex flex-col">
                            <a href="/marketplace/detail/<?php echo $p->id; ?>">
                                <h4 class="text-xs text-gray-800 font-medium line-clamp-2 h-8 mb-2 group-hover:text-primary transition-colors"><?php echo $p->name; ?></h4>
                            </a>
                            
                            <!-- Rating Display -->
                            <?php if(isset($p->avg_rating) && $p->review_count > 0) : ?>
                            <div class="flex items-center gap-1 mb-1">
                                <div class="flex text-yellow-400 text-[10px]">
                                    <?php for($i=1; $i<=5; $i++) : ?>
                                        <i class="<?= $i <= round($p->avg_rating) ? 'fas' : 'far'; ?> fa-star"></i>
                                    <?php endfor; ?>
                                </div>
                                <span class="text-[10px] text-gray-400">(<?= $p->review_count; ?>)</span>
                            </div>
                            <?php endif; ?>

                            <p class="text-primary font-bold text-base mb-1">Rp <?php echo number_format($p->price, 0, ',', '.'); ?></p>
                            
                            <div class="mt-auto">
                                <div class="flex items-center gap-1 mb-3">
                                    <?php if(isset($p->seller_name)) : ?>
                                    <span class="text-[10px] text-gray-400"><i class="fas fa-store mr-1"></i><?php echo $p->seller_name ?? ''; ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if($p->stock > 0) : ?>
                                <a href="/cart/add/<?php echo $p->id; ?>" class="w-full bg-orange-50 text-primary border border-primary font-bold py-2 rounded-lg text-xs hover:bg-primary hover:text-white transition-all flex items-center justify-center gap-2">
                                    <i class="fas fa-cart-plus"></i> + Keranjang
                                </a>
                                <?php else : ?>
                                <button disabled class="w-full bg-gray-100 text-gray-400 font-bold py-2 rounded-lg text-xs cursor-not-allowed">
                                    <i class="fas fa-ban"></i> Habis
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php else : ?>
            <div class="text-center py-20 bg-white rounded-2xl border-2 border-dashed border-gray-100">
                <div class="text-gray-300 text-6xl mb-4"><i class="fas fa-search"></i></div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Produk tidak ditemukan</h3>
                <p class="text-gray-500">Coba kata kunci lain atau sesuaikan filter pencarian Anda.</p>
                <a href="/marketplace" class="text-primary font-bold mt-4 inline-block hover:underline">Lihat Semua Produk</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>
