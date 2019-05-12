<div class="wrap">
    <h2>North Shore Round Up Settings</h2>
    <?php settings_errors(); ?>
    <form method="POST" action="options.php">
        <?php
        settings_fields('nsru-options');
        do_settings_sections('nsru-options');
        submit_button();
        ?>
    </form>
</div>