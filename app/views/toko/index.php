<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Store Header Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <!-- Banner -->
        <?php if(!empty($data['seller']->store_banner)): ?>
            <div class="h-32 bg-gray-200">
                <img src="/uploads/banner/<?php echo $data['seller']->store_banner; ?>" alt="Banner Toko" class="w-full h-full object-cover">
            </div>
        <?php else: ?>
            <div class="h-32 bg-gradient-to-r from-orange-400 to-primary"></div>
        <?php endif; ?>
        
        <div class="px-8 pb-8 relative flex flex-col md:flex-row gap-6 items-start md:items-center">
            <!-- Profile Picture -->
            <div class="-mt-12 w-32 h-32 rounded-full border-4 border-white bg-white shadow-md overflow-hidden shrink-0 flex items-center justify-center relative">
                <?php if(!empty($data['seller']->profile_picture)): ?>
                    <img src="/uploads/profile/<?php echo $data['seller']->profile_picture; ?>" alt="Store" class="w-full h-full object-cover">
                <?php else: ?>
                    <i class="fas fa-store text-6xl text-gray-400"></i>
                <?php endif; ?>
            </div>
            
            <!-- Info -->
            <div class="flex-1 mt-2 md:mt-0">
                <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <?php echo !empty($data['seller']->store_name) ? $data['seller']->store_name : $data['seller']->username; ?>
                    <span class="bg-primary text-white text-[10px] px-2 py-0.5 rounded font-bold uppercase tracking-wide shadow-sm">Star+</span>
                </h1>
                <p class="text-green-500 text-sm font-medium mb-3"><i class="fas fa-circle text-[10px] mr-1"></i> Aktif baru saja</p>
                <div class="flex flex-wrap gap-2">
                    <button class="px-4 py-1.5 border border-primary text-primary text-sm font-semibold rounded hover:bg-orange-50 transition">
                        <i class="fas fa-plus mr-1"></i> Ikuti
                    </button>
                    <button class="px-4 py-1.5 border border-gray-300 text-gray-700 text-sm font-semibold rounded hover:bg-gray-50 transition">
                        <i class="fas fa-comment-dots mr-1"></i> Chat
                    </button>
                </div>
            </div>
            
            <!-- Stats Grid -->
            <div class="grid grid-cols-2 gap-x-12 gap-y-3 text-sm text-gray-600 border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0 md:pl-8 mt-4 md:mt-0 w-full md:w-auto">
                <div class="flex items-center gap-2">
                    <i class="fas fa-box text-gray-400 w-4"></i>
                    <span>Produk: <strong class="text-primary"><?php echo $data['total_products']; ?></strong></span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-users text-gray-400 w-4"></i>
                    <span>Pengikut: <strong class="text-primary">--</strong></span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-star text-gray-400 w-4"></i>
                    <span>Penilaian: <strong class="text-primary"><?php echo $data['store_avg_rating']; ?></strong></span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-gray-400 w-4"></i>
                    <span>Bergabung: <strong class="text-primary"><?php echo date('Y', strtotime($data['seller']->created_at)); ?></strong></span>
                </div>
            </div>
        </div>
        
        <?php if(!empty($data['seller']->store_description)) : ?>
        <div class="px-8 pb-8 border-t border-gray-50 pt-4 text-sm text-gray-600 leading-relaxed">
            <h3 class="font-bold text-gray-800 mb-2">Deskripsi Toko</h3>
            <?php echo nl2br($data['seller']->store_description); ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Navigation Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
        <div class="flex border-b border-gray-100">
            <?php 
                $activeTab = $data['active_tab'] ?? 'semua'; 
                $tabClass = "px-6 py-4 font-semibold transition";
                $activeClass = " text-primary font-bold border-b-2 border-primary";
                $inactiveClass = " text-gray-500 hover:text-primary";
            ?>
            <a href="?tab=semua" class="<?php echo $tabClass . ($activeTab == 'semua' ? $activeClass : $inactiveClass); ?>">Semua Produk</a>
            <a href="?tab=terlaris" class="<?php echo $tabClass . ($activeTab == 'terlaris' ? $activeClass : $inactiveClass); ?>">Terlaris</a>
            <a href="?tab=terbaru" class="<?php echo $tabClass . ($activeTab == 'terbaru' ? $activeClass : $inactiveClass); ?>">Terbaru</a>
        </div>
    </div>

    <!-- Product List -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
        <?php if(empty($data['products'])) : ?>
            <div class="col-span-full text-center py-20 bg-white rounded-xl shadow-sm border border-gray-100">
                <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-500">Toko belum memiliki produk</h3>
            </div>
        <?php else : ?>
            <?php foreach($data['products'] as $p) : ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group relative flex flex-col">
                    <?php if($p->stock == 0) : ?>
                        <div class="absolute inset-0 bg-black/40 z-10 flex items-center justify-center">
                            <span class="bg-white text-gray-800 font-bold px-4 py-1 rounded-full text-sm">Habis</span>
                        </div>
                    <?php endif; ?>
                    
                    <a href="/marketplace/detail/<?php echo $p->id; ?>" class="block relative pt-[100%] overflow-hidden bg-gray-50">
                        <?php if(!empty($p->image_url)) : ?>
                            <img src="<?php echo $p->image_url; ?>" alt="<?php echo $p->name; ?>" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        <?php else : ?>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fas fa-image text-4xl text-gray-300"></i>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($p->id == $data['top_sold_product_id']) : ?>
                        <div class="absolute top-2 left-2">
                            <span class="bg-gradient-to-r from-primary to-orange-400 text-white text-[10px] px-1.5 py-0.5 rounded font-bold uppercase shadow-sm flex items-center gap-1">
                                <i class="fas fa-crown text-[8px]"></i> Terlaris
                            </span>
                        </div>
                        <?php endif; ?>
                    </a>
                    
                    <div class="p-3 flex flex-col flex-grow">
                        <a href="/marketplace/detail/<?php echo $p->id; ?>" class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2 group-hover:text-primary transition h-10 leading-snug">
                            <?php echo $p->name; ?>
                        </a>
                        
                        <div class="mt-auto">
                            <p class="text-primary font-bold text-lg mb-2">Rp <?php echo number_format($p->price, 0, ',', '.'); ?></p>
                            
                            <div class="flex items-center justify-between text-xs text-gray-500 border-t border-gray-100 pt-2">
                                <span class="flex items-center <?php echo $p->avg_rating > 0 ? 'text-yellow-400' : 'text-gray-300'; ?> font-medium">
                                    <i class="fas fa-star mr-1"></i> <?php echo $p->avg_rating > 0 ? number_format($p->avg_rating, 1) : '--'; ?>
                                </span>
                                <span><?php echo $p->sold_count > 0 ? $p->sold_count . ' Terjual' : '-- Terjual'; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
