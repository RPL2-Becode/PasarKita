<div class="max-w-4xl mx-auto px-4 py-8">
    <?php flash('profile_message'); ?>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="border-b border-gray-100 p-6">
            <h2 class="text-2xl font-bold text-gray-800">Edit Profil</h2>
            <p class="text-gray-500">Kelola informasi profil Anda untuk mengamankan akun.</p>
        </div>
        
        <div class="p-6">
            <form action="/profile/edit" method="POST" enctype="multipart/form-data">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Profile Picture Upload with Preview -->
                    <div class="col-span-1 md:col-span-2 flex flex-col md:flex-row gap-6 items-center bg-gray-50 p-5 rounded-xl border border-gray-100">
                        <div class="relative group cursor-pointer" onclick="document.getElementById('input_profile_picture').click()">
                            <div id="profile_circle" class="w-28 h-28 rounded-full border-4 border-white bg-white shadow-md overflow-hidden shrink-0 flex items-center justify-center">
                                <?php if(!empty($data['user']->profile_picture)): ?>
                                    <img src="/uploads/profile/<?php echo $data['user']->profile_picture; ?>" alt="Profile" id="profile_preview_img" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <img src="" id="profile_preview_img" class="w-full h-full object-cover" style="display:none;">
                                    <i class="fas fa-user text-4xl text-gray-300" id="profile_placeholder_icon"></i>
                                <?php endif; ?>
                            </div>
                            <div class="absolute inset-0 rounded-full bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                <i class="fas fa-camera text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="flex-grow w-full md:w-auto">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Foto Profil</label>
                            <p class="text-xs text-gray-400 mb-3">Klik lingkaran atau tombol di bawah untuk memilih gambar. Format: JPG, PNG. Maks 2MB.</p>
                            <input type="file" name="profile_picture" id="input_profile_picture" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-primary hover:file:bg-orange-100 transition cursor-pointer">
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                        <input type="text" value="<?php echo $data['user']->username; ?>" disabled class="w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-lg text-gray-500 cursor-not-allowed">
                        <p class="text-xs text-gray-400 mt-1">Username tidak dapat diubah.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="full_name" value="<?php echo $data['user']->full_name ?? ''; ?>" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary outline-none transition" placeholder="Masukkan nama lengkap">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="<?php echo $data['user']->email ?? ''; ?>" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary outline-none transition" placeholder="contoh@email.com">
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                        <input type="text" name="phone" value="<?php echo $data['user']->phone ?? ''; ?>" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary outline-none transition" placeholder="08xxxxxxxxxx">
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <?php echo ($data['user']->role == 'pelapak') ? 'Alamat Toko / Lengkap' : 'Alamat Lengkap'; ?>
                        </label>
                        <textarea name="address" rows="3" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary outline-none transition" placeholder="Nama Jalan, RT/RW, Desa/Kelurahan, Kecamatan, Kota/Kabupaten, Provinsi, Kode Pos"><?php echo $data['user']->address ?? ''; ?></textarea>
                    </div>

                    <?php if($data['user']->role == 'pelapak'): ?>
                        <div class="col-span-1 md:col-span-2 mt-4 pt-4 border-t border-gray-100">
                            <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-store mr-2 text-primary"></i>Informasi Toko</h3>
                        </div>
                        
                        <!-- Banner Upload with Preview -->
                        <div class="col-span-1 md:col-span-2 bg-gray-50 p-5 rounded-xl border border-gray-100">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Banner Toko</label>
                            <div id="banner_preview_box" class="mb-3 rounded-lg overflow-hidden h-32 bg-gray-200 border-2 border-dashed border-gray-300 flex items-center justify-center cursor-pointer relative group" onclick="document.getElementById('input_store_banner').click()">
                                <?php if(!empty($data['user']->store_banner)): ?>
                                    <img src="/uploads/banner/<?php echo $data['user']->store_banner; ?>" id="banner_preview_img" alt="Banner" class="w-full h-full object-cover absolute inset-0">
                                <?php else: ?>
                                    <img src="" id="banner_preview_img" alt="Banner" class="w-full h-full object-cover absolute inset-0" style="display:none;">
                                <?php endif; ?>
                                <div class="z-10 text-center" id="banner_placeholder">
                                    <?php if(empty($data['user']->store_banner)): ?>
                                        <i class="fas fa-image text-3xl text-gray-400 mb-1"></i>
                                        <p class="text-xs text-gray-400">Klik untuk pilih gambar banner</p>
                                    <?php endif; ?>
                                </div>
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition z-20">
                                    <i class="fas fa-camera text-white text-2xl"></i>
                                </div>
                            </div>
                            <input type="file" name="store_banner" id="input_store_banner" accept="image/*" class="hidden">
                            <p class="text-xs text-gray-400 mt-2">Disarankan rasio gambar landscape (16:9 atau 21:9). Maks 2MB.</p>
                        </div>
                        
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Toko</label>
                            <input type="text" name="store_name" value="<?php echo $data['user']->store_name ?? ''; ?>" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary outline-none transition" placeholder="Masukkan nama toko">
                        </div>
                        
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Toko</label>
                            <textarea name="store_description" rows="3" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary outline-none transition" placeholder="Jelaskan secara singkat tentang toko Anda"><?php echo $data['user']->store_description ?? ''; ?></textarea>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 pt-6">
                    <a href="/profile" class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">Batal</a>
                    <button type="submit" class="px-6 py-2 bg-primary text-white font-semibold rounded-lg hover:bg-orange-600 transition shadow-md">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Profile Picture Preview
document.getElementById('input_profile_picture').addEventListener('change', function() {
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var img = document.getElementById('profile_preview_img');
            img.src = e.target.result;
            img.style.display = 'block';
            
            var icon = document.getElementById('profile_placeholder_icon');
            if (icon) icon.style.display = 'none';
        }
        reader.readAsDataURL(this.files[0]);
    }
});

// Banner Preview
var bannerInput = document.getElementById('input_store_banner');
if (bannerInput) {
    bannerInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.getElementById('banner_preview_img');
                img.src = e.target.result;
                img.style.display = 'block';
                
                var placeholder = document.getElementById('banner_placeholder');
                if (placeholder) placeholder.innerHTML = '';
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
}
</script>
