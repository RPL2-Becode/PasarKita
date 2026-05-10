<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - PasarKita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen p-4">
    <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden fade-in">
        <div class="p-8 bg-blue-600 text-white">
            <a href="/products" class="text-blue-200 hover:text-white mb-4 inline-block"><i class="fas fa-arrow-left"></i> Kembali</a>
            <h1 class="text-2xl font-bold">Edit Produk</h1>
            <p class="text-blue-100 text-sm">Perbarui detail produk Anda</p>
        </div>

        <form action="/products/edit/<?php echo $data['id']; ?>" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Produk</label>
                <input type="text" name="name" class="w-full px-4 py-3 rounded-xl border <?php echo (!empty($data['name_err'])) ? 'border-red-500' : 'border-gray-200'; ?> outline-none focus:border-blue-600 transition" value="<?php echo $data['name']; ?>">
                <?php if(!empty($data['name_err'])) : ?>
                    <span class="text-red-500 text-xs mt-1"><?php echo $data['name_err']; ?></span>
                <?php endif; ?>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:border-blue-600 transition"><?php echo $data['description']; ?></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Harga (Rp)</label>
                    <input type="number" name="price" class="w-full px-4 py-3 rounded-xl border <?php echo (!empty($data['price_err'])) ? 'border-red-500' : 'border-gray-200'; ?> outline-none focus:border-blue-600 transition" value="<?php echo $data['price']; ?>">
                    <?php if(!empty($data['price_err'])) : ?>
                        <span class="text-red-500 text-xs mt-1"><?php echo $data['price_err']; ?></span>
                    <?php endif; ?>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Stok</label>
                    <input type="number" name="stock" class="w-full px-4 py-3 rounded-xl border <?php echo (!empty($data['stock_err'])) ? 'border-red-500' : 'border-gray-200'; ?> outline-none focus:border-blue-600 transition" value="<?php echo $data['stock']; ?>">
                    <?php if(!empty($data['stock_err'])) : ?>
                        <span class="text-red-500 text-xs mt-1"><?php echo $data['stock_err']; ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                <select name="category_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:border-blue-600 transition">
                    <option value="">-- Pilih Kategori --</option>
                    <?php if(isset($data['categories'])) : foreach($data['categories'] as $cat) : ?>
                        <option value="<?php echo $cat->id; ?>" <?php echo (isset($data['category_id']) && $data['category_id'] == $cat->id) ? 'selected' : ''; ?>><?php echo $cat->name; ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Foto Produk</label>
                <?php if(!empty($data['image_url'])) : ?>
                    <div class="mb-3">
                        <img src="<?php echo $data['image_url']; ?>" class="w-32 h-32 object-cover rounded-xl border border-gray-200" id="img-preview">
                        <p class="text-xs text-gray-400 mt-1">Foto saat ini. Upload baru untuk mengganti.</p>
                    </div>
                <?php endif; ?>
                <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl">
                    <div class="space-y-1 text-center">
                        <i class="fas fa-image text-gray-400 text-3xl mb-3"></i>
                        <div class="flex text-sm text-gray-600">
                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                <span>Upload file baru</span>
                                <input name="image" type="file" class="sr-only" onchange="previewImage(this, 'img-preview')">
                            </label>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-4 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                <i class="fas fa-save mr-2"></i> Simpan Perubahan
            </button>
        </form>
    </div>
    <script src="/js/main.js"></script>
</body>
</html>
