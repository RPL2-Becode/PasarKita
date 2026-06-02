<?php require_once '../app/views/templates/header.php'; ?>

<div class="max-w-4xl mx-auto px-4 py-8 fade-in">
    <div class="flex items-center gap-4 mb-8">
        <a href="/admin/dashboard" class="w-10 h-10 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-full flex items-center justify-center transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Integrasi API Eksternal</h1>
            <p class="text-gray-500 text-sm mt-1">Konfigurasi endpoint IP untuk terhubung ke aplikasi SmartBank dan LogistiKita (Tugas Teman).</p>
        </div>
    </div>

    <?php flash('api_message'); ?>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-network-wired mr-2 text-primary"></i>Pengaturan Koneksi API</h2>
        </div>
        
        <form action="/admin/save_api_settings" method="POST" class="p-8">
            <!-- SmartBank API -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-university text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">SmartBank API</h3>
                        <p class="text-xs text-gray-500">Endpoint untuk pembayaran, manajemen saldo, dan pajak/fee bank.</p>
                    </div>
                </div>
                <div class="ml-13">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Base URL / IP Address (SmartBank)</label>
                    <input type="url" name="smartbank_url" 
                           class="w-full border-gray-200 rounded-xl focus:border-blue-500 focus:ring focus:ring-blue-500/20 transition-all p-4 border" 
                           placeholder="Contoh: http://192.168.1.10:8000/api atau http://smartbank.local" 
                           value="<?php echo htmlspecialchars($data['settings']['smartbank_url'] ?? ''); ?>">
                    <p class="text-xs text-gray-400 mt-2"><i class="fas fa-info-circle mr-1"></i>URL ini akan digunakan saat melakukan request ke <code>/smartbank/pembayaran_transaksi</code> dsb.</p>
                </div>
            </div>

            <hr class="border-gray-100 my-8">

            <!-- LogistiKita API -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-truck text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">LogistiKita API</h3>
                        <p class="text-xs text-gray-500">Endpoint untuk request pengiriman, tracking, dan cek ongkir.</p>
                    </div>
                </div>
                <div class="ml-13">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Base URL / IP Address (LogistiKita)</label>
                    <input type="url" name="logistikita_url" 
                           class="w-full border-gray-200 rounded-xl focus:border-primary focus:ring focus:ring-primary/20 transition-all p-4 border" 
                           placeholder="Contoh: http://192.168.1.11:8000/api atau http://logistikita.local" 
                           value="<?php echo htmlspecialchars($data['settings']['logistikita_url'] ?? ''); ?>">
                    <p class="text-xs text-gray-400 mt-2"><i class="fas fa-info-circle mr-1"></i>URL ini akan digunakan saat melakukan request ke <code>/logistikita/request_pengiriman</code> dsb.</p>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-100">
                <button type="submit" class="bg-primary text-white font-bold py-3 px-8 rounded-xl hover:bg-orange-700 transition shadow-lg shadow-orange-200 flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Pengaturan API
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>
