/**
 * GlossCheck — Chat Application
 */
(function () {
    'use strict';

    const $ = (id) => document.getElementById(id);
    const getTime = () => new Date().toLocaleTimeString('ru-RU', { hour: '2-digit', minute: '2-digit' });
    const esc = (t) => { const d = document.createElement('div'); d.textContent = t; return d.innerHTML; };
    const uid = () => Date.now().toString(36) + Math.random().toString(36).slice(2, 8);

    const ic = (name, cls) => `<i class="fas fa-${name}${cls ? ' ' + cls : ''}"></i>`;

    function scrollDown() {
        requestAnimationFrame(() => {
            $('chatArea').scrollTo({ top: $('chatArea').scrollHeight, behavior: 'smooth' });
        });
    }

    /* ===== Toast ===== */
    function toast(msg, type = 'info') {
        const c = { info: 'bg-card border-border text-[var(--text)]', success: 'bg-[rgba(0,214,143,0.1)] border-[rgba(0,214,143,0.2)] text-accent', error: 'bg-[rgba(239,68,68,0.1)] border-[rgba(239,68,68,0.2)] text-[#f87171]' };
        const icons = { info: 'circle-info', success: 'circle-check', error: 'circle-exclamation' };
        const el = document.createElement('div');
        el.className = `pointer-events-auto flex items-center gap-2.5 px-4 py-3 rounded-xl border text-sm scale-in ${c[type]}`;
        el.innerHTML = `${ic(icons[type], 'text-xs')}${esc(msg)}`;
        $('toasts').appendChild(el);
        setTimeout(() => { el.style.transition = 'opacity .3s,transform .3s'; el.style.opacity = '0'; el.style.transform = 'translateX(20px)'; setTimeout(() => el.remove(), 300); }, 2800);
    }

    function formatMb(bytes) {
        return (bytes / 1024 / 1024).toFixed(1).replace('.0', '');
    }

    /* ===== State ===== */
    const FILE_MAX_SIZE = 100 * 1024 * 1024;
    const FILE_MAX_COUNT = 50;
    const FILE_ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp', 'tif', 'tiff', 'heic', 'pdf', 'doc', 'docx', 'txt', 'rtf', 'csv', 'xls', 'xlsx', 'ppt', 'pptx'];
    let user = null;
    let curChatId = null;
    let history = [];
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

    const getVisitorId = () => {
        let id = localStorage.getItem('gc_visitor_id');
        if (!id) { id = uid(); localStorage.setItem('gc_visitor_id', id); }
        return id;
    };

    /* ===== API ===== */
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

    /* ===== Chat management ===== */
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
        else {
            curChatId = null;
            history = [];
            renderChatList();
            showWelcome();
        }
    }

    /* ===== Auth modal ===== */
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
        $('resetPassLink').style.display = tab === 'login' ? 'block' : 'none';
        $('btnReg').style.display = tab === 'register' ? 'block' : 'none';
        $('authModalTitle').textContent = tab === 'login' ? 'Войти' : 'Создать аккаунт';
        $('authPass').setAttribute('autocomplete', tab === 'login' ? 'current-password' : 'new-password');
        $('authErr').classList.remove('show'); $('authOk').classList.remove('show');
        document.querySelectorAll('.input-field').forEach(f => f.classList.remove('error'));
    }
    function toggleEye() {
        const p = $('authPass'), iconEl = $('eyeIcon');
        if (p.type === 'password') { p.type = 'text'; iconEl.className = 'fas fa-eye-slash text-xs'; }
        else { p.type = 'password'; iconEl.className = 'fas fa-eye text-xs'; }
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
            await authApi('glosscheck_chat_register', { name, email, password: pass });
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
            await authApi('glosscheck_chat_login', { email, password: pass });
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
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            $('authEmail').classList.add('error');
            showErr('Введите email, к которому привязан аккаунт.');
            return;
        }

        $('resetPassLink').disabled = true;
        showOk('Отправляем ссылку для восстановления...');
        try {
            const data = await authApi('glosscheck_chat_reset_password', { email });
            if (data.nonce) GC_CHAT.nonce = data.nonce;
            showOk(data.message || 'Проверьте почту: мы отправили ссылку для восстановления.');
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

    /* ===== Chat CRUD ===== */
    function getActiveChats() { return serverChats; }
    function saveActiveChats(chats) { serverChats = chats; sortChats(); }

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
        if (!cleaned) { toast('Название не может быть пустым', 'error'); return; }
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

    /* ===== Render chat list ===== */
    function renderChatList() {
        const chats = getActiveChats();
        const el = $('chatList');

        if (chats.length === 0) {
            el.innerHTML = `<div class="text-center py-10">${ic('comments', 'text-2xl text-muted/55 mb-3')}<p class="text-[11px] text-muted/65">Пока нет диалогов</p></div>`;
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
            const chatIcon = chat.pinned ? 'thumbtack' : 'message';
            const chatIconCls = chat.pinned ? 'text-[10px] mt-1 flex-shrink-0 text-accent/80' : 'text-[10px] mt-1 flex-shrink-0 text-muted/80';
            html += `
                <div class="chat-item ${active ? 'active' : ''}" onclick="window.GCApp.openChat('${chat.id}')" title="${esc(chat.title)}">
                    <div class="flex items-start gap-2.5">
                        ${ic(chatIcon, chatIconCls)}
                        <div class="sidebar-label flex-1 min-w-0">
                            <p class="text-[13px] font-medium truncate ${active ? 'text-accent' : ''}">${esc(chat.title)}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <p class="text-[11px] text-muted/70 truncate flex-1">${esc(last)}</p>
                                <span class="text-[10px] text-muted/55 flex-shrink-0">${chat.messages.length}</span>
                            </div>
                        </div>
                        <button class="chat-menu-btn ${menuOpen ? 'open' : ''}" onclick="window.GCApp.openChatMenu('${chat.id}',event)" aria-label="Действия с чатом" type="button">${ic('ellipsis', 'text-[12px]')}</button>
                    </div>
                    ${menuOpen ? `
                        <div class="chat-context-menu" onclick="event.stopPropagation()">
                            <button class="chat-context-item" onclick="window.GCApp.renameChat('${chat.id}',event)" type="button">${ic('pen', '')}<span>Переименовать</span></button>
                            <button class="chat-context-item" onclick="window.GCApp.togglePinChat('${chat.id}',event)" type="button">${ic('thumbtack', '')}<span>${chat.pinned ? 'Открепить' : 'Закрепить'}</span></button>
                            <button class="chat-context-item" onclick="window.GCApp.shareChat('${chat.id}',event)" type="button">${ic('share', '')}<span>Поделиться</span></button>
                            <button class="chat-context-item danger" onclick="window.GCApp.deleteChat('${chat.id}',event)" type="button">${ic('trash-can', '')}<span>Удалить</span></button>
                        </div>` : ''}
                </div>`;
        });
        el.innerHTML = html;
    }

    /* ===== Sidebar footer ===== */
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
                    <button class="sidebar-label text-muted hover:text-[#f87171] transition-colors p-1.5 rounded-lg hover:bg-[rgba(239,68,68,0.06)] cursor-pointer" onclick="window.GCApp.logout()" title="Выйти" aria-label="Выйти">
                        ${ic('right-from-bracket', 'text-sm')}
                    </button>
                </div>`;
        } else {
            el.innerHTML = `
                <div class="space-y-2.5">
                    <div class="sidebar-user-row flex items-center gap-2.5 px-1">
                        <div class="w-9 h-9 rounded-full bg-muted/10 flex items-center justify-center flex-shrink-0">
                            ${ic('user', 'text-muted/65 text-xs')}
                        </div>
                        <div class="sidebar-label flex-1 min-w-0">
                            <p class="text-[13px] font-medium text-muted">Гость</p>
                            <p class="text-[10px] text-muted/65">Чаты сохраняются в базе</p>
                        </div>
                    </div>
                    <button onclick="window.GCApp.openAuthModal()" class="new-chat-btn w-full flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-xl border border-accent/20 text-accent text-[13px] font-medium transition-all hover:bg-accent/[0.08] cursor-pointer" title="Войти для сохранения">
                        ${ic('arrow-right-to-bracket', 'text-[11px]')}<span class="sidebar-label">Войти для сохранения</span>
                    </button>
                </div>`;
        }
    }

    function dismissBanner() {
        bannerDismissed = true;
        $('guestBanner').classList.add('hidden');
    }
    function updateBanner() {
        if (user || bannerDismissed) $('guestBanner').classList.add('hidden');
        else $('guestBanner').classList.remove('hidden');
    }

    /* ===== Render messages ===== */
    function renderMessages(msgs) {
        const c = $('msgContainer');
        sugVisible = false;
        if (msgs.length === 0) { c.innerHTML = ''; showWelcome(); return; }
        let html = '';
        msgs.forEach(m => {
            const t = m.time || getTime();
            if (m.role === 'user') {
                html += `<div class="flex justify-end gap-2.5"><div class="max-w-[82%] sm:max-w-[75%]"><div class="msg-bubble-user bg-accent text-[#050507] rounded-2xl rounded-br-sm px-4 py-3"><p class="text-sm leading-relaxed font-medium">${esc(m.content)}</p></div><p class="text-[11px] text-muted/75 mt-1.5 text-right pr-1">${t}</p></div><div class="w-8 h-8 rounded-full bg-accent/15 flex-shrink-0 flex items-center justify-center mt-auto">${ic('user', 'text-accent text-[11px]')}</div></div>`;
            } else {
                html += `<div class="flex gap-2.5"><div class="w-8 h-8 rounded-full bg-accent/10 flex-shrink-0 flex items-center justify-center mt-auto">${ic('robot', 'text-accent text-[11px]')}</div><div class="max-w-[82%] sm:max-w-[75%]"><div class="msg-bubble-ai bg-card rounded-2xl rounded-bl-sm px-4 py-3 border border-border/50"><p class="text-sm leading-relaxed text-[var(--text)]">${esc(m.content)}</p></div><div class="flex items-center gap-2 mt-1.5 pl-1"><p class="text-[11px] text-muted/75">${t}</p><button class="copy-answer text-[11px] text-muted/80 hover:text-accent transition-colors" data-copy="${esc(m.content)}" type="button">${ic('copy', 'text-[10px] mr-1')}Скопировать</button></div></div></div>`;
            }
        });
        c.innerHTML = html;
        bindCopyButtons();
        scrollDown();
    }

    function showWelcome() {
        $('msgContainer').innerHTML = `
            <div class="flex flex-col items-center text-center mt-6 sm:mt-10 fade-in">
                <div class="welcome-mark w-20 h-20 rounded-[28px] bg-accent/10 border border-accent/20 flex items-center justify-center mb-7">${ic('bolt', 'text-accent text-2xl')}</div>
                <p class="text-xs uppercase tracking-[0.22em] text-accent/80 mb-3">GlossCheck AI</p>
                <h2 class="text-3xl sm:text-4xl font-bold tracking-tight mb-3 text-[var(--text)]">Проверьте продукт</h2>
                <p class="text-base text-muted max-w-xl leading-relaxed">Проведите Gloss проверку, изучите состав и узнайте об ингредиентах</p>
            </div>
            <div id="suggestions" class="flex flex-wrap gap-2.5 justify-center mt-8 fade-in" style="animation-delay:.15s">
                <button onclick="window.GCApp.useSuggestion('Что ты умеешь?')" class="chip px-5 py-3 rounded-full bg-card/90 border border-border text-sm text-muted flex items-center gap-2.5 shadow-[0_12px_34px_rgba(0,0,0,.16)]">${ic('wand-magic-sparkles', 'text-xs text-accent/70')}Что ты умеешь?</button>
                <button onclick="window.GCApp.useSuggestion('Расскажи о себе')" class="chip px-5 py-3 rounded-full bg-card/90 border border-border text-sm text-muted flex items-center gap-2.5 shadow-[0_12px_34px_rgba(0,0,0,.16)]">${ic('circle-info', 'text-xs text-accent/70')}Расскажи о себе</button>
                <button onclick="window.GCApp.useSuggestion('Как это работает?')" class="chip px-5 py-3 rounded-full bg-card/90 border border-border text-sm text-muted flex items-center gap-2.5 shadow-[0_12px_34px_rgba(0,0,0,.16)]">${ic('gear', 'text-xs text-accent/70')}Как это работает?</button>
                <button onclick="window.GCApp.useSuggestion('Рекомендации')" class="chip px-5 py-3 rounded-full bg-card/90 border border-border text-sm text-muted flex items-center gap-2.5 shadow-[0_12px_34px_rgba(0,0,0,.16)]">${ic('circle-check', 'text-xs text-accent/70')}Рекомендации</button>
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

    /* ===== Sending ===== */
    function addUserMsg(text) {
        removeSuggestions();
        const c = $('msgContainer'), t = getTime();
        const w = c.querySelector('.flex.flex-col.items-center');
        if (w) w.remove();
        c.insertAdjacentHTML('beforeend', `<div class="flex justify-end gap-2.5 msg-enter"><div class="max-w-[82%] sm:max-w-[75%]"><div class="msg-bubble-user bg-accent text-[#050507] rounded-2xl rounded-br-sm px-4 py-3"><p class="text-sm leading-relaxed font-medium">${esc(text)}</p></div><p class="text-[11px] text-muted/75 mt-1.5 text-right pr-1">${t}</p></div><div class="w-8 h-8 rounded-full bg-accent/15 flex-shrink-0 flex items-center justify-center mt-auto">${ic('user', 'text-accent text-[11px]')}</div></div>`);
        scrollDown();
        history.push({ role: 'user', content: text, time: t });
    }

    function showTypingIndicator() {
        const id = 'tp-' + Date.now();
        $('msgContainer').insertAdjacentHTML('beforeend', `<div class="flex gap-2.5 msg-enter" id="${id}"><div class="w-8 h-8 rounded-full bg-accent/10 flex-shrink-0 flex items-center justify-center mt-auto">${ic('robot', 'text-accent text-[11px]')}</div><div class="max-w-[82%] sm:max-w-[75%]"><div class="msg-bubble-ai bg-card rounded-2xl rounded-bl-sm px-5 py-4 border border-border/50 flex items-center gap-2"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div><span class="text-xs text-muted ml-1">Анализирую</span></div></div></div>`);
        scrollDown();
        return id;
    }

    async function addAIMsg(text) {
        const id = 'ai-' + Date.now(), t = getTime();
        $('msgContainer').insertAdjacentHTML('beforeend', `<div class="flex gap-2.5 msg-enter" id="${id}"><div class="w-8 h-8 rounded-full bg-accent/10 flex-shrink-0 flex items-center justify-center mt-auto">${ic('robot', 'text-accent text-[11px]')}</div><div class="max-w-[82%] sm:max-w-[75%]"><div class="msg-bubble-ai bg-card rounded-2xl rounded-bl-sm px-4 py-3 border border-border/50"><p class="text-sm leading-relaxed text-[var(--text)]" id="${id}-t"></p></div><div class="flex items-center gap-2 mt-1.5 pl-1"><p class="text-[11px] text-muted/75">${t}</p><button class="copy-answer text-[11px] text-muted/80 hover:text-accent transition-colors" data-copy="" type="button">${ic('copy', 'text-[10px] mr-1')}Скопировать</button></div></div></div>`);
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

    /* ===== Sidebar ===== */
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

    function refreshUI() {
        renderChatList();
        renderSidebarFooter();
        updateBanner();
    }

    /* ===== Event listeners ===== */
    $('authPass').addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); authTab === 'login' ? doLogin() : doRegister(); } });
    $('authEmail').addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); $('authPass').focus(); } });
    $('authModal').addEventListener('click', e => { if (e.target === $('authModal')) closeAuthModal(); });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && $('authModal').classList.contains('open')) closeAuthModal();
        if (e.key === 'Escape') closeChatMenu();
    });
    document.addEventListener('click', closeChatMenu);

    $('msgInput').addEventListener('input', updateSendBtn);
    $('sendBtn').addEventListener('click', sendMessage);
    $('attachBtn').addEventListener('click', () => { $('fileInput').click(); });
    $('fileInput').addEventListener('change', e => {
        const files = Array.from(e.target.files || []);
        e.target.value = '';
        if (files.length === 0) return;

        if (files.length > FILE_MAX_COUNT) {
            toast(`Можно прикрепить максимум ${FILE_MAX_COUNT} файлов за раз.`, 'error');
            return;
        }
        const unsupported = files.find(file => {
            const ext = (file.name.split('.').pop() || '').toLowerCase();
            return !FILE_ALLOWED_EXTENSIONS.includes(ext);
        });
        if (unsupported) {
            toast('Поддерживаются документы и изображения: JPG, PNG, WebP, PDF, DOCX, TXT, XLSX и похожие форматы.', 'error');
            return;
        }
        const oversized = files.find(file => file.size > FILE_MAX_SIZE);
        if (oversized) {
            toast(`Файл ${oversized.name} слишком большой: ${formatMb(oversized.size)} МБ. Максимум — 100 МБ каждый.`, 'error');
            return;
        }

        toast('Файлы выбраны, но сейчас в чат отправляется только текст. Скопируйте или опишите нужные данные сообщением.', 'info');
    });
    $('msgInput').addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); } });

    /* ===== Init ===== */
    (async function init() {
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

    window.GCApp = {
        openAuthModal,
        closeAuthModal,
        switchTab,
        toggleEye,
        doLogin,
        doRegister,
        doResetPassword,
        logout,
        createNewChat,
        openChat,
        deleteChat,
        renameChat,
        togglePinChat,
        shareChat,
        openChatMenu,
        clearCurrentChat,
        useSuggestion,
        dismissBanner,
        openSidebar,
        closeSidebar,
        toggleSidebarCollapse,
    };
})();
