<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> - <?php echo e(config('app.name', 'SiBook')); ?></title>

    <!-- Google Fonts Bunny: Figtree & Inter untuk tipografi yang sangat halus & presisi -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    </style>
</head>
<body class="bg-[#FAFAFA] text-[#111827]" x-data="{ sidebarOpen: false, userDropdown: false }">
    
    
    <header class="h-16 bg-white border-b border-[#F0F2F5] px-6 lg:px-8 flex items-center justify-between z-30 sticky top-0 flex-shrink-0">
        <div class="flex items-center gap-4">
            
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 focus:outline-none lg:hidden">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <a href="/" class="text-[20px] font-bold tracking-tight text-[#111827] hover:opacity-90">
                SiBook
            </a>
        </div>

        <?php
            $name = auth()->user()->name ?? 'Admin BPS';
            $words = preg_split('/\s+/', trim($name));
            $initials = '';
            foreach (array_slice($words, 0, 2) as $w) {
                $initials .= strtoupper(mb_substr($w, 0, 1));
            }
            $initials = $initials ?: 'AB';
        ?>

        
        <div class="relative" @click.outside="userDropdown = false">
            <button @click="userDropdown = !userDropdown" 
                    class="w-8 h-8 rounded-full bg-black text-white font-bold text-xs flex items-center justify-center tracking-wider hover:opacity-90 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                <?php echo e($initials); ?>

            </button>

            
            <div x-show="userDropdown"
                 x-cloak
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1.5 z-50">
                <div class="px-4 py-2 border-b border-gray-100">
                    <p class="text-[11px] text-gray-400">Masuk sebagai</p>
                    <p class="text-xs font-semibold text-gray-800 truncate"><?php echo e(auth()->user()->name ?? 'Admin'); ?></p>
                </div>
                <a href="<?php echo e(route('profile.edit')); ?>" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-50">
                    Pengaturan Profil
                </a>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full text-left px-4 py-2 text-xs text-red-600 hover:bg-red-50 font-medium">
                        Keluar (Logout)
                    </button>
                </form>
            </div>
        </div>
    </header>

    
    <div class="h-[calc(100vh-4rem)] flex overflow-hidden">
        
        
        <div x-show="sidebarOpen" 
             x-cloak
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-gray-900/40 lg:hidden" 
             @click="sidebarOpen = false"></div>

        
        <div class="fixed inset-y-16 left-0 z-40 w-64 transform transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 flex-shrink-0"
             :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
            <?php echo $__env->make('layouts.sidebar.' . $role, array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        
        <main class="flex-1 overflow-y-auto px-6 py-6 lg:px-10 lg:py-8 bg-[#FAFAFA]">
            <?php if(session('success')): ?>
                <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl flex items-center justify-between">
                    <span><?php echo e(session('success')); ?></span>
                    <button type="button" @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold">&times;</button>
                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="mb-5 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-sm rounded-xl flex items-center justify-between">
                    <span><?php echo e(session('error')); ?></span>
                    <button type="button" @click="$el.parentElement.remove()" class="text-rose-500 hover:text-rose-700 font-bold">&times;</button>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
</body>
</html>
<?php /**PATH D:\Sibook-V5\resources\views/layouts/role.blade.php ENDPATH**/ ?>