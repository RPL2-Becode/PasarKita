<div class="max-w-4xl mx-auto px-4 py-8">
    <?php flash('profile_message'); ?>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        
        <!-- Banner for Pelapak -->
        <?php if($data['user']->role == 'pelapak'): ?>
            <?php if(!empty($data['user']->store_banner)): ?>
                <div class="h-36 bg-gray-200">
                    <img src="/uploads/banner/<?php echo $data['user']->store_banner; ?>" alt="Banner Toko" class="w-full h-full object-cover">
                </div>
            <?php else: ?>
                <div class="h-36 bg-gradient-to-r from-orange-400 to-primary"></div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="p-6 md:p-8 flex flex-col md:flex-row gap-8 items-start">
            
            <!-- Left Sidebar / Profile Picture -->
            <div class="w-full md:w-1/3 flex flex-col items-center border-r border-gray-100 md:pr-8 <?php echo $data['user']->role == 'pelapak' ? '-mt-16 relative z-10' : ''; ?>">
                <div class="w-32 h-32 rounded-full bg-gray-200 border-4 border-white shadow-lg overflow-hidden mb-4 relative flex items-center justify-center">
                    <?php if(!empty($data['user']->profile_picture)): ?>
                        <img src="/uploads/profile/<?php echo $data['user']->profile_picture; ?>" alt="Profile" class="w-full h-full object-cover">
                    <?php else: ?>
                        <i class="fas fa-user text-6xl text-gray-400"></i>
                    <?php endif; ?>
                </div>
                <h2 class="text-xl font-bold text-gray-800"><?php echo $data['user']->username; ?></h2>
                <p class="text-gray-500 capitalize mb-4"><?php echo $data['user']->role; ?></p>
                
                <a href="/profile/edit" class="w-full text-center px-4 py-2 border border-primary text-primary font-semibold rounded-lg hover:bg-orange-50 transition">
                    Edit Profil
                </a>
            </div>

            <!-- Right Content / Details -->
            <div class="w-full md:w-2/3">
                <h3 class="text-lg font-bold text-gray-800 mb-6 border-b pb-2">Informasi Profil</h3>
                
                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <span class="text-gray-500 font-medium sm:w-1/3">Nama Lengkap</span>
                        <span class="text-gray-800 font-medium sm:w-2/3"><?php echo !empty($data['user']->full_name) ? $data['user']->full_name : '<em class="text-gray-400 font-normal">Belum diatur</em>'; ?></span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <span class="text-gray-500 font-medium sm:w-1/3">Email</span>
                        <span class="text-gray-800 font-medium sm:w-2/3"><?php echo !empty($data['user']->email) ? $data['user']->email : '<em class="text-gray-400 font-normal">Belum diatur</em>'; ?></span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <span class="text-gray-500 font-medium sm:w-1/3">Nomor Telepon</span>
                        <span class="text-gray-800 font-medium sm:w-2/3"><?php echo !empty($data['user']->phone) ? $data['user']->phone : '<em class="text-gray-400 font-normal">Belum diatur</em>'; ?></span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-start">
                        <span class="text-gray-500 font-medium sm:w-1/3">
                            <?php echo ($data['user']->role == 'pelapak') ? 'Alamat Toko' : 'Alamat'; ?>
                        </span>
                        <span class="text-gray-800 font-medium sm:w-2/3"><?php echo !empty($data['user']->address) ? nl2br($data['user']->address) : '<em class="text-gray-400 font-normal">Belum diatur</em>'; ?></span>
                    </div>
                </div>

                <?php if($data['user']->role == 'pelapak'): ?>
                <h3 class="text-lg font-bold text-gray-800 mt-8 mb-6 border-b pb-2">Informasi Toko</h3>
                
                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <span class="text-gray-500 font-medium sm:w-1/3">Nama Toko</span>
                        <span class="text-gray-800 font-medium sm:w-2/3"><?php echo !empty($data['user']->store_name) ? $data['user']->store_name : '<em class="text-gray-400 font-normal">Belum diatur</em>'; ?></span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-start">
                        <span class="text-gray-500 font-medium sm:w-1/3">Deskripsi Toko</span>
                        <span class="text-gray-800 font-medium sm:w-2/3"><?php echo !empty($data['user']->store_description) ? nl2br($data['user']->store_description) : '<em class="text-gray-400 font-normal">Belum diatur</em>'; ?></span>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
