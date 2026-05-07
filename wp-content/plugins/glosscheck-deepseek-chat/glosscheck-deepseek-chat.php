<?php
/**
 * Plugin Name: GlossCheck DeepSeek Chat
 * Description: DeepSeek chat with WordPress DB history, prompt snapshots, limits, and AJAX API.
 * Version: 1.1.0
 * Author: GlossCheck
 */

if (!defined('ABSPATH')) {
    exit;
}

final class GlossCheck_DeepSeek_Chat {
    private const NONCE = 'glosscheck_chat_send';
    private const OPT_PROMPT = 'glosscheck_chat_prompt';
    private const OPT_MODEL = 'glosscheck_chat_model';
    private const OPT_MAX_MESSAGES = 'glosscheck_chat_max_messages';
    private const OPT_MAX_TOKENS = 'glosscheck_chat_max_tokens';
    private const OPT_RATE_LIMIT = 'glosscheck_chat_rate_limit';
    private const META_BLOCKED = 'glosscheck_chat_blocked';
    private const META_RATE_LIMIT = 'glosscheck_chat_rate_limit';
    private const META_MAX_MESSAGES = 'glosscheck_chat_max_messages';
    private const META_MAX_TOKENS = 'glosscheck_chat_max_tokens';

    public static function init(): void {
        add_action('init', [__CLASS__, 'ensure_defaults']);
        add_action('admin_menu', [__CLASS__, 'admin_menu']);
        add_action('admin_init', [__CLASS__, 'settings']);
        add_shortcode('deepseek_chat', [__CLASS__, 'shortcode']);

        foreach (['send', 'list', 'create', 'clear', 'delete', 'current_user', 'register', 'login', 'logout'] as $action) {
            add_action('wp_ajax_glosscheck_chat_' . $action, [__CLASS__, 'ajax_' . $action]);
            add_action('wp_ajax_nopriv_glosscheck_chat_' . $action, [__CLASS__, 'ajax_' . $action]);
        }

        add_action('wp_ajax_glosscheck_deepseek_chat', [__CLASS__, 'ajax_send']);
        add_action('wp_ajax_nopriv_glosscheck_deepseek_chat', [__CLASS__, 'ajax_send']);
    }

    public static function activate(): void {
        self::create_tables();
        self::ensure_defaults();
    }

    public static function ensure_defaults(): void {
        self::create_tables();
        add_option(self::OPT_PROMPT, self::default_prompt());
        add_option(self::OPT_MODEL, 'deepseek-chat');
        add_option(self::OPT_MAX_MESSAGES, 20);
        add_option(self::OPT_MAX_TOKENS, 1200);
        add_option(self::OPT_RATE_LIMIT, 12);
    }

    private static function create_tables(): void {
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $charset = $wpdb->get_charset_collate();
        $chats = self::chats_table();
        $messages = self::messages_table();

        dbDelta("CREATE TABLE {$chats} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            public_id VARCHAR(64) NOT NULL,
            owner_hash CHAR(64) NOT NULL,
            user_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
            owner_label VARCHAR(190) NOT NULL DEFAULT 'Гость',
            title VARCHAR(180) NOT NULL DEFAULT 'Новый диалог',
            prompt LONGTEXT NOT NULL,
            model VARCHAR(80) NOT NULL DEFAULT 'deepseek-chat',
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY public_id (public_id),
            KEY owner_hash (owner_hash),
            KEY updated_at (updated_at)
        ) {$charset};");

        dbDelta("CREATE TABLE {$messages} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            chat_id BIGINT UNSIGNED NOT NULL,
            role VARCHAR(20) NOT NULL,
            content LONGTEXT NOT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            KEY chat_id (chat_id),
            KEY created_at (created_at)
        ) {$charset};");
    }

