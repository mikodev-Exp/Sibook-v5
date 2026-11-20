<?php $__env->startSection('title', 'User Register'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-[1400px] mx-auto space-y-4">

    
    <nav class="flex items-center gap-2 text-xs text-gray-500 font-medium">
        <span>Pengguna</span>
        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-400">User Register</span>
    </nav>

    
    <h1 class="text-2xl lg:text-3xl font-extrabold text-gray-900 tracking-tight">
        User Register
    </h1>

    
    <div id="user-register-table-container"
         data-async-container
         @click="SiBookAsync.handleClick($event, 'user-register-table-container')"
         class="bg-white rounded-2xl border border-gray-150 shadow-[0_1px_3px_rgba(0,0,0,0.02)] overflow-hidden flex flex-col min-h-[580px] lg:min-h-[calc(100vh-220px)]">
        
        
        <div class="p-4 sm:p-5 flex flex-col sm:flex-row items-center justify-between gap-3 border-b border-gray-100">
            
            <div class="flex items-center gap-1.5 overflow-x-auto w-full sm:w-auto">
                <?php
                    $curRole = request('role');
                ?>
                <a href="<?php echo e(route('admin.user-register.index', request()->except(['role', 'page']))); ?>"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition <?php echo e(!$curRole ? 'bg-orange-50 text-orange-600 border border-orange-200' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-50'); ?>">
                    Semua
                </a>
                <a href="<?php echo e(route('admin.user-register.index', array_merge(request()->except('page'), ['role' => 'pendaftar']))); ?>"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition <?php echo e($curRole === 'pendaftar' ? 'bg-orange-50 text-orange-600 border border-orange-200' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-50'); ?>">
                    Pendaftar
                </a>
                <a href="<?php echo e(route('admin.user-register.index', array_merge(request()->except('page'), ['role' => 'mahasiswa']))); ?>"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition <?php echo e($curRole === 'mahasiswa' ? 'bg-orange-50 text-orange-600 border border-orange-200' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-50'); ?>">
                    Mahasiswa
                </a>
            </div>

            
            <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                <form method="GET" action="<?php echo e(route('admin.user-register.index')); ?>" 
                      @submit.prevent="SiBookAsync.handleFormSubmit($event, 'user-register-table-container')"
                      class="relative w-full sm:w-64">
                    <?php if(request('role')): ?>
                        <input type="hidden" name="role" value="<?php echo e(request('role')); ?>">
                    <?php endif; ?>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="<?php echo e(request('search')); ?>"
                           @input.debounce.300ms="SiBookAsync.handleSearch($event, 'user-register-table-container')"
                           placeholder="Cari nama, email..." 
                           class="w-full pl-9 pr-3 py-1.5 text-xs bg-white border border-gray-200 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition shadow-sm">
                </form>
            </div>
        </div>

        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left border-collapse text-[13px]">
                <thead>
                    <tr class="border-b border-gray-100 font-semibold text-gray-700 bg-white">
                        <th class="py-3.5 pl-6 pr-3">Nama</th>
                        <th class="py-3.5 px-3">Email</th>
                        <th class="py-3.5 px-3">Role</th>
                        <th class="py-3.5 pl-3 pr-6">Tanggal Daftar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-800">
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $roleName = $user->getRoleNames()->first() ?? 'pendaftar';
                            $roleBadge = match($roleName) {
                                'admin' => 'bg-purple-50 text-purple-700 border-purple-200',
                                'mahasiswa' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'super_user' => 'bg-blue-50 text-blue-700 border-blue-200',
                                default => 'bg-amber-50 text-amber-700 border-amber-200',
                            };
                        ?>
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td class="py-4 pl-6 pr-3 font-semibold text-gray-900"><?php echo e($user->name); ?></td>
                            <td class="py-4 px-3 text-gray-600"><?php echo e($user->email); ?></td>
                            <td class="py-4 px-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border <?php echo e($roleBadge); ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $roleName))); ?>

                                </span>
                            </td>
                            <td class="py-4 pl-3 pr-6 text-gray-500 whitespace-nowrap"><?php echo e($user->created_at->translatedFormat('d M Y')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="py-10 text-center text-gray-400">Belum ada akun pendaftar yang terdaftar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <div class="mt-auto px-5 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-2 bg-gray-50/40">
            <div class="text-xs text-gray-500">
                <?php if($users->total() > 0): ?>
                    Menampilkan <?php echo e($users->firstItem()); ?> - <?php echo e($users->lastItem()); ?> dari <?php echo e($users->total()); ?> user
                <?php else: ?>
                    Tidak ada data user pendaftar
                <?php endif; ?>
            </div>
            <?php if($users->hasPages()): ?>
                <div>
                    <?php echo e($users->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.role', ['role' => 'admin'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Sibook-V5\resources\views/admin/user-register/index.blade.php ENDPATH**/ ?>