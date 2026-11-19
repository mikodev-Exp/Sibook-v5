<?php $__env->startSection('title', 'Detail Permohonan Magang'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .card-anim { animation: fadeUp .3s ease both; }
    .card-anim:nth-child(2) { animation-delay: .05s; }
    .card-anim:nth-child(3) { animation-delay: .10s; }
    .card-anim:nth-child(4) { animation-delay: .15s; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $user    = $permohonan->user;
    $profile = $user?->mahasiswaProfile;
    $st      = $permohonan->status instanceof \App\Enums\StatusPermohonan
                ? $permohonan->status
                : \App\Enums\StatusPermohonan::from($permohonan->status);
    $badgeClass = match($st) {
        \App\Enums\StatusPermohonan::Approved        => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        \App\Enums\StatusPermohonan::ReviewedByAdmin => 'bg-blue-50 text-blue-700 border-blue-200',
        \App\Enums\StatusPermohonan::Rejected        => 'bg-rose-50 text-rose-700 border-rose-200',
        \App\Enums\StatusPermohonan::Submitted       => 'bg-amber-50 text-amber-700 border-amber-200',
        default                                       => 'bg-gray-100 text-gray-500 border-gray-200',
    };
?>

<div class="max-w-5xl mx-auto space-y-5">

    
    <nav class="flex items-center gap-1.5 text-xs text-gray-400 font-medium">
        <a href="<?php echo e(route('admin.permohonan.index')); ?>" class="text-orange-500 hover:underline font-semibold">Permohonan Magang</a>
        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span>Detail #<?php echo e($permohonan->id); ?></span>
    </nav>

    
    <?php if(session('success')): ?>
    <div x-data="{ show: true }" x-show="show" x-transition
         class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
        <svg class="w-4 h-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <?php echo e(session('success')); ?>

        <button @click="show=false" class="ml-auto text-emerald-500 hover:text-emerald-700">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <?php endif; ?>

    
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 card-anim">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-100 to-orange-200
                            flex items-center justify-center shrink-0 shadow-sm">
                    <span class="text-xl font-extrabold text-orange-600">
                        <?php echo e(strtoupper(substr($user?->name ?? '?', 0, 2))); ?>

                    </span>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-gray-900 tracking-tight"><?php echo e($user?->name ?? '-'); ?></h1>
                    <p class="text-sm text-gray-500"><?php echo e($user?->email ?? '-'); ?></p>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border <?php echo e($badgeClass); ?>">
                    <?php echo e($st->label()); ?>

                </span>
                <a href="<?php echo e(route('admin.permohonan.index')); ?>"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-gray-200
                          text-xs font-semibold text-gray-600 transition">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        
        <div class="lg:col-span-2 space-y-5">

            
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 card-anim">
                <h2 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-orange-100 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </span>
                    Data Institusi & Rencana Magang
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3.5 text-sm">
                    <?php
                        $fields = [
                            ['Universitas',     $profile?->universitas],
                            ['Program Studi',   $profile?->program_studi],
                            ['Semester',        $profile?->semester ? $profile->semester . ' / ' . ($profile->total_semester ?? '-') : null],
                            ['Bidang Diminati', $permohonan->bidang_diminati ?? $profile?->bidang_diminati],
                            ['Tanggal Mulai',   $permohonan->tanggal_mulai_rencana
                                                    ? \Carbon\Carbon::parse($permohonan->tanggal_mulai_rencana)->isoFormat('D MMMM Y')
                                                    : null],
                            ['Tanggal Selesai', $permohonan->tanggal_selesai_rencana
                                                    ? \Carbon\Carbon::parse($permohonan->tanggal_selesai_rencana)->isoFormat('D MMMM Y')
                                                    : null],
                            ['Diajukan',        $permohonan->created_at
                                                    ? \Carbon\Carbon::parse($permohonan->created_at)->isoFormat('D MMMM Y, HH:mm')
                                                    : null],
                        ];
                    ?>
                    <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div>
                            <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5"><?php echo e($label); ?></div>
                            <div class="text-gray-800 font-medium"><?php echo e($value ?? '-'); ?></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 card-anim">
                <h2 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </span>
                    Berkas Dokumen
                    <span class="ml-auto text-[11px] font-normal text-gray-400"><?php echo e($permohonan->dokumens?->count() ?? 0); ?> file</span>
                </h2>

                <?php $__empty_1 = true; $__currentLoopData = $permohonan->dokumens ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dok): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $ext = strtolower(pathinfo($dok->nama_asli ?? $dok->path, PATHINFO_EXTENSION));
                        $iconColor = match($ext) {
                            'pdf'  => 'text-rose-500 bg-rose-50 border-rose-200',
                            'doc','docx' => 'text-blue-500 bg-blue-50 border-blue-200',
                            'jpg','jpeg','png','webp' => 'text-purple-500 bg-purple-50 border-purple-200',
                            'xls','xlsx' => 'text-emerald-500 bg-emerald-50 border-emerald-200',
                            default => 'text-gray-500 bg-gray-100 border-gray-200',
                        };
                    ?>
                    <div class="flex items-center justify-between gap-3 p-3 rounded-xl border border-gray-100 hover:border-gray-200 hover:bg-gray-50/50 transition group mb-2">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-lg <?php echo e($iconColor); ?> border flex items-center justify-center shrink-0 font-bold text-[10px] uppercase">
                                <?php echo e($ext ?: 'FILE'); ?>

                            </div>
                            <div class="min-w-0">
                                <div class="text-xs font-semibold text-gray-800 truncate">
                                    <?php echo e($dok->nama_asli ?? basename($dok->path)); ?>

                                </div>
                                <?php if($dok->jenis): ?>
                                    <div class="text-[10.5px] text-gray-400"><?php echo e(ucwords(str_replace('_', ' ', $dok->jenis))); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <a href="<?php echo e(asset('storage/' . $dok->path)); ?>" target="_blank"
                           class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-orange-50 hover:bg-orange-100
                                  text-orange-600 text-xs font-semibold transition border border-orange-200">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Unduh File
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="py-8 text-center text-gray-400 text-sm">
                        Belum ada berkas yang diunggah.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="space-y-5">

            
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 card-anim">
                <h2 class="text-sm font-bold text-gray-700 mb-4">Status Verifikasi</h2>

                
                <?php
                    $timeline = [
                        ['draft',              'Draft',                        'gray'],
                        ['submitted',          'Diajukan',                     'amber'],
                        ['reviewed_by_admin',  'Ditinjau Admin',               'blue'],
                        ['approved',           'Disetujui',                    'emerald'],
                    ];
                    $stVal = $st->value;
                    $order = ['draft'=>0,'submitted'=>1,'reviewed_by_admin'=>2,'approved'=>3,'rejected'=>3];
                    $curOrder = $order[$stVal] ?? 0;
                ?>
                <div class="relative pl-5 space-y-3">
                    <div class="absolute left-[9px] top-2 bottom-2 w-0.5 bg-gray-100"></div>
                    <?php if($stVal === 'rejected'): ?>
                        <?php $__currentLoopData = $timeline; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$tVal, $tLabel, $tColor]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $done = $order[$tVal] < $curOrder; ?>
                            <div class="flex items-center gap-2.5 relative">
                                <div class="w-4 h-4 rounded-full border-2 shrink-0 z-10
                                    <?php echo e($done ? 'bg-emerald-400 border-emerald-400' : 'bg-gray-100 border-gray-200'); ?>

                                    flex items-center justify-center">
                                    <?php if($done): ?>
                                        <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <?php endif; ?>
                                </div>
                                <span class="text-xs <?php echo e($done ? 'text-gray-700 font-semibold' : 'text-gray-400'); ?>"><?php echo e($tLabel); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center gap-2.5 relative">
                            <div class="w-4 h-4 rounded-full border-2 bg-rose-500 border-rose-500 shrink-0 z-10 flex items-center justify-center">
                                <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </div>
                            <span class="text-xs text-rose-600 font-bold">Ditolak</span>
                        </div>
                    <?php else: ?>
                        <?php $__currentLoopData = $timeline; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$tVal, $tLabel, $tColor]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $done    = $order[$tVal] < $curOrder;
                                $current = $tVal === $stVal;
                            ?>
                            <div class="flex items-center gap-2.5 relative">
                                <div class="w-4 h-4 rounded-full border-2 shrink-0 z-10
                                    <?php echo e($current ? 'bg-orange-500 border-orange-500 shadow-sm shadow-orange-200' : ($done ? 'bg-emerald-400 border-emerald-400' : 'bg-gray-100 border-gray-200')); ?>

                                    flex items-center justify-center">
                                    <?php if($done): ?>
                                        <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <?php elseif($current): ?>
                                        <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                                    <?php endif; ?>
                                </div>
                                <span class="text-xs <?php echo e($current ? 'text-orange-600 font-bold' : ($done ? 'text-gray-700 font-semibold' : 'text-gray-400')); ?>">
                                    <?php echo e($tLabel); ?>

                                </span>
                                <?php if($current): ?>
                                    <span class="ml-auto text-[10px] text-orange-500 font-semibold bg-orange-50 border border-orange-100 px-1.5 py-0.5 rounded-full">Sekarang</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>

                <?php if($permohonan->reviewed_at): ?>
                    <div class="mt-4 pt-3 border-t border-gray-100 text-[11px] text-gray-400">
                        Terakhir diperbarui: <span class="font-medium text-gray-600"><?php echo e(\Carbon\Carbon::parse($permohonan->reviewed_at)->isoFormat('D MMM Y, HH:mm')); ?></span>
                        <?php if($permohonan->reviewer): ?>
                            <br>oleh <span class="font-medium text-gray-600"><?php echo e($permohonan->reviewer->name); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            
            <?php if($permohonan->catatan_admin): ?>
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 card-anim">
                <h2 class="text-sm font-bold text-gray-700 mb-2">Catatan Admin</h2>
                <p class="text-xs text-gray-600 leading-relaxed italic">"<?php echo e($permohonan->catatan_admin); ?>"</p>
            </div>
            <?php endif; ?>

            
            <?php if(in_array($st->value, ['submitted', 'reviewed_by_admin'])): ?>
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 card-anim">
                <h2 class="text-sm font-bold text-gray-700 mb-2">Ambil Tindakan</h2>
                <p class="text-[11px] text-gray-400 mb-4 leading-relaxed">
                    Saat disetujui, akun pemohon otomatis dialihkan menjadi role Mahasiswa dan periode magang aktif sesuai tanggal rencana.
                </p>

                
                <?php if($st->value === 'submitted'): ?>
                <form method="POST" action="<?php echo e(route('admin.permohonan.update-status', $permohonan->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <input type="hidden" name="status" value="reviewed_by_admin">
                    <textarea name="catatan_admin"
                              placeholder="Catatan tinjauan (opsional)…"
                              rows="2"
                              class="w-full px-3 py-2 text-xs border border-gray-200 rounded-xl mb-2 resize-none
                                     focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition"></textarea>
                    <button type="submit"
                            class="w-full py-2.5 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold
                                   transition shadow-sm">
                        Tinjau Berkas (Admin)
                    </button>
                </form>
                <?php endif; ?>

                
                <?php if($st->value === 'reviewed_by_admin'): ?>
                <form method="POST" action="<?php echo e(route('admin.permohonan.update-status', $permohonan->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <input type="hidden" name="status" value="approved">
                    <textarea name="catatan_admin"
                              placeholder="Catatan persetujuan (opsional)…"
                              rows="2"
                              class="w-full px-3 py-2 text-xs border border-gray-200 rounded-xl mb-2 resize-none
                                     focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition"></textarea>
                    <button type="submit"
                            class="w-full py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold
                                   transition shadow-sm">
                        Setujui Berkas
                    </button>
                </form>
                <?php endif; ?>

                
                <div class="mt-3 pt-3 border-t border-gray-100" x-data="{ showReject: false }">
                    <button type="button" @click="showReject = !showReject"
                            class="w-full py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold
                                   transition border border-rose-200">
                        Tolak Permohonan
                    </button>
                    <form method="POST" action="<?php echo e(route('admin.permohonan.update-status', $permohonan->id)); ?>"
                          x-show="showReject" x-transition class="mt-2 space-y-2">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <input type="hidden" name="status" value="rejected">
                        <textarea name="catatan_admin"
                                  placeholder="Alasan penolakan (wajib)…"
                                  rows="2" required
                                  class="w-full px-3 py-2 text-xs border border-rose-200 rounded-xl resize-none
                                         focus:outline-none focus:ring-2 focus:ring-rose-400 transition"></textarea>
                        <button type="submit"
                                class="w-full py-2.5 rounded-xl bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold
                                       transition shadow-sm">
                            Konfirmasi Tolak
                        </button>
                    </form>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.role', ['role' => 'admin'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Sibook-V5\resources\views/admin/permohonan/show.blade.php ENDPATH**/ ?>