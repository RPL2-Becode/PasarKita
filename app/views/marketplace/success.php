<?php require_once '../app/views/templates/header.php'; ?>

<div class="max-w-xl mx-auto px-4 py-20 text-center fade-in">
    <div class="w-24 h-24 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-8 text-4xl shadow-xl shadow-green-100">
        <i class="fas fa-check"></i>
    </div>
    
    <h1 class="text-4xl font-extrabold text-gray-900 mb-4">Pesanan Berhasil!</h1>
    <p class="text-gray-500 mb-10 leading-relaxed text-lg">
        Terima kasih atas pesanan Anda. Transaksi telah dikonfirmasi oleh <span class="font-bold text-blue-600">SmartBank</span>. Penjual akan segera memproses barang Anda.
    </p>

    <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm mb-10 text-left">
        <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-50">
            <span class="text-gray-400 text-sm">ID Pesanan</span>
            <span class="font-mono font-bold text-gray-800"><?php echo $data['order_id']; ?></span>
        </div>
        <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-50">
            <span class="text-gray-400 text-sm">Total Pembayaran</span>
            <span class="text-primary font-extrabold text-xl">Rp <?php echo number_format($data['total_payment'], 0, ',', '.'); ?></span>
        </div>
        <?php if(isset($data['smartbank_trx_id'])) : ?>
        <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-50">
            <span class="text-gray-400 text-sm">SmartBank Trx ID</span>
            <span class="font-mono font-bold text-blue-600"><?php echo $data['smartbank_trx_id']; ?></span>
        </div>
        <?php endif; ?>
        <div class="flex justify-between items-center">
            <span class="text-gray-400 text-sm">Metode</span>
            <span class="text-blue-600 font-bold flex items-center gap-2"><i class="fas fa-university"></i> SmartBank API</span>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="/marketplace" class="bg-primary text-white font-bold px-8 py-4 rounded-xl hover:bg-orange-600 transition shadow-lg shadow-orange-100">
            Kembali Belanja
        </a>
        <a href="/home" class="bg-white text-gray-700 border border-gray-200 font-bold px-8 py-4 rounded-xl hover:border-primary transition">
            Lihat Status Pesanan
        </a>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>
