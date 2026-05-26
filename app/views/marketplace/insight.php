<div class="max-w-7xl mx-auto px-4 py-8 fade-in">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900"><i class="fas fa-chart-line text-primary mr-3"></i> UMKM Insight</h1>
            <p class="text-gray-500 text-sm mt-1">Pantau performa penjualan dan produk terlaris toko Anda</p>
        </div>
        <div class="bg-orange-50 text-orange-700 px-4 py-2 rounded-lg font-bold text-sm border border-orange-200">
            <i class="fas fa-crown mr-2"></i>Insight Premium Aktif
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Pemasukan Bersih -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-5 shadow-sm border border-green-100 flex flex-col justify-between card-hover">
            <div class="flex justify-between items-start mb-2">
                <p class="text-xs text-green-700 font-bold uppercase tracking-wider">Pemasukan Toko</p>
                <div class="w-8 h-8 bg-green-200 rounded-full flex items-center justify-center text-green-700"><i class="fas fa-wallet"></i></div>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-green-900">Rp <?php echo number_format($data['net_revenue'], 0, ',', '.'); ?></p>
                <p class="text-[10px] text-green-600 mt-1 font-semibold">Pendapatan bersih Anda</p>
            </div>
        </div>

        <!-- Pemasukan Kotor -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col justify-between card-hover">
            <div class="flex justify-between items-start mb-2">
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Total Penjualan</p>
                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-500"><i class="fas fa-money-bill-wave"></i></div>
            </div>
            <div>
                <p class="text-xl font-bold text-gray-800">Rp <?php echo number_format($data['gross_revenue'], 0, ',', '.'); ?></p>
                <p class="text-[10px] text-gray-400 mt-1">Pemasukan kotor (Gross)</p>
            </div>
        </div>

        <!-- Potongan Fee -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col justify-between card-hover">
            <div class="flex justify-between items-start mb-2">
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Potongan Fee (2%)</p>
                <div class="w-8 h-8 bg-red-50 rounded-full flex items-center justify-center text-red-500"><i class="fas fa-percentage"></i></div>
            </div>
            <div>
                <p class="text-xl font-bold text-red-600">-Rp <?php echo number_format($data['fee_deduction'], 0, ',', '.'); ?></p>
                <p class="text-[10px] text-gray-400 mt-1">Biaya layanan marketplace</p>
            </div>
        </div>
        
        <!-- Total Pesanan -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col justify-between card-hover">
            <div class="flex justify-between items-start mb-2">
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Total Pesanan</p>
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600"><i class="fas fa-box"></i></div>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900"><?php echo $data['order_count']; ?></p>
                <p class="text-[10px] text-gray-400 mt-1">Pesanan berhasil diproses</p>
            </div>
        </div>
    </div>

    <!-- Top Products -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-star text-yellow-400 mr-2"></i>Produk Terlaris Anda</h2>
        </div>
        
        <div class="p-0">
            <?php if(empty($data['top_products'])): ?>
                <div class="p-8 text-center text-gray-500 flex flex-col items-center">
                    <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                    <p class="font-semibold">Belum ada data penjualan produk.</p>
                </div>
            <?php else: ?>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                            <th class="p-4 font-bold">Produk</th>
                            <th class="p-4 font-bold text-center">Terjual</th>
                            <th class="p-4 font-bold text-right">Total Penjualan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['top_products'] as $product): ?>
                            <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                                <td class="p-4 flex items-center gap-4">
                                    <img src="<?php echo $product->image_url; ?>" class="w-12 h-12 rounded object-cover border border-gray-200 shadow-sm">
                                    <span class="font-bold text-gray-800"><?php echo $product->name; ?></span>
                                </td>
                                <td class="p-4 text-center font-bold text-primary">
                                    <?php echo $product->sold_count; ?>
                                </td>
                                <td class="p-4 text-right font-bold text-gray-700">
                                    Rp <?php echo number_format($product->revenue, 0, ',', '.'); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
