<?php require_once '../app/views/templates/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 py-8 fade-in">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Monitoring Transaksi</h1>
            <p class="text-gray-500 text-sm mt-1">Pantau, kelola status order, dan input nomor resi</p>
        </div>
        <div class="flex gap-3">
            <?php if($_SESSION['user_role'] == 'admin') : ?>
            <a href="/admin/dashboard" class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-semibold hover:border-primary hover:text-primary transition"><i class="fas fa-chart-bar mr-2"></i>Dashboard</a>
            <?php endif; ?>
        </div>
    </div>

    <?php flash('order_message'); ?>

    <!-- Status Filter -->
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="/admin/orders" class="px-4 py-2 rounded-full text-sm font-semibold transition <?php echo empty($data['status_filter']) ? 'bg-primary text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-primary'; ?>">Semua</a>
        <?php
        $statuses = ['Menunggu Pembayaran', 'Menunggu Konfirmasi', 'Sedang Dikemas', 'Dikirim', 'Selesai', 'Dibatalkan', 'Pengajuan Pembatalan'];
        foreach($statuses as $st) :
        ?>
        <a href="/admin/orders?status=<?php echo urlencode($st); ?>" class="px-4 py-2 rounded-full text-sm font-semibold transition <?php echo ($data['status_filter'] == $st) ? 'bg-primary text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-primary'; ?>"><?php echo $st; ?></a>
        <?php endforeach; ?>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="table-container" style="border:none; border-radius:0;">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Pembeli</th>
                        <th>Subtotal</th>
                        <th>Fee (2%)</th>
                        <th>Ongkir</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Resi</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['orders'] as $order) : ?>
                    <tr>
                        <td class="font-mono text-xs font-bold">
                            <a href="/admin/orderdetail/<?php echo $order->id; ?>" class="text-primary hover:underline"><?php echo $order->id; ?></a>
                        </td>
                        <td class="font-semibold"><?php echo $order->buyer_name; ?></td>
                        <td>Rp <?php echo number_format($order->total_subtotal, 0, ',', '.'); ?></td>
                        <td class="text-orange-600">Rp <?php echo number_format($order->fee_marketplace, 0, ',', '.'); ?></td>
                        <td>Rp <?php echo number_format($order->fee_shipping, 0, ',', '.'); ?></td>
                        <td class="font-bold">Rp <?php echo number_format($order->total_payment, 0, ',', '.'); ?></td>
                        <td>
                            <?php
                            $statusClass = 'badge-info';
                            if($order->status == 'Selesai') $statusClass = 'badge-success';
                            elseif($order->status == 'Dibatalkan') $statusClass = 'badge-danger';
                            elseif($order->status == 'Pengajuan Pembatalan') $statusClass = 'bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-bold';
                            elseif($order->status == 'Dikirim') $statusClass = 'badge-success';
                            elseif($order->status == 'Menunggu Konfirmasi' || $order->status == 'Menunggu Pembayaran') $statusClass = 'badge-warning';
                            
                            $isCustomBadge = strpos($statusClass, 'bg-') !== false;
                            ?>
                            <span class="<?php echo $isCustomBadge ? $statusClass : 'badge ' . $statusClass; ?>"><?php echo $order->status; ?></span>
                        </td>
                        <td>
                            <?php if(!empty($order->resi_number)) : ?>
                                <div class="text-xs">
                                    <span class="font-bold text-blue-600"><?= $order->shipping_service; ?></span><br>
                                    <span class="font-mono text-gray-700"><?= $order->resi_number; ?></span>
                                </div>
                            <?php else : ?>
                                <span class="text-gray-300 text-xs">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-gray-500 text-xs"><?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></td>
                        <td>
                            <div class="flex flex-col gap-1">
                                <!-- Status Update -->
                                <form action="/admin/updatestatus" method="POST" class="flex items-center gap-1">
                                    <input type="hidden" name="order_id" value="<?php echo $order->id; ?>">
                                    <select name="status" class="text-xs border border-gray-200 rounded-lg px-2 py-1 outline-none focus:border-primary">
                                        <?php foreach($statuses as $st) : ?>
                                        <option value="<?php echo $st; ?>" <?php echo ($order->status == $st) ? 'selected' : ''; ?>><?php echo $st; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="bg-primary text-white px-2 py-1 rounded-lg text-xs font-bold hover:bg-orange-700 transition">✓</button>
                                </form>
                                <!-- Resi Input (shown when status is Sedang Dikemas or needs resi) -->
                                <?php if(in_array($order->status, ['Menunggu Konfirmasi', 'Sedang Dikemas']) || ($order->status == 'Dikirim' && empty($order->resi_number))) : ?>
                                <button onclick="toggleResiForm('resi-<?= $order->id; ?>')" class="text-xs text-blue-600 hover:underline text-left">
                                    <i class="fas fa-truck mr-1"></i>Input Resi
                                </button>
                                <div id="resi-<?= $order->id; ?>" class="hidden mt-1">
                                    <form action="/admin/updateresi" method="POST" class="space-y-1">
                                        <input type="hidden" name="order_id" value="<?= $order->id; ?>">
                                        <select name="shipping_service" class="w-full text-xs border border-gray-200 rounded px-2 py-1 focus:border-primary outline-none bg-white">
                                            <option value="LogistikKita">LogistikKita</option>
                                        </select>
                                        <input type="text" name="resi_number" placeholder="No. Resi..." class="w-full text-xs border border-gray-200 rounded px-2 py-1 focus:border-primary outline-none">
                                        <button type="submit" class="w-full bg-blue-600 text-white text-xs font-bold py-1 rounded hover:bg-blue-700 transition">
                                            <i class="fas fa-paper-plane mr-1"></i>Kirim
                                        </button>
                                    </form>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($data['orders'])) : ?>
                    <tr><td colspan="10" class="text-center py-12 text-gray-400">Tidak ada order yang ditemukan</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function toggleResiForm(id) {
    const el = document.getElementById(id);
    el.classList.toggle('hidden');
}
</script>

<?php require_once '../app/views/templates/footer.php'; ?>
