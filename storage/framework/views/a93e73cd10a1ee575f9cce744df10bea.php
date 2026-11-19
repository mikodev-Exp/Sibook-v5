<?php
    $permohonanPendingCount = \App\Models\PermohonanMagang::where('status', 'submitted')->count();
    $userRegisterCount = \App\Models\User::role('pendaftar')->count();
    $perizinanPendingCount = \App\Models\Perizinan::where('status', 'submitted')->count();
    $logbookPendingCount = \App\Models\Logbook::where('status', 'submitted')->count();
?>

<aside class="w-64 bg-white border-r border-[#F0F2F5] h-full flex flex-col justify-between py-5 px-3 select-none">
    
    <div class="space-y-1">

        
        <a href="<?php echo e(route('admin.logbook.index')); ?>"
           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-[13.5px] transition-colors duration-150
                  <?php echo e(request()->routeIs('admin.logbook.*') 
                     ? 'bg-[#FEF6EE] text-[#E06D28] font-semibold' 
                     : 'text-[#4B5563] hover:text-[#111827] hover:bg-gray-50/80 font-normal'); ?>">
            <div class="flex items-center gap-3 min-w-0">
                <svg class="w-[18px] h-[18px] flex-shrink-0 <?php echo e(request()->routeIs('admin.logbook.*') ? 'text-[#E06D28]' : 'text-[#9CA3AF]'); ?>" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span>Logbook</span>
            </div>
            <?php if($logbookPendingCount > 0): ?>
                <span class="px-1.5 py-0.5 text-[10.5px] font-bold rounded-md bg-amber-100 text-amber-800">
                    <?php echo e($logbookPendingCount); ?>

                </span>
            <?php endif; ?>
        </a>

        
        <a href="<?php echo e(route('admin.perizinan.index')); ?>"
           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-[13.5px] transition-colors duration-150
                  <?php echo e(request()->routeIs('admin.perizinan.*') 
                     ? 'bg-[#FEF6EE] text-[#E06D28] font-semibold' 
                     : 'text-[#4B5563] hover:text-[#111827] hover:bg-gray-50/80 font-normal'); ?>">
            <div class="flex items-center gap-3 min-w-0">
                <svg class="w-[18px] h-[18px] flex-shrink-0 <?php echo e(request()->routeIs('admin.perizinan.*') ? 'text-[#E06D28]' : 'text-[#9CA3AF]'); ?>" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Perizinan</span>
            </div>
            <?php if($perizinanPendingCount > 0): ?>
                <span class="px-1.5 py-0.5 text-[10.5px] font-bold rounded-md bg-orange-100 text-orange-800">
                    <?php echo e($perizinanPendingCount); ?>

                </span>
            <?php endif; ?>
        </a>

        
        <a href="<?php echo e(route('admin.presensi.manual')); ?>"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13.5px] transition-colors duration-150
                  <?php echo e(request()->routeIs('admin.presensi.manual') 
                     ? 'bg-[#FEF6EE] text-[#E06D28] font-semibold' 
                     : 'text-[#4B5563] hover:text-[#111827] hover:bg-gray-50/80 font-normal'); ?>">
            
            <svg class="w-[18px] h-[18px] flex-shrink-0 <?php echo e(request()->routeIs('admin.presensi.manual') ? 'text-[#E06D28]' : 'text-[#9CA3AF]'); ?>" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Presensi Manual</span>
        </a>

        
        <a href="<?php echo e(route('admin.presensi.index')); ?>"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13.5px] transition-colors duration-150
                  <?php echo e(request()->routeIs('admin.presensi.index') 
                     ? 'bg-[#FEF6EE] text-[#E06D28] font-semibold' 
                     : 'text-[#4B5563] hover:text-[#111827] hover:bg-gray-50/80 font-normal'); ?>">
            
            <svg class="w-[18px] h-[18px] flex-shrink-0 <?php echo e(request()->routeIs('admin.presensi.index') ? 'text-[#E06D28]' : 'text-[#9CA3AF]'); ?>" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <span>Presensi Magang</span>
        </a>

        
        <a href="<?php echo e(route('admin.user-register.index')); ?>"
           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-[13.5px] transition-colors duration-150
                  <?php echo e(request()->routeIs('admin.user-register.*') 
                     ? 'bg-[#FEF6EE] text-[#E06D28] font-semibold' 
                     : 'text-[#4B5563] hover:text-[#111827] hover:bg-gray-50/80 font-normal'); ?>">
            <span class="flex items-center gap-3">
                
                <svg class="w-[18px] h-[18px] flex-shrink-0 <?php echo e(request()->routeIs('admin.user-register.*') ? 'text-[#E06D28]' : 'text-[#9CA3AF]'); ?>" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                <span>User Register</span>
            </span>
            <?php if($userRegisterCount > 0): ?>
                <span class="inline-flex items-center justify-center px-2 py-0.5 text-[11px] font-semibold rounded-full bg-[#FFF4EC] text-[#D97706]">
                    <?php echo e($userRegisterCount); ?>

                </span>
            <?php endif; ?>
        </a>

        
        <a href="<?php echo e(route('admin.dashboard')); ?>"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13.5px] transition-colors duration-150
                  <?php echo e(request()->routeIs('admin.dashboard') 
                     ? 'bg-[#FEF6EE] text-[#E06D28] font-semibold' 
                     : 'text-[#4B5563] hover:text-[#111827] hover:bg-gray-50/80 font-normal'); ?>">
            
            <svg class="w-[18px] h-[18px] flex-shrink-0 <?php echo e(request()->routeIs('admin.dashboard') ? 'text-[#E06D28]' : 'text-[#9CA3AF]'); ?>" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                <rect x="3" y="14" width="7" height="7" rx="1.5"/>
            </svg>
            <span>Dashboard</span>
        </a>

        
        <a href="<?php echo e(route('admin.mahasiswa.index')); ?>"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13.5px] transition-colors duration-150
                  <?php echo e(request()->routeIs('admin.mahasiswa.*') 
                     ? 'bg-[#FEF6EE] text-[#E06D28] font-semibold' 
                     : 'text-[#4B5563] hover:text-[#111827] hover:bg-gray-50/80 font-normal'); ?>">
            
            <svg class="w-[18px] h-[18px] flex-shrink-0 <?php echo e(request()->routeIs('admin.mahasiswa.*') ? 'text-[#E06D28]' : 'text-[#9CA3AF]'); ?>" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                <circle cx="12" cy="12" r="9"/>
                <circle cx="12" cy="10" r="3"/>
                <path stroke-linecap="round" d="M6.168 18.849A4 4 0 0110 16h4a4 4 0 013.832 2.849"/>
            </svg>
            <span>Mahasiswa Magang</span>
        </a>

        
        <a href="<?php echo e(route('admin.permohonan.index')); ?>"
           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-[13.5px] transition-colors duration-150
                  <?php echo e(request()->routeIs('admin.permohonan.*') 
                     ? 'bg-[#FEF6EE] text-[#E06D28] font-semibold' 
                     : 'text-[#4B5563] hover:text-[#111827] hover:bg-gray-50/80 font-normal'); ?>">
            <span class="flex items-center gap-3">
                
                <svg class="w-[18px] h-[18px] flex-shrink-0 <?php echo e(request()->routeIs('admin.permohonan.*') ? 'text-[#E06D28]' : 'text-[#9CA3AF]'); ?>" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 9h2m-2 4h6m-6 4h6"/>
                </svg>
                <span>Permohonan Magang</span>
            </span>
            <?php if($permohonanPendingCount > 0): ?>
                <span class="inline-flex items-center justify-center px-2 py-0.5 text-[11px] font-semibold rounded-full bg-[#FFF4EC] text-[#D97706]">
                    <?php echo e($permohonanPendingCount); ?>

                </span>
            <?php endif; ?>
        </a>

    </div>

    
    <div class="px-3.5 pt-4">
        <div class="flex items-center justify-between text-[#4B5563] text-[13px] font-normal cursor-pointer hover:text-[#111827] transition">
            <span>Presensi</span>
            <svg class="w-3.5 h-3.5 text-[#9CA3AF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </div>
</aside>
<?php /**PATH D:\Sibook-V5\resources\views/layouts/sidebar/admin.blade.php ENDPATH**/ ?>