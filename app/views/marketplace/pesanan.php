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
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-6 hover:shadow-md transition overflow-hidden">
                    <!-- Items -->
                    <?php if(!empty($order->items)): ?>
                        <?php foreach($order->items as $index => $item) : ?>
                            <?php if($index == 0) : ?>
                                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                    <div class="flex items-center gap-3">
                                        <span class="font-extrabold text-gray-800 text-sm uppercase flex items-center"><i class="fas fa-store text-gray-400 mr-2"></i> <?php echo !empty($item->store_name) ? $item->store_name : $item->seller_name; ?></span>
                                        <button class="bg-primary text-white text-[10px] font-bold px-2.5 py-1 rounded flex items-center gap-1 hover:bg-orange-600 transition"><i class="fas fa-comment-alt"></i> Chat</button>
                                        <a href="/toko/<?php echo $item->seller_name; ?>" class="border border-gray-300 text-gray-600 text-[10px] font-bold px-2.5 py-1 rounded flex items-center gap-1 hover:bg-gray-100 transition"><i class="fas fa-store"></i> Kunjungi Toko</a>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-primary flex items-center gap-1"><i class="fas fa-truck"></i> <?php echo $order->status == 'Dikirim' ? 'Pesanan tiba di alamat tujuan.' : 'Status pesanan: ' . $order->status; ?></span>
                                        <span class="text-xs font-bold <?php echo strpos($status_color, 'text-') !== false ? explode(' ', $status_color)[1] : 'text-gray-500'; ?> uppercase border-l pl-2 ml-1 border-gray-300"><?php echo $order->status; ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="px-6 py-4 flex gap-4 border-b border-gray-50">
                                <img src="<?php echo $item->image_url; ?>" class="w-20 h-20 object-cover rounded border border-gray-100">
                                <div class="flex-grow">
                                    <h4 class="text-gray-800 text-base font-medium"><?php echo $item->product_name; ?></h4>
                                    <p class="text-xs text-gray-500 mt-1">Variasi: Default</p>
                                    <p class="text-sm text-gray-600 mt-1">x<?php echo $item->quantity; ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-gray-800">Rp<?php echo number_format($item->price_at_purchase, 0, ',', '.'); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- Footer Total & Actions -->
                    <div class="bg-white px-6 py-4 flex flex-col md:flex-row justify-between items-end md:items-center gap-4">
                        <div class="text-xs text-gray-500">
                            <p class="mb-1">Tanggal: <?php echo date('d-m-Y H:i', strtotime($order->created_at)); ?></p>
                            <?php if(!empty($order->smartbank_trx_id)): ?>
                            <p class="flex items-center gap-1 text-[10px]"><i class="fas fa-info-circle text-blue-500"></i> SmartBank Trx ID: <strong class="font-mono"><?php echo $order->smartbank_trx_id; ?></strong></p>
                            <?php endif; ?>
                        </div>
                        <div class="flex flex-col items-end gap-4 w-full md:w-auto">
                            <div class="flex items-center gap-3">
                                <span class="text-sm text-gray-800">Total Pesanan:</span>
                                <span class="text-2xl font-normal text-primary">Rp<?php echo number_format($order->total_payment, 0, ',', '.'); ?></span>
                            </div>
                            <div class="flex flex-wrap items-center justify-end gap-2 w-full">
                                <?php if($order->status == 'Menunggu Pembayaran' || $order->status == 'Menunggu Konfirmasi' || $order->status == 'Sedang Dikemas') : ?>
                                    <form action="/pesanan/cancel/<?php echo $order->id; ?>" method="POST" class="inline m-0">
                                        <button type="submit" class="bg-white border border-gray-300 text-gray-700 text-sm px-6 py-2 rounded hover:bg-gray-50 transition w-full md:w-auto">Batalkan Pesanan</button>
                                    </form>
                                <?php elseif($order->status == 'Dikirim') : ?>
                                    <form action="/pesanan/complete/<?php echo $order->id; ?>" method="POST" class="inline m-0">
                                        <button type="submit" class="bg-primary text-white text-sm px-6 py-2 rounded hover:bg-orange-600 transition shadow-sm w-full md:w-auto">Pesanan Selesai</button>
                                    </form>
                                    <button class="bg-white border border-gray-300 text-gray-700 text-sm px-6 py-2 rounded hover:bg-gray-50 transition w-full md:w-auto">Ajukan Pengembalian</button>
                                <?php endif; ?>
                                <button class="bg-white border border-gray-300 text-gray-700 text-sm px-6 py-2 rounded hover:bg-gray-50 transition w-full md:w-auto">Hubungi Penjual</button>
                                <a href="/pesanan/detail/<?php echo $order->id; ?>" class="bg-white border border-gray-300 text-gray-700 text-sm px-6 py-2 rounded hover:bg-gray-50 transition text-center w-full md:w-auto">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>
