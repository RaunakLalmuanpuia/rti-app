<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <meta name="author" content="Raunak Lalmuanpuia">
    <meta name="description" content="Developed by Raunak Lalmuanpuia">


    <title inertia>{{ config('app.name', 'RTI') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

{{--    <script src="https://cdn.ux4g.gov.in/accessibility-beta-v1.15/accessibility-widget.js" defer></script>--}}

    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead

</head>
<body style="background-color: #fbfbfc" class="font-sans antialiased">
@inertia
</body>
</html>
