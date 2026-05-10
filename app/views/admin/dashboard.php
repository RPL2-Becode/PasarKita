<?php require_once '../app/views/templates/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 py-8 fade-in">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Dashboard Admin</h1>
            <p class="text-gray-500 text-sm mt-1">Ringkasan finansial dan aktivitas PasarKita</p>
        </div>
        <div class="flex gap-3">
            <a href="/admin/orders" class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-semibold hover:border-primary hover:text-primary transition"><i class="fas fa-list-alt mr-2"></i>Monitoring Order</a>
            <a href="/admin/users" class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-semibold hover:border-primary hover:text-primary transition"><i class="fas fa-users mr-2"></i>Manajemen User</a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center"><i class="fas fa-wallet text-green-600 text-xl"></i></div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Total Revenue</p>
                    <p class="text-2xl font-extrabold text-gray-900">Rp <?php echo number_format($data['total_revenue'], 0, ',', '.'); ?></p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center"><i class="fas fa-percentage text-orange-600 text-xl"></i></div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Fee Marketplace (2%)</p>
                    <p class="text-2xl font-extrabold text-gray-900">Rp <?php echo number_format($data['total_fees'], 0, ',', '.'); ?></p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center"><i class="fas fa-shopping-bag text-blue-600 text-xl"></i></div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Total Order</p>
                    <p class="text-2xl font-extrabold text-gray-900"><?php echo $data['total_orders']; ?></p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center"><i class="fas fa-users text-purple-600 text-xl"></i></div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Total User</p>
                    <p class="text-2xl font-extrabold text-gray-900"><?php echo $data['total_users']; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Status Summary -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-center">
            <p class="text-2xl font-extrabold text-yellow-600"><?php echo $data['pending_orders']; ?></p>
            <p class="text-xs text-yellow-700 font-semibold mt-1">Menunggu Konfirmasi</p>
        </div>
        <div class="bg-cyan-50 border border-cyan-200 rounded-xl p-4 text-center">
            <p class="text-2xl font-extrabold text-cyan-600"><?php echo $data['shipped_orders']; ?></p>
            <p class="text-xs text-cyan-700 font-semibold mt-1">Dikirim</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
            <p class="text-2xl font-extrabold text-green-600"><?php echo $data['completed_orders']; ?></p>
            <p class="text-xs text-green-700 font-semibold mt-1">Selesai</p>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-center">
            <p class="text-2xl font-extrabold text-red-600"><?php echo $data['cancelled_orders']; ?></p>
            <p class="text-xs text-red-700 font-semibold mt-1">Dibatalkan</p>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-history mr-2 text-primary"></i>Transaksi Terbaru</h2>
            <a href="/admin/orders" class="text-sm text-primary font-semibold hover:underline">Lihat Semua →</a>
        </div>
        <div class="table-container" style="border:none; border-radius:0;">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Pembeli</th>
                        <th>Total</th>
                        <th>Fee</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $recent = array_slice($data['recent_orders'], 0, 10);
                    foreach($recent as $order) : 
                    ?>
                    <tr>
                        <td class="font-mono text-xs font-bold"><?php echo $order->id; ?></td>
                        <td><?php echo $order->buyer_name; ?></td>
                        <td class="font-semibold">Rp <?php echo number_format($order->total_payment, 0, ',', '.'); ?></td>
                        <td class="text-orange-600">Rp <?php echo number_format($order->fee_marketplace, 0, ',', '.'); ?></td>
                        <td>
                            <?php
                            $statusClass = 'badge-info';
                            if($order->status == 'Selesai') $statusClass = 'badge-success';
                            elseif($order->status == 'Dibatalkan') $statusClass = 'badge-danger';
                            elseif($order->status == 'Menunggu Konfirmasi' || $order->status == 'Menunggu Pembayaran') $statusClass = 'badge-warning';
                            ?>
                            <span class="badge <?php echo $statusClass; ?>"><?php echo $order->status; ?></span>
                        </td>
                        <td class="text-gray-500 text-xs"><?php echo date('d M Y H:i', strtotime($order->created_at)); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($data['recent_orders'])) : ?>
                    <tr><td colspan="6" class="text-center py-8 text-gray-400">Belum ada transaksi</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>
