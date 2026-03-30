# WP Learn Theme — Навчальна верстка

Навчальний HTML/CSS-макет для освоєння "натягування" верстки на WordPress.

---

## Файли та їх WP-відповідники

| HTML-файл       | PHP-файл у темі   | Призначення                              |
|-----------------|-------------------|------------------------------------------|
| `index.html`    | `index.php`       | Головна сторінка блогу, список постів    |
| `single.html`   | `single.php`      | Окремий пост                             |
| `page.html`     | `page.php`        | Статична сторінка (Про нас, Контакти)   |
| `archive.html`  | `archive.php`     | Архів за рубрикою / міткою / датою      |
| `style.css`     | `style.css`       | CSS + метадані теми (обов'язково!)      |
| *(немає)*       | `header.php`      | Шапка (get_header() підключає його)     |
| *(немає)*       | `footer.php`      | Підвал (get_footer())                   |
| *(немає)*       | `sidebar.php`     | Сайдбар (get_sidebar())                 |
| *(немає)*       | `functions.php`   | Реєстрація меню, сайдбарів, хуків       |
| *(немає)*       | `comments.php`    | Форма та список коментарів              |

---

## Ієрархія шаблонів WordPress (Template Hierarchy)

WordPress обирає шаблон за суворою ієрархією. Наприклад, для рубрики:

```
category-технології.php
  → category-5.php
    → category.php
      → archive.php
        → index.php
```

Повна ієрархія: https://developer.wordpress.org/themes/basics/template-hierarchy/

---

## Головні WP-функції у верстці

### Шапка / підвал

| Функція                  | Де використовується          | Що робить                                      |
|--------------------------|------------------------------|------------------------------------------------|
| `wp_head()`              | `<head>` → перед `</head>`   | Виводить стилі, мета-теги, скрипти плагінів   |
| `wp_footer()`            | Перед `</body>`              | Виводить скрипти плагінів в кінці сторінки    |
| `get_header()`           | На початку кожного шаблону   | Підключає header.php                           |
| `get_footer()`           | В кінці кожного шаблону      | Підключає footer.php                           |
| `get_sidebar()`          | Де потрібен сайдбар          | Підключає sidebar.php                          |
| `bloginfo('name')`       | Назва сайту в `<title>`, лого | Дані з налаштувань WordPress                  |
| `body_class()`           | Атрибут class у `<body>`     | Автоматично додає класи залежно від сторінки  |

### Меню

```php
// functions.php — реєстрація зони меню
register_nav_menus([
    'primary' => 'Головне меню',
    'footer'  => 'Меню у підвалі',
]);

// header.php / footer.php — виведення меню
wp_nav_menu([
    'theme_location' => 'primary',
    'menu_class'     => 'main-nav__list',
    'container'      => false,
    'fallback_cb'    => false,
]);
```

**Важливо:** WordPress автоматично додає класи до пунктів меню:
- `current-menu-item` — поточна сторінка
- `menu-item-has-children` — є підменю
- `sub-menu` — список підменю

### The Loop (серце WordPress)

```php
<?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php the_post_thumbnail('large'); ?>

            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

            <div class="post-meta">
                <?php echo get_the_date(); ?>
                <?php the_author_posts_link(); ?>
                <?php the_category(', '); ?>
            </div>

            <div class="entry-content">
                <?php the_content(); ?>
                <?php // або для анонсу: the_excerpt(); ?>
            </div>
        </article>

    <?php endwhile; ?>

    <?php the_posts_pagination(); ?>

<?php else : ?>
    <p>Постів не знайдено.</p>
<?php endif; ?>
```

### Хлібні крихти

WordPress не має вбудованих хлібних крихт. Підключаються через плагін:

**Варіант 1 — Yoast SEO:**
```php
// functions.php — увімкнути в налаштуваннях Yoast
yoast_breadcrumb('<nav class="breadcrumbs">', '</nav>');
```

**Варіант 2 — Breadcrumb NavXT:**
```php
if ( function_exists('bcn_display') ) {
    echo '<nav class="breadcrumbs" aria-label="Хлібні крихти">';
    bcn_display();
    echo '</nav>';
}
```

**Варіант 3 — Rank Math:**
```php
echo rank_math()->get_breadcrumbs();
// або шорткод: echo do_shortcode('[rank_math_breadcrumb]');
```

### Сайдбари та віджети

```php
// functions.php — реєстрація сайдбару
function wplearn_widgets_init() {
    register_sidebar([
        'name'          => 'Бічна панель',
        'id'            => 'sidebar-1',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget__title">',
        'after_title'   => '</h3>',
    ]);

    // Footer widget zones
    register_sidebar(['name' => 'Підвал 1', 'id' => 'footer-1', /* ... */]);
    register_sidebar(['name' => 'Підвал 2', 'id' => 'footer-2', /* ... */]);
    register_sidebar(['name' => 'Підвал 3', 'id' => 'footer-3', /* ... */]);
}
add_action('widgets_init', 'wplearn_widgets_init');

// sidebar.php — виведення
if ( is_active_sidebar('sidebar-1') ) {
    dynamic_sidebar('sidebar-1');
}
```

### Підключення стилів і скриптів

```php
// functions.php — ПРАВИЛЬНИЙ спосіб (ніколи не в header.php напряму!)
function wplearn_enqueue_assets() {
    wp_enqueue_style(
        'wplearn-style',
        get_stylesheet_uri(),    // підключає style.css теми
        [],
        '1.0'
    );

    wp_enqueue_script(
        'wplearn-navigation',
        get_template_directory_uri() . '/js/navigation.js',
        [],
        '1.0',
        true  // true = в footer, false = в head
    );
}
add_action('wp_enqueue_scripts', 'wplearn_enqueue_assets');
```

### style.css — метадані теми (обов'язково!)

Перший коментар у `style.css` — це не просто коментар. WordPress читає його
як метадані теми. Без нього тема не з'явиться у списку тем.

```css
/*
Theme Name:   WP Learn Theme
Theme URI:    https://example.com
Author:       Your Name
Author URI:   https://yoursite.com
Description:  Навчальна тема WordPress
Version:      1.0
License:      GNU General Public License v2 or later
License URI:  http://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wplearn
*/
```

---

## Плагіни, що пов'язані з цією версткою

| Плагін             | Що надає у верстці              | Функція виведення               |
|--------------------|---------------------------------|---------------------------------|
| Yoast SEO          | Хлібні крихти                  | `yoast_breadcrumb()`           |
| Rank Math          | Хлібні крихти, SEO-дані        | `rank_math()->get_breadcrumbs()`|
| Breadcrumb NavXT   | Гнучкі хлібні крихти           | `bcn_display()`                |
| Contact Form 7     | Форми (контакти)               | `[contact-form-7 id="1"]`      |
| WooCommerce        | Інтернет-магазин               | Власні шаблони та хуки         |
| Jetpack            | Форми, каруселі, статистика    | Шорткоди                       |

---

## Корисні посилання

- [WordPress Template Hierarchy](https://developer.wordpress.org/themes/basics/template-hierarchy/)
- [WordPress Theme Developer Handbook](https://developer.wordpress.org/themes/)
- [WordPress Function Reference](https://developer.wordpress.org/reference/)
- [The Loop](https://developer.wordpress.org/themes/basics/the-loop/)
- [wp_enqueue_scripts](https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/)

---

## Порядок "натягування" цієї верстки на WP

1. Створити папку теми в `/wp-content/themes/wplearn/`
2. Скопіювати `style.css` (з метаданими теми вгорі)
3. Розрізати `index.html` на `header.php`, `index.php`, `footer.php`, `sidebar.php`
4. Повторити для `single.html` → `single.php`, `page.html` → `page.php`, `archive.html` → `archive.php`
5. Замінити статичний HTML на PHP-виклики WordPress (the_title, the_content, тощо)
6. Написати `functions.php`: реєстрація меню, сайдбарів, wp_enqueue_style
7. Активувати тему в адмінпанелі: Зовнішній вигляд → Теми
8. Налаштувати меню: Зовнішній вигляд → Меню
9. Налаштувати віджети: Зовнішній вигляд → Віджети
10. Перевірити всі типи сторінок у браузері
