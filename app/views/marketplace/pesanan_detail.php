<?php require_once '../app/views/templates/header.php'; ?>

<div class="max-w-4xl mx-auto px-4 py-8 fade-in">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="/pesanan" class="w-10 h-10 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-full flex items-center justify-center transition">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-extrabold text-gray-900">Detail Pesanan</h2>
                <p class="text-gray-500 font-mono text-sm">#<?php echo $data['order']->id; ?></p>
            </div>
        </div>
        
        <?php if(!empty($data['items']) && !empty($data['items'][0]->seller_id)) : ?>
        <a href="/chat/index/<?php echo $data['items'][0]->seller_id; ?>?order_id=<?php echo $data['order']->id; ?>" class="bg-orange-100 text-primary border border-primary px-4 py-2 rounded-lg text-sm font-bold hover:bg-primary hover:text-white transition flex items-center gap-2">
            <i class="fas fa-comment-dots"></i> Chat Penjual
        </a>
        <?php endif; ?>
    </div>

    <?php flash('pesanan_message'); ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-4 bg-gray-50 border-b border-gray-100 font-bold text-gray-700">Daftar Produk</div>
                <div class="p-6 space-y-4">
                    <?php foreach($data['items'] as $item) : ?>
                        <div class="flex items-center gap-4 pb-4 border-b border-gray-50 last:border-0 last:pb-0">
                            <div class="w-16 h-16 bg-gray-100 rounded-xl overflow-hidden shrink-0">
                                <?php if(isset($item->image_url) && $item->image_url) : ?>
                                    <img src="<?php echo $item->image_url; ?>" class="w-full h-full object-cover">
                                <?php else : ?>
                                    <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fas fa-box"></i></div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow">
                                <h4 class="font-bold text-gray-800"><?php echo isset($item->product_name) ? $item->product_name : 'Produk telah dihapus'; ?></h4>
                                <p class="text-sm text-gray-500"><?php echo $item->quantity; ?> x Rp <?php echo number_format($item->price_at_purchase, 0, ',', '.'); ?></p>
                            </div>
                            <div class="text-right">
                                <p class="font-extrabold text-primary">Rp <?php echo number_format($item->quantity * $item->price_at_purchase, 0, ',', '.'); ?></p>
                                <?php if($data['order']->status == 'Selesai' && !$item->has_reviewed) : ?>
                                    <button onclick="openReviewModal(<?php echo $item->product_id; ?>, '<?php echo addslashes($item->product_name); ?>')" class="mt-2 text-xs bg-orange-100 text-primary font-bold px-3 py-1 rounded-lg hover:bg-primary hover:text-white transition">Beri Ulasan</button>
                                <?php elseif($item->has_reviewed) : ?>
                                    <span class="mt-2 text-xs text-green-500 inline-block font-bold"><i class="fas fa-check"></i> Sudah diulas</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Status & Info -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Informasi</h3>
                
                <div class="mb-4">
                    <p class="text-xs text-gray-500 mb-1">Status Pesanan</p>
                    <span class="inline-block bg-blue-50 text-blue-600 font-bold px-3 py-1 rounded-lg text-sm"><?php echo $data['order']->status; ?></span>
                </div>
                
                <div class="mb-4">
                    <p class="text-xs text-gray-500 mb-1">Tanggal Transaksi</p>
                    <p class="font-semibold text-gray-800 text-sm"><?php echo date('d M Y, H:i', strtotime($data['order']->created_at)); ?></p>
                </div>
                
                <div>
                    <p class="text-xs text-gray-500 mb-1">SmartBank Trx ID</p>
                    <p class="font-mono text-gray-800 text-xs bg-gray-50 p-2 rounded"><?php echo $data['order']->smartbank_trx_id ?? '-'; ?></p>
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Ringkasan Pembayaran</h3>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Harga Barang</span>
                        <span class="font-semibold text-gray-800">Rp <?php echo number_format($data['order']->total_subtotal, 0, ',', '.'); ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Ongkos Kirim <?= !empty($data['order']->shipping_service) ? '(' . $data['order']->shipping_service . ')' : ''; ?></span>
                        <span class="font-semibold text-gray-800">Rp <?php echo number_format($data['order']->fee_shipping, 0, ',', '.'); ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Biaya Layanan (2%)</span>
                        <span class="font-semibold text-gray-800">Rp <?php echo number_format($data['order']->fee_marketplace, 0, ',', '.'); ?></span>
                    </div>
                </div>
                
                <div class="border-t border-dashed border-gray-200 pt-4 flex justify-between items-center">
                    <span class="font-bold text-gray-800">Total Belanja</span>
                    <span class="text-xl font-extrabold text-primary">Rp <?php echo number_format($data['order']->total_payment, 0, ',', '.'); ?></span>
                </div>
            </div>

            <!-- Tracking Info (Phase 2) -->
            <?php if(in_array($data['order']->status, ['Dikirim', 'Selesai']) && !empty($data['order']->resi_number)) : ?>
            <div class="bg-blue-50 rounded-2xl border border-blue-100 shadow-sm p-6">
                <h3 class="font-bold text-blue-800 mb-4 pb-2 border-b border-blue-100 flex items-center gap-2">
                    <i class="fas fa-truck text-blue-500"></i> Info Pengiriman
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-blue-600">Jasa Pengiriman</span>
                        <span class="font-bold text-blue-800 bg-white px-3 py-1 rounded-full text-sm border border-blue-200">
                            <?= htmlspecialchars($data['order']->shipping_service); ?>
                        </span>
                    </div>
                    <div>
                        <span class="text-sm text-blue-600 block mb-1">Nomor Resi</span>
                        <div class="flex items-center gap-2 bg-white border border-blue-200 rounded-xl p-3">
                            <span class="font-mono font-bold text-gray-800 flex-grow"><?= htmlspecialchars($data['order']->resi_number); ?></span>
                            <button onclick="navigator.clipboard.writeText('<?= $data['order']->resi_number; ?>').then(()=>alert('Resi disalin!'))" class="text-blue-500 hover:text-blue-700 transition text-sm" title="Salin resi">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    <?php
                    // LogistikKita currently doesn't have an external tracking URL in the system
                    // But we can add a placeholder or internal link if needed later
                    $tracking_url = null; 
                    if($tracking_url) :
                    ?>
                    <a href="<?= $tracking_url; ?>" target="_blank" class="block w-full text-center bg-blue-600 text-white font-bold py-2.5 rounded-xl hover:bg-blue-700 transition text-sm">
                        <i class="fas fa-external-link-alt mr-2"></i>Lacak Paket di <?= $data['order']->shipping_service; ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php elseif($data['order']->status == 'Dikirim') : ?>
            <div class="bg-yellow-50 rounded-2xl border border-yellow-100 p-4">
                <p class="text-sm text-yellow-700 text-center"><i class="fas fa-clock mr-2"></i>Nomor resi sedang diproses oleh penjual.</p>
            </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <?php if(in_array($data['order']->status, ['Menunggu Pembayaran', 'Menunggu Konfirmasi', 'Sedang Dikemas'])) : ?>
            <div class="mt-6">
                <button onclick="openCancelModal()" class="w-full bg-white border-2 border-red-100 text-red-500 font-bold py-3 rounded-xl hover:bg-red-50 hover:border-red-200 transition">
                    <i class="fas fa-times-circle mr-2"></i> Ajukan Pembatalan
                </button>
            </div>
            <?php elseif($data['order']->status == 'Pengajuan Pembatalan') : ?>
            <div class="bg-orange-50 rounded-2xl border border-orange-100 p-4 mt-6">
                <p class="text-sm text-orange-700 text-center font-semibold"><i class="fas fa-info-circle mr-2"></i>Pengajuan pembatalan sedang diproses.</p>
            </div>
            <?php elseif($data['order']->status == 'Pengajuan Pengembalian') : ?>
            <div class="bg-orange-50 rounded-2xl border border-orange-100 p-4 mt-6">
                <p class="text-sm text-orange-700 text-center font-semibold"><i class="fas fa-info-circle mr-2"></i>Pengajuan pengembalian sedang diproses.</p>
            </div>
            <?php elseif(in_array($data['order']->status, ['Dikirim', 'Selesai'])) : ?>
            <div class="mt-6 space-y-3">
                <?php if($data['order']->status == 'Dikirim') : ?>
                <form action="/pesanan/complete/<?php echo $data['order']->id; ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin telah menerima pesanan ini dengan baik?');">
                    <button type="submit" class="w-full bg-primary text-white font-bold py-3 rounded-xl hover:bg-orange-700 transition shadow-sm">
                        <i class="fas fa-check-circle mr-2"></i> Pesanan Diterima
                    </button>
                </form>
                <?php endif; ?>
                <button onclick="openReturnModal()" class="w-full bg-white border-2 border-gray-200 text-gray-700 font-bold py-3 rounded-xl hover:bg-gray-50 transition">
                    <i class="fas fa-undo mr-2"></i> Ajukan Pengembalian
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Review Modal -->
<div id="reviewModal" class="fixed inset-0 bg-black/50 z-[100] hidden items-center justify-center fade-in">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-800">Beri Ulasan Produk</h3>
            <button onclick="closeReviewModal()" class="text-gray-400 hover:text-red-500"><i class="fas fa-times"></i></button>
        </div>
        <form action="/review/submit" method="POST" class="p-6">
            <input type="hidden" name="order_id" value="<?php echo $data['order']->id; ?>">
            <input type="hidden" name="product_id" id="review_product_id" value="">
            
            <div class="mb-4 text-center">
                <p class="text-sm text-gray-500 mb-2">Produk:</p>
                <p class="font-bold text-gray-800" id="review_product_name"></p>
            </div>

            <div class="mb-6 flex justify-center gap-2 text-3xl text-gray-300" id="star-rating">
                <i class="fas fa-star cursor-pointer hover:text-yellow-400 transition" data-rating="1"></i>
                <i class="fas fa-star cursor-pointer hover:text-yellow-400 transition" data-rating="2"></i>
                <i class="fas fa-star cursor-pointer hover:text-yellow-400 transition" data-rating="3"></i>
                <i class="fas fa-star cursor-pointer hover:text-yellow-400 transition" data-rating="4"></i>
                <i class="fas fa-star cursor-pointer hover:text-yellow-400 transition" data-rating="5"></i>
            </div>
            <input type="hidden" name="rating" id="review_rating" value="5" required>

            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Ulasan / Komentar (Opsional)</label>
                <textarea name="comment" rows="3" class="w-full border-gray-200 rounded-lg focus:border-primary focus:ring focus:ring-primary/20 transition-all text-sm p-3 border" placeholder="Bagaimana kualitas produk ini?"></textarea>
            </div>

            <button type="submit" class="w-full bg-primary text-white font-bold py-3 rounded-xl hover:bg-orange-700 transition">Kirim Ulasan</button>
        </form>
    </div>
