<?php require_once '../app/views/templates/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 py-8 fade-in">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Manajemen User</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola akun pengguna PasarKita</p>
        </div>
        <div class="flex gap-3">
            <a href="/admin/dashboard" class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-semibold hover:border-primary hover:text-primary transition"><i class="fas fa-chart-bar mr-2"></i>Dashboard</a>
            <a href="/admin/orders" class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-semibold hover:border-primary hover:text-primary transition"><i class="fas fa-list-alt mr-2"></i>Monitoring Order</a>
        </div>
    </div>

    <?php flash('user_message'); ?>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-users mr-2 text-primary"></i>Daftar User (<?php echo count($data['users']); ?>)</h2>
        </div>
        <div class="table-container" style="border:none; border-radius:0;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Saldo</th>
                        <th>Terdaftar</th>
                        <th>Ubah Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['users'] as $user) : ?>
                    <tr>
                        <td class="font-mono text-xs">#<?php echo $user->id; ?></td>
                        <td class="font-semibold"><?php echo $user->username; ?></td>
                        <td>
                            <?php
                            $roleClass = 'badge-info';
                            if($user->role == 'admin') $roleClass = 'badge-danger';
                            elseif($user->role == 'pelapak') $roleClass = 'badge-success';
                            elseif($user->role == 'operator') $roleClass = 'badge-warning';
                            ?>
                            <span class="badge <?php echo $roleClass; ?>"><?php echo $user->role; ?></span>
                        </td>
                        <td>Rp <?php echo number_format($user->balance, 0, ',', '.'); ?></td>
                        <td class="text-gray-500 text-xs"><?php echo date('d M Y', strtotime($user->created_at)); ?></td>
                        <td>
                            <form action="/admin/updaterole" method="POST" class="flex items-center gap-2">
                                <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
                                <select name="role" class="text-xs border border-gray-200 rounded-lg px-2 py-1 outline-none focus:border-primary">
                                    <option value="consumen" <?php echo ($user->role == 'consumen') ? 'selected' : ''; ?>>Consumen</option>
                                    <option value="pelapak" <?php echo ($user->role == 'pelapak') ? 'selected' : ''; ?>>Pelapak</option>
                                    <option value="operator" <?php echo ($user->role == 'operator') ? 'selected' : ''; ?>>Operator</option>
                                    <option value="admin" <?php echo ($user->role == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                </select>
                                <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-blue-600 transition">Simpan</button>
                            </form>
                        </td>
                        <td>
                            <?php if($user->id != $_SESSION['user_id']) : ?>
                            <form action="/admin/deleteuser/<?php echo $user->id; ?>" method="POST" onsubmit="return confirm('Yakin hapus user <?php echo $user->username; ?>?')">
                                <button type="submit" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition text-xs"><i class="fas fa-trash"></i></button>
                            </form>
                            <?php else : ?>
                            <span class="text-gray-300 text-xs">(Anda)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>
