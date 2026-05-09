<?php
/**
 * GlossCheck — Front Page Template
 */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class('glosscheck-app'); ?>>
    <?php wp_body_open(); ?>

    <!-- Auth Modal -->
    <div class="modal-overlay" id="authModal">
        <div class="modal-box">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-semibold" id="authModalTitle">Войти</h3>
                    <p class="text-xs text-muted mt-0.5">Сохраняйте чаты между сессиями</p>
                </div>
                <button class="w-8 h-8 rounded-lg border border-border flex items-center justify-center text-muted hover:text-[var(--text)] hover:border-[rgba(255,255,255,0.1)] transition-all cursor-pointer" onclick="GCApp.closeAuthModal()" aria-label="Закрыть">
                    <span class="gc-icon text-sm"><?php echo gc_svg('xmark'); ?></span>
                </button>
            </div>

            <div class="flex border-b border-border mb-5 -mx-7 px-7">
                <div class="auth-tab active" data-tab="login" onclick="GCApp.switchTab('login')">Вход</div>
                <div class="auth-tab" data-tab="register" onclick="GCApp.switchTab('register')">Регистрация</div>
            </div>

            <div class="auth-msg err" id="authErr">
                <span class="gc-icon text-[10px]"><?php echo gc_svg('circle-exclamation'); ?></span>
                <span id="authErrText"></span>
            </div>
            <div class="auth-msg ok" id="authOk">
                <span class="gc-icon text-[10px]"><?php echo gc_svg('circle-check'); ?></span>
                <span id="authOkText"></span>
            </div>

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
                    <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-muted hover:text-[var(--text)] transition-colors cursor-pointer" onclick="GCApp.toggleEye()" aria-label="Показать пароль">
                        <span class="gc-icon text-xs" id="eyeIcon"><?php echo gc_svg('eye'); ?></span>
                    </button>
                </div>
            </div>

            <button id="btnLogin" class="btn-primary" onclick="GCApp.doLogin()">
                <span class="gc-icon text-[11px] mr-1.5 inline-block align-middle"><?php echo gc_svg('arrow-right-to-bracket'); ?></span>Войти
            </button>
            <button id="btnReg" class="btn-primary" onclick="GCApp.doRegister()" style="display:none">
                <span class="gc-icon text-[11px] mr-1.5 inline-block align-middle"><?php echo gc_svg('user-plus'); ?></span>Создать аккаунт
            </button>
            <div class="flex justify-center mt-7">
                <button type="button" id="resetPassLink" class="text-accent text-xs hover:text-accent-dim transition-colors underline-offset-2 hover:underline" onclick="GCApp.doResetPassword()" style="background:none;border:none;cursor:pointer;padding:0">Восстановить пароль</button>
            </div>
        </div>
    </div>

    <!-- Main Interface -->
    <div id="app" class="h-full" style="display:none;">
        <div class="app-shell flex h-full relative z-10">

            <div class="sidebar-overlay" id="sidebarOverlay" onclick="GCApp.closeSidebar()"></div>

            <!-- Sidebar -->
            <aside class="sidebar custom-scroll" id="sidebar">
                <div class="sidebar-top p-5 border-b border-border flex-shrink-0">
                    <div class="sidebar-head flex items-center gap-3.5 mb-5">
                        <div class="sidebar-brand-icon w-11 h-11 rounded-2xl bg-accent/10 flex items-center justify-center border border-accent/20 shadow-[inset_0_1px_0_rgba(255,255,255,.07)] flex-shrink-0">
                            <span class="gc-icon text-accent text-base"><?php echo gc_svg('bolt'); ?></span>
                        </div>
                        <span class="sidebar-label brand-wordmark text-[34px] leading-none">GlossCheck</span>
                    </div>
                    <div class="sidebar-actions">
                        <button class="new-chat-btn w-full flex items-center justify-center gap-2.5 px-3.5 py-3 rounded-2xl bg-accent/[0.10] border border-accent/20 text-accent text-[14px] font-semibold transition-all hover:bg-accent/[0.15] cursor-pointer" onclick="GCApp.createNewChat()" title="Новый диалог">
                            <span class="gc-icon text-xs"><?php echo gc_svg('plus'); ?></span><span class="sidebar-label">Новый диалог</span>
                        </button>
                        <button class="sidebar-toggle hidden transition-all cursor-pointer" onclick="GCApp.toggleSidebarCollapse()" title="Свернуть сайдбар" aria-label="Свернуть сайдбар" type="button">
                            <span class="gc-icon" style="font-size:13px"><?php echo gc_svg('angles-left'); ?></span>
                        </button>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto custom-scroll p-4 space-y-1.5" id="chatList"></div>

                <div class="p-5 border-t border-border flex-shrink-0" id="sidebarFooter"></div>
            </aside>

            <!-- Main Area -->
            <div class="flex-1 flex flex-col min-w-0 h-full">

                <!-- Guest Banner -->
                <div class="guest-banner px-4 py-2.5 flex items-center gap-3 flex-shrink-0" id="guestBanner">
                    <span class="gc-icon text-amber-400/70 text-xs flex-shrink-0"><?php echo gc_svg('cloud-arrow-up'); ?></span>
                    <p class="text-xs text-amber-200/70 flex-1">Гостевые чаты сохраняются на этом устройстве</p>
                    <button class="text-xs text-accent font-medium hover:text-accent-dim transition-colors flex-shrink-0 cursor-pointer" onclick="GCApp.openAuthModal()">Войти</button>
                    <button class="text-muted/70 hover:text-muted transition-colors cursor-pointer ml-1 flex-shrink-0" onclick="GCApp.dismissBanner()" aria-label="Закрыть">
                        <span class="gc-icon text-xs"><?php echo gc_svg('xmark'); ?></span>
                    </button>
                </div>

                <!-- Header -->
                <header class="app-header flex items-center justify-between px-4 sm:px-7 py-3 border-b border-border/50 backdrop-blur-sm flex-shrink-0">
                    <div class="flex items-center gap-3">
                        <button class="hamburger-btn w-9 h-9 rounded-xl border border-border flex items-center justify-center text-muted hover:text-[var(--text)] hover:border-[rgba(255,255,255,0.1)] transition-all cursor-pointer" onclick="GCApp.openSidebar()" aria-label="Меню">
                            <span class="gc-icon text-sm"><?php echo gc_svg('bars'); ?></span>
                        </button>
                        <div class="sm:hidden">
                            <h2 class="brand-wordmark text-base font-semibold tracking-tight">GlossCheck</h2>
                            <div class="flex items-center gap-1.5 mt-0.5"><div class="w-1.5 h-1.5 rounded-full bg-accent status-dot"></div><span class="text-[10px] text-muted">GlossCheck AI</span></div>
                        </div>
                        <div class="hidden sm:block">
                            <div class="flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-accent status-dot"></div><span class="text-sm font-semibold text-[var(--text)]">Чат с GlossCheck</span></div>
                        </div>
                    </div>
                    <button class="w-11 h-11 rounded-2xl border border-border bg-card/70 flex items-center justify-center text-muted hover:text-accent hover:border-accent/30 transition-all cursor-pointer" onclick="GCApp.clearCurrentChat()" title="Очистить диалог" aria-label="Очистить">
                        <span class="gc-icon text-xs"><?php echo gc_svg('eraser'); ?></span>
                    </button>
                </header>

                <!-- Chat -->
                <main class="chat-stage flex-1 overflow-y-auto custom-scroll min-h-0" id="chatArea" role="log" aria-label="Сообщения">
                    <div class="max-w-3xl mx-auto px-4 sm:px-8 py-10 sm:py-14 space-y-5" id="msgContainer"></div>
                </main>

                <!-- Composer -->
                <footer class="composer-shell border-t border-border/50 px-4 sm:px-6 py-5 backdrop-blur-sm flex-shrink-0">
                    <div class="max-w-3xl mx-auto">
                        <div class="input-wrap flex items-center gap-2.5 sm:gap-3 bg-card rounded-[22px] border border-border px-4 sm:px-5 py-3 sm:py-3.5">
                            <input type="text" id="msgInput" placeholder="Напишите сообщение..." class="flex-1 bg-transparent outline-none text-base sm:text-lg text-[var(--text)] placeholder-muted" autocomplete="off" aria-label="Сообщение">
                            <button id="attachBtn" class="attach-btn flex-shrink-0" type="button" title="Прикрепить файл" aria-label="Прикрепить файл">
                                <span class="gc-icon text-sm"><?php echo gc_svg('paperclip'); ?></span>
                            </button>
                            <input id="fileInput" class="hidden" type="file" multiple accept="image/*,.pdf,.doc,.docx,.txt,.rtf,.csv,.xls,.xlsx,.ppt,.pptx" aria-hidden="true">
                            <button id="sendBtn" class="send-btn w-12 h-12 rounded-2xl bg-accent text-[#05100b] flex items-center justify-center flex-shrink-0" aria-label="Отправить" disabled>
                                <span class="gc-icon text-xs sm:text-sm"><?php echo gc_svg('arrow-up'); ?></span>
                            </button>
                        </div>
                        <div class="composer-note text-center text-[11px] text-muted/75 mt-2 space-y-1">
                            <p>В этом режиме извлекается только текст. Можно выбрать документы или изображения: максимум 50 файлов, до 100 МБ каждый.</p>
                            <p class="hidden sm:block">GlossCheck — информационный сервис. Он может ошибаться. Не используйте его для медицинских решений и всегда проверяйте важную информацию.</p>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <!-- Toasts -->
    <div id="toasts" class="fixed bottom-6 right-6 z-[300] space-y-2 pointer-events-none"></div>

    <script>
        var GC_CHAT = <?php echo wp_json_encode([
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('glosscheck_chat_send'),
        ]); ?>;
    </script>
    <?php wp_footer(); ?>
</body>
</html>