</div>

<!-- Cancel Modal -->
<div id="cancelModal" class="fixed inset-0 bg-black/50 z-[100] hidden items-center justify-center fade-in">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-red-50">
            <h3 class="font-bold text-red-800">Ajukan Pembatalan</h3>
            <button onclick="closeCancelModal()" class="text-gray-400 hover:text-red-500"><i class="fas fa-times"></i></button>
        </div>
        <form action="/pesanan/cancel/<?php echo $data['order']->id; ?>" method="POST" class="p-6">
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-3"><i class="fas fa-exclamation-triangle text-orange-500 mr-2"></i> Peringatan: Pembatalan pesanan akan mengembalikan saldo Anda dikurangi biaya layanan (fee marketplace).</p>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Alasan Pembatalan</label>
                <textarea name="reason" rows="3" class="w-full border-gray-200 rounded-lg focus:border-red-500 focus:ring focus:ring-red-500/20 transition-all text-sm p-3 border" placeholder="Berikan alasan Anda..." required></textarea>
            </div>
            <button type="submit" class="w-full bg-red-500 text-white font-bold py-3 rounded-xl hover:bg-red-600 transition">Konfirmasi Pembatalan</button>
        </form>
    </div>
</div>

<!-- Return Modal -->
<div id="returnModal" class="fixed inset-0 bg-black/50 z-[100] hidden items-center justify-center fade-in">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-800">Ajukan Pengembalian</h3>
            <button onclick="closeReturnModal()" class="text-gray-400 hover:text-red-500"><i class="fas fa-times"></i></button>
        </div>
        <form action="/pesanan/return_order/<?php echo $data['order']->id; ?>" method="POST" class="p-6">
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-3"><i class="fas fa-exclamation-triangle text-orange-500 mr-2"></i> Peringatan: Pengembalian pesanan akan mengembalikan saldo Anda dikurangi biaya layanan (fee marketplace).</p>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Alasan Pengembalian</label>
                <textarea name="reason" rows="3" class="w-full border-gray-200 rounded-lg focus:border-primary focus:ring focus:ring-primary/20 transition-all text-sm p-3 border" placeholder="Jelaskan alasan pengembalian (contoh: barang cacat, tidak sesuai)..." required></textarea>
            </div>
            <button type="submit" class="w-full bg-primary text-white font-bold py-3 rounded-xl hover:bg-orange-700 transition">Kirim Pengajuan</button>
        </form>
    </div>