    public static function settings(): void {
        register_setting('glosscheck_chat_settings', self::OPT_PROMPT, ['sanitize_callback' => 'sanitize_textarea_field']);
        register_setting('glosscheck_chat_settings', self::OPT_MODEL, ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('glosscheck_chat_settings', self::OPT_MAX_MESSAGES, ['sanitize_callback' => 'absint']);
        register_setting('glosscheck_chat_settings', self::OPT_MAX_TOKENS, ['sanitize_callback' => 'absint']);
        register_setting('glosscheck_chat_settings', self::OPT_RATE_LIMIT, ['sanitize_callback' => 'absint']);
    }

    public static function admin_menu(): void {
        add_menu_page('GlossCheck Chat', 'GlossCheck Chat', 'manage_options', 'glosscheck-chat-history', [__CLASS__, 'history_page'], 'dashicons-format-chat', 58);
        add_submenu_page('glosscheck-chat-history', 'История чатов', 'История чатов', 'manage_options', 'glosscheck-chat-history', [__CLASS__, 'history_page']);
        add_submenu_page('glosscheck-chat-history', 'Пользователи чата', 'Пользователи чата', 'manage_options', 'glosscheck-chat-users', [__CLASS__, 'users_page']);
        add_submenu_page('glosscheck-chat-history', 'Настройки чата', 'Настройки чата', 'manage_options', 'glosscheck-chat-settings', [__CLASS__, 'settings_page']);
    }

    public static function history_page(): void {
        if (!current_user_can('manage_options')) {
            return;
        }

        global $wpdb;
        $selected_id = isset($_GET['chat_id']) ? absint($_GET['chat_id']) : 0;
        $chats = $wpdb->get_results(
            "SELECT c.id, c.public_id, c.owner_hash, c.user_id, c.owner_label, c.title, c.model, c.created_at, c.updated_at, u.user_email, u.display_name, COUNT(m.id) AS messages_count
             FROM " . self::chats_table() . " c
             LEFT JOIN " . self::messages_table() . " m ON m.chat_id = c.id
             LEFT JOIN {$wpdb->users} u ON u.ID = c.user_id
             GROUP BY c.id
             ORDER BY c.updated_at DESC
             LIMIT 200",
            ARRAY_A
        );

        if (!$selected_id && !empty($chats)) {
            $selected_id = (int) $chats[0]['id'];
        }

        $selected_chat = null;
        foreach ($chats as $chat) {
            if ((int) $chat['id'] === $selected_id) {
                $selected_chat = $chat;
                break;
            }
        }

        $messages = [];
        if ($selected_chat) {
            $messages = $wpdb->get_results($wpdb->prepare(
                "SELECT role, content, created_at FROM " . self::messages_table() . " WHERE chat_id=%d ORDER BY id ASC",
                $selected_id
            ), ARRAY_A);
        }
        ?>
        <div class="wrap">
            <h1>История чатов</h1>
            <p>Здесь отображаются все диалоги, сохранённые плагином GlossCheck DeepSeek Chat.</p>
            <style>
                .gc-admin-history{display:grid;grid-template-columns:minmax(280px,380px) 1fr;gap:18px;margin-top:18px}
                .gc-admin-panel{background:#fff;border:1px solid #dcdcde;border-radius:8px;overflow:hidden}
                .gc-admin-chat{display:block;padding:12px 14px;border-bottom:1px solid #f0f0f1;text-decoration:none;color:#1d2327}
                .gc-admin-chat:hover,.gc-admin-chat.is-active{background:#f6f7f7}
                .gc-admin-chat strong{display:block;margin-bottom:4px}
                .gc-admin-meta{color:#646970;font-size:12px}
                .gc-admin-messages{padding:16px;max-height:70vh;overflow:auto}
                .gc-admin-message{margin:0 0 14px;padding:12px 14px;border-radius:8px;border:1px solid #dcdcde;background:#f6f7f7}
                .gc-admin-message.user{background:#e7f7ef;border-color:#b7e4cd}
                .gc-admin-message.assistant{background:#f7f7fb}
                .gc-admin-role{font-weight:700;margin-bottom:6px}
                .gc-admin-content{white-space:pre-wrap;line-height:1.55}
                @media(max-width:960px){.gc-admin-history{grid-template-columns:1fr}.gc-admin-messages{max-height:none}}
            </style>
            <div class="gc-admin-history">
                <div class="gc-admin-panel">
                    <?php if (empty($chats)) : ?>
                        <div class="gc-admin-chat"><strong>Диалогов пока нет</strong><span class="gc-admin-meta">Они появятся после первых сообщений на сайте.</span></div>
                    <?php else : ?>
                        <?php foreach ($chats as $chat) : ?>
                            <?php
                            $url = admin_url('admin.php?page=glosscheck-chat-history&chat_id=' . (int) $chat['id']);
                            $active = (int) $chat['id'] === $selected_id ? ' is-active' : '';
                            ?>
                            <a class="gc-admin-chat<?php echo esc_attr($active); ?>" href="<?php echo esc_url($url); ?>">
                                <strong><?php echo esc_html($chat['title']); ?></strong>
                                <span class="gc-admin-meta">
                                    <?php echo esc_html(mysql2date('d.m.Y H:i', $chat['updated_at'])); ?>
                                    · <?php echo esc_html(self::chat_owner_label($chat)); ?>
                                    · <?php echo esc_html((string) (int) $chat['messages_count']); ?> сообщ.
                                </span>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="gc-admin-panel">
                    <div class="gc-admin-messages">
                        <?php if (!$selected_chat) : ?>
                            <h2>Выберите диалог</h2>
                            <p>После выбора здесь появится переписка.</p>
                        <?php else : ?>
                            <h2><?php echo esc_html($selected_chat['title']); ?></h2>
                            <p class="gc-admin-meta">
                                Создан: <?php echo esc_html(mysql2date('d.m.Y H:i', $selected_chat['created_at'])); ?>
                                · Обновлён: <?php echo esc_html(mysql2date('d.m.Y H:i', $selected_chat['updated_at'])); ?>
                                · Пользователь: <?php echo esc_html(self::chat_owner_label($selected_chat)); ?>
                                · Модель: <?php echo esc_html($selected_chat['model']); ?>
                            </p>
                            <?php if (empty($messages)) : ?>
                                <p>В этом диалоге пока нет сообщений.</p>
                            <?php else : ?>
                                <?php foreach ($messages as $message) : ?>
                                    <?php $role = $message['role'] === 'user' ? 'user' : 'assistant'; ?>
                                    <div class="gc-admin-message <?php echo esc_attr($role); ?>">
                                        <div class="gc-admin-role">
                                            <?php echo $role === 'user' ? 'Пользователь' : 'DeepSeek'; ?>
                                            <span class="gc-admin-meta">· <?php echo esc_html(mysql2date('d.m.Y H:i', $message['created_at'])); ?></span>
                                        </div>
                                        <div class="gc-admin-content"><?php echo esc_html($message['content']); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public static function users_page(): void {
        if (!current_user_can('manage_options')) {
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['gc_chat_user_id'])) {
            check_admin_referer('glosscheck_chat_user_limits');
            $user_id = absint($_POST['gc_chat_user_id']);
            if ($user_id > 0 && get_user_by('id', $user_id)) {
                update_user_meta($user_id, self::META_BLOCKED, isset($_POST['gc_chat_blocked']) ? '1' : '0');
                update_user_meta($user_id, self::META_RATE_LIMIT, max(0, min(60, absint($_POST['gc_chat_rate_limit'] ?? 0))));
                update_user_meta($user_id, self::META_MAX_MESSAGES, max(0, min(40, absint($_POST['gc_chat_max_messages'] ?? 0))));
                update_user_meta($user_id, self::META_MAX_TOKENS, max(0, min(4000, absint($_POST['gc_chat_max_tokens'] ?? 0))));
                echo '<div class="notice notice-success is-dismissible"><p>Настройки пользователя сохранены.</p></div>';
            }
        }

        global $wpdb;
        $rows = $wpdb->get_results(
            "SELECT u.ID, u.user_email, u.display_name, u.user_registered,
                    COUNT(DISTINCT c.id) AS chats_count,
                    COUNT(m.id) AS messages_count,
                    MAX(c.updated_at) AS last_chat_at
             FROM {$wpdb->users} u
             LEFT JOIN " . self::chats_table() . " c ON c.user_id = u.ID
             LEFT JOIN " . self::messages_table() . " m ON m.chat_id = c.id
             GROUP BY u.ID
             ORDER BY last_chat_at DESC, u.ID DESC
             LIMIT 200",
            ARRAY_A
        );
        ?>
        <div class="wrap">
            <h1>Пользователи чата</h1>
            <p>Здесь можно ограничить или заблокировать пользователей, чтобы контролировать расход API.</p>
            <table class="widefat striped" style="margin-top:16px">
                <thead>
                    <tr>
                        <th>Пользователь</th>
                        <th>Статистика</th>
                        <th>Блокировка</th>
                        <th>Лимиты</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rows)) : ?>
                        <tr><td colspan="4">Пользователей пока нет.</td></tr>
                    <?php else : ?>
                        <?php foreach ($rows as $row) : ?>
                            <?php
                            $user_id = (int) $row['ID'];
                            $blocked = self::user_blocked($user_id);
                            $rate = (int) get_user_meta($user_id, self::META_RATE_LIMIT, true);
                            $messages = (int) get_user_meta($user_id, self::META_MAX_MESSAGES, true);
                            $tokens = (int) get_user_meta($user_id, self::META_MAX_TOKENS, true);
                            ?>
                            <tr>
                                <td>
                                    <strong><?php echo esc_html($row['display_name'] ?: 'Без имени'); ?></strong><br>
                                    <a href="<?php echo esc_url(get_edit_user_link($user_id)); ?>"><?php echo esc_html($row['user_email']); ?></a><br>
                                    <span class="description">ID: <?php echo esc_html((string) $user_id); ?> · с <?php echo esc_html(mysql2date('d.m.Y', $row['user_registered'])); ?></span>
                                </td>
                                <td>
                                    Диалогов: <?php echo esc_html((string) (int) $row['chats_count']); ?><br>
                                    Сообщений: <?php echo esc_html((string) (int) $row['messages_count']); ?><br>
                                    <span class="description">Последний чат: <?php echo $row['last_chat_at'] ? esc_html(mysql2date('d.m.Y H:i', $row['last_chat_at'])) : 'нет'; ?></span>
                                </td>
                                <td>
                                    <?php if ($blocked) : ?>
                                        <span style="color:#b32d2e;font-weight:700">Заблокирован</span>
                                    <?php else : ?>
                                        <span style="color:#008a20;font-weight:700">Активен</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form method="post">
                                        <?php wp_nonce_field('glosscheck_chat_user_limits'); ?>
                                        <input type="hidden" name="gc_chat_user_id" value="<?php echo esc_attr((string) $user_id); ?>">
                                        <label><input type="checkbox" name="gc_chat_blocked" value="1" <?php checked($blocked); ?>> Заблокировать чат</label><br>
                                        <label>Сообщений/мин: <input type="number" name="gc_chat_rate_limit" min="0" max="60" value="<?php echo esc_attr((string) $rate); ?>" style="width:72px"> <span class="description">0 = общий</span></label><br>
                                        <label>Лимит истории: <input type="number" name="gc_chat_max_messages" min="0" max="40" value="<?php echo esc_attr((string) $messages); ?>" style="width:72px"> <span class="description">0 = общий</span></label><br>
                                        <label>Токены ответа: <input type="number" name="gc_chat_max_tokens" min="0" max="4000" value="<?php echo esc_attr((string) $tokens); ?>" style="width:72px"> <span class="description">0 = общий</span></label><br>
                                        <?php submit_button('Сохранить', 'secondary small', '', false); ?>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    public static function settings_page(): void {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1>Настройки чата</h1>
            <p>Промт сохраняется в новый диалог при его создании. Уже начатые диалоги продолжают работать со своим сохранённым промтом.</p>
            <?php if (!self::api_key()) : ?>
                <div class="notice notice-warning"><p>API-ключ не найден. Добавьте <code>define('GLOSSCHECK_DEEPSEEK_API_KEY', 'ключ');</code> в <code>wp-config.php</code> или переменную окружения Docker.</p></div>
            <?php else : ?>
                <div class="notice notice-success"><p>API-ключ найден в серверной конфигурации.</p></div>
            <?php endif; ?>
            <form method="post" action="options.php">
                <?php settings_fields('glosscheck_chat_settings'); ?>
                <table class="form-table" role="presentation">
                    <tr>
                        <th><label for="<?php echo esc_attr(self::OPT_PROMPT); ?>">Промт для новых диалогов</label></th>
                        <td><textarea id="<?php echo esc_attr(self::OPT_PROMPT); ?>" name="<?php echo esc_attr(self::OPT_PROMPT); ?>" rows="12" class="large-text code"><?php echo esc_textarea(self::prompt()); ?></textarea></td>
                    </tr>
                    <tr>
                        <th><label for="<?php echo esc_attr(self::OPT_MODEL); ?>">Модель</label></th>
                        <td><input id="<?php echo esc_attr(self::OPT_MODEL); ?>" name="<?php echo esc_attr(self::OPT_MODEL); ?>" class="regular-text" value="<?php echo esc_attr(self::model()); ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="<?php echo esc_attr(self::OPT_MAX_MESSAGES); ?>">Лимит истории</label></th>
                        <td><input id="<?php echo esc_attr(self::OPT_MAX_MESSAGES); ?>" name="<?php echo esc_attr(self::OPT_MAX_MESSAGES); ?>" type="number" min="4" max="40" value="<?php echo esc_attr(self::max_messages()); ?>"> <span class="description">Рекомендуется 15–20 сообщений.</span></td>
                    </tr>
                    <tr>
                        <th><label for="<?php echo esc_attr(self::OPT_MAX_TOKENS); ?>">Максимум токенов ответа</label></th>
                        <td><input id="<?php echo esc_attr(self::OPT_MAX_TOKENS); ?>" name="<?php echo esc_attr(self::OPT_MAX_TOKENS); ?>" type="number" min="200" max="4000" value="<?php echo esc_attr(self::max_tokens()); ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="<?php echo esc_attr(self::OPT_RATE_LIMIT); ?>">Rate limit</label></th>
                        <td><input id="<?php echo esc_attr(self::OPT_RATE_LIMIT); ?>" name="<?php echo esc_attr(self::OPT_RATE_LIMIT); ?>" type="number" min="1" max="60" value="<?php echo esc_attr(self::rate_limit()); ?>"> <span class="description">Сообщений в минуту на одного посетителя.</span></td>
                    </tr>
                </table>
                <?php submit_button('Сохранить настройки'); ?>
            </form>
        </div>
        <?php
    }

    public static function shortcode(): string {
        $id = 'gc-shortcode-' . wp_generate_uuid4();
        $cfg = ['ajaxUrl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce(self::NONCE)];
        ob_start();
        ?>
        <div id="<?php echo esc_attr($id); ?>" class="gc-shortcode-chat">
            <div class="gc-shortcode-chat__messages" role="log"></div>
            <form class="gc-shortcode-chat__form">
                <input type="text" placeholder="Напишите сообщение..." autocomplete="off">
                <button type="submit">Отправить</button>
                <button type="button" data-clear>Очистить</button>
            </form>
        </div>
        <style>
            #<?php echo esc_attr($id); ?>{max-width:760px;margin:0 auto;color:#f4f8f2;font-family:Manrope,system-ui,sans-serif}
            #<?php echo esc_attr($id); ?> .gc-shortcode-chat__messages{min-height:320px;padding:18px;display:flex;flex-direction:column;gap:12px;border:1px solid rgba(224,244,229,.14);border-radius:18px;background:rgba(7,13,11,.64);overflow:auto}
            #<?php echo esc_attr($id); ?> .msg{max-width:82%;padding:12px 14px;border-radius:16px;white-space:pre-wrap;line-height:1.5}
            #<?php echo esc_attr($id); ?> .user{align-self:flex-end;background:#4ff2a8;color:#05100b;border-bottom-right-radius:4px}
            #<?php echo esc_attr($id); ?> .assistant{align-self:flex-start;background:rgba(15,24,21,.78);border:1px solid rgba(224,244,229,.14);border-bottom-left-radius:4px}
            #<?php echo esc_attr($id); ?> .gc-shortcode-chat__form{display:grid;grid-template-columns:1fr auto auto;gap:10px;margin-top:12px}
            #<?php echo esc_attr($id); ?> input,#<?php echo esc_attr($id); ?> button{min-height:46px;border-radius:12px;border:1px solid rgba(224,244,229,.14);font:inherit}
            #<?php echo esc_attr($id); ?> input{min-width:0;padding:0 14px;color:#f4f8f2;background:rgba(15,24,21,.78)}
            #<?php echo esc_attr($id); ?> button{padding:0 16px;cursor:pointer;background:#4ff2a8;color:#05100b;font-weight:700}
            @media(max-width:640px){#<?php echo esc_attr($id); ?> .gc-shortcode-chat__form{grid-template-columns:1fr}}
        </style>
        <script>
        (() => {
            const root = document.getElementById(<?php echo wp_json_encode($id); ?>);
            if (!root) return;
            const cfg = <?php echo wp_json_encode($cfg); ?>;
            const box = root.querySelector('.gc-shortcode-chat__messages');
            const form = root.querySelector('form');
            const input = root.querySelector('input');
            const clear = root.querySelector('[data-clear]');
            const owner = 'shortcode:' + (localStorage.getItem('gc_visitor_id') || Math.random().toString(36).slice(2));
            localStorage.setItem('gc_visitor_id', owner.replace('shortcode:', ''));
            let chatId = '';
            let busy = false;
            const add = (role, text) => {
                const el = document.createElement('div');
                el.className = 'msg ' + role;
                el.textContent = text;
                box.appendChild(el);
                box.scrollTop = box.scrollHeight;
                return el;
            };
            const api = async (action, data = {}) => {
                const body = new URLSearchParams({ action, nonce: cfg.nonce, owner, ...data });
                const res = await fetch(cfg.ajaxUrl, { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }, body: body.toString() });
                const json = await res.json();
                if (!res.ok || !json.success) throw new Error(json?.data?.message || 'Ошибка запроса.');
                return json.data;
            };
            form.addEventListener('submit', async e => {
                e.preventDefault();
                const text = input.value.trim();
                if (!text || busy) return;
                busy = true;
                input.value = '';
                add('user', text);
                const pending = add('assistant', 'Анализирую...');
                try {
                    const data = await api('glosscheck_chat_send', { chat_id: chatId, message: text });
                    chatId = data.chat?.id || chatId;
                    pending.textContent = data.answer;
                } catch (err) {
                    pending.textContent = 'Пока не могу ответить через DeepSeek: ' + (err.message || 'ошибка');
                } finally {
                    busy = false;
                    input.focus();
                }
            });
            clear.addEventListener('click', async () => {
                if (chatId) await api('glosscheck_chat_clear', { chat_id: chatId }).catch(() => {});
                box.innerHTML = '';
            });
        })();
        </script>
        <?php
        return (string) ob_get_clean();
    }

    public static function ajax_list(): void {
        self::verify();
        $owner = self::owner_hash();
        global $wpdb;
        $rows = $wpdb->get_results($wpdb->prepare(
            "SELECT id, public_id, title, created_at, updated_at FROM " . self::chats_table() . " WHERE owner_hash=%s ORDER BY updated_at DESC LIMIT 50",
            $owner
        ), ARRAY_A);

        $chats = [];
        foreach ($rows as $row) {
            $messages = self::messages_for((int) $row['id'], self::max_messages());
            $chats[] = self::format_chat($row, $messages);
        }
        wp_send_json_success(['chats' => $chats]);
    }

    public static function ajax_create(): void {
        self::verify();
        $chat = self::create_chat(self::owner_hash());
        wp_send_json_success(['chat' => self::format_chat($chat, [])]);
    }

    public static function ajax_clear(): void {
        self::verify();
        $chat = self::find_chat(self::chat_public_id(), self::owner_hash());
        if ($chat) {
            global $wpdb;
            $wpdb->delete(self::messages_table(), ['chat_id' => (int) $chat['id']], ['%d']);
            $wpdb->update(self::chats_table(), ['title' => 'Новый диалог', 'updated_at' => current_time('mysql')], ['id' => (int) $chat['id']], ['%s', '%s'], ['%d']);
        }
        wp_send_json_success(['ok' => true]);
    }

    public static function ajax_delete(): void {
        self::verify();
        $chat = self::find_chat(self::chat_public_id(), self::owner_hash());
        if ($chat) {
            global $wpdb;
            $wpdb->delete(self::messages_table(), ['chat_id' => (int) $chat['id']], ['%d']);
            $wpdb->delete(self::chats_table(), ['id' => (int) $chat['id']], ['%d']);
        }
        wp_send_json_success(['ok' => true]);
    }

    public static function ajax_current_user(): void {
        self::verify();
        if (!is_user_logged_in()) {
            wp_send_json_success(['user' => null, 'nonce' => wp_create_nonce(self::NONCE)]);
        }

        $user = wp_get_current_user();
        wp_send_json_success(['user' => self::format_user($user), 'nonce' => wp_create_nonce(self::NONCE)]);
    }

    public static function ajax_register(): void {
        self::verify();

        $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
        $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
        $password = isset($_POST['password']) ? (string) wp_unslash($_POST['password']) : '';

        if ($name === '' || !is_email($email) || strlen($password) < 6) {
            wp_send_json_error(['message' => 'Заполните имя, email и пароль минимум 6 символов.'], 400);
        }

        if (email_exists($email)) {
            wp_send_json_error(['message' => 'Такой email уже зарегистрирован. Войдите.'], 409);
        }

        $email_parts = explode('@', $email);
        $base = sanitize_user($email_parts[0] ?? '', true) ?: 'glosscheck';
        $username = $base;
        $i = 2;
        while (username_exists($username)) {
            $username = $base . $i;
            $i++;
        }

        $user_id = wp_create_user($username, $password, $email);
        if (is_wp_error($user_id)) {
            wp_send_json_error(['message' => $user_id->get_error_message()], 500);
        }

        wp_update_user([
            'ID' => $user_id,
            'display_name' => $name,
            'first_name' => $name,
        ]);

        wp_set_current_user((int) $user_id);
        wp_set_auth_cookie((int) $user_id, true, is_ssl());

        wp_send_json_success(['user' => self::format_user(get_user_by('id', $user_id)), 'nonce' => wp_create_nonce(self::NONCE)]);
    }

    public static function ajax_login(): void {
        self::verify();

        $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
        $password = isset($_POST['password']) ? (string) wp_unslash($_POST['password']) : '';

        if (!is_email($email) || $password === '') {
            wp_send_json_error(['message' => 'Введите email и пароль.'], 400);
        }

        $user = get_user_by('email', $email);
        if (!$user) {
            wp_send_json_error(['message' => 'Аккаунт не найден. Зарегистрируйтесь.'], 404);
        }

        $signed = wp_signon([
            'user_login' => $user->user_login,
            'user_password' => $password,
            'remember' => true,
        ], is_ssl());

        if (is_wp_error($signed)) {
            wp_send_json_error(['message' => 'Неверный пароль.'], 403);
        }

        wp_send_json_success(['user' => self::format_user($signed), 'nonce' => wp_create_nonce(self::NONCE)]);
    }

    public static function ajax_logout(): void {
        self::verify();
        wp_logout();
        wp_send_json_success(['ok' => true, 'nonce' => wp_create_nonce(self::NONCE)]);
    }

    public static function ajax_send(): void {
        self::verify();
        self::blocked_guard();
        self::rate_guard();

        $message = isset($_POST['message']) ? sanitize_textarea_field(wp_unslash($_POST['message'])) : '';
        $message = mb_substr(trim($message), 0, 4000);
        if ($message === '') {
            wp_send_json_error(['message' => 'Введите сообщение.'], 400);
        }

        $owner = self::owner_hash();
        $chat = self::find_chat(self::chat_public_id(), $owner);
        if (!$chat) {
            $chat = self::create_chat($owner);
        }

        $messages = self::messages_for((int) $chat['id'], self::max_messages());
        if (count($messages) >= self::max_messages()) {
            wp_send_json_error(['message' => 'Достигнут лимит сообщений. Нажмите «Новый диалог» или очистите текущий чат.'], 429);
        }

        self::insert_message((int) $chat['id'], 'user', $message);
        if ($chat['title'] === 'Новый диалог') {
            self::update_title((int) $chat['id'], $message);
        }

        $api_key = self::api_key();
        if ($api_key === '') {
            $notice = 'DeepSeek API-ключ пока не задан на сервере.';
            self::insert_message((int) $chat['id'], 'assistant', $notice);
            wp_send_json_error(['message' => $notice . ' Добавьте GLOSSCHECK_DEEPSEEK_API_KEY в wp-config.php.'], 500);
        }

        $fresh = self::messages_for((int) $chat['id'], self::max_messages());
        $answer = self::deepseek($api_key, $chat, $fresh);
        if (is_wp_error($answer)) {
            wp_send_json_error(['message' => $answer->get_error_message()], 500);
        }

        self::insert_message((int) $chat['id'], 'assistant', $answer);
        $chat = self::find_chat($chat['public_id'], $owner);
        $messages = self::messages_for((int) $chat['id'], self::max_messages());

        wp_send_json_success([
            'answer' => $answer,
            'chat' => self::format_chat($chat, $messages),
            'limits' => ['maxMessages' => self::max_messages(), 'maxTokens' => self::max_tokens()],
        ]);
    }

    private static function deepseek(string $api_key, array $chat, array $history) {
        $messages = [['role' => 'system', 'content' => (string) $chat['prompt']]];
        foreach ($history as $item) {
            $messages[] = ['role' => $item['role'], 'content' => $item['content']];
        }

        $response = wp_remote_post('https://api.deepseek.com/chat/completions', [
            'timeout' => 45,
            'headers' => ['Authorization' => 'Bearer ' . $api_key, 'Content-Type' => 'application/json'],
            'body' => wp_json_encode([
                'model' => $chat['model'] ?: self::model(),
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => self::max_tokens(),
                'stream' => false,
            ], JSON_UNESCAPED_UNICODE),
        ]);

        if (is_wp_error($response)) {
            return $response;
        }

        $code = (int) wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);
        if ($code < 200 || $code >= 300) {
            return new WP_Error('deepseek_api_error', sanitize_text_field($body['error']['message'] ?? 'DeepSeek API вернул ошибку.'));
        }

        $answer = $body['choices'][0]['message']['content'] ?? '';
        if (!is_string($answer) || trim($answer) === '') {
            return new WP_Error('deepseek_empty_answer', 'DeepSeek вернул пустой ответ.');
        }

        return wp_kses_post(trim($answer));
    }

    private static function create_chat(string $owner_hash): array {
        global $wpdb;
        $now = current_time('mysql');
        $public_id = wp_generate_uuid4();
        $wpdb->insert(self::chats_table(), [
            'public_id' => $public_id,
            'owner_hash' => $owner_hash,
            'user_id' => get_current_user_id(),
            'owner_label' => self::current_owner_label(),
            'title' => 'Новый диалог',
            'prompt' => self::prompt(),
            'model' => self::model(),
            'created_at' => $now,
            'updated_at' => $now,
        ], ['%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s']);
        return self::find_chat($public_id, $owner_hash);
    }

    private static function find_chat(string $public_id, string $owner_hash): ?array {
        if ($public_id === '') {
            return null;
        }
        global $wpdb;
        $row = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM " . self::chats_table() . " WHERE public_id=%s AND owner_hash=%s LIMIT 1",
            $public_id,
            $owner_hash
        ), ARRAY_A);
        return is_array($row) ? $row : null;
    }

    private static function messages_for(int $chat_id, int $limit): array {
        global $wpdb;
        $rows = $wpdb->get_results($wpdb->prepare(
            "SELECT role, content, created_at FROM " . self::messages_table() . " WHERE chat_id=%d ORDER BY id DESC LIMIT %d",
            $chat_id,
            max(1, $limit * 2)
        ), ARRAY_A);
        $rows = array_reverse($rows ?: []);
        return array_map(static fn($r) => ['role' => $r['role'], 'content' => $r['content'], 'time' => mysql2date('H:i', $r['created_at'])], $rows);
    }

    private static function insert_message(int $chat_id, string $role, string $content): void {
        global $wpdb;
        $now = current_time('mysql');
        $wpdb->insert(self::messages_table(), [
            'chat_id' => $chat_id,
            'role' => $role,
            'content' => $content,
            'created_at' => $now,
        ], ['%d', '%s', '%s', '%s']);
        $wpdb->update(self::chats_table(), ['updated_at' => $now], ['id' => $chat_id], ['%s'], ['%d']);
    }

    private static function update_title(int $chat_id, string $message): void {
        global $wpdb;
        $title = mb_substr(wp_strip_all_tags($message), 0, 50);
        $wpdb->update(self::chats_table(), ['title' => $title ?: 'Новый диалог'], ['id' => $chat_id], ['%s'], ['%d']);
    }

    private static function format_chat(array $chat, array $messages): array {
        return [
            'id' => $chat['public_id'],
            'title' => $chat['title'],
            'createdAt' => strtotime($chat['created_at']) * 1000,
            'updatedAt' => strtotime($chat['updated_at']) * 1000,
            'messages' => $messages,
        ];
    }

    private static function verify(): void {
        check_ajax_referer(self::NONCE, 'nonce');
    }

    private static function owner_hash(): string {
        if (is_user_logged_in()) {
            return hash('sha256', 'wp_user:' . get_current_user_id() . '|' . wp_salt('auth'));
        }

        $owner = isset($_POST['owner']) ? sanitize_text_field(wp_unslash($_POST['owner'])) : '';
        if ($owner === '') {
            $owner = 'anon:' . ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
        }
        return hash('sha256', $owner . '|' . wp_salt('auth'));
    }

    private static function chat_public_id(): string {
        return isset($_POST['chat_id']) ? sanitize_text_field(wp_unslash($_POST['chat_id'])) : '';
    }

    private static function rate_guard(): void {
        $limit = self::rate_limit();
        $key = 'gc_rate_' . self::owner_hash();
        $hits = (int) get_transient($key);
        if ($hits >= $limit) {
            wp_send_json_error(['message' => 'Слишком много сообщений за минуту. Попробуйте чуть позже.'], 429);
        }
        set_transient($key, $hits + 1, MINUTE_IN_SECONDS);
    }

    private static function blocked_guard(): void {
        if (is_user_logged_in() && self::user_blocked(get_current_user_id())) {
            wp_send_json_error(['message' => 'Доступ к чату для этого аккаунта ограничен администратором.'], 403);
        }
    }

    private static function user_blocked(int $user_id): bool {
        return get_user_meta($user_id, self::META_BLOCKED, true) === '1';
    }

    private static function current_owner_label(): string {
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            $name = $user->display_name ?: $user->user_login;
            return trim($name . ' <' . $user->user_email . '>');
        }

        return 'Гость';
    }

    private static function chat_owner_label(array $chat): string {
        if (!empty($chat['user_email'])) {
            $name = $chat['display_name'] ?: 'Пользователь';
            return trim($name . ' <' . $chat['user_email'] . '>');
        }

        if (!empty($chat['owner_label'])) {
            return (string) $chat['owner_label'];
        }

        return !empty($chat['user_id']) ? 'Пользователь #' . (int) $chat['user_id'] : 'Гость';
    }

    private static function chats_table(): string {
        global $wpdb;
        return $wpdb->prefix . 'glosscheck_chats';
    }

    private static function messages_table(): string {
        global $wpdb;
        return $wpdb->prefix . 'glosscheck_messages';
    }

    private static function api_key(): string {
        if (defined('GLOSSCHECK_DEEPSEEK_API_KEY') && is_string(GLOSSCHECK_DEEPSEEK_API_KEY)) {
            return trim(GLOSSCHECK_DEEPSEEK_API_KEY);
        }
        $env = getenv('GLOSSCHECK_DEEPSEEK_API_KEY');
        return is_string($env) ? trim($env) : '';
    }

    private static function prompt(): string {
        $value = get_option(self::OPT_PROMPT, self::default_prompt());
        return is_string($value) && trim($value) !== '' ? trim($value) : self::default_prompt();
    }

    private static function model(): string {
        $value = get_option(self::OPT_MODEL, 'deepseek-chat');
        return is_string($value) && trim($value) !== '' ? trim($value) : 'deepseek-chat';
    }

    private static function max_messages(): int {
        if (is_user_logged_in()) {
            $custom = (int) get_user_meta(get_current_user_id(), self::META_MAX_MESSAGES, true);
            if ($custom > 0) {
                return min(40, max(4, $custom));
            }
        }

        return min(40, max(4, (int) get_option(self::OPT_MAX_MESSAGES, 20)));
    }

    private static function max_tokens(): int {
        if (is_user_logged_in()) {
            $custom = (int) get_user_meta(get_current_user_id(), self::META_MAX_TOKENS, true);
            if ($custom > 0) {
                return min(4000, max(200, $custom));
            }
        }

        return min(4000, max(200, (int) get_option(self::OPT_MAX_TOKENS, 1200)));
    }

    private static function rate_limit(): int {
        if (is_user_logged_in()) {
            $custom = (int) get_user_meta(get_current_user_id(), self::META_RATE_LIMIT, true);
            if ($custom > 0) {
                return min(60, max(1, $custom));
            }
        }

        return min(60, max(1, (int) get_option(self::OPT_RATE_LIMIT, 12)));
    }

    private static function default_prompt(): string {
        return 'Ты дружелюбный помощник GlossCheck. Отвечай на русском языке, ясно и без лишней воды. Если информации недостаточно, задай один уточняющий вопрос.';
    }

    private static function format_user($user): ?array {
        if (!$user instanceof WP_User || !$user->exists()) {
            return null;
        }

        return [
            'id' => (int) $user->ID,
            'name' => $user->display_name ?: $user->user_login,
            'email' => $user->user_email,
        ];
    }
}

register_activation_hook(__FILE__, ['GlossCheck_DeepSeek_Chat', 'activate']);
GlossCheck_DeepSeek_Chat::init();
