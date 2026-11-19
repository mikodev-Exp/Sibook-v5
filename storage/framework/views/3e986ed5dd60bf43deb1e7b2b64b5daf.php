<?php $__env->startSection('title', 'Perizinan'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto space-y-6">

    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Perizinan Magang</h1>
            <p class="text-xs text-gray-500 mt-1">Ajukan permohonan izin atau cuti dan pantau status persetujuannya.</p>
        </div>

        
        <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-white text-gray-700 border border-gray-200 shadow-sm">
                <span class="font-bold"><?php echo e($totalIzin); ?></span> Total
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="font-bold"><?php echo e($disetujui); ?></span> Disetujui
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                <span class="font-bold"><?php echo e($menunggu); ?></span> Menunggu
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                <span class="font-bold"><?php echo e($ditolak); ?></span> Ditolak
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-150 p-6 shadow-[0_1px_3px_rgba(0,0,0,0.02)] sticky top-20">
                <div class="flex items-center gap-2.5 pb-4 mb-4 border-b border-gray-100">
                    <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Ajukan Izin Baru</h2>
                        <p class="text-[11px] text-gray-400">Lengkapi formulir permohonan</p>
                    </div>
                </div>

                <form action="<?php echo e(route('mahasiswa.perizinan.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <?php echo csrf_field(); ?>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" value="<?php echo e(old('tanggal_mulai', date('Y-m-d'))); ?>" required
                               class="w-full text-xs border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:outline-none focus:ring-1 focus:ring-orange-500">
                        <?php $__errorArgs = ['tanggal_mulai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-[11px] text-rose-600 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" value="<?php echo e(old('tanggal_selesai', date('Y-m-d'))); ?>" required
                               class="w-full text-xs border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:outline-none focus:ring-1 focus:ring-orange-500">
                        <?php $__errorArgs = ['tanggal_selesai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-[11px] text-rose-600 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Alasan Izin</label>
                        <textarea name="alasan" rows="3" required placeholder="Jelaskan alasan izin / sakit..."
                                  class="w-full text-xs border border-gray-200 rounded-xl p-3 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-orange-500"><?php echo e(old('alasan')); ?></textarea>
                        <?php $__errorArgs = ['alasan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-[11px] text-rose-600 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Unggah Surat / Bukti (PDF / Foto)</label>
                        <input type="file" name="bukti_file" accept=".pdf,.jpg,.jpeg,.png"
                               class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                        <p class="text-[11px] text-gray-400 mt-1">Maksimal 5MB (Format PDF, PNG, JPG)</p>
                        <?php $__errorArgs = ['bukti_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-[11px] text-rose-600 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="pt-1">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Atau Tautan Dokumen (Google Drive, dll)</label>
                        <input type="url" name="url_bukti" value="<?php echo e(old('url_bukti')); ?>" placeholder="https://..."
                               class="w-full text-xs border border-gray-200 rounded-xl px-3 py-2 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-orange-500">
                        <?php $__errorArgs = ['url_bukti'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-[11px] text-rose-600 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <button type="submit"
                            class="w-full py-2.5 px-4 bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold rounded-xl transition shadow-sm hover:shadow">
                        Kirim Pengajuan Izin
                    </button>
                </form>
            </div>
        </div>

        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-150 shadow-[0_1px_3px_rgba(0,0,0,0.02)] overflow-hidden flex flex-col min-h-[500px]">
                <div class="p-4 sm:p-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Riwayat Pengajuan Izin</h2>
                        <p class="text-[11px] text-gray-400">Daftar perizinan yang telah Anda ajukan</p>
                    </div>
                </div>

                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse text-[13px]">
                        <thead>
                            <tr class="border-b border-gray-100 font-semibold text-gray-700 bg-gray-50/50">
                                <th class="py-3 px-4">Periode</th>
                                <th class="py-3 px-3">Alasan</th>
                                <th class="py-3 px-3">Bukti</th>
                                <th class="py-3 px-4 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-800">
                            <?php $__empty_1 = true; $__currentLoopData = $perizinans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $status = $item->status?->value ?? (string) $item->status;
                                    $badge = match($status) {
                                        'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        default => 'bg-amber-50 text-amber-700 border-amber-200',
                                    };
                                    $label = match($status) {
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                        default => 'Menunggu Review',
                                    };
                                ?>
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <div class="font-semibold text-gray-900 text-xs">
                                            <?php echo e(\Carbon\Carbon::parse($item->tanggal_mulai)->isoFormat('D MMM Y')); ?>

                                        </div>
                                        <div class="text-[11px] text-gray-400">
                                            s/d <?php echo e(\Carbon\Carbon::parse($item->tanggal_selesai)->isoFormat('D MMM Y')); ?>

                                        </div>
                                    </td>
                                    <td class="py-3.5 px-3 max-w-xs">
                                        <p class="text-xs text-gray-800 line-clamp-2" title="<?php echo e($item->alasan); ?>"><?php echo e($item->alasan); ?></p>
                                        <?php if($item->catatan): ?>
                                            <p class="text-[11px] text-gray-400 mt-1 italic">Catatan: <?php echo e($item->catatan); ?></p>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3.5 px-3 whitespace-nowrap">
                                        <?php if($item->url_bukti): ?>
                                            <a href="<?php echo e($item->url_bukti); ?>" target="_blank"
                                               class="text-xs font-semibold text-orange-600 hover:underline inline-flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                                <span>Lihat</span>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold border <?php echo e($badge); ?>">
                                            <?php echo e($label); ?>

                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <svg class="w-10 h-10 mb-2 text-gray-300 stroke-[1.2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <p class="text-xs font-semibold text-gray-600">Belum ada pengajuan izin</p>
                                            <p class="text-[11px] text-gray-400 mt-0.5">Ajukan permohonan izin melalui formulir di samping.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if($perizinans->hasPages()): ?>
                    <div class="p-4 border-t border-gray-100 mt-auto bg-gray-50/40">
                        <?php echo e($perizinans->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.role', ['role' => 'mahasiswa'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Sibook-V5\resources\views/mahasiswa/perizinan/index.blade.php ENDPATH**/ ?>