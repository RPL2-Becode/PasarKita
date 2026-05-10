<?php require_once '../app/views/templates/header.php'; ?>

<div class="max-w-4xl mx-auto px-4 py-8 fade-in">
    <div class="flex items-center gap-4 mb-6">
        <a href="/pesanan" class="w-10 h-10 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-full flex items-center justify-center transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900">Detail Pesanan</h2>
            <p class="text-gray-500 font-mono text-sm">#<?php echo $data['order']->id; ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-4 bg-gray-50 border-b border-gray-100 font-bold text-gray-700">Daftar Produk</div>
                <div class="p-6 space-y-4">
                    <?php foreach($data['items'] as $item) : ?>
                        <div class="flex items-center gap-4 pb-4 border-b border-gray-50 last:border-0 last:pb-0">
                            <div class="w-16 h-16 bg-gray-100 rounded-xl overflow-hidden shrink-0">
                                <?php if(isset($item->image_url) && $item->image_url) : ?>
                                    <img src="<?php echo $item->image_url; ?>" class="w-full h-full object-cover">
                                <?php else : ?>
                                    <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fas fa-box"></i></div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow">
                                <h4 class="font-bold text-gray-800"><?php echo isset($item->product_name) ? $item->product_name : 'Produk telah dihapus'; ?></h4>
                                <p class="text-sm text-gray-500"><?php echo $item->quantity; ?> x Rp <?php echo number_format($item->price_at_purchase, 0, ',', '.'); ?></p>
                            </div>
                            <div class="text-right">
                                <p class="font-extrabold text-primary">Rp <?php echo number_format($item->quantity * $item->price_at_purchase, 0, ',', '.'); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Status & Info -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Informasi</h3>
                
                <div class="mb-4">
                    <p class="text-xs text-gray-500 mb-1">Status Pesanan</p>
                    <span class="inline-block bg-blue-50 text-blue-600 font-bold px-3 py-1 rounded-lg text-sm"><?php echo $data['order']->status; ?></span>
                </div>
                
                <div class="mb-4">
                    <p class="text-xs text-gray-500 mb-1">Tanggal Transaksi</p>
                    <p class="font-semibold text-gray-800 text-sm"><?php echo date('d M Y, H:i', strtotime($data['order']->created_at)); ?></p>
                </div>
                
                <div>
                    <p class="text-xs text-gray-500 mb-1">SmartBank Trx ID</p>
                    <p class="font-mono text-gray-800 text-xs bg-gray-50 p-2 rounded"><?php echo $data['order']->smartbank_trx_id ?? '-'; ?></p>
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Ringkasan Pembayaran</h3>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Harga Barang</span>
                        <span class="font-semibold text-gray-800">Rp <?php echo number_format($data['order']->total_subtotal, 0, ',', '.'); ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Ongkos Kirim</span>
                        <span class="font-semibold text-gray-800">Rp <?php echo number_format($data['order']->fee_shipping, 0, ',', '.'); ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Biaya Layanan (2%)</span>
                        <span class="font-semibold text-gray-800">Rp <?php echo number_format($data['order']->fee_marketplace, 0, ',', '.'); ?></span>
                    </div>
                </div>
                
                <div class="border-t border-dashed border-gray-200 pt-4 flex justify-between items-center">
                    <span class="font-bold text-gray-800">Total Belanja</span>
                    <span class="text-xl font-extrabold text-primary">Rp <?php echo number_format($data['order']->total_payment, 0, ',', '.'); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>
