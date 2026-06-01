<?php require_once '../app/views/templates/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 py-8 fade-in">
    <!-- Seller Dashboard Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <?php if(!empty($data['seller']->store_banner)): ?>
            <div class="h-24 bg-gray-200">
                <img src="/uploads/banner/<?php echo $data['seller']->store_banner; ?>" alt="Banner Toko" class="w-full h-full object-cover">
            </div>
        <?php else: ?>
            <div class="h-24 bg-gradient-to-r from-orange-400 to-primary"></div>
        <?php endif; ?>
        <div class="px-8 pb-6 relative flex flex-col md:flex-row gap-6 items-start md:items-center justify-between">
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
            <div class="mt-4 md:mt-0 flex gap-2">
                <a href="/products" class="px-4 py-2 border border-gray-200 text-gray-600 font-bold rounded-lg hover:bg-gray-50 transition flex items-center gap-2 text-sm">
                    <i class="fas fa-box"></i> Produk Saya
                </a>
                <a href="/profile/edit" class="px-4 py-2 border border-primary text-primary font-bold rounded-lg hover:bg-orange-50 transition flex items-center gap-2 text-sm">
                    <i class="fas fa-user-edit"></i> Edit Profil
                </a>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900">Kelola Pesanan Masuk</h2>
            <p class="text-gray-500 text-xs mt-0.5">Konfirmasi pesanan baru, kelola proses packing, dan atur pengiriman produk Anda.</p>
        </div>
    </div>

    <?php flash('order_message'); ?>

    <!-- Status Filters -->
    <div class="flex flex-wrap gap-2 mb-8">
        <a href="/products/orders" class="px-4 py-2 rounded-full text-xs font-bold transition <?php echo empty($data['status_filter']) ? 'bg-primary text-white shadow-md shadow-orange-100' : 'bg-white text-gray-600 border border-gray-200 hover:border-primary'; ?>">Semua</a>
        <?php
        $statuses = ['Menunggu Konfirmasi', 'Sedang Dikemas', 'Diserahkan ke Kurir', 'Dikirim', 'Selesai', 'Dibatalkan', 'Pengajuan Pembatalan', 'Pengajuan Pengembalian', 'Dikembalikan'];
        foreach($statuses as $st) :
        ?>
        <a href="/products/orders?status=<?php echo urlencode($st); ?>" class="px-4 py-2 rounded-full text-xs font-bold transition <?php echo ($data['status_filter'] == $st) ? 'bg-primary text-white shadow-md shadow-orange-100' : 'bg-white text-gray-600 border border-gray-200 hover:border-primary'; ?>"><?php echo $st; ?></a>
        <?php endforeach; ?>
    </div>

    <!-- Orders Cards -->
    <div class="space-y-6">
        <?php foreach($data['orders'] as $order) : ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                <!-- Card Header -->
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="bg-orange-50 text-primary p-2 rounded-lg text-sm">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 font-medium">Order ID</span>
                            <h4 class="font-mono text-sm font-bold text-gray-800"><?php echo $order->id; ?></h4>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-6">
                        <div>
                            <span class="text-xs text-gray-400 block text-right">Pembeli</span>
                            <span class="text-sm font-semibold text-gray-700 flex items-center gap-1 justify-end">
                                <i class="fas fa-user text-gray-400 text-xs"></i><?php echo $order->buyer_name; ?>
                                <a href="/chat/index/<?php echo $order->buyer_id; ?>?order_id=<?php echo $order->id; ?>" class="text-primary hover:text-orange-700 ml-1" title="Chat Pembeli"><i class="fas fa-comment-dots"></i></a>
                            </span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 block text-right">Tanggal</span>
                            <span class="text-xs font-medium text-gray-600"><?php echo date('d M Y, H:i', strtotime($order->created_at)); ?></span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 block text-right">Status</span>
                            <?php
                            $badgeStyle = 'bg-gray-100 text-gray-600';
                            if($order->status == 'Menunggu Konfirmasi') $badgeStyle = 'bg-yellow-50 text-yellow-600 border border-yellow-100';
                            elseif($order->status == 'Sedang Dikemas') $badgeStyle = 'bg-orange-50 text-orange-600 border border-orange-100';
                            elseif($order->status == 'Diserahkan ke Kurir') $badgeStyle = 'bg-purple-50 text-purple-600 border border-purple-100';
                            elseif($order->status == 'Dikirim') $badgeStyle = 'bg-blue-50 text-blue-600 border border-blue-100';
                            elseif($order->status == 'Selesai') $badgeStyle = 'bg-green-50 text-green-600 border border-green-100';
                            elseif($order->status == 'Dibatalkan' || $order->status == 'Dikembalikan') $badgeStyle = 'bg-red-50 text-red-600 border border-red-100';
                            elseif($order->status == 'Pengajuan Pembatalan' || $order->status == 'Pengajuan Pengembalian') $badgeStyle = 'bg-orange-50 text-orange-700 border border-orange-200';
                            ?>
                            <span class="px-3 py-1 rounded-full text-xs font-extrabold tracking-wide inline-block <?php echo $badgeStyle; ?>">
                                <?php echo $order->status; ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Card Body (Items) -->
                <div class="p-6 divide-y divide-gray-100">
                    <div class="pb-4 space-y-4">
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Produk dari Toko Anda:</span>
                        <?php 
                        $seller_subtotal = 0;
                        foreach($order->items as $item) : 
                            $item_total = $item->price_at_purchase * $item->quantity;
                            $seller_subtotal += $item_total;
                        ?>
                            <div class="flex items-center justify-between gap-4 py-1">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-lg bg-gray-50 border border-gray-100 overflow-hidden shrink-0 flex items-center justify-center">
                                        <?php if($item->image_url): ?>
                                            <img src="<?php echo $item->image_url; ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <i class="fas fa-image text-gray-300 text-lg"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-bold text-gray-800"><?php echo $item->product_name; ?></h5>
                                        <p class="text-xs text-gray-500">Rp <?php echo number_format($item->price_at_purchase, 0, ',', '.'); ?> x <?php echo $item->quantity; ?></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-bold text-gray-800">Rp <?php echo number_format($item_total, 0, ',', '.'); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Summary & Actions -->
                    <div class="pt-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <div class="text-xs text-gray-400">Total Penghasilan Toko:</div>
                            <div class="text-lg font-extrabold text-primary">Rp <?php echo number_format($seller_subtotal, 0, ',', '.'); ?></div>
                            <div class="text-[10px] text-gray-400">(Estimasi bersih setelah potongan fee 2% marketplace: <span class="font-bold">Rp <?php echo number_format($seller_subtotal * 0.98, 0, ',', '.'); ?></span>)</div>
                        </div>

                        <!-- Action Forms -->
                        <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                            <?php if($order->status == 'Menunggu Konfirmasi') : ?>
                                <form action="/products/update_order_status" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memproses pesanan ini ke tahap pengemasan?')">
                                    <input type="hidden" name="order_id" value="<?php echo $order->id; ?>">
                                    <input type="hidden" name="status" value="Sedang Dikemas">
                                    <button type="submit" class="w-full sm:w-auto bg-orange-500 text-white font-bold px-6 py-2.5 rounded-lg text-xs hover:bg-orange-600 transition flex items-center justify-center gap-2 shadow-sm">
                                        <i class="fas fa-box-open"></i> Konfirmasi & Kemas
                                    </button>
                                </form>
                            <?php elseif($order->status == 'Sedang Dikemas') : ?>
                                <form action="/products/update_order_status" method="POST" onsubmit="return confirm('Apakah Anda yakin paket telah diserahkan ke Jasa Pengiriman?')">
                                    <input type="hidden" name="order_id" value="<?php echo $order->id; ?>">
                                    <input type="hidden" name="status" value="Diserahkan ke Kurir">
                                    <button type="submit" class="w-full sm:w-auto bg-purple-600 text-white font-bold px-6 py-2.5 rounded-lg text-xs hover:bg-purple-700 transition flex items-center justify-center gap-2 shadow-sm">
                                        <i class="fas fa-truck-loading"></i> Serahkan ke Jasa Kirim
                                    </button>
                                </form>
                            <?php elseif($order->status == 'Diserahkan ke Kurir') : ?>
                                <div class="text-right text-xs text-purple-600 font-bold flex items-center gap-1.5 bg-purple-50 px-4 py-2.5 rounded-lg border border-purple-100 shadow-sm animate-pulse">
                                    <i class="fas fa-truck text-purple-500"></i> Paket Diserahkan — Menunggu Validasi & Nomor Resi dari Admin/Operator
                                </div>
                            <?php elseif($order->status == 'Dikirim') : ?>
                                <div class="text-right">
                                    <div class="text-xs text-gray-400 font-medium">Jasa Pengiriman: <span class="font-bold text-blue-600"><?php echo $order->shipping_service; ?></span></div>
                                    <div class="text-xs font-mono text-gray-600 bg-gray-50 px-3 py-1.5 rounded border border-gray-100 mt-1 inline-block"><i class="fas fa-receipt mr-1 text-gray-400"></i><?php echo $order->resi_number; ?></div>
                                    <div class="text-[10px] text-blue-500 mt-1 font-bold"><i class="fas fa-hourglass-half mr-0.5"></i> Menunggu pembeli menyelesaikan pesanan</div>
                                </div>
                            <?php elseif($order->status == 'Selesai') : ?>
                                <div class="text-right text-xs text-green-600 font-bold flex items-center gap-1.5 bg-green-50 px-3 py-1.5 rounded-lg border border-green-100">
                                    <i class="fas fa-check-circle"></i> Pesanan Selesai & Dana Cair
                                </div>
                            <?php elseif($order->status == 'Dibatalkan' || $order->status == 'Dikembalikan') : ?>
                                <div class="text-right text-xs text-red-600 font-bold flex items-center gap-1.5 bg-red-50 px-3 py-1.5 rounded-lg border border-red-100">
                                    <i class="fas fa-times-circle"></i> Pesanan <?php echo $order->status; ?>
                                </div>
                            <?php elseif($order->status == 'Pengajuan Pembatalan' || $order->status == 'Pengajuan Pengembalian') : ?>
                                <div class="text-right text-xs text-orange-600 font-bold flex items-center gap-1.5 bg-orange-50 px-3 py-1.5 rounded-lg border border-orange-100">
                                    <i class="fas fa-info-circle"></i> <?php echo $order->status; ?> sedang diproses Admin
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if(empty($data['orders'])) : ?>
            <div class="text-center py-20 bg-white rounded-xl border-2 border-dashed border-gray-200 shadow-sm">
                <i class="fas fa-box-open text-5xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 font-medium">Tidak ada pesanan masuk dalam kategori ini.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleResiForm(id) {
    const el = document.getElementById(id);
    if(el) {
        el.classList.toggle('hidden');
    }
}
</script>

<?php require_once '../app/views/templates/footer.php'; ?>
