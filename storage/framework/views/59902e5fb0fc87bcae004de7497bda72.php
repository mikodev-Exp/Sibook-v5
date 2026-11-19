<?php
    $pendingIzin = auth()->user()->perizinans()->where('status', 'submitted')->count();
    $draftLogbook = auth()->user()->logbooks()->where('status', 'draft')->count();
?>

<aside class="w-64 bg-white border-r border-[#F0F2F5] h-full flex flex-col justify-between py-5 px-3 select-none">
    
    <div class="space-y-1">

        
        <a href="<?php echo e(route('mahasiswa.dashboard')); ?>"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13.5px] transition-colors duration-150
                  <?php echo e(request()->routeIs('mahasiswa.dashboard') 
                     ? 'bg-[#FEF6EE] text-[#E06D28] font-semibold' 
                     : 'text-[#4B5563] hover:text-[#111827] hover:bg-gray-50/80 font-normal'); ?>">
            <svg class="w-[18px] h-[18px] flex-shrink-0 <?php echo e(request()->routeIs('mahasiswa.dashboard') ? 'text-[#E06D28]' : 'text-[#9CA3AF]'); ?>" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Dashboard</span>
        </a>

        
        <a href="<?php echo e(route('mahasiswa.presensi.index')); ?>"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13.5px] transition-colors duration-150
                  <?php echo e(request()->routeIs('mahasiswa.presensi.*') 
                     ? 'bg-[#FEF6EE] text-[#E06D28] font-semibold' 
                     : 'text-[#4B5563] hover:text-[#111827] hover:bg-gray-50/80 font-normal'); ?>">
            <svg class="w-[18px] h-[18px] flex-shrink-0 <?php echo e(request()->routeIs('mahasiswa.presensi.*') ? 'text-[#E06D28]' : 'text-[#9CA3AF]'); ?>" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Presensi Harian</span>
        </a>

        
        <a href="<?php echo e(route('mahasiswa.logbook.index')); ?>"
           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-[13.5px] transition-colors duration-150
                  <?php echo e(request()->routeIs('mahasiswa.logbook.*') 
                     ? 'bg-[#FEF6EE] text-[#E06D28] font-semibold' 
                     : 'text-[#4B5563] hover:text-[#111827] hover:bg-gray-50/80 font-normal'); ?>">
            <div class="flex items-center gap-3 min-w-0">
                <svg class="w-[18px] h-[18px] flex-shrink-0 <?php echo e(request()->routeIs('mahasiswa.logbook.*') ? 'text-[#E06D28]' : 'text-[#9CA3AF]'); ?>" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span>Logbook</span>
            </div>
            <?php if($draftLogbook > 0): ?>
                <span class="px-1.5 py-0.5 text-[10px] font-bold rounded-md bg-amber-100 text-amber-700">
                    <?php echo e($draftLogbook); ?>

                </span>
            <?php endif; ?>
        </a>

        
        <a href="<?php echo e(route('mahasiswa.perizinan.index')); ?>"
           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-[13.5px] transition-colors duration-150
                  <?php echo e(request()->routeIs('mahasiswa.perizinan.*') 
                     ? 'bg-[#FEF6EE] text-[#E06D28] font-semibold' 
                     : 'text-[#4B5563] hover:text-[#111827] hover:bg-gray-50/80 font-normal'); ?>">
            <div class="flex items-center gap-3 min-w-0">
                <svg class="w-[18px] h-[18px] flex-shrink-0 <?php echo e(request()->routeIs('mahasiswa.perizinan.*') ? 'text-[#E06D28]' : 'text-[#9CA3AF]'); ?>" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Perizinan</span>
            </div>
            <?php if($pendingIzin > 0): ?>
                <span class="px-1.5 py-0.5 text-[10px] font-bold rounded-md bg-blue-100 text-blue-700">
                    <?php echo e($pendingIzin); ?>

                </span>
            <?php endif; ?>
        </a>

    </div>

    
    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span class="text-[11px] font-semibold text-gray-700">Mahasiswa Magang</span>
        </div>
        <p class="text-[11px] text-gray-400 mt-1 truncate">BPS Kota Surabaya</p>
    </div>
</aside>
<?php /**PATH D:\Sibook-V5\resources\views/layouts/sidebar/mahasiswa.blade.php ENDPATH**/ ?>