<?php require_once '../app/views/templates/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 py-8 fade-in">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Dashboard Admin</h1>
            <p class="text-gray-500 text-sm mt-1">Ringkasan finansial dan aktivitas PasarKita</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="/admin/api_settings" class="px-4 py-2 bg-blue-50 border border-blue-200 rounded-lg text-sm font-semibold text-blue-700 hover:bg-blue-600 hover:text-white transition"><i class="fas fa-network-wired mr-2"></i>Integrasi API</a>
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

    <!-- Recent Orders Chart -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-10">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-chart-line mr-2 text-primary"></i>Grafik Transaksi Terbaru</h2>
            <a href="/admin/orders" class="text-sm text-primary font-semibold hover:underline">Lihat Semua Transaksi →</a>
        </div>
        <div class="p-6">
            <canvas id="revenueChart" height="100"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const rawData = <?php echo json_encode($data['recent_orders']); ?>;
    
    // Reverse data to show oldest to newest left to right
    const chartData = rawData.slice(0, 15).reverse();
    
    const labels = chartData.map(order => {
        const d = new Date(order.created_at);
        return d.getDate() + '/' + (d.getMonth()+1) + ' ' + d.getHours() + ':' + (d.getMinutes()<10?'0':'') + d.getMinutes();
    });
    
    const totals = chartData.map(order => order.total_payment);
    const fees = chartData.map(order => order.fee_marketplace);

    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Total Transaksi (Rp)',
                    data: totals,
                    borderColor: '#ff8a00',
                    backgroundColor: 'rgba(255, 138, 0, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Fee Admin (Rp)',
                    data: fees,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value, index, values) {
                            return 'Rp ' + (value/1000) + 'k';
                        }
                    }
                }
            }
        }
    });
});
</script>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>
