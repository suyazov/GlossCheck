<?php
$theme_uri = get_template_directory_uri();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo esc_url($theme_uri); ?>/assets/images/favicon.svg" type="image/svg+xml">
    <link rel="shortcut icon" href="<?php echo esc_url($theme_uri); ?>/assets/images/favicon.svg" type="image/svg+xml">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
            font-family: "Canis Minor";
            src: url('<?php echo esc_url($theme_uri); ?>/assets/fonts/canis-minor.otf') format('opentype');
            font-display: swap;
            font-weight: 400;
            font-style: normal;
        }
        :root {
            --bg: #07100d; --surface: rgba(8, 18, 15, 0.68); --card: rgba(10, 18, 17, 0.66); --border: rgba(224, 244, 229, 0.12);
            --text: #f5f8ef; --muted: #b7c3b3; --accent: #41f0a5; --accent-dim: #23c783;
            --panel-shadow: 0 24px 70px rgba(0, 0, 0, 0.25);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        button { outline: none; }
        button:focus-visible { outline: 2px solid rgba(65,240,165,.44); outline-offset: 3px; }
        html { margin-top: 0!important; }
        body.admin-bar { --wp-admin-bar-offset: 32px; }
        body.admin-bar #wpadminbar { position: fixed; }
        body {
            font-family: 'Manrope', sans-serif;
            background-color: var(--bg);
            background-image: linear-gradient(120deg, rgba(5, 12, 10, 0.34), rgba(5, 12, 10, 0.46)), url('<?php echo esc_url($theme_uri); ?>/assets/images/bg_desktop.jpg');
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
            content:''; position:fixed; inset:0; opacity:0.045; pointer-events:none; z-index:0;
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
        .attach-btn { width: 42px; height: 42px; border-radius: 16px; color: var(--muted); background: rgba(255,255,255,.035); border: 1px solid rgba(224,244,229,.10); display: flex; align-items: center; justify-content: center; transition: color .2s, background .2s, border-color .2s, transform .15s; }
        .attach-btn:hover { color: var(--accent); background: rgba(65,240,165,.08); border-color: rgba(65,240,165,.24); transform: translateY(-1px); }
        .composer-note { max-width: 680px; margin-left: auto; margin-right: auto; }
        @media (max-width: 640px) {
            .attach-btn { width: 38px; height: 38px; border-radius: 14px; }
        }

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
            background: linear-gradient(180deg, rgba(6,16,13,.72), rgba(5,13,11,.66)); border-right: 1px solid rgba(224,244,229,.10); backdrop-filter: blur(24px);
            z-index: 50; transform: translateX(-100%); transition: transform 0.35s cubic-bezier(0.16,1,0.3,1);
            display: flex; flex-direction: column;
            box-shadow: inset -1px 0 0 rgba(255,255,255,.025);
        }
        .sidebar.open { transform: translateX(0); }
        .sidebar-top { border-color: rgba(224,244,229,.08)!important; }
        #sidebarFooter { border-color: rgba(224,244,229,.08)!important; }

        @media (min-width: 768px) {
            .sidebar { position: relative; transform: none; z-index: 10; width: 320px; min-width: 320px; transition: width .28s ease, min-width .28s ease, transform .35s cubic-bezier(0.16,1,0.3,1); }
            .app-shell { background: linear-gradient(90deg, rgba(4,12,10,.34), rgba(4,12,10,.10) 34%, rgba(4,12,10,.18)); }
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
            #app.sidebar-collapsed .sidebar .chat-item.active i { color: var(--accent); }
            #app.sidebar-collapsed .sidebar #chatList { padding: 14px 0; display: flex; flex-direction: column; align-items: center; gap: 8px; }
            #app.sidebar-collapsed .sidebar #sidebarFooter { padding: 16px 0; display: flex; justify-content: center; }
            #app.sidebar-collapsed .sidebar #sidebarFooter > div { width: 52px; }
            #app.sidebar-collapsed .sidebar .sidebar-toggle i { transform: rotate(180deg); }
        }

        .chat-item {
            position: relative; padding: 12px 14px; border-radius: 16px; cursor: pointer;
            transition: background 0.2s, border-color 0.2s; border: 1px solid transparent;
        }
        .chat-item:hover { background: rgba(255,255,255,0.035); }
        .chat-item.active { background: rgba(65,240,165,0.075); border-color: rgba(65,240,165,0.17); box-shadow: inset 0 1px 0 rgba(255,255,255,.035); }
        .chat-menu-btn { width: 26px; height: 26px; border-radius: 999px; color: var(--muted); opacity: 0; transition: opacity .18s, color .18s, background .18s, border-color .18s; border: 1px solid transparent; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .chat-item:hover .chat-menu-btn,
        .chat-menu-btn.open { opacity: 1; }
        .chat-menu-btn:hover,
        .chat-menu-btn.open { color: var(--text); background: rgba(255,255,255,.06); border-color: rgba(224,244,229,.14); }
        .chat-context-menu { position: absolute; top: 34px; right: 10px; z-index: 70; min-width: 172px; padding: 6px; border-radius: 14px; background: rgba(249,252,247,.97); color: #17211d; border: 1px solid rgba(20,30,26,.12); box-shadow: 0 18px 44px rgba(0,0,0,.28); backdrop-filter: blur(16px); cursor: default; }
        .chat-context-item { width: 100%; display: flex; align-items: center; gap: 10px; padding: 9px 10px; border-radius: 10px; font-size: 13px; line-height: 1.15; color: #17211d; transition: background .16s, color .16s; text-align: left; }
        .chat-context-item:hover { background: rgba(8,18,15,.07); }
        .chat-context-item.danger { color: #dc2626; }
        .chat-context-item.danger:hover { background: rgba(220,38,38,.08); }
        .chat-context-item i { width: 14px; text-align: center; font-size: 12px; }

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
            background: linear-gradient(180deg, rgba(7,17,14,.50), rgba(7,17,14,.36));
            box-shadow: 0 1px 0 rgba(255,255,255,.025);
            border-color: rgba(224,244,229,.08)!important;
        }
        .chat-stage {
            background:
                radial-gradient(circle at 50% 20%, rgba(65,240,165,.055), transparent 34%),
                linear-gradient(180deg, rgba(7,16,13,.08), rgba(7,16,13,.14));
        }
        .welcome-mark {
            box-shadow: inset 0 1px 0 rgba(255,255,255,.08), 0 20px 60px rgba(0,0,0,.24);
        }
        .composer-shell {
            background: linear-gradient(180deg, rgba(7,16,13,.34), rgba(7,16,13,.50));
            border-color: rgba(224,244,229,.08)!important;
            box-shadow: inset 0 1px 0 rgba(255,255,255,.02);
        }
        .composer-shell .input-wrap {
            background: rgba(10, 14, 18, .78)!important;
            border-color: rgba(224,244,229,.10)!important;
        }

        @media (prefers-reduced-motion: reduce) {
            *,*::before,*::after { animation-duration:.01ms!important; animation-iteration-count:1!important; transition-duration:.01ms!important; }
        }
        @media (max-width: 640px) {
            body {
                background-image: linear-gradient(rgba(5, 11, 9, 0.18), rgba(5, 11, 9, 0.42)), url('<?php echo esc_url($theme_uri); ?>/assets/images/bg_mobile.jpg');
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
        </div>
    </div>

    <!-- ==================== ОСНОВНОЙ ИНТЕРФЕЙС ==================== -->
    <div id="app" class="h-full" style="display:none;">
        <div class="app-shell flex h-full relative z-10">

            <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

            <!-- САЙДБАР -->
            <aside class="sidebar custom-scroll" id="sidebar">
                <div class="sidebar-top p-5 border-b border-border flex-shrink-0">
                    <div class="sidebar-head flex items-center gap-3.5 mb-5">
                        <div class="sidebar-brand-icon w-11 h-11 rounded-2xl bg-accent/10 flex items-center justify-center border border-accent/20 shadow-[inset_0_1px_0_rgba(255,255,255,.07)] flex-shrink-0">
                            <i class="fas fa-bolt text-accent text-base"></i>
                        </div>
                        <span class="sidebar-label brand-wordmark text-[34px] leading-none">GlossCheck</span>
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
                    <div class="flex items-center gap-3">
                        <button class="hamburger-btn w-9 h-9 rounded-xl border border-border flex items-center justify-center text-muted hover:text-[var(--text)] hover:border-[rgba(255,255,255,0.1)] transition-all cursor-pointer" onclick="openSidebar()" aria-label="Меню">
                            <i class="fas fa-bars text-sm"></i>
                        </button>
                        <div class="sm:hidden">
                            <h2 class="brand-wordmark text-base font-semibold tracking-tight">GlossCheck</h2>
                            <div class="flex items-center gap-1.5 mt-0.5"><div class="w-1.5 h-1.5 rounded-full bg-accent status-dot"></div><span class="text-[10px] text-muted">GlossCheck AI</span></div>
                        </div>
                        <div class="hidden sm:block">
                            <div class="flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-accent status-dot"></div><span class="text-sm font-semibold text-[var(--text)]">Чат с GlossCheck</span></div>
                        </div>
                    </div>
                    <button class="w-11 h-11 rounded-2xl border border-border bg-card/70 flex items-center justify-center text-muted hover:text-accent hover:border-accent/30 transition-all cursor-pointer" onclick="clearCurrentChat()" title="Очистить диалог" aria-label="Очистить">
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
                        <div class="input-wrap flex items-center gap-2.5 sm:gap-3 bg-card rounded-[22px] border border-border px-4 sm:px-5 py-3 sm:py-3.5">
                            <input type="text" id="msgInput" placeholder="Напишите сообщение..." class="flex-1 bg-transparent outline-none text-base sm:text-lg text-[var(--text)] placeholder-muted" autocomplete="off" aria-label="Сообщение">
                            <button id="attachBtn" class="attach-btn flex-shrink-0" type="button" title="Прикрепить фото" aria-label="Прикрепить фото">
                                <i class="fas fa-paperclip text-sm"></i>
                            </button>
                            <input id="photoInput" class="hidden" type="file" accept="image/jpeg,image/png,image/webp" aria-hidden="true">
                            <button id="sendBtn" class="send-btn w-12 h-12 rounded-2xl bg-accent text-[#05100b] flex items-center justify-center flex-shrink-0" aria-label="Отправить" disabled>
                                <i class="fas fa-arrow-up text-xs sm:text-sm font-bold"></i>
                            </button>
                        </div>
                        <div class="composer-note text-center text-[11px] text-muted/75 mt-2 space-y-1">
                            <p>Сейчас анализируется только текст. Фото: JPG, PNG или WebP до 5 МБ, но распознавание изображений пока не подключено.</p>
                            <p class="hidden sm:block">GlossCheck — информационный сервис. Он может ошибаться. Не используйте его для медицинских решений и всегда проверяйте важную информацию.</p>
                        </div>
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
        ]); ?>;

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

        function formatMb(bytes) {
            return (bytes / 1024 / 1024).toFixed(1).replace('.0', '');
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
        const PHOTO_MAX_SIZE = 5 * 1024 * 1024;
        const PHOTO_ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/webp'];
        let user = null;          // null = гость, объект = авторизован
        let curChatId = null;
        let history = [];         // текущие сообщения { role, content, time }
        let typing = false;
        let sugVisible = false;
        let serverChats = [];
        let authTab = 'login';
        let bannerDismissed = false;
        let chatMenuOpenId = null;
        let sidebarCollapsed = localStorage.getItem('gc_sidebar_collapsed') === '1';

        function ownerKey() {
            return user ? `wp:${user.id || user.email}` : `guest:${getVisitorId()}`;
        }

        async function chatApi(action, data = {}) {
            const payload = new URLSearchParams();
            payload.append('action', action);
            payload.append('nonce', GC_CHAT.nonce);
            payload.append('owner', ownerKey());
            Object.entries(data).forEach(([key, value]) => payload.append(key, value));

            const res = await fetch(GC_CHAT.ajaxUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                body: payload.toString(),
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
            sortChats();
        }

        function sortChats() {
            serverChats.sort((a, b) => {
                if (!!a.pinned !== !!b.pinned) return a.pinned ? -1 : 1;
                return (b.updatedAt || 0) - (a.updatedAt || 0);
            });
        }

        async function loadServerChats() {
            const data = await chatApi('glosscheck_chat_list');
            serverChats = data.chats || [];
            sortChats();
            const requestedChat = new URLSearchParams(window.location.search).get('chat');
            const initialChat = requestedChat && serverChats.some(c => c.id === requestedChat) ? requestedChat : serverChats[0]?.id;
            if (initialChat) openChat(initialChat);
            else await createNewChat();
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
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && $('authModal').classList.contains('open')) closeAuthModal();
            if (e.key === 'Escape') closeChatMenu();
        });
        document.addEventListener('click', closeChatMenu);

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
            chatMenuOpenId = null;
            curChatId = chatId;
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

        async function renameChat(chatId, e) {
            if (e) e.stopPropagation();
            const chat = getActiveChats().find(c => c.id === chatId);
            closeChatMenu();
            if (!chat) return;
            const title = window.prompt('Новое название диалога', chat.title || 'Новый диалог');
            if (title === null) return;
            const cleaned = title.trim();
            if (!cleaned) {
                toast('Название не может быть пустым', 'error');
                return;
            }
            try {
                const data = await chatApi('glosscheck_chat_rename', { chat_id: chatId, title: cleaned });
                upsertChat(data.chat);
                renderChatList();
                toast('Диалог переименован', 'success');
            } catch (err) {
                toast(err.message, 'error');
            }
        }

        async function togglePinChat(chatId, e) {
            if (e) e.stopPropagation();
            const chat = getActiveChats().find(c => c.id === chatId);
            closeChatMenu();
            if (!chat) return;
            try {
                const data = await chatApi('glosscheck_chat_pin', { chat_id: chatId, pinned: chat.pinned ? 0 : 1 });
                upsertChat(data.chat);
                renderChatList();
                toast(data.chat.pinned ? 'Диалог закреплён' : 'Диалог откреплён', 'success');
            } catch (err) {
                toast(err.message, 'error');
            }
        }

        async function shareChat(chatId, e) {
            if (e) e.stopPropagation();
            closeChatMenu();
            const url = new URL(window.location.href);
            url.searchParams.set('chat', chatId);
            try {
                await navigator.clipboard.writeText(url.toString());
                toast('Ссылка на диалог скопирована', 'success');
            } catch {
                toast('Не удалось скопировать ссылку', 'error');
            }
        }

        function openChatMenu(chatId, e) {
            e.stopPropagation();
            chatMenuOpenId = chatMenuOpenId === chatId ? null : chatId;
            renderChatList();
        }

        function closeChatMenu() {
            if (!chatMenuOpenId) return;
            chatMenuOpenId = null;
            renderChatList();
        }

        async function clearCurrentChat() {
            if (!curChatId || history.length === 0) return;
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
            if (msgs.length > 0 && (!chat.title || chat.title === 'Новый диалог')) {
                const first = msgs.find(m => m.role === 'user');
                if (first) chat.title = first.content.slice(0, 50) + (first.content.length > 50 ? '...' : '');
            } else if (msgs.length === 0) chat.title = 'Новый диалог';
            saveActiveChats(chats);
            renderChatList();
        }

        /* ===== РЕНДЕР СПИСКА ЧАТОВ ===== */
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

            chats.forEach(chat => {
                let grp = chat.pinned ? 'Закреплённые' : chat.updatedAt >= today ? 'Сегодня' : chat.updatedAt >= yesterday ? 'Вчера' : chat.updatedAt >= weekAgo ? 'На этой неделе' : 'Раньше';
                if (grp !== lastGrp) { html += `<div class="sidebar-section px-3 pt-3 pb-1 text-[10px] font-semibold text-muted/80 uppercase tracking-wider">${grp}</div>`; lastGrp = grp; }
                const active = chat.id === curChatId;
                const menuOpen = chatMenuOpenId === chat.id;
                const last = chat.messages.length > 0 ? chat.messages[chat.messages.length - 1].content.slice(0, 32) + (chat.messages[chat.messages.length - 1].content.length > 32 ? '...' : '') : 'Пустой диалог';
                html += `
                    <div class="chat-item ${active ? 'active' : ''}" onclick="openChat('${chat.id}')" title="${esc(chat.title)}">
                        <div class="flex items-start gap-2.5">
                            <i class="fas ${chat.pinned ? 'fa-thumbtack text-accent/80' : 'fa-message text-muted/80'} text-[10px] mt-1 flex-shrink-0"></i>
                            <div class="sidebar-label flex-1 min-w-0">
                                <p class="text-[13px] font-medium truncate ${active ? 'text-accent' : ''}">${esc(chat.title)}</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <p class="text-[11px] text-muted/70 truncate flex-1">${esc(last)}</p>
                                    <span class="text-[10px] text-muted/55 flex-shrink-0">${chat.messages.length}</span>
                                </div>
                            </div>
                            <button class="chat-menu-btn ${menuOpen ? 'open' : ''}" onclick="openChatMenu('${chat.id}',event)" aria-label="Действия с чатом" type="button"><i class="fas fa-ellipsis text-[12px]"></i></button>
                        </div>
                        ${menuOpen ? `
                            <div class="chat-context-menu" onclick="event.stopPropagation()">
                                <button class="chat-context-item" onclick="renameChat('${chat.id}',event)" type="button"><i class="fas fa-pen"></i><span>Переименовать</span></button>
                                <button class="chat-context-item" onclick="togglePinChat('${chat.id}',event)" type="button"><i class="fas fa-thumbtack"></i><span>${chat.pinned ? 'Открепить' : 'Закрепить'}</span></button>
                                <button class="chat-context-item" onclick="shareChat('${chat.id}',event)" type="button"><i class="fas fa-share"></i><span>Поделиться</span></button>
                                <button class="chat-context-item danger" onclick="deleteChat('${chat.id}',event)" type="button"><i class="fas fa-trash-can"></i><span>Удалить</span></button>
                            </div>` : ''}
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
                    html += `<div class="flex gap-2.5"><div class="w-8 h-8 rounded-full bg-accent/10 flex-shrink-0 flex items-center justify-center mt-auto"><i class="fas fa-robot text-accent text-[11px]"></i></div><div class="max-w-[82%] sm:max-w-[75%]"><div class="msg-bubble-ai bg-card rounded-2xl rounded-bl-sm px-4 py-3 border border-border/50"><p class="text-sm leading-relaxed text-[var(--text)]">${esc(m.content)}</p></div><div class="flex items-center gap-2 mt-1.5 pl-1"><p class="text-[11px] text-muted/75">${t}</p><button class="copy-answer text-[11px] text-muted/80 hover:text-accent transition-colors" data-copy="${esc(m.content)}" type="button"><i class="fas fa-copy text-[10px] mr-1"></i>Скопировать</button></div></div></div>`;
                }
            });
            c.innerHTML = html;
            bindCopyButtons();
            scrollDown();
        }

        function showWelcome() {
            $('msgContainer').innerHTML = `
                <div class="flex flex-col items-center text-center mt-6 sm:mt-10 fade-in">
                    <div class="welcome-mark w-20 h-20 rounded-[28px] bg-accent/10 border border-accent/20 flex items-center justify-center mb-7"><i class="fas fa-bolt text-accent text-2xl"></i></div>
                    <p class="text-xs uppercase tracking-[0.22em] text-accent/80 mb-3">GlossCheck AI</p>
                    <h2 class="text-3xl sm:text-4xl font-bold tracking-tight mb-3 text-[var(--text)]">Проверьте продукт</h2>
                    <p class="text-base text-muted max-w-xl leading-relaxed">Проведите Gloss проверку, изучите состав и узнайте об ингредиентах</p>
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
        function addUserMsg(text) {
            removeSuggestions();
            const c = $('msgContainer'), t = getTime();
            const w = c.querySelector('.flex.flex-col.items-center');
            if (w) w.remove();
            c.insertAdjacentHTML('beforeend', `<div class="flex justify-end gap-2.5 msg-enter"><div class="max-w-[82%] sm:max-w-[75%]"><div class="msg-bubble-user bg-accent text-[#050507] rounded-2xl rounded-br-sm px-4 py-3"><p class="text-sm leading-relaxed font-medium">${esc(text)}</p></div><p class="text-[11px] text-muted/75 mt-1.5 text-right pr-1">${t}</p></div><div class="w-8 h-8 rounded-full bg-accent/15 flex-shrink-0 flex items-center justify-center mt-auto"><i class="fas fa-user text-accent text-[11px]"></i></div></div>`);
            scrollDown();
            history.push({ role: 'user', content: text, time: t });
        }

        function showTypingIndicator() {
            const id = 'tp-' + Date.now();
            $('msgContainer').insertAdjacentHTML('beforeend', `<div class="flex gap-2.5 msg-enter" id="${id}"><div class="w-8 h-8 rounded-full bg-accent/10 flex-shrink-0 flex items-center justify-center mt-auto"><i class="fas fa-robot text-accent text-[11px]"></i></div><div class="max-w-[82%] sm:max-w-[75%]"><div class="msg-bubble-ai bg-card rounded-2xl rounded-bl-sm px-5 py-4 border border-border/50 flex items-center gap-2"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div><span class="text-xs text-muted ml-1">Анализирую</span></div></div></div>`);
            scrollDown();
            return id;
        }

        async function addAIMsg(text) {
            const id = 'ai-' + Date.now(), t = getTime();
            $('msgContainer').insertAdjacentHTML('beforeend', `<div class="flex gap-2.5 msg-enter" id="${id}"><div class="w-8 h-8 rounded-full bg-accent/10 flex-shrink-0 flex items-center justify-center mt-auto"><i class="fas fa-robot text-accent text-[11px]"></i></div><div class="max-w-[82%] sm:max-w-[75%]"><div class="msg-bubble-ai bg-card rounded-2xl rounded-bl-sm px-4 py-3 border border-border/50"><p class="text-sm leading-relaxed text-[var(--text)]" id="${id}-t"></p></div><div class="flex items-center gap-2 mt-1.5 pl-1"><p class="text-[11px] text-muted/75">${t}</p><button class="copy-answer text-[11px] text-muted/80 hover:text-accent transition-colors" data-copy="" type="button"><i class="fas fa-copy text-[10px] mr-1"></i>Скопировать</button></div></div></div>`);
            const el = $(id + '-t');
            if (!el) return;
            for (let i = 0; i < text.length; i++) {
                el.textContent += text[i];
                if (i % 3 === 0) scrollDown();
                await new Promise(r => setTimeout(r, 10 + Math.random() * 15));
            }
            scrollDown();
            history.push({ role: 'assistant', content: text, time: t });
            const copy = $(id)?.querySelector('.copy-answer');
            if (copy) copy.dataset.copy = text;
            bindCopyButtons();
        }

        async function requestAI(text) {
            return chatApi('glosscheck_chat_send', { message: text, chat_id: curChatId || '' });
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
            $('sendBtn').disabled = true;
            inp.value = '';

            let tpId = null;
            try {
                addUserMsg(text);
                await new Promise(r => setTimeout(r, 250));
                tpId = showTypingIndicator();

                const data = await requestAI(text);
                const tpEl = $(tpId); if (tpEl) tpEl.remove();
                await addAIMsg(data.answer);
                if (data.chat) {
                    upsertChat(data.chat);
                    curChatId = data.chat.id;
                }
                persistMessages([...history]);
            } catch (err) {
                const tpEl = tpId ? $(tpId) : null; if (tpEl) tpEl.remove();
                const msg = err && err.message ? err.message : 'Не удалось получить ответ DeepSeek.';
                toast(msg, 'error');
                await addAIMsg('Пока не могу ответить через DeepSeek: ' + msg);
                persistMessages([...history]);
            }

            typing = false;
            updateSendBtn();
            inp.focus();
        }

        function updateSendBtn() { $('sendBtn').disabled = !$('msgInput').value.trim() || typing; }
        $('msgInput').addEventListener('input', updateSendBtn);
        $('sendBtn').addEventListener('click', sendMessage);
        $('attachBtn').addEventListener('click', () => {
            $('photoInput').click();
        });
        $('photoInput').addEventListener('change', e => {
            const file = e.target.files && e.target.files[0] ? e.target.files[0] : null;
            e.target.value = '';
            if (!file) return;

            if (!PHOTO_ALLOWED_TYPES.includes(file.type)) {
                toast('Поддерживаются только фото JPG, PNG или WebP.', 'error');
                return;
            }
            if (file.size > PHOTO_MAX_SIZE) {
                toast(`Фото слишком большое: ${formatMb(file.size)} МБ. Максимум — 5 МБ.`, 'error');
                return;
            }

            toast('Фото выбрано, но сейчас анализируется только текст. Опишите продукт сообщением.', 'info');
        });
        $('msgInput').addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); } });

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
            try {
                const data = await authApi('glosscheck_chat_current_user');
                if (data.nonce) GC_CHAT.nonce = data.nonce;
                user = data.user || null;
            } catch (err) {
                user = null;
            }

            /* Показать приложение */
            $('app').style.display = 'block';
            applySidebarCollapse();

            refreshUI();

            try {
                await loadServerChats();
            } catch (err) {
                toast(err.message || 'Не удалось загрузить диалоги', 'error');
            }

            $('msgInput').focus();
        })();
    </script>
    <?php wp_footer(); ?>
</body>
</html>
