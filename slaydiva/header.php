<!DOCTYPE html>
<html>
 <head>
    <meta charset="UTF-8">
    <title>Slaydiva</title>
    <?php wp_head(); ?>
</head>
<body>
    <div id="color-picker-panel">
    <p>Выбери цвет фона:</p>
    <input type="color" id="bg-color-picker" value="#800080">
    <button onclick="saveBgColor()">Сохранить</button>
</div>

<script>

window.onload = function() {
    const savedColor = localStorage.getItem('bgColor');
    if (savedColor) {
        document.body.style.backgroundColor = savedColor;
        document.getElementById('bg-color-picker').value = savedColor;
    }
}


document.getElementById('bg-color-picker').addEventListener('input', function() {
    document.body.style.backgroundColor = this.value;
});


function saveBgColor() {
    const color = document.getElementById('bg-color-picker').value;
    localStorage.setItem('bgColor', color);
    alert('Цвет сохранён!');
}
</script>
    <audio id="bg-music" loop autoplay>
    <source src="<?php echo get_template_directory_uri(); ?>/assets/audio/music.mp3" type="audio/mpeg">
</audio>

<button id="music-btn" onclick="toggleMusic()">🔊 Выключить музыку</button>

<script>
function toggleMusic() {
    const music = document.getElementById('bg-music');
    const btn = document.getElementById('music-btn');
    
    if (music.paused) {
        music.play();
        btn.innerHTML = '🔊 Выключить музыку';
    } else {
        music.pause();
        btn.innerHTML = '🔇 Включить музыку';
    }
}
</script>
    
<header>
    <div class="logo">
        <a href="<?php echo home_url(); ?>">Slaydiva</a>
    </div>

<?php
wp_nav_menu( array(
    'theme_location' => 'main-menu',
    'container'      => 'nav',
    'menu_class'     => 'main-menu',
    'fallback_cb'    => false,
) );
?>
</header>
