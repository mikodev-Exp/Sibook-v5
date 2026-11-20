<?php $__env->startSection('title', 'Lanjutkan Logbook'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto space-y-5">
    <div class="flex items-center gap-3">
        <a href="<?php echo e(route('mahasiswa.logbook.index')); ?>" 
           class="p-2 bg-white rounded-xl shadow-sm border border-gray-200 hover:bg-gray-50 text-gray-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Lanjutkan Proyek Logbook</h1>
            <p class="text-xs text-gray-500 mt-0.5">Pilih proyek atau pekerjaan yang masih dalam status draf untuk dilanjutkan.</p>
        </div>
    </div>

    <?php if($drafts->isEmpty()): ?>
        <div class="bg-white p-12 rounded-2xl shadow-[0_1px_3px_rgba(0,0,0,0.02)] border border-gray-150 text-center">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="text-sm font-bold text-gray-800">Tidak ada draf tertunda</p>
            <p class="text-xs text-gray-400 mt-1">Semua aktivitas logbook Anda telah selesai dikirim.</p>
            <div class="mt-4">
                <a href="<?php echo e(route('mahasiswa.logbook.create')); ?>" class="inline-flex items-center gap-1.5 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                    Buat Logbook Baru
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="grid gap-3.5">
            <?php $__currentLoopData = $drafts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $draft): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white p-5 rounded-2xl shadow-[0_1px_3px_rgba(0,0,0,0.02)] border border-gray-150 hover:border-orange-200 transition flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-orange-50 text-orange-700 border border-orange-200 uppercase tracking-wider">
                                <?php echo e($draft->tim); ?>

                            </span>
                            <?php if($draft->jenis_gsbpm): ?>
                                <span class="text-[11px] text-gray-400 font-medium">GSBPM: <?php echo e($draft->jenis_gsbpm); ?></span>
                            <?php endif; ?>
                        </div>
                        <h3 class="text-sm font-bold text-gray-900 mt-1.5"><?php echo e($draft->kegiatan); ?></h3>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-1"><?php echo e($draft->deskripsi); ?></p>
                        <p class="text-[11px] text-gray-400 mt-2 font-medium">
                            Dimulai: <?php echo e(\Carbon\Carbon::parse($draft->tanggal)->isoFormat('D MMM Y')); ?> (<?php echo e($draft->jam_mulai ? substr($draft->jam_mulai, 0, 5) : '-'); ?>)
                        </p>
                    </div>
                    <div class="shrink-0">
                        <a href="<?php echo e(route('mahasiswa.logbook.create', ['parent_id' => $draft->id])); ?>" 
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                            <span>Lanjutkan Hari Ini</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.role', ['role' => 'mahasiswa'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Sibook-V5\resources\views/mahasiswa/logbook/lanjutkan.blade.php ENDPATH**/ ?>