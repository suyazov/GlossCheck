<?php
$theme_uri = get_template_directory_uri();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo esc_url($theme_uri); ?>/assets/images/favicon.png" type="image/png">
    <link rel="shortcut icon" href="<?php echo esc_url($theme_uri); ?>/assets/images/favicon.png" type="image/png">
    <link rel="preconnect" href="https://cdn.tailwindcss.com">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?php echo esc_url($theme_uri); ?>/assets/css/fa-icons.css">
    <link rel="preload" href="<?php echo esc_url($theme_uri); ?>/assets/fonts/manrope-500.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?php echo esc_url($theme_uri); ?>/assets/fonts/manrope-600.woff2" as="font" type="font/woff2" crossorigin>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Manrope', 'sans-serif'] },
                    colors: {
                        accent: '#00d68f',
                        'accent-dim': '#00a36e',
                        surface: '#0c0c10',
                        card: '#131318',
                        border: '#1e1e26',
                        muted: '#b7c3b3',
                    }
                }
            }
        }
    </script>
    <style>
        @font-face {
            font-family: 'Manrope'; font-style: normal; font-weight: 300; font-display: swap;
            src: url('<?php echo esc_url($theme_uri); ?>/assets/fonts/manrope-300.woff2') format('woff2');
        }
        @font-face {
            font-family: 'Manrope'; font-style: normal; font-weight: 400; font-display: swap;
            src: url('<?php echo esc_url($theme_uri); ?>/assets/fonts/manrope-400.woff2') format('woff2');
        }
        @font-face {
            font-family: 'Manrope'; font-style: normal; font-weight: 500; font-display: swap;
            src: url('<?php echo esc_url($theme_uri); ?>/assets/fonts/manrope-500.woff2') format('woff2');
        }
        @font-face {
            font-family: 'Manrope'; font-style: normal; font-weight: 600; font-display: swap;
            src: url('<?php echo esc_url($theme_uri); ?>/assets/fonts/manrope-600.woff2') format('woff2');
        }
        @font-face {
            font-family: 'Manrope'; font-style: normal; font-weight: 700; font-display: swap;
            src: url('<?php echo esc_url($theme_uri); ?>/assets/fonts/manrope-700.woff2') format('woff2');
        }
        @font-face {
            font-family: 'Manrope'; font-style: normal; font-weight: 800; font-display: swap;
            src: url('<?php echo esc_url($theme_uri); ?>/assets/fonts/manrope-800.woff2') format('woff2');
        }
        @font-face {
            font-family: "Canis Minor";
            src: url('<?php echo esc_url($theme_uri); ?>/assets/fonts/canis-minor.otf') format('opentype');
            font-display: swap;
            font-weight: 400;
            font-style: normal;
        }
        :root {
            --bg: #07100d; --surface: rgba(8, 18, 15, 0.82); --card: rgba(14, 25, 22, 0.84); --border: rgba(224, 244, 229, 0.16);
            --text: #f5f8ef; --muted: #b7c3b3; --accent: #41f0a5; --accent-dim: #23c783;
            --panel-shadow: 0 24px 70px rgba(0, 0, 0, 0.25);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { margin-top: 0!important; }
        body.admin-bar { --wp-admin-bar-offset: 32px; }
        body.admin-bar #wpadminbar { position: fixed; }
        body {
            font-family: 'Manrope', sans-serif;
            background-color: var(--bg);
            background-image: linear-gradient(120deg, rgba(5, 12, 10, 0.2), rgba(5, 12, 10, 0.5)), image-set('<?php echo esc_url($theme_uri); ?>/assets/images/bg_desktop.webp' type('image/webp'), '<?php echo esc_url($theme_uri); ?>/assets/images/bg_desktop.jpg' type('image/jpeg'));
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: var(--text);
            overflow: hidden;
            height: calc(100vh - var(--wp-admin-bar-offset, 0px));
            height: calc(100dvh - var(--wp-admin-bar-offset, 0px));
            padding-top: var(--wp-admin-bar-offset, 0px);
        }
        @media (max-width: 782px) {
            body.admin-bar { --wp-admin-bar-offset: 46px; }
        }
        .brand-wordmark {
            font-family: "Canis Minor", "Cormorant Garamond", Georgia, serif;
            letter-spacing: 0;
            font-weight: 400;
            color: #f8f3dc;
            text-shadow: 0 9px 30px rgba(0,0,0,.28);
        }

        body::after {
            content:''; position:fixed; inset:0; opacity:0.055; pointer-events:none; z-index:0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
        }

        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: var(--muted); }

        .msg-enter { animation: msgSlide 0.45s cubic-bezier(0.16,1,0.3,1) forwards; opacity:0; }
        @keyframes msgSlide { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
        .fade-in { animation: fadeIn 0.4s ease forwards; opacity:0; }
        @keyframes fadeIn { to{opacity:1} }
        .scale-in { animation: scaleIn 0.3s cubic-bezier(0.16,1,0.3,1) forwards; opacity:0; }
        @keyframes scaleIn { from{opacity:0;transform:scale(0.92)} to{opacity:1;transform:scale(1)} }
        .slide-up { animation: slideUp 0.35s cubic-bezier(0.16,1,0.3,1) forwards; opacity:0; }
        @keyframes slideUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }

        .typing-dot { width:7px;height:7px;border-radius:50%;background:var(--muted);animation:dotB 1.4s ease-in-out infinite; }
        .typing-dot:nth-child(2){animation-delay:.2s} .typing-dot:nth-child(3){animation-delay:.4s}
        @keyframes dotB { 0%,60%,100%{transform:translateY(0);opacity:.4} 30%{transform:translateY(-7px);opacity:1} }

        .input-field {
            background: var(--card); border: 1px solid var(--border); border-radius: 12px;
            padding: 11px 14px; color: var(--text); outline: none; width: 100%;
            font-family: inherit; font-size: 13px; transition: border-color 0.25s, box-shadow 0.25s;
        }
        .input-field::placeholder { color: var(--muted); }
        .input-field:focus { border-color: rgba(0,214,143,0.35); box-shadow: 0 0 0 3px rgba(0,214,143,0.07); }
        .input-field.error { border-color: rgba(239,68,68,0.5); box-shadow: 0 0 0 3px rgba(239,68,68,0.07); }

        .btn-primary {
            background: var(--accent); color: #050507; font-weight: 600; border: none; border-radius: 12px;
            padding: 11px 20px; cursor: pointer; font-family: inherit; font-size: 13px; width: 100%;
            transition: background 0.2s, transform 0.15s, opacity 0.2s;
        }
        .btn-primary:hover { background: var(--accent-dim); transform: translateY(-1px); }
        .btn-primary:active { transform: scale(0.98); }
        .btn-primary:disabled { opacity: 0.4; cursor: not-allowed; transform: none; }

        .auth-tab {
            flex: 1; padding: 9px; text-align: center; font-size: 13px; font-weight: 500;
            color: var(--muted); cursor: pointer; border-bottom: 2px solid transparent;
            transition: color 0.25s, border-color 0.25s;
        }
        .auth-tab.active { color: var(--accent); border-color: var(--accent); }
        .auth-tab:hover:not(.active) { color: var(--text); }

        .input-wrap {
            transition: border-color 0.3s, box-shadow 0.3s, background 0.3s;
            box-shadow: 0 18px 55px rgba(0, 0, 0, .28);
        }
        .input-wrap:focus-within { border-color: rgba(65,240,165,0.46)!important; box-shadow: 0 0 0 3px rgba(65,240,165,0.08), 0 20px 70px rgba(0,0,0,.34); background: rgba(11,18,17,.93)!important; }
        .send-btn { transition: background 0.2s, transform 0.15s, opacity 0.2s; }
        .send-btn:not(:disabled):hover { background: var(--accent-dim); transform: scale(1.05); }
        .send-btn:not(:disabled):active { transform: scale(0.95); }
        .send-btn:disabled { opacity: 0.35; cursor: not-allowed; }

        .chip { transition: background 0.2s, border-color 0.2s, color 0.2s, transform 0.15s; cursor: pointer; }
        .chip:hover { background: rgba(65,240,165,0.11); border-color: rgba(65,240,165,0.34); color: var(--accent); transform: translateY(-1px); }
        .chip:active { transform: scale(0.97); }

        .sidebar-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.55); z-index: 40;
            opacity: 0; pointer-events: none; transition: opacity 0.3s; backdrop-filter: blur(2px);
        }
        .sidebar-overlay.open { opacity: 1; pointer-events: auto; }

        .sidebar {
            position: fixed; top: 0; left: 0; bottom: 0; width: 300px; max-width: 85vw;
            background: linear-gradient(180deg, rgba(8,18,15,.94), rgba(7,14,12,.9)); border-right: 1px solid var(--border); backdrop-filter: blur(24px);
            z-index: 50; transform: translateX(-100%); transition: transform 0.35s cubic-bezier(0.16,1,0.3,1);
            display: flex; flex-direction: column;
            box-shadow: 20px 0 70px rgba(0,0,0,.25);
        }
        .sidebar.open { transform: translateX(0); }

        @media (min-width: 768px) {
            .sidebar { position: relative; transform: none; z-index: 10; width: 320px; min-width: 320px; transition: width .28s ease, min-width .28s ease, transform .35s cubic-bezier(0.16,1,0.3,1); }
            .sidebar-overlay { display: none!important; }
            .hamburger-btn { display: none!important; }
            .sidebar-toggle { display: flex!important; width: 44px; height: 44px; min-width: 44px; border-radius: 16px; border: 1px solid rgba(224,244,229,.13); background: rgba(255,255,255,.035); align-items: center; justify-content: center; color: var(--muted); box-shadow: none; }
            .sidebar-toggle:hover { color: var(--accent); border-color: rgba(65,240,165,.28); background: rgba(65,240,165,.08); }
            .sidebar-toggle i { font-size: 13px; transition: transform .22s ease; }
            .sidebar-head .brand-wordmark { font-size: 31px!important; flex: 1; min-width: 0; }
            .sidebar-actions { display: grid; grid-template-columns: 1fr 44px; gap: 10px; align-items: stretch; }
            #app.sidebar-collapsed .sidebar { width: 88px; min-width: 88px; }
            #app.sidebar-collapsed .sidebar .sidebar-label,
            #app.sidebar-collapsed .sidebar .sidebar-meta,
            #app.sidebar-collapsed .sidebar .sidebar-section,
            #app.sidebar-collapsed .sidebar .chat-menu-btn,
            #app.sidebar-collapsed .sidebar .chat-context-menu { display: none!important; }
            #app.sidebar-collapsed .sidebar .sidebar-top { padding: 16px 0; display: flex; flex-direction: column; align-items: center; gap: 12px; }
            #app.sidebar-collapsed .sidebar .sidebar-head { flex-direction: column; justify-content: flex-start; align-items: center; gap: 10px; margin-bottom: 0; }
            #app.sidebar-collapsed .sidebar .sidebar-logo { height: 96px; }
            #app.sidebar-collapsed .sidebar .sidebar-actions { display: flex; flex-direction: column; gap: 12px; align-items: center; }
            #app.sidebar-collapsed .sidebar .sidebar-brand-icon,
            #app.sidebar-collapsed .sidebar .new-chat-btn,
            #app.sidebar-collapsed .sidebar .chat-item,
            #app.sidebar-collapsed .sidebar .sidebar-user-row,
            #app.sidebar-collapsed .sidebar .sidebar-toggle { width: 52px; height: 52px; min-width: 52px; padding: 0; margin: 0 auto; border-radius: 18px; border: 1px solid rgba(65,240,165,.18); background: rgba(65,240,165,.055); box-shadow: inset 0 1px 0 rgba(255,255,255,.04); }
            #app.sidebar-collapsed .sidebar .new-chat-btn,
            #app.sidebar-collapsed .sidebar .chat-item,
            #app.sidebar-collapsed .sidebar .sidebar-user-row,
            #app.sidebar-collapsed .sidebar .sidebar-toggle { justify-content: center; align-items: center; }
            #app.sidebar-collapsed .sidebar .sidebar-brand-icon { width: 56px; height: 56px; min-width: 56px; border-radius: 20px; background: rgba(65,240,165,.09); border-color: rgba(65,240,165,.24); }
            #app.sidebar-collapsed .sidebar .sidebar-toggle { order: 2; color: var(--accent); }
            #app.sidebar-collapsed .sidebar .sidebar-toggle:hover,
            #app.sidebar-collapsed .sidebar .new-chat-btn:hover,
            #app.sidebar-collapsed .sidebar .chat-item:hover { background: rgba(65,240,165,.11); border-color: rgba(65,240,165,.28); }
            #app.sidebar-collapsed .sidebar .new-chat-btn { order: 3; }
            #app.sidebar-collapsed .sidebar .chat-item .flex { justify-content: center; }
            #app.sidebar-collapsed .sidebar .chat-item i,
            #app.sidebar-collapsed .sidebar .new-chat-btn i,
            #app.sidebar-collapsed .sidebar .sidebar-user-row i { margin-top: 0; font-size: 14px; color: var(--accent); }
            #app.sidebar-collapsed .sidebar .chat-item.active { background: rgba(65,240,165,.14); border-color: rgba(65,240,165,.34); }
            #app.sidebar-collapsed .sidebar #chatList { padding: 14px 0; display: flex; flex-direction: column; align-items: center; gap: 8px; }
            #app.sidebar-collapsed .sidebar #sidebarFooter { padding: 16px 0; display: flex; justify-content: center; }
            #app.sidebar-collapsed .sidebar #sidebarFooter > div { width: 52px; }
            #app.sidebar-collapsed .sidebar .sidebar-toggle i { transform: rotate(180deg); }
        }

        .chat-item {
            padding: 12px 14px; border-radius: 16px; cursor: pointer;
            transition: background 0.2s, border-color 0.2s; border: 1px solid transparent;
        }
        .chat-item:hover { background: rgba(255,255,255,0.035); }
        .chat-item.active { background: rgba(65,240,165,0.09); border-color: rgba(65,240,165,0.18); box-shadow: inset 0 1px 0 rgba(255,255,255,.04); }
        .chat-item .del-btn { opacity: 0; transition: opacity 0.2s, color 0.2s; color: var(--muted); cursor: pointer; padding: 2px 5px; border-radius: 4px; }
        .chat-item:hover .del-btn { opacity: 1; }
        .chat-item .del-btn:hover { color: #f87171; }
        .chat-item .chat-menu-btn { opacity: 0; transition: opacity 0.2s, color 0.2s; color: var(--muted); cursor: pointer; padding: 2px 5px; border-radius: 4px; border: none; background: none; }
        .chat-item:hover .chat-menu-btn { opacity: 1; }
        .chat-item .chat-menu-btn:hover { color: var(--text); }
        .chat-item .chat-menu-btn.open { opacity: 1; color: var(--accent); }
        .chat-context-menu { display: none; position: absolute; right: 14px; top: 100%; z-index: 60; min-width: 160px; background: var(--surface); border: 1px solid var(--border); border-radius: 12px; backdrop-filter: blur(18px); box-shadow: 0 12px 40px rgba(0,0,0,.35); overflow: hidden; }
        .chat-context-menu.open { display: block; }
        .chat-context-item { display: flex; align-items: center; gap: 8px; width: 100%; padding: 9px 13px; font-size: 12px; color: var(--muted); background: none; border: none; cursor: pointer; font-family: inherit; transition: background 0.15s, color 0.15s; }
        .chat-context-item:hover { background: rgba(255,255,255,0.04); color: var(--text); }
        .chat-context-item.danger:hover { background: rgba(239,68,68,0.08); color: #f87171; }
        .chat-context-item i { width: 14px; text-align: center; font-size: 11px; }
        .attach-btn { background: none; border: none; color: var(--muted); cursor: pointer; padding: 6px; border-radius: 10px; transition: color 0.2s; }
        .attach-btn:hover { color: var(--accent); }

        .status-dot { animation: sPulse 2.5s ease-in-out infinite; }
        @keyframes sPulse { 0%,100%{box-shadow:0 0 0 0 rgba(0,214,143,0.4)} 50%{box-shadow:0 0 0 4px rgba(0,214,143,0)} }

        .msg-bubble-user { transition: box-shadow 0.2s; }
        .msg-bubble-user:hover { box-shadow: 0 0 20px rgba(0,214,143,0.12); }
        .msg-bubble-ai { transition: border-color 0.2s, box-shadow 0.2s; }
        .msg-bubble-ai:hover { border-color: rgba(0,214,143,0.15); box-shadow: 0 0 20px rgba(0,214,143,0.04); }

        /* Модальное окно авторизации */
        .modal-overlay {
            position: fixed; inset: 0; z-index: 200; display: flex; align-items: center; justify-content: center;
            background: rgba(0,0,0,0.65); backdrop-filter: blur(4px);
            opacity: 0; pointer-events: none; transition: opacity 0.3s;
        }
        .modal-overlay.open { opacity: 1; pointer-events: auto; }
        .modal-overlay.open .modal-box { transform: scale(1); opacity: 1; }
        .modal-box {
            background: var(--surface); border: 1px solid var(--border); border-radius: 20px;
            backdrop-filter: blur(22px);
            width: 100%; max-width: 400px; margin: 0 16px; padding: 28px;
            transform: scale(0.92); opacity: 0; transition: transform 0.35s cubic-bezier(0.16,1,0.3,1), opacity 0.3s;
        }

        .auth-msg {
            border-radius: 10px; padding: 9px 13px; font-size: 12px; display: none; align-items: center; gap: 7px; margin-bottom: 14px;
        }
        .auth-msg.show { display: flex; }
        .auth-msg.err { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2); color: #f87171; }
        .auth-msg.ok { background: rgba(0,214,143,0.08); border: 1px solid rgba(0,214,143,0.2); color: var(--accent); }

        /* Баннер-подсказка для неавторизованных */
        .guest-banner {
            background: rgba(231, 196, 95, 0.11); border-bottom: 1px solid rgba(231,196,95,0.16);
            transition: opacity 0.3s, max-height 0.3s, padding 0.3s;
            overflow: hidden;
        }
        .guest-banner.hidden { opacity: 0; max-height: 0!important; padding-top: 0!important; padding-bottom: 0!important; border-color: transparent; }
        .app-header {
            min-height: 72px;
            background: linear-gradient(180deg, rgba(9,18,15,.72), rgba(9,18,15,.54));
            box-shadow: 0 1px 0 rgba(255,255,255,.04);
        }
        .chat-stage {
            background: radial-gradient(circle at 50% 20%, rgba(65,240,165,.08), transparent 34%);
        }
        .welcome-mark {
            box-shadow: inset 0 1px 0 rgba(255,255,255,.08), 0 20px 60px rgba(0,0,0,.24);
        }
        .composer-shell {
            background: linear-gradient(180deg, rgba(7,16,13,.72), rgba(7,16,13,.9));
        }

        @media (prefers-reduced-motion: reduce) {
            *,*::before,*::after { animation-duration:.01ms!important; animation-iteration-count:1!important; transition-duration:.01ms!important; }
        }
        @media (max-width: 640px) {
            body {
                background-image: linear-gradient(rgba(5, 11, 9, 0.18), rgba(5, 11, 9, 0.42)), image-set('<?php echo esc_url($theme_uri); ?>/assets/images/bg_mobile.webp' type('image/webp'), '<?php echo esc_url($theme_uri); ?>/assets/images/bg_mobile.jpg' type('image/jpeg'));
                background-attachment: scroll;
            }
            .brand-wordmark { font-size: 28px!important; }
        }
    </style>
    <?php wp_head(); ?>
</head>
<body <?php body_class('glosscheck-app'); ?>>
    <?php wp_body_open(); ?>

    <!-- ==================== МОДАЛКА АВТОРИЗАЦИИ ==================== -->
    <div class="modal-overlay" id="authModal">
        <div class="modal-box">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-semibold" id="authModalTitle">Войти</h3>
                    <p class="text-xs text-muted mt-0.5">Сохраняйте чаты между сессиями</p>
                </div>
                <button class="w-8 h-8 rounded-lg border border-border flex items-center justify-center text-muted hover:text-[var(--text)] hover:border-[rgba(255,255,255,0.1)] transition-all cursor-pointer" onclick="closeAuthModal()" aria-label="Закрыть">
                    <i class="fas fa-xmark text-sm"></i>
                </button>
            </div>

            <div class="flex border-b border-border mb-5 -mx-7 px-7">
                <div class="auth-tab active" data-tab="login" onclick="switchTab('login')">Вход</div>
                <div class="auth-tab" data-tab="register" onclick="switchTab('register')">Регистрация</div>
            </div>

            <div class="auth-msg err" id="authErr"><i class="fas fa-circle-exclamation text-[10px]"></i><span id="authErrText"></span></div>
            <div class="auth-msg ok" id="authOk"><i class="fas fa-circle-check text-[10px]"></i><span id="authOkText"></span></div>

            <div id="nameWrap" class="mb-3.5" style="display:none">
                <label class="block text-[11px] text-muted mb-1 font-medium">Имя</label>
                <input type="text" id="regName" class="input-field" placeholder="Как вас зовут?" autocomplete="name">
            </div>
            <div class="mb-3.5">
                <label class="block text-[11px] text-muted mb-1 font-medium">Email</label>
                <input type="email" id="authEmail" class="input-field" placeholder="mail@example.com" autocomplete="email">
            </div>
            <div class="mb-5">
                <label class="block text-[11px] text-muted mb-1 font-medium">Пароль</label>
                <div class="relative">
                    <input type="password" id="authPass" class="input-field pr-10" placeholder="Минимум 6 символов" autocomplete="current-password">
                    <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-muted hover:text-[var(--text)] transition-colors cursor-pointer" onclick="toggleEye()" aria-label="Показать пароль">
                        <i class="fas fa-eye text-xs" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button id="btnLogin" class="btn-primary" onclick="doLogin()"><i class="fas fa-arrow-right-to-bracket mr-1.5 text-[11px]"></i>Войти</button>
            <button id="btnReg" class="btn-primary" onclick="doRegister()" style="display:none"><i class="fas fa-user-plus mr-1.5 text-[11px]"></i>Создать аккаунт</button>
            <div class="flex justify-center mt-7">
                <button type="button" id="resetPassLink" class="text-accent text-xs hover:text-accent-dim transition-colors underline-offset-2 hover:underline" onclick="doResetPassword()" style="background:none;border:none;cursor:pointer;padding:0">Восстановить пароль</button>
            </div>
        </div>
    </div>

    <!-- ==================== ОСНОВНОЙ ИНТЕРФЕЙС ==================== -->
    <div id="app" class="h-full" style="display:none;">
        <div class="flex h-full relative z-10">

            <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

            <!-- САЙДБАР -->
            <aside class="sidebar custom-scroll" id="sidebar">
                <div class="sidebar-top p-5 border-b border-border flex-shrink-0">
                    <div class="sidebar-head flex items-center justify-center gap-3.5 mb-5">
                        <img src="<?php echo esc_url($theme_uri); ?>/assets/images/logo.webp" alt="GlossCheck" class="sidebar-logo h-28 w-auto">
                    </div>
                    <div class="sidebar-actions">
                        <button class="new-chat-btn w-full flex items-center justify-center gap-2.5 px-3.5 py-3 rounded-2xl bg-accent/[0.10] border border-accent/20 text-accent text-[14px] font-semibold transition-all hover:bg-accent/[0.15] cursor-pointer" onclick="createNewChat()" title="Новый диалог">
                            <i class="fas fa-plus text-xs"></i><span class="sidebar-label">Новый диалог</span>
                        </button>
                        <button class="sidebar-toggle hidden transition-all cursor-pointer" onclick="toggleSidebarCollapse()" title="Свернуть сайдбар" aria-label="Свернуть сайдбар" type="button">
                            <i class="fas fa-angles-left"></i>
                        </button>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto custom-scroll p-4 space-y-1.5" id="chatList"></div>

                <!-- Подвал сайдбара -->
                <div class="p-5 border-t border-border flex-shrink-0" id="sidebarFooter"></div>
            </aside>

            <!-- ОСНОВНАЯ ОБЛАСТЬ -->
            <div class="flex-1 flex flex-col min-w-0 h-full">

                <!-- Баннер для гостя -->
                <div class="guest-banner px-4 py-2.5 flex items-center gap-3 flex-shrink-0" id="guestBanner">
                    <i class="fas fa-cloud-arrow-up text-amber-400/70 text-xs flex-shrink-0"></i>
                    <p class="text-xs text-amber-200/70 flex-1">Гостевые чаты сохраняются на этом устройстве</p>
                    <button class="text-xs text-accent font-medium hover:text-accent-dim transition-colors flex-shrink-0 cursor-pointer" onclick="openAuthModal()">Войти</button>
                    <button class="text-muted/70 hover:text-muted transition-colors cursor-pointer ml-1 flex-shrink-0" onclick="dismissBanner()" aria-label="Закрыть">
                        <i class="fas fa-xmark text-xs"></i>
                    </button>
                </div>

                <!-- Шапка -->
                <header class="app-header flex items-center justify-between px-4 sm:px-7 py-3 border-b border-border/50 backdrop-blur-sm flex-shrink-0">
                    <button class="hamburger-btn w-9 h-9 rounded-xl border border-border flex items-center justify-center text-muted hover:text-[var(--text)] hover:border-[rgba(255,255,255,0.1)] transition-all cursor-pointer flex-shrink-0" onclick="openSidebar()" aria-label="Меню">
                        <i class="fas fa-bars text-sm"></i>
                    </button>
                    <div class="sm:hidden flex-1 flex justify-center min-w-0">
                        <img src="<?php echo esc_url($theme_uri); ?>/assets/images/logo.webp" alt="GlossCheck" class="h-16 w-auto">
                    </div>
                    <div class="hidden sm:flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-accent status-dot"></div><span class="text-sm font-semibold text-[var(--text)]">Чат с GlossCheck</span>
                    </div>
                    <button class="w-11 h-11 rounded-2xl border border-border bg-card/70 flex items-center justify-center text-muted hover:text-accent hover:border-accent/30 transition-all cursor-pointer flex-shrink-0" onclick="clearCurrentChat()" title="Очистить диалог" aria-label="Очистить">
                        <i class="fas fa-eraser text-xs"></i>
                    </button>
                </header>

                <!-- Чат -->
                <main class="chat-stage flex-1 overflow-y-auto custom-scroll min-h-0" id="chatArea" role="log" aria-label="Сообщения">
                    <div class="max-w-3xl mx-auto px-4 sm:px-8 py-10 sm:py-14 space-y-5" id="msgContainer"></div>
                </main>

                <!-- Ввод -->
                <footer class="composer-shell border-t border-border/50 px-4 sm:px-6 py-5 backdrop-blur-sm flex-shrink-0">
                    <div class="max-w-3xl mx-auto">
                        <div id="attachBar" class="hidden flex items-center gap-2 mb-2 px-1">
                            <div id="attachPreview" class="flex items-center gap-2"></div>
                        </div>
                        <div class="input-wrap flex items-end gap-2.5 sm:gap-3 bg-card rounded-[22px] border border-border px-4 sm:px-5 py-3 sm:py-3.5">
                            <button id="attachBtn" class="attach-btn flex-shrink-0" type="button" title="Прикрепить файл" aria-label="Прикрепить файл">
                                <i class="fas fa-paperclip text-sm"></i>
                            </button>
                            <textarea id="msgInput" placeholder="Напишите сообщение..." class="flex-1 bg-transparent outline-none text-base sm:text-lg text-[var(--text)] placeholder-muted resize-none overflow-hidden max-h-40" autocomplete="off" aria-label="Сообщение" rows="1"></textarea>
                            <button id="sendBtn" class="send-btn w-12 h-12 rounded-2xl bg-accent text-[#05100b] flex items-center justify-center flex-shrink-0" aria-label="Отправить" disabled>
                                <i class="fas fa-arrow-up text-xs sm:text-sm font-bold"></i>
                            </button>
                            <button id="stopBtn" class="send-btn w-12 h-12 rounded-2xl bg-accent text-[#05100b] items-center justify-center flex-shrink-0" style="display:none" aria-label="Остановить" type="button">
                                <i class="fas fa-stop text-xs sm:text-sm font-bold"></i>
                            </button>
                        </div>
                        <div class="text-center text-[11px] text-muted/75 mt-2 space-y-1">
                            <p>Можно прикрепить фото (JPG, PNG, WebP), .txt или .pdf (до 10 МБ)</p>
                            <p class="hidden sm:block">GlossCheck — информационный сервис. Может допускать ошибки. Не используйте для медицинских решений и всегда проверяйте важную информацию.</p>
                        </div>
                        <input id="fileInput" class="hidden" type="file" accept="image/jpeg,image/png,image/webp,image/gif,.txt,text/plain,.pdf,application/pdf" aria-hidden="true">
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <!-- ТОСТЫ -->
    <div id="toasts" class="fixed bottom-6 right-6 z-[300] space-y-2 pointer-events-none"></div>

    <script>
        /* ===== УТИЛИТЫ ===== */
        const $ = id => document.getElementById(id);
        const getTime = () => new Date().toLocaleTimeString('ru-RU', { hour: '2-digit', minute: '2-digit' });
        const esc = t => { const d = document.createElement('div'); d.textContent = t; return d.innerHTML; };
        const uid = () => Date.now().toString(36) + Math.random().toString(36).slice(2, 8);
        const GC_CHAT = <?php echo wp_json_encode([
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('glosscheck_chat_send'),
            'themeUri' => get_template_directory_uri(),
        ]); ?>;


        let userScrolledUp = false;
        $("chatArea")?.addEventListener("scroll", () => {
            const el = $("chatArea");
            userScrolledUp = el.scrollHeight - el.scrollTop - el.clientHeight > 80;
        });

        function scrollDown() {
            requestAnimationFrame(() => {
                $('chatArea').scrollTo({ top: $('chatArea').scrollHeight, behavior: 'smooth' });
            });
        }

        function toast(msg, type = 'info') {
            const c = { info: 'bg-card border-border text-[var(--text)]', success: 'bg-[rgba(0,214,143,0.1)] border-[rgba(0,214,143,0.2)] text-accent', error: 'bg-[rgba(239,68,68,0.1)] border-[rgba(239,68,68,0.2)] text-[#f87171]' };
            const ic = { info: 'fa-circle-info', success: 'fa-circle-check', error: 'fa-circle-exclamation' };
            const el = document.createElement('div');
            el.className = `pointer-events-auto flex items-center gap-2.5 px-4 py-3 rounded-xl border text-sm scale-in ${c[type]}`;
            el.innerHTML = `<i class="fas ${ic[type]} text-xs"></i>${esc(msg)}`;
            $('toasts').appendChild(el);
            setTimeout(() => { el.style.transition = 'opacity .3s,transform .3s'; el.style.opacity = '0'; el.style.transform = 'translateX(20px)'; setTimeout(() => el.remove(), 300); }, 2800);
        }

        /* ===== ХРАНИЛИЩЕ ===== */
        const getVisitorId = () => {
            let id = localStorage.getItem('gc_visitor_id');
            if (!id) {
                id = uid();
                localStorage.setItem('gc_visitor_id', id);
            }
            return id;
        };

        /* ===== СОСТОЯНИЕ ===== */
        let user = null;          // null = гость, объект = авторизован
        let curChatId = null;
        let history = [];         // текущие сообщения { role, content, time }
        let typing = false;
        let abortCtrl = null;
        let pendingImage = null;
        let pendingFileText = null;
        let pendingFileType = null; // 'txt' | 'pdf'
        let sugVisible = false;
        let serverChats = [];
        let authTab = 'login';
        let bannerDismissed = false;
        let chatMenuOpenId = null;
        let sidebarCollapsed = localStorage.getItem('gc_sidebar_collapsed') === '1';
        const FILE_MAX_SIZE = 100 * 1024 * 1024;
        const FILE_MAX_COUNT = 50;
        const FILE_ALLOWED_EXTENSIONS = ['jpg','jpeg','png','webp','gif','bmp','tif','tiff','heic','pdf','doc','docx','txt','rtf','csv','xls','xlsx','ppt','pptx'];
        function formatMb(bytes) { return (bytes / 1024 / 1024).toFixed(1).replace('.0', ''); }

        async function extractPdfText(file) {
            if (!window.pdfjsLib) {
                await new Promise((resolve, reject) => {
                    const s = document.createElement('script');
                    s.src = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js';
                    s.onload = () => { window.pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js'; resolve(); };
                    s.onerror = reject;
                    document.head.appendChild(s);
                });
            }
            const buf = await file.arrayBuffer();
            const pdf = await window.pdfjsLib.getDocument({ data: buf }).promise;
            let text = '';
            for (let i = 1; i <= pdf.numPages; i++) {
                const page = await pdf.getPage(i);
                const content = await page.getTextContent();
                text += content.items.map(it => it.str).join(' ') + '\n';
            }
            return text.trim();
        }

        function ownerKey() {
            return user ? `wp:${user.id || user.email}` : `guest:${getVisitorId()}`;
        }

        async function chatApi(action, data = {}, signal) {
            const payload = new URLSearchParams();
            payload.append('action', action);
            payload.append('nonce', GC_CHAT.nonce);
            payload.append('owner', ownerKey());
            Object.entries(data).forEach(([key, value]) => payload.append(key, value));

            const res = await fetch(GC_CHAT.ajaxUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                body: payload.toString(),
                signal,
            });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.success) {
                throw new Error(json && json.data && json.data.message ? json.data.message : 'Ошибка WordPress AJAX.');
            }
            return json.data;
        }

        async function authApi(action, data = {}) {
            return chatApi(action, data);
        }

        function upsertChat(chat) {
            const idx = serverChats.findIndex(c => c.id === chat.id);
            if (idx >= 0) serverChats[idx] = chat;
            else serverChats.unshift(chat);
            serverChats.sort((a, b) => (b.updatedAt || 0) - (a.updatedAt || 0));
        }

        async function loadServerChats() {
            const data = await chatApi('glosscheck_chat_list');
            serverChats = data.chats || [];
            sortChats();
            const urlChatId = new URLSearchParams(location.search).get('chat');
            if (urlChatId && serverChats.find(c => c.id === urlChatId)) {
                openChat(urlChatId);
            } else if (serverChats.length > 0) {
                openChat(serverChats[0].id);
            } else {
                await createNewChat();
            }
        }

        /* ===== МОДАЛКА АВТОРИЗАЦИИ ===== */
        function openAuthModal() {
            clearAuthFields();
            $('authModal').classList.add('open');
            closeSidebar();
        }
        function closeAuthModal() {
            $('authModal').classList.remove('open');
            clearAuthFields();
        }
        function clearAuthFields() {
            $('authEmail').value = ''; $('authPass').value = ''; $('regName').value = '';
            $('authErr').classList.remove('show'); $('authOk').classList.remove('show');
            document.querySelectorAll('.input-field').forEach(f => f.classList.remove('error'));
        }
        function switchTab(tab) {
            authTab = tab;
            document.querySelectorAll('.auth-tab').forEach(t => t.classList.toggle('active', t.dataset.tab === tab));
            $('nameWrap').style.display = tab === 'register' ? 'block' : 'none';
            $('btnLogin').style.display = tab === 'login' ? 'block' : 'none';
            $('btnReg').style.display = tab === 'register' ? 'block' : 'none';
            $('resetPassLink').style.display = tab === 'login' ? 'inline' : 'none';
            $('authPass').setAttribute('autocomplete', tab === 'login' ? 'current-password' : 'new-password');
            $('authModalTitle').textContent = tab === 'login' ? 'Войти' : 'Создать аккаунт';
            $('authErr').classList.remove('show'); $('authOk').classList.remove('show');
            document.querySelectorAll('.input-field').forEach(f => f.classList.remove('error'));
        }
        function toggleEye() {
            const p = $('authPass'), i = $('eyeIcon');
            if (p.type === 'password') { p.type = 'text'; i.className = 'fas fa-eye-slash text-xs'; }
            else { p.type = 'password'; i.className = 'fas fa-eye text-xs'; }
        }
        function showErr(t) { $('authErrText').textContent = t; $('authErr').classList.add('show'); $('authOk').classList.remove('show'); }
        function showOk(t) { $('authOkText').textContent = t; $('authOk').classList.add('show'); $('authErr').classList.remove('show'); }

        async function doRegister() {
            const name = $('regName').value.trim(), email = $('authEmail').value.trim().toLowerCase(), pass = $('authPass').value;
            let bad = false;
            document.querySelectorAll('.input-field').forEach(f => f.classList.remove('error'));
            if (!name) { $('regName').classList.add('error'); bad = true; }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { $('authEmail').classList.add('error'); bad = true; }
            if (pass.length < 6) { $('authPass').classList.add('error'); bad = true; }
            if (bad) { showErr('Заполните все поля корректно'); return; }

            $('btnReg').disabled = true;
            showOk('Создаём аккаунт...');
            try {
                const data = await authApi('glosscheck_chat_register', { name, email, password: pass });
                showOk('Аккаунт создан. Загружаем ваши чаты...');
                setTimeout(() => window.location.reload(), 450);
            } catch (err) {
                showErr(err.message || 'Не удалось создать аккаунт.');
            } finally {
                $('btnReg').disabled = false;
            }
        }
        async function doLogin() {
            const email = $('authEmail').value.trim().toLowerCase(), pass = $('authPass').value;
            let bad = false;
            document.querySelectorAll('.input-field').forEach(f => f.classList.remove('error'));
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { $('authEmail').classList.add('error'); bad = true; }
            if (!pass) { $('authPass').classList.add('error'); bad = true; }
            if (bad) { showErr('Введите email и пароль'); return; }

            $('btnLogin').disabled = true;
            try {
                const data = await authApi('glosscheck_chat_login', { email, password: pass });
                showOk('Вход выполнен. Загружаем ваши чаты...');
                setTimeout(() => window.location.reload(), 450);
            } catch (err) {
                showErr(err.message || 'Не удалось войти.');
            } finally {
                $('btnLogin').disabled = false;
            }
        }

        async function doResetPassword() {
            const email = $('authEmail').value.trim().toLowerCase();
            document.querySelectorAll('.input-field').forEach(f => f.classList.remove('error'));
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { $('authEmail').classList.add('error'); showErr('Введите email, привязанный к аккаунту.'); return; }
            $('resetPassLink').disabled = true;
            showOk('Отправляем ссылку восстановления...');
            try {
                const data = await authApi('glosscheck_chat_reset_password', { email });
                if (data.nonce) GC_CHAT.nonce = data.nonce;
                showOk(data.message || 'Проверьте почту: отправили ссылку для восстановления.');
            } catch (err) {
                showErr(err.message || 'Не удалось отправить письмо.');
            } finally {
                $('resetPassLink').disabled = false;
            }
        }

        async function loginAs(u) {
            user = u;
            closeAuthModal();
            refreshUI();
            await loadServerChats().catch(err => toast(err.message, 'error'));
            toast(`Добро пожаловать, ${u.name}!`, 'success');
        }

        async function logout() {
            await authApi('glosscheck_chat_logout').catch(() => null);
            window.location.reload();
        }

        /* Enter в полях авторизации */
        $('authPass').addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); authTab === 'login' ? doLogin() : doRegister(); } });
        $('authEmail').addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); $('authPass').focus(); } });

        /* Закрытие модалки по клику на оверлей */
        $('authModal').addEventListener('click', e => { if (e.target === $('authModal')) closeAuthModal(); });
        /* Закрытие по Escape */
        document.addEventListener('keydown', e => { if (e.key === 'Escape') { if ($('authModal').classList.contains('open')) closeAuthModal(); else closeChatMenu(); } });
        /* Закрытие контекстного меню по клику вне */
        document.addEventListener('click', e => { if (chatMenuOpenId && !e.target.closest('.chat-context-menu') && !e.target.closest('.chat-menu-btn')) closeChatMenu(); });

        /* ===== УПРАВЛЕНИЕ ЧАТАМИ ===== */
        function getActiveChats() {
            return serverChats;
        }
        function saveActiveChats(chats) {
            serverChats = chats;
            sortChats();
        }

        async function createNewChat() {
            try {
                const data = await chatApi('glosscheck_chat_create');
                upsertChat(data.chat);
                openChat(data.chat.id);
                closeSidebar();
            } catch (err) {
                toast(err.message, 'error');
            }
        }

        function openChat(chatId) {
            curChatId = chatId;
            chatMenuOpenId = null;
            const chats = getActiveChats();
            const chat = chats.find(c => c.id === chatId);
            if (!chat) return;
            history = chat.messages.map(m => ({ role: m.role, content: m.content, time: m.time }));
            renderChatList();
            renderMessages(chat.messages);
            closeSidebar();
            $('msgInput').focus();
        }

        async function deleteChat(chatId, e) {
            if (e) e.stopPropagation();
            closeChatMenu();
            try {
                await chatApi('glosscheck_chat_delete', { chat_id: chatId });
                let chats = getActiveChats().filter(c => c.id !== chatId);
                saveActiveChats(chats);
                if (curChatId === chatId) {
                    if (chats.length > 0) openChat(chats[0].id);
                    else await createNewChat();
                } else renderChatList();
                toast('Диалог удалён', 'info');
            } catch (err) {
                toast(err.message, 'error');
            }
        }

        async function clearCurrentChat() {
            if (!curChatId) return;
            try {
                await chatApi('glosscheck_chat_clear', { chat_id: curChatId });
                persistMessages([]);
                history = [];
                renderMessages([]);
                toast('Диалог очищен', 'info');
            } catch (err) {
                toast(err.message, 'error');
            }
        }

        function persistMessages(msgs) {
            const chats = getActiveChats();
            const chat = chats.find(c => c.id === curChatId);
            if (!chat) return;
            chat.messages = msgs;
            chat.updatedAt = Date.now();
            if (msgs.length > 0) {
                const first = msgs.find(m => m.role === 'user');
                if (first && (!chat.title || chat.title === 'Новый диалог')) chat.title = first.content.slice(0, 50) + (first.content.length > 50 ? '...' : '');
            } else chat.title = 'Новый диалог';
            saveActiveChats(chats);
            renderChatList();
        }

        /* ===== РЕНДЕР СПИСКА ЧАТОВ ===== */
        function sortChats() {
            serverChats.sort((a, b) => {
                if (a.pinned && !b.pinned) return -1;
                if (!a.pinned && b.pinned) return 1;
                return (b.updatedAt || 0) - (a.updatedAt || 0);
            });
        }

        function openChatMenu(chatId, e) {
            if (e) e.stopPropagation();
            chatMenuOpenId = chatMenuOpenId === chatId ? null : chatId;
            renderChatList();
        }
        function closeChatMenu() {
            if (chatMenuOpenId) { chatMenuOpenId = null; renderChatList(); }
        }

        async function renameChat(chatId, e) {
            if (e) e.stopPropagation();
            closeChatMenu();
            const chat = serverChats.find(c => c.id === chatId);
            if (!chat) return;
            const newTitle = prompt('Переименовать диалог:', chat.title);
            if (!newTitle || !newTitle.trim() || newTitle.trim() === chat.title) return;
            try {
                await chatApi('glosscheck_chat_rename', { chat_id: chatId, title: newTitle.trim() });
                chat.title = newTitle.trim();
                renderChatList();
                toast('Диалог переименован', 'info');
            } catch (err) {
                toast(err.message, 'error');
            }
        }

        async function togglePinChat(chatId, e) {
            if (e) e.stopPropagation();
            closeChatMenu();
            try {
                const chat = serverChats.find(c => c.id === chatId);
                if (!chat) return;
                const data = await chatApi('glosscheck_chat_pin', { chat_id: chatId, pinned: chat.pinned ? '0' : '1' });
                if (data.chat) { const idx = serverChats.findIndex(c => c.id === chatId); if (idx >= 0) serverChats[idx] = data.chat; }
                sortChats();
                renderChatList();
                toast(chat.pinned ? 'Диалог откреплён' : 'Диалог закреплён', 'info');
            } catch (err) {
                toast(err.message, 'error');
            }
        }

        function shareChat(chatId, e) {
            if (e) e.stopPropagation();
            closeChatMenu();
            const url = new URL(location.href);
            url.searchParams.set('chat', chatId);
            navigator.clipboard.writeText(url.toString()).then(() => toast('Ссылка на диалог скопирована', 'success')).catch(() => toast('Не удалось скопировать', 'error'));
        }

        function renderChatList() {
            const chats = getActiveChats();
            const el = $('chatList');

            if (chats.length === 0) {
                el.innerHTML = `<div class="text-center py-10"><i class="fas fa-comments text-2xl text-muted/55 mb-3"></i><p class="text-[11px] text-muted/65">Пока нет диалогов</p></div>`;
                return;
            }

            const now = new Date(), today = new Date(now.getFullYear(), now.getMonth(), now.getDate()).getTime();
            const yesterday = today - 864e5, weekAgo = today - 7 * 864e5;
            let html = '', lastGrp = '';
            const menuOpen = chatMenuOpenId;

            chats.forEach(chat => {
                let grp;
                if (chat.pinned) { grp = 'Закреплённые'; }
                else { grp = chat.updatedAt >= today ? 'Сегодня' : chat.updatedAt >= yesterday ? 'Вчера' : chat.updatedAt >= weekAgo ? 'На этой неделе' : 'Раньше'; }
                if (grp !== lastGrp) { html += `<div class="sidebar-section px-3 pt-3 pb-1 text-[10px] font-semibold text-muted/80 uppercase tracking-wider">${grp}</div>`; lastGrp = grp; }
                const active = chat.id === curChatId;
                const last = chat.messages.length > 0 ? chat.messages[chat.messages.length - 1].content.slice(0, 32) + (chat.messages[chat.messages.length - 1].content.length > 32 ? '...' : '') : 'Пустой диалог';
                const isOpen = menuOpen === chat.id;
                html += `
                    <div class="chat-item relative ${active ? 'active' : ''}" onclick="openChat('${chat.id}')" title="${esc(chat.title)}">
                        <div class="flex items-start gap-2.5">
                            <i class="fas ${chat.pinned ? 'fa-thumbtack' : 'fa-message'} text-[10px] ${chat.pinned ? 'text-accent' : 'text-muted/80'} mt-1 flex-shrink-0"></i>
                            <div class="sidebar-label flex-1 min-w-0">
                                <p class="text-[13px] font-medium truncate ${active ? 'text-accent' : ''}">${esc(chat.title)}</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <p class="text-[11px] text-muted/70 truncate flex-1">${esc(last)}</p>
                                    <span class="text-[10px] text-muted/55 flex-shrink-0">${chat.messages.length}</span>
                                </div>
                            </div>
                            <button class="chat-menu-btn flex-shrink-0 mt-0.5 ${isOpen ? 'open' : ''}" onclick="openChatMenu('${chat.id}',event)" aria-label="Действия" type="button"><i class="fas fa-ellipsis text-[12px]"></i></button>
                        </div>
                        <div class="chat-context-menu ${isOpen ? 'open' : ''}" onclick="event.stopPropagation()">
                            <button class="chat-context-item" onclick="renameChat('${chat.id}',event)" type="button"><i class="fas fa-pen"></i><span>Переименовать</span></button>
                            <button class="chat-context-item" onclick="togglePinChat('${chat.id}',event)" type="button"><i class="fas fa-thumbtack"></i><span>${chat.pinned ? 'Открепить' : 'Закрепить'}</span></button>
                            <button class="chat-context-item" onclick="shareChat('${chat.id}',event)" type="button"><i class="fas fa-share"></i><span>Поделиться</span></button>
                            <button class="chat-context-item danger" onclick="deleteChat('${chat.id}',event)" type="button"><i class="fas fa-trash-can"></i><span>Удалить</span></button>
                        </div>
                    </div>`;
            });
            el.innerHTML = html;
        }

        /* ===== ПОДВАЛ САЙДБАРА ===== */
        function renderSidebarFooter() {
            const el = $('sidebarFooter');
            if (user) {
                el.innerHTML = `
                    <div class="sidebar-user-row flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-accent/10 flex items-center justify-center flex-shrink-0">
                            <span class="text-accent text-xs font-bold">${esc(user.name[0]).toUpperCase()}</span>
                        </div>
                        <div class="sidebar-label flex-1 min-w-0">
                            <p class="text-[13px] font-medium truncate">${esc(user.name)}</p>
                            <p class="text-[10px] text-muted truncate">${esc(user.email)}</p>
                        </div>
                        <button class="sidebar-label text-muted hover:text-[#f87171] transition-colors p-1.5 rounded-lg hover:bg-[rgba(239,68,68,0.06)] cursor-pointer" onclick="logout()" title="Выйти" aria-label="Выйти">
                            <i class="fas fa-right-from-bracket text-sm"></i>
                        </button>
                    </div>`;
            } else {
                el.innerHTML = `
                    <div class="space-y-2.5">
                        <div class="sidebar-user-row flex items-center gap-2.5 px-1">
                            <div class="w-9 h-9 rounded-full bg-muted/10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user text-muted/65 text-xs"></i>
                            </div>
                            <div class="sidebar-label flex-1 min-w-0">
                                <p class="text-[13px] font-medium text-muted">Гость</p>
                                <p class="text-[10px] text-muted/65">Чаты сохраняются в базе</p>
                            </div>
                        </div>
                        <button onclick="openAuthModal()" class="new-chat-btn w-full flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-xl border border-accent/20 text-accent text-[13px] font-medium transition-all hover:bg-accent/[0.08] cursor-pointer" title="Войти для сохранения">
                            <i class="fas fa-right-to-bracket text-[11px]"></i><span class="sidebar-label">Войти для сохранения</span>
                        </button>
                    </div>`;
            }
        }

        /* ===== БАННЕР ГОСТЯ ===== */
        function dismissBanner() {
            bannerDismissed = true;
            $('guestBanner').classList.add('hidden');
        }
        function updateBanner() {
            if (user || bannerDismissed) $('guestBanner').classList.add('hidden');
            else $('guestBanner').classList.remove('hidden');
        }

        /* ===== РЕНДЕР СООБЩЕНИЙ ===== */
        function renderMessages(msgs) {
            const c = $('msgContainer');
            sugVisible = false;
            if (msgs.length === 0) { c.innerHTML = ''; showWelcome(); return; }
            let html = '';
            msgs.forEach(m => {
                const t = m.time || getTime();
                if (m.role === 'user') {
                    html += `<div class="flex justify-end gap-2.5"><div class="max-w-[82%] sm:max-w-[75%]"><div class="msg-bubble-user bg-accent text-[#050507] rounded-2xl rounded-br-sm px-4 py-3"><p class="text-sm leading-relaxed font-medium">${esc(m.content)}</p></div><p class="text-[11px] text-muted/75 mt-1.5 text-right pr-1">${t}</p></div><div class="w-8 h-8 rounded-full bg-accent/15 flex-shrink-0 flex items-center justify-center mt-auto"><i class="fas fa-user text-accent text-[11px]"></i></div></div>`;
                } else {
                    html += `<div class="flex gap-2.5"><div class="w-8 h-8 rounded-full flex-shrink-0 mt-auto overflow-hidden"><img src="${GC_CHAT.themeUri}/assets/images/chat-icon.webp" alt="AI" class="w-full h-full object-cover"></div><div class="max-w-[82%] sm:max-w-[75%]"><div class="msg-bubble-ai bg-card rounded-2xl rounded-bl-sm px-4 py-3 border border-border/50"><div class="text-sm leading-relaxed text-[var(--text)] msg-md">${renderMd(m.content)}</div></div><div class="flex items-center gap-2 mt-1.5 pl-1"><p class="text-[11px] text-muted/75">${t}</p><button class="copy-answer text-[11px] text-muted/80 hover:text-accent transition-colors" data-copy="${esc(m.content)}" type="button"><i class="fas fa-copy text-[10px] mr-1"></i>Скопировать</button></div></div></div>`;
                }
            });
            c.innerHTML = html;
            bindCopyButtons();
            scrollDown();
        }

        function showWelcome() {
            $('msgContainer').innerHTML = `
                <div class="flex flex-col items-center text-center mt-6 sm:mt-10 fade-in">
                    <div class="welcome-mark w-20 h-20 rounded-[28px] bg-accent/10 border border-accent/20 flex items-center justify-center mb-7"><img src="${GC_CHAT.themeUri}/assets/images/gecko.webp" alt="GlossCheck" class="w-14 h-14 object-contain"></div>
                    <p class="text-xs uppercase tracking-[0.22em] text-accent/80 mb-3">GlossCheck AI</p>
                    <h2 class="text-3xl sm:text-4xl font-bold tracking-tight mb-3 text-[var(--text)]">Проверьте продукт</h2>
                    <p class="text-base text-muted max-w-xl leading-relaxed">Проведите глосс-чек, изучите состав и узнайте об ингредиентах</p>
                </div>
                <div id="suggestions" class="flex flex-wrap gap-2.5 justify-center mt-8 fade-in" style="animation-delay:.15s">
                    <button onclick="useSuggestion('Что ты умеешь?')" class="chip px-5 py-3 rounded-full bg-card/90 border border-border text-sm text-muted flex items-center gap-2.5 shadow-[0_12px_34px_rgba(0,0,0,.16)]"><i class="fas fa-wand-magic-sparkles text-xs text-accent/70"></i>Что ты умеешь?</button>
                    <button onclick="useSuggestion('Расскажи о себе')" class="chip px-5 py-3 rounded-full bg-card/90 border border-border text-sm text-muted flex items-center gap-2.5 shadow-[0_12px_34px_rgba(0,0,0,.16)]"><i class="fas fa-circle-info text-xs text-accent/70"></i>Расскажи о себе</button>
                    <button onclick="useSuggestion('Как это работает?')" class="chip px-5 py-3 rounded-full bg-card/90 border border-border text-sm text-muted flex items-center gap-2.5 shadow-[0_12px_34px_rgba(0,0,0,.16)]"><i class="fas fa-gear text-xs text-accent/70"></i>Как это работает?</button>
                    <button onclick="useSuggestion('Рекомендации')" class="chip px-5 py-3 rounded-full bg-card/90 border border-border text-sm text-muted flex items-center gap-2.5 shadow-[0_12px_34px_rgba(0,0,0,.16)]"><i class="fas fa-circle-check text-xs text-accent/70"></i>Рекомендации</button>
                </div>`;
            sugVisible = true;
        }
        function useSuggestion(t) { $('msgInput').value = t; sendMessage(); }
        function removeSuggestions() {
            if (!sugVisible) return;
            const el = $('suggestions');
            if (el) { el.style.transition = 'opacity .25s,transform .25s'; el.style.opacity = '0'; el.style.transform = 'translateY(-8px)'; setTimeout(() => el.remove(), 250); }
            sugVisible = false;
        }

        /* ===== ОТПРАВКА ===== */
        function addUserMsg(text, imageData, fileText) {
            removeSuggestions();
            const c = $('msgContainer'), t = getTime();
            const w = c.querySelector('.flex.flex-col.items-center');
            if (w) w.remove();
            const imgHtml = imageData ? `<img src="${imageData}" class="max-h-48 rounded-xl mb-2 cursor-pointer" onclick="window.open(this.src,'_blank')">` : '';
            const fileLabel = pendingFileType === 'pdf' ? 'PDF файл' : 'Текстовый файл';
            const fileIcon = pendingFileType === 'pdf' ? 'fa-file-pdf' : 'fa-file-lines';
            const fileHtml = fileText ? `<div class="flex items-center gap-2 mb-2 px-2 py-1.5 rounded-lg bg-[#050507]/20"><i class="fas ${fileIcon} text-xs"></i><span class="text-xs font-medium">${fileLabel}</span><span class="text-[10px] opacity-60">${fileText.length} символов</span></div>` : '';
            c.insertAdjacentHTML('beforeend', `<div class="flex justify-end gap-2.5 msg-enter"><div class="max-w-[82%] sm:max-w-[75%]"><div class="msg-bubble-user bg-accent text-[#050507] rounded-2xl rounded-br-sm px-4 py-3">${imgHtml}${fileHtml}<p class="text-sm leading-relaxed font-medium">${esc(text)}</p></div><p class="text-[11px] text-muted/75 mt-1.5 text-right pr-1">${t}</p></div><div class="w-8 h-8 rounded-full bg-accent/15 flex-shrink-0 flex items-center justify-center mt-auto"><i class="fas fa-user text-accent text-[11px]"></i></div></div>`);
            scrollDown();
            history.push({ role: 'user', content: text, time: t });
        }

        function showTypingIndicator() {
            const id = 'tp-' + Date.now();
            $('msgContainer').insertAdjacentHTML('beforeend', `<div class="flex gap-2.5 msg-enter" id="${id}"><div class="w-8 h-8 rounded-full flex-shrink-0 mt-auto overflow-hidden"><img src="${GC_CHAT.themeUri}/assets/images/chat-icon.webp" alt="AI" class="w-full h-full object-cover"></div><div class="max-w-[82%] sm:max-w-[75%]"><div class="msg-bubble-ai bg-card rounded-2xl rounded-bl-sm px-5 py-4 border border-border/50 flex items-center gap-2"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div><span class="text-xs text-muted ml-1">Анализирую</span></div></div></div>`);
            scrollDown();
            return id;
        }

        async function addAIMsg(text) {
            const id = 'ai-' + Date.now(), t = getTime();
            $('msgContainer').insertAdjacentHTML('beforeend', `<div class="flex gap-2.5 msg-enter" id="${id}"><div class="w-8 h-8 rounded-full flex-shrink-0 mt-auto overflow-hidden"><img src="${GC_CHAT.themeUri}/assets/images/chat-icon.webp" alt="AI" class="w-full h-full object-cover"></div><div class="max-w-[82%] sm:max-w-[75%]"><div class="msg-bubble-ai bg-card rounded-2xl rounded-bl-sm px-4 py-3 border border-border/50"><div class="text-sm leading-relaxed text-[var(--text)] msg-md" id="${id}-t"></div></div><div class="flex items-center gap-2 mt-1.5 pl-1"><p class="text-[11px] text-muted/75">${t}</p><button class="copy-answer text-[11px] text-muted/80 hover:text-accent transition-colors" data-copy="" type="button"><i class="fas fa-copy text-[10px] mr-1"></i>Скопировать</button></div></div></div>`);
            const el = $(id + '-t');
            if (!el) return;
            const len = text.length;
            const step = len > 800 ? 4 : len > 300 ? 3 : 2;
            const delay = len > 800 ? 5 : len > 300 ? 8 : 12;
            userScrolledUp = false;
            for (let i = 0; i < len; i += step) {
                el.innerHTML = renderMd(text.slice(0, Math.min(i + step, len)));
                if (!userScrolledUp && i % (step * 2) === 0) scrollDown();
                await new Promise(r => setTimeout(r, delay));
            }
            el.innerHTML = renderMd(text);
            userScrolledUp = false;
            scrollDown();
            history.push({ role: 'assistant', content: text, time: t });
            const copy = $(id)?.querySelector('.copy-answer');
            if (copy) copy.dataset.copy = text;
            bindCopyButtons();
        }

        async function requestAI(text, signal) {
            const data = { message: text, chat_id: curChatId || '' };
            if (pendingImage) data.image_data = pendingImage;
            if (pendingFileText) data.file_text = pendingFileText;
            return chatApi('glosscheck_chat_send', data, signal);
        }

        function bindCopyButtons() {
            document.querySelectorAll('.copy-answer:not([data-bound])').forEach(btn => {
                btn.dataset.bound = '1';
                btn.addEventListener('click', async () => {
                    const text = btn.dataset.copy || '';
                    if (!text) return;
                    try {
                        await navigator.clipboard.writeText(text);
                        toast('Ответ скопирован', 'success');
                    } catch {
                        toast('Не удалось скопировать ответ', 'error');
                    }
                });
            });
        }

        async function sendMessage() {
            const inp = $('msgInput'), text = inp.value.trim();
            if (!text || typing) return;
            typing = true;
            abortCtrl = new AbortController();
            $('sendBtn').disabled = true;
            inp.value = '';
            inp.style.height = 'auto';
            updateSendBtn();

            let tpId = null;
            try {
                addUserMsg(text, pendingImage, pendingFileText);
                await new Promise(r => setTimeout(r, 250));
                tpId = showTypingIndicator();

                const data = await requestAI(text, abortCtrl.signal);
                const tpEl = $(tpId); if (tpEl) tpEl.remove();
                await addAIMsg(data.answer);
                if (data.chat) {
                    upsertChat(data.chat);
                    curChatId = data.chat.id;
                }
                persistMessages([...history]);
            } catch (err) {
                const tpEl = tpId ? $(tpId) : null; if (tpEl) tpEl.remove();
                if (err.name === 'AbortError') {
                    if (history.length > 0 && history[history.length - 1].role === 'user') {
                        await addAIMsg('⏹ Генерация остановлена.');
                    }
                    persistMessages([...history]);
                } else {
                    const msg = err && err.message ? err.message : 'Не удалось получить ответ.';
                    toast(msg, 'error');
                    await addAIMsg('Пока не могу ответить: ' + msg);
                    persistMessages([...history]);
                }
            }

            typing = false;
            abortCtrl = null;
            pendingImage = null;
            pendingFileText = null;
            pendingFileType = null;
            updateSendBtn();
            updateAttachIndicator();
            inp.focus();
        }

        function stopGeneration() {
            if (abortCtrl) abortCtrl.abort();
        }

        function updateSendBtn() {
            $('sendBtn').disabled = !$('msgInput').value.trim() || typing;
            $('stopBtn').style.display = typing ? 'flex' : 'none';
            $('sendBtn').style.display = typing ? 'none' : 'flex';
        }
        function renderMd(text) {
            return esc(text)
                .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.+?)\*/g, '<em>$1</em>')
                .replace(/`([^`]+)`/g, '<code class="bg-card px-1.5 py-0.5 rounded text-accent text-[13px]">$1</code>')
                .replace(/^### (.+)$/gm, '<h3 class="text-base font-semibold mt-3 mb-1">$1</h3>')
                .replace(/^## (.+)$/gm, '<h3 class="text-base font-semibold mt-3 mb-1">$1</h3>')
                .replace(/^# (.+)$/gm, '<h3 class="text-base font-semibold mt-3 mb-1">$1</h3>')
                .replace(/^[-•] (.+)$/gm, '<div class="flex gap-2 ml-2"><span class="text-accent mt-0.5">•</span><span>$1</span></div>')
                .replace(/^\d+\. (.+)$/gm, '<div class="ml-2">$&</div>')
                .replace(/\n\n/g, '</p><p class="mt-2">')
                .replace(/\n/g, '<br>');
        }
        $('msgInput').addEventListener('input', () => { updateSendBtn(); autoResizeInput(); });
        $('sendBtn').addEventListener('click', sendMessage);
        $('stopBtn').addEventListener('click', stopGeneration);
        $('msgInput').addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); } });
        function autoResizeInput() {
            const ta = $('msgInput');
            ta.style.height = 'auto';
            ta.style.height = Math.min(ta.scrollHeight, 160) + 'px';
        }
        $('attachBtn').addEventListener('click', () => $('fileInput').click());
        $('fileInput').addEventListener('change', async function() {
            const file = this.files[0];
            this.value = '';
            if (!file) return;
            if (file.size > 10 * 1024 * 1024) { toast('Файл слишком большой (макс. 10 МБ)', 'error'); return; }
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = e => {
                    pendingImage = e.target.result;
                    updateAttachIndicator();
                    toast('Фото прикреплено', 'success');
                };
                reader.readAsDataURL(file);
            } else if (file.name.endsWith('.txt') || file.type === 'text/plain') {
                const reader = new FileReader();
                reader.onload = e => {
                    pendingFileText = e.target.result.slice(0, 8000);
                    pendingFileType = 'txt';
                    updateAttachIndicator();
                    toast('Текстовый файл прикреплён', 'success');
                };
                reader.readAsText(file);
            } else if (file.name.endsWith('.pdf') || file.type === 'application/pdf') {
                toast('Извлекаем текст из PDF...', 'success');
                try {
                    const text = await extractPdfText(file);
                    if (!text) { toast('PDF не содержит текста (возможно, скан)', 'error'); return; }
                    pendingFileText = text.slice(0, 8000);
                    pendingFileType = 'pdf';
                    updateAttachIndicator();
                    toast('PDF прикреплён', 'success');
                } catch (err) {
                    toast('Не удалось прочитать PDF', 'error');
                }
            } else {
                toast('Поддерживаются изображения, .txt и .pdf файлы', 'error');
            }
        });
        function updateAttachIndicator() {
            const btn = $('attachBtn');
            const bar = $('attachBar');
            const preview = $('attachPreview');
            if (pendingImage || pendingFileText) {
                btn.style.color = 'var(--accent)';
                btn.title = 'Файл прикреплён';
                bar.classList.remove('hidden');
                bar.classList.add('flex');
                let html = '';
                if (pendingImage) {
                    html += `<div class="relative group"><img src="${pendingImage}" class="h-10 w-10 rounded-lg object-cover"><button onclick="removeAttach('image')" class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-[#050507] text-white flex items-center justify-center text-[10px] opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer" type="button"><i class="fas fa-xmark"></i></button></div>`;
                }
                if (pendingFileText) {
                    const icon = pendingFileType === 'pdf' ? 'fa-file-pdf' : 'fa-file-lines';
                    html += `<div class="relative group"><div class="h-10 w-10 rounded-lg bg-card border border-border flex items-center justify-center"><i class="fas ${icon} text-sm text-accent"></i></div><button onclick="removeAttach('file')" class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-[#050507] text-white flex items-center justify-center text-[10px] opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer" type="button"><i class="fas fa-xmark"></i></button></div>`;
                }
                preview.innerHTML = html;
            } else {
                btn.style.color = '';
                btn.title = 'Прикрепить файл';
                bar.classList.add('hidden');
                bar.classList.remove('flex');
                preview.innerHTML = '';
            }
        }
        function removeAttach(type) {
            if (type === 'image') pendingImage = null;
            if (type === 'file') { pendingFileText = null; pendingFileType = null; }
            updateAttachIndicator();
        }

        /* ===== САЙДБАР ===== */
        function openSidebar() { $('sidebar').classList.add('open'); $('sidebarOverlay').classList.add('open'); }
        function closeSidebar() { $('sidebar').classList.remove('open'); $('sidebarOverlay').classList.remove('open'); }
        function applySidebarCollapse() {
            $('app').classList.toggle('sidebar-collapsed', sidebarCollapsed);
            const btn = document.querySelector('.sidebar-toggle');
            if (btn) {
                btn.title = sidebarCollapsed ? 'Развернуть сайдбар' : 'Свернуть сайдбар';
                btn.setAttribute('aria-label', sidebarCollapsed ? 'Развернуть сайдбар' : 'Свернуть сайдбар');
                btn.setAttribute('aria-expanded', sidebarCollapsed ? 'false' : 'true');
            }
        }
        function toggleSidebarCollapse() {
            if (window.innerWidth < 768) return;
            sidebarCollapsed = !sidebarCollapsed;
            localStorage.setItem('gc_sidebar_collapsed', sidebarCollapsed ? '1' : '0');
            applySidebarCollapse();
        }

        /* ===== ОБНОВЛЕНИЕ ВСЕГО UI ===== */
        function refreshUI() {
            renderChatList();
            renderSidebarFooter();
            updateBanner();
        }

        /* ===== ИНИЦИАЛИЗАЦИЯ ===== */
        (async function init() {
            /* Показать приложение сразу */
            $('app').style.display = 'block';
            applySidebarCollapse();
            refreshUI();
            showWelcome();
            updateSendBtn();

            const startNetwork = async () => {
                try {
                    const data = await authApi('glosscheck_chat_current_user');
                    if (data.nonce) GC_CHAT.nonce = data.nonce;
                    user = data.user || null;
                } catch (err) {
                    user = null;
                }
                refreshUI();
                try {
                    await loadServerChats();
                } catch (err) {
                    toast(err.message || 'Не удалось загрузить диалоги', 'error');
                }
            };

            if ('requestIdleCallback' in window) {
                requestIdleCallback(startNetwork, { timeout: 900 });
            } else {
                setTimeout(startNetwork, 250);
            }

            $('msgInput').focus();
        })();
    </script>
    <?php wp_footer(); ?>
</body>
</html>
