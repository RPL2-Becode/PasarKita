<?php require_once '../app/views/templates/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 py-8 fade-in">
    <!-- Breadcrumbs / Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Jelajahi Produk <span class="text-primary">UMKM</span></h1>
            <p class="text-gray-500">Menampilkan semua produk pilihan terbaik untukmu</p>
        </div>
        
        <!-- Mobile Search (Visible on small screens) -->
        <form action="/marketplace" method="GET" class="md:hidden relative">
            <input type="text" name="search" placeholder="Cari produk..." class="w-full border border-gray-200 rounded-lg py-2 px-10 focus:border-primary outline-none" value="<?php echo $data['search']; ?>">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        </form>
    </div>

    <?php flash('cart_message'); ?>

    <?php if(!empty($data['search'])) : ?>
        <div class="mb-6 text-gray-600 italic">
            Menampilkan hasil pencarian untuk: <span class="font-bold text-primary">"<?php echo $data['search']; ?>"</span>
            <a href="/marketplace" class="ml-2 text-sm text-red-500 hover:underline"><i class="fas fa-times"></i> Reset</a>
        </div>
    <?php endif; ?>

    <!-- Category Filter -->
    <?php if(isset($data['categories']) && !empty($data['categories'])) : ?>
    <div class="flex flex-wrap gap-2 mb-8">
        <a href="/marketplace" class="px-4 py-2 rounded-full text-sm font-semibold transition <?php echo empty($data['selected_category']) ? 'bg-primary text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:border-primary hover:text-primary'; ?>">
            <i class="fas fa-th mr-1"></i> Semua
        </a>
        <?php foreach($data['categories'] as $cat) : ?>
        <a href="/marketplace?category=<?php echo $cat->id; ?>" class="px-4 py-2 rounded-full text-sm font-semibold transition <?php echo ($data['selected_category'] == $cat->id) ? 'bg-primary text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:border-primary hover:text-primary'; ?>">
            <?php echo $cat->name; ?>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Product Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
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
                        <div class="absolute bottom-2 left-2 bg-white/90 backdrop-blur px-2 py-1 rounded text-[10px] font-bold text-gray-600">
                            <i class="fas fa-box mr-1"></i> Stok: <?php echo $p->stock; ?>
                        </div>
                    </div>
                </a>
                <div class="p-3 flex-grow flex flex-col">
                    <a href="/marketplace/detail/<?php echo $p->id; ?>">
                        <h4 class="text-xs text-gray-800 font-medium line-clamp-2 h-8 mb-2 group-hover:text-primary transition-colors"><?php echo $p->name; ?></h4>
                    </a>
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

    <?php if(empty($data['products'])) : ?>
        <div class="text-center py-20 bg-white rounded-2xl border-2 border-dashed border-gray-100">
            <div class="text-gray-300 text-6xl mb-4"><i class="fas fa-search"></i></div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Produk tidak ditemukan</h3>
            <p class="text-gray-500">Coba kata kunci lain atau jelajahi kategori kami.</p>
            <a href="/marketplace" class="text-primary font-bold mt-4 inline-block hover:underline">Lihat Semua Produk</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>
