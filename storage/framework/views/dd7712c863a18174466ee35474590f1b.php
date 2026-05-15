<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title inertia><?php echo e(config('app.name', 'Portal SPMB - Pesantren Riyadussalikin')); ?></title>
    <meta name="description" content="Portal Pendaftaran Santri Baru (SPMB) Pondok Pesantren Riyadussalikin Padaherang. Daftar sekarang dan bergabunglah menjadi bagian dari generasi Qurani.">
    <meta name="keywords" content="spmb pesantren, pendaftaran santri baru, ppdb pesantren, riyadussalikin padaherang">
    <link rel="icon" href="/Logo Riyad.png" type="image/png">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(config('app.url')); ?>">
    <meta property="og:title" content="Portal SPMB - Pesantren Riyadussalikin">
    <meta property="og:description" content="Pendaftaran Santri Baru Pondok Pesantren Riyadussalikin Padaherang.">
    <meta property="og:image" content="<?php echo e(asset('Logo Riyad.png')); ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo e(config('app.url')); ?>">
    <meta property="twitter:title" content="Portal SPMB - Pesantren Riyadussalikin">
    <meta property="twitter:description" content="Pendaftaran Santri Baru Pondok Pesantren Riyadussalikin Padaherang.">
    <meta property="twitter:image" content="<?php echo e(asset('Logo Riyad.png')); ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <?php echo app('Tighten\Ziggy\BladeRouteGenerator')->generate(); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"]); ?>
    <?php if (!isset($__inertiaSsrDispatched)) { $__inertiaSsrDispatched = true; $__inertiaSsrResponse = app(\Inertia\Ssr\Gateway::class)->dispatch($page); }  if ($__inertiaSsrResponse) { echo $__inertiaSsrResponse->head; } ?>
</head>

<body class="font-sans antialiased">
    <?php if (!isset($__inertiaSsrDispatched)) { $__inertiaSsrDispatched = true; $__inertiaSsrResponse = app(\Inertia\Ssr\Gateway::class)->dispatch($page); }  if ($__inertiaSsrResponse) { echo $__inertiaSsrResponse->body; } elseif (config('inertia.use_script_element_for_initial_page')) { ?><script data-page="app" type="application/json"><?php echo json_encode($page); ?></script><div id="app"></div><?php } else { ?><div id="app" data-page="<?php echo e(json_encode($page)); ?>"></div><?php } ?>
</body>

</html><?php /**PATH C:\laragon\www\PesantrenPPDB\SPMB\resources\views/app.blade.php ENDPATH**/ ?>