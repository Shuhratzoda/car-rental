<!DOCTYPE html>
<html lang="tg">
<head>
    <meta charset="utf-8">
    <title><?= e($title ?? 'Хато') ?></title>
    <style>
        body{font-family:system-ui,sans-serif;background:#0b0b18;color:#eee;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0}
        .box{text-align:center;padding:2rem}
        h1{font-size:4rem;margin:0;color:#7c5cff}
        p{opacity:.8}
        a{color:#00e0c6}
    </style>
</head>
<body>
<div class="box">
    <h1><?= (int)($code ?? 500) ?></h1>
    <h2><?= e($title ?? 'Хато') ?></h2>
    <p><?= e($message ?? '') ?></p>
    <p><a href="/">Ба саҳифаи асосӣ</a></p>
</div>
</body>
</html>
