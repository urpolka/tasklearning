<?php

function slaydiva_styles() {
    wp_enqueue_style('slaydiva-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'slaydiva_styles');
function register_my_menus() {
    register_nav_menus( array(
        'primary' => 'Главное меню',
    ) );
}
add_action( 'after_setup_theme', 'register_my_menus' );


function my_contact_form_shortcode() {
    ob_start();

    if ( isset($_GET['status']) ) {
        if ( $_GET['status'] === 'success' ) {
            echo '<div class="form-message success">Партия гордится тобой!</div>';
        } elseif ( $_GET['status'] === 'error' ) {
            echo '<div class="form-message error">Ошибка. Партия недовольна</div>';
        }
    }
    $errors = get_transient('my_form_errors');
    if ( is_array($errors) ) {
        echo '<div class="form-message error-list"><ul>';
        foreach ( $errors as $error ) {
            echo '<li>' . esc_html($error) . '</li>';
        }
        echo '</ul></div>';
        delete_transient('my_form_errors');
    }
    $old = get_transient('my_form_data');
    if ( is_array($old) ) {
        $old_name    = esc_attr($old['name']);
        $old_message = esc_textarea($old['message']);
        delete_transient('my_form_data');
    } else {
        $old_name = $old_message = '';
    }
    ?>
    <form method="post" action="" class="custom-contact-form">
        <?php wp_nonce_field('my_form_action', 'my_form_nonce'); ?>
        <p>
            <label for="cf_name">Имя *</label>
            <input type="text" name="name" id="cf_name" value="<?php echo $old_name; ?>" required>
        </p>
        <p>
            <label for="cf_message">Послание *</label>
            <textarea name="message" id="cf_message" rows="5" required><?php echo $old_message; ?></textarea>
        </p>
        <p>
            <button type="submit" name="submit_form">Передать</button>
        </p>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('contact_form', 'my_contact_form_shortcode');

function my_form_handler() {
    if ( isset($_POST['submit_form']) ) {

        if ( ! isset($_POST['my_form_nonce']) || ! wp_verify_nonce($_POST['my_form_nonce'], 'my_form_action') ) {
            wp_die('Недействительный запрос.');
        }
        $name    = sanitize_text_field( $_POST['name'] );
        $message = sanitize_textarea_field( $_POST['message'] );
        $errors  = [];

        if ( empty($name) ) {
            $errors[] = 'Поле "Имя" обязательно.';
        }
        if ( empty($message) ) {
            $errors[] = 'Поле "Послание" обязательно.';
        }

        if ( empty($errors) ) {
            $to = get_option('admin_email'); // письмо уйдёт на email администратора
            $subject = 'Сообщение с сайта от ' . $name;
            $body    = "Имя: $name\n\nСообщение:\n$message";
            $headers = array('Content-Type: text/plain; charset=UTF-8');

            $sent = wp_mail($to, $subject, $body, $headers);
            $status = $sent ? 'success' : 'error';
            wp_redirect( add_query_arg( 'status', $status, wp_get_referer() ) );
            exit;
        } else {
            set_transient( 'my_form_errors', $errors, 60 );
            set_transient( 'my_form_data', array('name' => $name, 'message' => $message), 60 );
            wp_redirect( wp_get_referer() );
            exit;
        }
    }
}
add_action('init', 'my_form_handler');

