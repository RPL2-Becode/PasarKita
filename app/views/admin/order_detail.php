<?php require_once '../app/views/templates/header.php'; ?>

<div class="max-w-4xl mx-auto px-4 py-8 fade-in">
    <a href="/admin/orders" class="text-gray-500 hover:text-primary text-sm mb-6 inline-flex items-center gap-1">
        <i class="fas fa-arrow-left"></i> Kembali ke Monitoring
    </a>

    <?php if(isset($data['order']) && $data['order']) : $o = $data['order']; ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mt-4">
        <div class="p-6 bg-gray-50 border-b border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900">Order <?php echo $o->id; ?></h1>
                    <p class="text-sm text-gray-500 mt-1">Dipesan pada <?php echo date('d M Y, H:i WIB', strtotime($o->created_at)); ?></p>
                </div>
                <div>
                    <?php
                    $statusClass = 'badge-info';
                    if($o->status == 'Selesai') $statusClass = 'badge-success';
                    elseif($o->status == 'Dibatalkan') $statusClass = 'badge-danger';
                    elseif($o->status == 'Menunggu Konfirmasi' || $o->status == 'Menunggu Pembayaran') $statusClass = 'badge-warning';
                    ?>
                    <span class="badge <?php echo $statusClass; ?> text-sm px-4 py-1"><?php echo $o->status; ?></span>
                </div>
            </div>
        </div>

        <!-- Buyer Info -->
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-3">Informasi Pembeli</h2>
            <p class="font-semibold text-gray-800"><i class="fas fa-user mr-2 text-gray-400"></i><?php echo $o->buyer_name; ?></p>
        </div>

        <!-- Order Items -->
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Item Pesanan</h2>
            <div class="space-y-3">
                <?php foreach($data['items'] as $item) : ?>
                <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-xl">
                    <img src="<?php echo $item->image_url ? $item->image_url : 'https://via.placeholder.com/60'; ?>" class="w-14 h-14 rounded-lg object-cover bg-gray-200">
                    <div class="flex-grow">
                        <p class="font-semibold text-gray-800"><?php echo $item->product_name; ?></p>
                        <p class="text-sm text-gray-500"><?php echo $item->quantity; ?> × Rp <?php echo number_format($item->price_at_purchase, 0, ',', '.'); ?></p>
                    </div>
                    <p class="font-bold text-gray-900">Rp <?php echo number_format($item->quantity * $item->price_at_purchase, 0, ',', '.'); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="p-6">
            <h2 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Ringkasan Pembayaran</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>Rp <?php echo number_format($o->total_subtotal, 0, ',', '.'); ?></span></div>
                <div class="flex justify-between"><span class="text-gray-500">Biaya Layanan (2%)</span><span class="text-orange-600">Rp <?php echo number_format($o->fee_marketplace, 0, ',', '.'); ?></span></div>
                <div class="flex justify-between"><span class="text-gray-500">Ongkos Kirim</span><span>Rp <?php echo number_format($o->fee_shipping, 0, ',', '.'); ?></span></div>
                <hr class="my-2">
                <div class="flex justify-between text-base font-extrabold"><span>Total Pembayaran</span><span class="text-primary">Rp <?php echo number_format($o->total_payment, 0, ',', '.'); ?></span></div>
            </div>
            <?php if(!empty($o->smartbank_trx_id)) : ?>
            <div class="mt-4 p-3 bg-blue-50 rounded-xl text-sm">
                <i class="fas fa-university mr-1 text-blue-500"></i> SmartBank Trx ID: <strong class="font-mono"><?php echo $o->smartbank_trx_id; ?></strong>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php else : ?>
    <div class="text-center py-20">
        <i class="fas fa-exclamation-triangle text-6xl text-gray-300 mb-4"></i>
        <h2 class="text-xl font-bold text-gray-500">Order tidak ditemukan</h2>
    </div>
    <?php endif; ?>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>
