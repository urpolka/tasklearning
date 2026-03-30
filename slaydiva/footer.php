<footer>
    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/brat2.jpg">
    <p>Welcome to Kazakhstaaaan</p>
</footer>

<script>
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
        } else {
            entry.target.classList.remove('visible');
        }
    });
});

document.querySelectorAll('.bounce, .slide-left, .slide-right, .spin')
    .forEach(el => observer.observe(el));
</script>

<?php wp_footer(); ?>
</body>
</html>