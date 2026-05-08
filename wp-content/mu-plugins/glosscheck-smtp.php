<?php
/**
 * Plugin Name: GlossCheck SMTP
 * Description: Configures WordPress mail delivery through SMTP when credentials are defined in wp-config.php.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('phpmailer_init', static function ($phpmailer): void {
    if (
        !defined('GLOSSCHECK_SMTP_HOST') ||
        !defined('GLOSSCHECK_SMTP_USER') ||
        !defined('GLOSSCHECK_SMTP_PASS')
    ) {
        return;
    }

    $phpmailer->isSMTP();
    $phpmailer->Host = GLOSSCHECK_SMTP_HOST;
    $phpmailer->SMTPAuth = true;
    $phpmailer->Username = GLOSSCHECK_SMTP_USER;
    $phpmailer->Password = GLOSSCHECK_SMTP_PASS;
    $phpmailer->Port = defined('GLOSSCHECK_SMTP_PORT') ? (int) GLOSSCHECK_SMTP_PORT : 465;
    $phpmailer->SMTPSecure = defined('GLOSSCHECK_SMTP_SECURE') ? GLOSSCHECK_SMTP_SECURE : 'ssl';
    $phpmailer->CharSet = 'UTF-8';

    if (defined('GLOSSCHECK_SMTP_FROM') && is_email(GLOSSCHECK_SMTP_FROM)) {
        $phpmailer->From = GLOSSCHECK_SMTP_FROM;
        $phpmailer->Sender = GLOSSCHECK_SMTP_FROM;
    }

    if (defined('GLOSSCHECK_SMTP_FROM_NAME')) {
        $phpmailer->FromName = GLOSSCHECK_SMTP_FROM_NAME;
    }
});
