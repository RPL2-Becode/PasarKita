<?php require_once '../app/views/templates/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 py-8 fade-in">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Monitoring Transaksi</h1>
            <p class="text-gray-500 text-sm mt-1">Pantau dan kelola status order</p>
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
        $statuses = ['Menunggu Pembayaran', 'Menunggu Konfirmasi', 'Sedang Dikemas', 'Dikirim', 'Selesai', 'Dibatalkan'];
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
                            elseif($order->status == 'Menunggu Konfirmasi' || $order->status == 'Menunggu Pembayaran') $statusClass = 'badge-warning';
                            ?>
                            <span class="badge <?php echo $statusClass; ?>"><?php echo $order->status; ?></span>
                        </td>
                        <td class="text-gray-500 text-xs"><?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></td>
                        <td>
                            <form action="/admin/updatestatus" method="POST" class="flex items-center gap-2">
                                <input type="hidden" name="order_id" value="<?php echo $order->id; ?>">
                                <select name="status" class="text-xs border border-gray-200 rounded-lg px-2 py-1 outline-none focus:border-primary">
                                    <?php foreach($statuses as $st) : ?>
                                    <option value="<?php echo $st; ?>" <?php echo ($order->status == $st) ? 'selected' : ''; ?>><?php echo $st; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="bg-primary text-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-orange-700 transition">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($data['orders'])) : ?>
                    <tr><td colspan="9" class="text-center py-12 text-gray-400">Tidak ada order yang ditemukan</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>
