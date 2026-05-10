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
            $fee_s = 5000;
            $total_payment = $total_subtotal + $fee_m + $fee_s;
        ?>
            <div class="lg:w-1/3">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-24">
                    <h3 class="text-lg font-bold mb-6">Ringkasan Belanja</h3>
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
                            <span>Rp <?php echo number_format($fee_s, 0, ',', '.'); ?></span>
                        </div>
                        <div class="flex justify-between text-xl font-extrabold text-gray-900 pt-2">
                            <span>Total Tagihan</span>
                            <span class="text-primary">Rp <?php echo number_format($total_payment, 0, ',', '.'); ?></span>
                        </div>
                    </div>
                    
                    <form action="/checkout/process" method="POST">
                        <input type="hidden" name="subtotal" value="<?php echo $total_subtotal; ?>">
                        <input type="hidden" name="fee_m" value="<?php echo $fee_m; ?>">
                        <input type="hidden" name="fee_s" value="<?php echo $fee_s; ?>">
                        <input type="hidden" name="total" value="<?php echo $total_payment; ?>">
                        
                        <button type="submit" class="w-full bg-primary text-white font-bold py-4 rounded-xl mt-8 hover:bg-orange-600 transition shadow-lg shadow-orange-100">
                            Pesan Sekarang
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>
