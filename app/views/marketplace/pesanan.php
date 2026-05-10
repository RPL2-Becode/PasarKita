<?php require_once '../app/views/templates/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 py-8 fade-in">
    <div class="flex items-center gap-3 mb-8">
        <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center text-xl shadow-inner">
            <i class="fas fa-shopping-bag"></i>
        </div>
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900">Pesanan Saya</h2>
            <p class="text-gray-500 text-sm">Pantau status pesanan dan riwayat belanja Anda.</p>
        </div>
    </div>

    <?php if(empty($data['orders'])) : ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-cart-2130356-1800917.png" class="w-48 mx-auto opacity-70 mb-4" alt="Empty">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Belum ada pesanan</h3>
            <p class="text-gray-500 mb-6">Anda belum pernah melakukan pemesanan di PasarKita.</p>
            <a href="/marketplace" class="bg-primary text-white font-bold py-3 px-6 rounded-xl hover:bg-orange-600 transition shadow-lg inline-block">Mulai Belanja</a>
        </div>
    <?php else : ?>
        <div class="grid grid-cols-1 gap-6">
            <?php foreach($data['orders'] as $order) : ?>
                <?php 
                    // Set color based on status
                    $status_color = 'bg-gray-100 text-gray-600';
                    $status_icon = 'fa-clock';
                    
                    if($order->status == 'Menunggu Pembayaran') { $status_color = 'bg-yellow-100 text-yellow-700'; $status_icon = 'fa-wallet'; }
                    elseif($order->status == 'Menunggu Konfirmasi') { $status_color = 'bg-blue-100 text-blue-700'; $status_icon = 'fa-hourglass-half'; }
                    elseif($order->status == 'Sedang Dikemas') { $status_color = 'bg-purple-100 text-purple-700'; $status_icon = 'fa-box'; }
                    elseif($order->status == 'Dikirim') { $status_color = 'bg-indigo-100 text-indigo-700'; $status_icon = 'fa-truck'; }
                    elseif($order->status == 'Selesai') { $status_color = 'bg-green-100 text-green-700'; $status_icon = 'fa-check-circle'; }
                    elseif($order->status == 'Dibatalkan') { $status_color = 'bg-red-100 text-red-700'; $status_icon = 'fa-times-circle'; }
                ?>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md transition">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-50 pb-4 mb-4 gap-4">
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider"><i class="fas fa-shopping-bag mr-1"></i> Belanja</span>
                                <span class="text-xs text-gray-400"><?php echo date('d M Y, H:i', strtotime($order->created_at)); ?></span>
                                <span class="text-[10px] <?php echo $status_color; ?> px-2 py-0.5 rounded-full font-bold uppercase tracking-wider"><i class="fas <?php echo $status_icon; ?> mr-1"></i> <?php echo $order->status; ?></span>
                            </div>
                            <h4 class="font-mono text-primary font-bold text-lg"><?php echo $order->id; ?></h4>
                        </div>
                        <div class="text-left md:text-right">
                            <p class="text-xs text-gray-500 mb-1">Total Pembayaran</p>
                            <p class="text-xl font-extrabold text-gray-900">Rp <?php echo number_format($order->total_payment, 0, ',', '.'); ?></p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between mt-4 bg-gray-50 p-3 rounded-xl border border-gray-100">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <i class="fas fa-info-circle text-blue-500"></i>
                            <span>SmartBank Trx ID: <strong class="font-mono text-gray-800"><?php echo $order->smartbank_trx_id ?? 'N/A'; ?></strong></span>
                        </div>
                        <a href="/pesanan/detail/<?php echo $order->id; ?>" class="text-sm font-bold text-primary hover:text-orange-700 bg-orange-100 hover:bg-orange-200 px-4 py-1.5 rounded-lg transition">Lihat Detail</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>
