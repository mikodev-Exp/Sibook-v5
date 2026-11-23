<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'alt' => 'Badan Pusat Statistik Kota Surabaya',
    'class' => 'h-10 w-auto object-contain',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'alt' => 'Badan Pusat Statistik Kota Surabaya',
    'class' => 'h-10 w-auto object-contain',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div <?php echo e($attributes->merge(['class' => 'inline-flex items-center justify-center shrink-0'])); ?>>
    <img src="<?php echo e(asset('images/logo-bps.jpg')); ?>"
         alt="<?php echo e($alt); ?>"
         class="<?php echo e($class); ?>"
         loading="lazy"
         onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.classList.remove('hidden');" />

    
    <div class="hidden inline-flex items-center justify-center bg-indigo-50 border border-indigo-200 text-indigo-700 font-bold text-xs rounded-lg px-2.5 py-1">
        BPS
    </div>
</div>
<?php /**PATH D:\Sibook-V5\resources\views/components/logo-bps.blade.php ENDPATH**/ ?>