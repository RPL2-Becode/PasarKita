<?php require_once '../app/views/templates/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 py-8 fade-in">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Keranjang Belanja</h1>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Cart Items -->
        <div class="lg:w-2/3">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <?php if(!empty($data['cart'])) : ?>
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-500 text-[10px] font-bold uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Produk</th>
                                <th class="px-6 py-4">Harga</th>
                                <th class="px-6 py-4">Jumlah</th>
                                <th class="px-6 py-4">Subtotal</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php 
                            $total_subtotal = 0;
                            foreach($data['cart'] as $item) : 
                                $sub = $item['price'] * $item['quantity'];
                                $total_subtotal += $sub;
                            ?>
                                <tr>
                                    <td class="px-6 py-4 flex items-center gap-4">
                                        <img src="<?php echo $item['image_url']; ?>" class="w-12 h-12 rounded object-cover">
                                        <span class="font-bold text-gray-800 text-sm"><?php echo $item['name']; ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                                    <td class="px-6 py-4 text-sm font-bold"><?php echo $item['quantity']; ?></td>
                                    <td class="px-6 py-4 text-sm font-bold text-primary">Rp <?php echo number_format($sub, 0, ',', '.'); ?></td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="/cart/remove/<?php echo $item['id']; ?>" class="text-red-500 hover:text-red-700 transition"><i class="fas fa-trash-can"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <div class="p-20 text-center text-gray-400">
                        <i class="fas fa-shopping-basket text-6xl mb-4"></i>
                        <p>Keranjang Anda masih kosong.</p>
                        <a href="/marketplace" class="text-primary font-bold mt-4 inline-block hover:underline">Mulai Berbelanja</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Summary -->
        <?php if(!empty($data['cart'])) : 
            $fee_m = $total_subtotal * 0.02;
        ?>
            <div class="lg:w-1/3">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-24">
                    <h3 class="text-lg font-bold mb-6">Ringkasan Belanja</h3>

                    <!-- Shipping Service Selector -->
                    <div class="mb-5">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block"><i class="fas fa-truck mr-1 text-primary"></i>Ekspedisi Pengiriman</label>
                        <div class="space-y-2">
                            <label class="flex items-center justify-between p-3 border-2 border-primary bg-orange-50 rounded-xl cursor-not-allowed">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="selected_courier" value="LogistikKita" checked disabled class="accent-orange-500">
                                    <div>
                                        <p class="font-bold text-gray-800 text-sm">LogistikKita</p>
                                        <p class="text-xs text-gray-400">Flat Rate</p>
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-primary">Rp 5.000</span>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between text-gray-500">
                            <span>Total Harga (Item)</span>
                            <span>Rp <?php echo number_format($total_subtotal, 0, ',', '.'); ?></span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>Biaya Layanan (2%)</span>
                            <span>Rp <?php echo number_format($fee_m, 0, ',', '.'); ?></span>
                        </div>
                        <div class="flex justify-between text-gray-500 border-b border-dashed pb-4">
                            <span>Biaya Pengiriman</span>
                            <span>Rp 5.000</span>
                        </div>
                        <div class="flex justify-between text-xl font-extrabold text-gray-900 pt-2">
                            <span>Total Tagihan</span>
                            <span class="text-primary">Rp <?php echo number_format($total_subtotal + $fee_m + 5000, 0, ',', '.'); ?></span>
                        </div>
                    </div>
                    
                    <form action="/checkout/process" method="POST">
                        <input type="hidden" name="subtotal" value="<?php echo $total_subtotal; ?>">
                        <input type="hidden" name="fee_s" value="5000">
                        <input type="hidden" name="shipping_service" value="LogistikKita">
                        
                        <button type="submit" class="w-full bg-primary text-white font-bold py-4 rounded-xl mt-6 hover:bg-orange-600 transition shadow-lg shadow-orange-100">
                            <i class="fas fa-lock mr-2"></i>Pesan Sekarang
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
const subtotal = <?= $total_subtotal ?? 0; ?>;
const feeM = <?= isset($fee_m) ? $fee_m : 0; ?>;

function selectCourier(idx, fee, service) {
    // Update hidden inputs
    document.getElementById('input-fee-shipping').value = fee;
    document.getElementById('input-shipping-service').value = service;

    // Update display
    document.getElementById('display-fee-shipping').textContent = 'Rp ' + fee.toLocaleString('id-ID');
    const total = subtotal + feeM + fee;
    document.getElementById('display-total').textContent = 'Rp ' + total.toLocaleString('id-ID');

    // Update label styles
    document.querySelectorAll('[id^="label-courier-"]').forEach((el, i) => {
        if(i === idx) {
            el.classList.add('border-primary', 'bg-orange-50');
            el.classList.remove('border-gray-100');
            el.querySelector('input[type=radio]').checked = true;
        } else {
            el.classList.remove('border-primary', 'bg-orange-50');
            el.classList.add('border-gray-100');
        }
    });
}
</script>

<?php require_once '../app/views/templates/footer.php'; ?>