</div>

<script>
    const stars = document.querySelectorAll('#star-rating i');
    const ratingInput = document.getElementById('review_rating');

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.getAttribute('data-rating');
            ratingInput.value = rating;
            
            // Update UI
            stars.forEach(s => {
                if(s.getAttribute('data-rating') <= rating) {
                    s.classList.remove('text-gray-300');
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                    s.classList.add('text-gray-300');
                }
            });
        });
    });

    // Default to 5 stars UI
    stars.forEach(s => {
        s.classList.remove('text-gray-300');
        s.classList.add('text-yellow-400');
    });

    function openReviewModal(productId, productName) {
        document.getElementById('review_product_id').value = productId;
        document.getElementById('review_product_name').textContent = productName;
        document.getElementById('reviewModal').classList.remove('hidden');
        document.getElementById('reviewModal').classList.add('flex');
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').classList.add('hidden');
        document.getElementById('reviewModal').classList.remove('flex');
    }

    function openCancelModal() {
        document.getElementById('cancelModal').classList.remove('hidden');
        document.getElementById('cancelModal').classList.add('flex');
    }

    function closeCancelModal() {
        document.getElementById('cancelModal').classList.add('hidden');
        document.getElementById('cancelModal').classList.remove('flex');
    }

    function openReturnModal() {
        document.getElementById('returnModal').classList.remove('hidden');
        document.getElementById('returnModal').classList.add('flex');
    }

    function closeReturnModal() {
        document.getElementById('returnModal').classList.add('hidden');
        document.getElementById('returnModal').classList.remove('flex');
    }
</script>

<?php require_once '../app/views/templates/footer.php'; ?>
