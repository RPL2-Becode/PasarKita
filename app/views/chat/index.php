<?php require_once '../app/views/templates/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 py-8 fade-in h-[calc(100vh-100px)] flex flex-col">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex-grow flex">
        
        <!-- Sidebar: Conversations List -->
        <div class="w-full md:w-1/3 border-r border-gray-100 flex flex-col <?php echo $data['active_contact'] ? 'hidden md:flex' : 'flex'; ?>">
            <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 text-lg">Pesan</h3>
            </div>
            <div class="overflow-y-auto flex-grow">
                <?php if(empty($data['conversations'])) : ?>
                    <div class="p-8 text-center text-gray-400">
                        <i class="fas fa-comments text-4xl mb-3"></i>
                        <p class="text-sm">Belum ada obrolan.</p>
                    </div>
                <?php else: ?>
                    <?php foreach($data['conversations'] as $conv) : ?>
                        <a href="/chat/index/<?php echo $conv->contact_id; ?>" class="flex items-center gap-3 p-4 border-b border-gray-50 hover:bg-orange-50 transition <?php echo ($data['active_contact'] && $data['active_contact']->id == $conv->contact_id) ? 'bg-orange-50 border-l-4 border-l-primary' : ''; ?>">
                            <div class="relative w-12 h-12 rounded-full bg-gray-200 overflow-hidden shrink-0 border border-gray-200 flex items-center justify-center">
                                <?php if(!empty($conv->profile_picture)) : ?>
                                    <img src="/uploads/profile/<?php echo $conv->profile_picture; ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <i class="fas fa-user text-gray-400"></i>
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow overflow-hidden">
                                <div class="flex justify-between items-baseline mb-1">
                                    <h4 class="font-bold text-gray-800 text-sm truncate"><?php echo !empty($conv->store_name) ? $conv->store_name : $conv->username; ?></h4>
                                    <span class="text-[10px] text-gray-400 whitespace-nowrap ml-2"><?php echo date('H:i', strtotime($conv->last_message_time)); ?></span>
                                </div>
                                <p class="text-xs <?php echo ($conv->unread_count > 0) ? 'font-bold text-gray-800' : 'text-gray-500'; ?> truncate">
                                    <?php echo ($conv->sender_id == $data['current_user_id']) ? 'Anda: ' : ''; ?><?php echo htmlspecialchars($conv->last_message); ?>
                                </p>
                            </div>
                            <?php if($conv->unread_count > 0) : ?>
                                <div class="bg-red-500 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center shrink-0">
                                    <?php echo $conv->unread_count; ?>
                                </div>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="w-full md:w-2/3 flex flex-col bg-gray-50/50 <?php echo !$data['active_contact'] ? 'hidden md:flex items-center justify-center' : ''; ?>">
            <?php if(!$data['active_contact']) : ?>
                <div class="text-center text-gray-400">
                    <i class="fas fa-paper-plane text-5xl mb-4 opacity-50"></i>
                    <p>Pilih percakapan untuk mulai mengirim pesan</p>
                </div>
            <?php else: ?>
                <!-- Chat Header -->
                <div class="p-4 bg-white border-b border-gray-100 flex items-center gap-3">
                    <a href="/chat" class="md:hidden text-gray-500 hover:text-primary"><i class="fas fa-arrow-left"></i></a>
                    <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden shrink-0 border border-gray-200 flex items-center justify-center">
                        <?php if(!empty($data['active_contact']->profile_picture)) : ?>
                            <img src="/uploads/profile/<?php echo $data['active_contact']->profile_picture; ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i class="fas fa-user text-gray-400"></i>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm"><?php echo !empty($data['active_contact']->store_name) ? $data['active_contact']->store_name : $data['active_contact']->username; ?></h4>
                        <?php if($data['active_contact']->role == 'pelapak'): ?>
                            <span class="text-[10px] bg-primary text-white px-1.5 py-0.5 rounded">Penjual</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Chat Messages -->
                <div class="flex-grow p-4 overflow-y-auto" id="chat-messages" style="scroll-behavior: smooth;">
                    <?php if(empty($data['messages'])) : ?>
                        <div class="text-center text-gray-400 my-8 text-sm">Belum ada pesan. Mulai obrolan sekarang!</div>
                    <?php else: ?>
                        <?php foreach($data['messages'] as $msg) : ?>
                            <?php $is_me = ($msg->sender_id == $data['current_user_id']); ?>
                            <div class="flex mb-4 <?php echo $is_me ? 'justify-end' : 'justify-start'; ?>">
                                <div class="max-w-[75%] <?php echo $is_me ? 'bg-primary text-white' : 'bg-white border border-gray-200 text-gray-800'; ?> rounded-2xl p-3 shadow-sm relative <?php echo $is_me ? 'rounded-tr-sm' : 'rounded-tl-sm'; ?>">
                                    
                                    <!-- Embedded Product Context -->
                                    <?php if(!empty($msg->product_id)) : ?>
                                        <div class="<?php echo $is_me ? 'bg-white/10' : 'bg-gray-50'; ?> p-2 rounded-lg mb-2 flex gap-2 items-center">
                                            <?php if($msg->product_image): ?>
                                                <img src="<?php echo $msg->product_image; ?>" class="w-12 h-12 object-cover rounded">
                                            <?php else: ?>
                                                <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center"><i class="fas fa-box text-gray-400"></i></div>
                                            <?php endif; ?>
                                            <div class="text-xs">
                                                <p class="font-bold truncate max-w-[150px]"><?php echo $msg->product_name; ?></p>
                                                <p class="<?php echo $is_me ? 'text-white font-bold' : 'text-primary font-bold'; ?>">Rp<?php echo number_format($msg->product_price, 0, ',', '.'); ?></p>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Embedded Order Context -->
                                    <?php if(!empty($msg->order_id)) : ?>
                                        <div class="<?php echo $is_me ? 'bg-white/15' : 'bg-white border border-gray-200'; ?> p-3 rounded-lg mb-2 text-xs shadow-sm">
                                            <div class="flex items-center justify-between mb-1 gap-4">
                                                <span class="font-bold flex items-center gap-1.5"><i class="fas fa-receipt <?php echo $is_me ? 'text-white/70' : 'text-primary'; ?>"></i> #<?php echo $msg->order_id; ?></span>
                                                <span class="px-2 py-0.5 rounded text-[9px] font-extrabold tracking-wider <?php echo $is_me ? 'bg-white text-primary' : 'bg-orange-100 text-orange-700'; ?> uppercase"><?php echo $msg->order_status; ?></span>
                                            </div>
                                            <div class="flex justify-between items-end mt-2 pt-2 border-t <?php echo $is_me ? 'border-white/20' : 'border-gray-100'; ?>">
                                                <span class="opacity-80">Total Pembayaran:</span>
                                                <span class="font-bold <?php echo $is_me ? 'text-white' : 'text-primary'; ?> text-sm">Rp <?php echo number_format($msg->order_total, 0, ',', '.'); ?></span>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <p class="text-sm whitespace-pre-wrap leading-relaxed"><?php echo htmlspecialchars($msg->message); ?></p>
                                    <div class="text-[10px] text-right mt-1 <?php echo $is_me ? 'text-primary-100' : 'text-gray-400'; ?>">
                                        <?php echo date('H:i', strtotime($msg->created_at)); ?>
                                        <?php if($is_me && $msg->is_read) : ?>
                                            <i class="fas fa-check-double ml-1 text-blue-300"></i>
                                        <?php elseif($is_me) : ?>
                                            <i class="fas fa-check ml-1"></i>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Context Preview Before Sending -->
                <?php if($data['product_context'] || $data['order_context']): ?>
                <div class="px-4 py-3 bg-blue-50 border-t border-blue-100 text-xs flex gap-3 items-center overflow-x-auto shadow-inner">
                    <span class="text-blue-700 font-bold whitespace-nowrap"><i class="fas fa-paperclip mr-1"></i> Melampirkan:</span>
                    <?php if($data['product_context']): ?>
                        <div class="bg-white px-3 py-1.5 rounded-lg border border-blue-200 flex items-center gap-3 relative shadow-sm">
                            <?php if($data['product_context']->image_url): ?>
                                <img src="<?php echo $data['product_context']->image_url; ?>" class="w-8 h-8 object-cover rounded">
                            <?php else: ?>
                                <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center"><i class="fas fa-box text-gray-400"></i></div>
                            <?php endif; ?>
                            <div class="flex flex-col">
                                <span class="truncate max-w-[150px] font-semibold text-gray-800"><?php echo $data['product_context']->name; ?></span>
                                <span class="text-primary font-bold">Rp <?php echo number_format($data['product_context']->price, 0, ',', '.'); ?></span>
                            </div>
                            <a href="/chat/index/<?php echo $data['active_contact']->id; ?>" class="text-gray-400 hover:text-red-500 ml-2"><i class="fas fa-times-circle text-base"></i></a>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($data['order_context']): ?>
                        <div class="bg-white px-3 py-2 rounded-lg border border-blue-200 flex items-center gap-3 relative shadow-sm">
                            <div class="bg-blue-100 text-blue-600 w-8 h-8 rounded flex items-center justify-center shrink-0">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-mono font-bold text-gray-800">#<?php echo $data['order_context']->id; ?></span>
                                <span class="text-[10px] font-bold text-orange-600 uppercase tracking-wider"><?php echo $data['order_context']->status; ?></span>
                            </div>
                            <a href="/chat/index/<?php echo $data['active_contact']->id; ?>" class="text-gray-400 hover:text-red-500 ml-2"><i class="fas fa-times-circle text-base"></i></a>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Chat Input -->
                <div class="p-4 bg-white border-t border-gray-100">
                    <form action="/chat/send" method="POST" class="flex gap-2">
                        <input type="hidden" name="receiver_id" value="<?php echo $data['active_contact']->id; ?>">
                        <?php if($data['product_context']): ?>
                            <input type="hidden" name="product_id" value="<?php echo $data['product_context']->id; ?>">
                        <?php endif; ?>
                        <?php if($data['order_context']): ?>
                            <input type="hidden" name="order_id" value="<?php echo $data['order_context']->id; ?>">
                        <?php endif; ?>
                        
                        <input type="text" name="message" placeholder="Tulis pesan..." required class="flex-grow bg-gray-100 border-transparent rounded-xl px-4 py-3 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm outline-none">
                        <button type="submit" class="bg-primary text-white w-12 h-12 rounded-xl flex items-center justify-center hover:bg-orange-600 transition shadow-sm shrink-0">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Scroll to bottom of chat automatically
    const chatContainer = document.getElementById('chat-messages');
    if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
</script>

<?php require_once '../app/views/templates/footer.php'; ?>
