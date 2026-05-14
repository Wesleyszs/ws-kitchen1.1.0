/**
 * WS Kitchen — script.js  (VERSÃO CORRIGIDA)
 * Sem duplicatas. Funciona para logados E anônimos.
 * Realtime polling a cada 5s após enviar pedido.
 */

/* ─── estado global ─────────────────────────────────── */
let cart         = [];
let dadosLogin   = { logado: false };
let mesaSelecionada = null;
let pedidoAtual  = null;   // { id, mesa }
let pollingTimer = null;

/* ─── refs DOM (setadas no DOMContentLoaded) ────────── */
let cartItemsEl, cartTotalEl, cartCountEl, cartFab, cartPanel, closeCart;
let modalOverlay, modalTitulo, modalSubtitulo, campoNome, inputNome, inputObs;
let mesaGrid, btnConfirmar, btnCancelar, btnFinalizar;
let statusBar, sbPedidoId, sbBadge, sbMesa, sbTempo;

/* ─── utilitários ───────────────────────────────────── */
const money = v => Number(v).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

function pad(n) { return String(n).padStart(3, '0'); }

/* ─── VERIFICAR LOGIN ───────────────────────────────── */
async function verificarLogin() {
  try {
    const res  = await fetch('php/verificar_login.php');
    dadosLogin = await res.json();
    const area = document.getElementById('areaUsuario');
    if (!area) return;
    if (dadosLogin.logado) {
      area.innerHTML = `<span class="usuario-logado">Olá, ${dadosLogin.nome}</span>
                        <a href="logout.php" class="btn-login">Sair</a>`;
    } else {
      area.innerHTML = `<a href="login.php" class="btn-login">Entrar</a>`;
    }
  } catch (e) { console.error('verificarLogin:', e); }
}

/* ─── CARRINHO ──────────────────────────────────────── */
function addToCart(id) {
  const p  = (window.allProducts || []).find(x => x.id === id);
  if (!p) return;
  const ex = cart.find(x => x.id === id);
  if (ex) ex.qty += 1;
  else cart.push({ ...p, qty: 1 });
  updateCart();
  if (cartFab) cartFab.style.display = 'grid';
}

function updateQty(id, delta) {
  const item = cart.find(x => x.id === id);
  if (!item) return;
  item.qty += delta;
  if (item.qty <= 0) cart = cart.filter(x => x.id !== id);
  updateCart();
}

function removeItem(id) {
  cart = cart.filter(x => x.id !== id);
  updateCart();
}

function updateCart() {
  if (!cartItemsEl) return;
  cartItemsEl.innerHTML = cart.length
    ? cart.map(item => `
        <div class="cart-item">
          <div class="cart-item-top">
            <div>
              <strong>${item.name}</strong>
              <p style="color:var(--text-soft);font-size:.95rem;">${money(item.price)} cada</p>
            </div>
            <button class="qty-btn" onclick="removeItem(${item.id})">🗑</button>
          </div>
          <div class="qty-controls">
            <button class="qty-btn" onclick="updateQty(${item.id},-1)">−</button>
            <strong>${item.qty}</strong>
            <button class="qty-btn" onclick="updateQty(${item.id},1)">+</button>
          </div>
        </div>`).join('')
    : '<p style="color:var(--text-soft);">Seu carrinho está vazio.</p>';

  const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
  const count = cart.reduce((s, i) => s + i.qty, 0);
  if (cartTotalEl) cartTotalEl.textContent = money(total);
  if (cartCountEl) cartCountEl.textContent = count;
  if (cartFab)     cartFab.style.display   = count > 0 ? 'grid' : 'none';
}

/* ─── MODAL MESA ────────────────────────────────────── */
function abrirModal() {
  if (cart.length === 0) { showToast('Seu carrinho está vazio.', 'warn'); return; }
  if (inputNome) inputNome.value = '';
  if (inputObs)  inputObs.value  = '';
  mesaSelecionada = null;
  document.querySelectorAll('.mesa-btn').forEach(b => b.classList.remove('selected'));

  if (dadosLogin.logado) {
    if (modalTitulo)    modalTitulo.textContent    = `Olá, ${dadosLogin.nome}!`;
    if (modalSubtitulo) modalSubtitulo.textContent = 'Escolha a mesa e confirme.';
    if (campoNome)      campoNome.style.display    = 'none';
  } else {
    if (modalTitulo)    modalTitulo.textContent    = 'Dados do Pedido';
    if (modalSubtitulo) modalSubtitulo.textContent = 'Informe seu nome e escolha a mesa.';
    if (campoNome)      campoNome.style.display    = 'block';
  }
  if (modalOverlay) modalOverlay.classList.add('show');
}

async function confirmarPedido() {
  /* valida nome para anônimos */
  if (!dadosLogin.logado) {
    const nome = inputNome ? inputNome.value.trim() : '';
    if (!nome) {
      if (inputNome) { inputNome.focus(); inputNome.style.borderColor = 'var(--primary)'; }
      return;
    }
  }
  /* valida mesa */
  if (!mesaSelecionada) {
    if (mesaGrid) {
      mesaGrid.style.outline      = '2px solid var(--primary)';
      mesaGrid.style.borderRadius = '14px';
      setTimeout(() => { mesaGrid.style.outline = ''; }, 1500);
    }
    return;
  }

  if (btnConfirmar) { btnConfirmar.disabled = true; btnConfirmar.textContent = 'Enviando…'; }

  const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
  const body  = {
    itens:        cart.map(i => ({ nome: i.name, preco: i.price, quantidade: i.qty })),
    total,
    mesa:         mesaSelecionada,
    observacao:   inputObs  ? inputObs.value.trim()  : '',
    nome_anonimo: dadosLogin.logado ? '' : (inputNome ? inputNome.value.trim() : ''),
  };

  try {
    const res  = await fetch('php/salvar_pedido.php', {
      method:  'POST',
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify(body),
    });

    // Captura texto bruto antes de parsear (evita erro silencioso)
    const texto = await res.text();
    let data;
    try { data = JSON.parse(texto); }
    catch { console.error('Resposta não-JSON:', texto); showToast('Erro no servidor.', 'error'); return; }

    if (data.sucesso) {
      /* fecha modal e limpa carrinho */
      if (modalOverlay) modalOverlay.classList.remove('show');
      cart = [];
      updateCart();
      if (cartPanel) cartPanel.classList.remove('open');

      /* guarda pedido e inicia polling */
      pedidoAtual = { id: data.pedido_id, mesa: mesaSelecionada };
      salvarPedidoLocal(data.pedido_id);
      iniciarPolling();

      /* notificação visual — FUNCIONA PARA LOGADOS E ANÔNIMOS */
      showToastSucesso(data.pedido_id, mesaSelecionada);

      /* atualiza floating cards do hero */
      const heroMesa  = document.getElementById('heroMesaText');
      const heroItens = document.getElementById('heroItensText');
      if (heroMesa)  heroMesa.textContent  = `Mesa ${mesaSelecionada}`;
      if (heroItens) heroItens.textContent = `Pedido #${pad(data.pedido_id)} enviado`;

    } else {
      showToast('Erro: ' + (data.mensagem || 'falha desconhecida'), 'error');
    }
  } catch (e) {
    console.error('confirmarPedido:', e);
    showToast('Erro ao enviar. Tente novamente.', 'error');
  } finally {
    if (btnConfirmar) { btnConfirmar.disabled = false; btnConfirmar.textContent = 'Confirmar Pedido'; }
  }
}

/* ─── TOAST GENÉRICO ────────────────────────────────── */
function showToast(msg, type = 'info') {
  const colors = { info: '#3b82f6', warn: '#f59e0b', error: '#ef4444', success: '#22c55e' };
  const color  = colors[type] || colors.info;
  _fireToast(`<span>${msg}</span>`, color);
}

/* ─── TOAST DE SUCESSO (estilo iFood) ───────────────── */
function showToastSucesso(pedidoId, mesa) {
  const html = `
    <div style="display:flex;align-items:center;gap:14px;">
      <div style="width:46px;height:46px;border-radius:50%;background:#22c55e22;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;">✅</div>
      <div>
        <strong style="display:block;font-size:1rem;margin-bottom:2px;">Pedido #${pad(pedidoId)} enviado!</strong>
        <span style="color:#a3a3a3;font-size:.88rem;">Mesa ${mesa} — acompanhe o status abaixo 👇</span>
      </div>
    </div>`;
  _fireToast(html, '#22c55e', 7000);
}

function _fireToast(html, borderColor, duration = 4500) {
  document.getElementById('ws-toast-el')?.remove();
  _injectToastStyle();
  const t = document.createElement('div');
  t.id = 'ws-toast-el';
  t.innerHTML = html;
  t.style.cssText = `
    position:fixed;bottom:28px;right:28px;z-index:500;
    background:#171717;border:1px solid #2a2a2a;
    border-left:4px solid ${borderColor};
    border-radius:18px;padding:18px 22px;
    box-shadow:0 20px 60px rgba(0,0,0,.55);
    animation:wsSlideUp .3s ease;max-width:340px;cursor:pointer;`;
  t.onclick = () => t.remove();
  document.body.appendChild(t);
  setTimeout(() => t.remove(), duration);
}

function _injectToastStyle() {
  if (document.getElementById('ws-toast-css')) return;
  const s = document.createElement('style');
  s.id = 'ws-toast-css';
  s.textContent = '@keyframes wsSlideUp{from{transform:translateY(18px);opacity:0}to{transform:translateY(0);opacity:1}}';
  document.head.appendChild(s);
}

/* ─── PERSISTÊNCIA LOCAL (pedido sobrevive ao F5) ────── */
function salvarPedidoLocal(id) {
  try { localStorage.setItem('ws_pedido_id', id); } catch(e) {}
}

function carregarPedidoLocal() {
  try {
    const id = parseInt(localStorage.getItem('ws_pedido_id'));
    if (id > 0) {
      pedidoAtual = { id };
      iniciarPolling();
    }
  } catch(e) {}
}

/* ─── POLLING REALTIME ──────────────────────────────── */
function iniciarPolling() {
  if (pollingTimer) clearInterval(pollingTimer);
  atualizarStatus(); // imediato
  pollingTimer = setInterval(atualizarStatus, 5000); // a cada 5s
}

const STATUS_CONFIG = {
  'Pendente':   { label: '⏳ Pendente',          desc: 'Seu pedido foi recebido!',              badge: 'badge-Pendente'  },
  'Preparando': { label: '🍳 Em preparo',         desc: 'A cozinha está preparando seu pedido.', badge: 'badge-Preparando'},
  'Saiu':       { label: '🛵 Saiu para entrega',  desc: 'Seu pedido está a caminho!',            badge: 'badge-Saiu'      },
  'Entregue':   { label: '✅ Entregue',            desc: 'Bom apetite! 😄',                       badge: 'badge-Entregue'  },
  'Cancelado':  { label: '❌ Cancelado',           desc: 'Pedido cancelado.',                     badge: 'badge-Cancelado' },
};

let _ultimoStatus = null;

async function atualizarStatus() {
  if (!pedidoAtual || !pedidoAtual.id) return;
  try {
    const res  = await fetch(`php/status_pedido.php?id=${pedidoAtual.id}`);
    const data = await res.json();
    if (!data.sucesso) return;

    const cfg = STATUS_CONFIG[data.status] || { label: data.status, desc: '', badge: '' };

    /* ── status bar flutuante ── */
    if (statusBar) {
      statusBar.classList.add('show');
      if (sbPedidoId) sbPedidoId.textContent = `Pedido #${pad(data.id)}`;
      if (sbBadge)    { sbBadge.className = `status-badge ${cfg.badge}`; sbBadge.textContent = cfg.label; }
      if (sbMesa)     sbMesa.textContent   = data.mesa ? `Mesa ${data.mesa}` : '—';
      if (sbTempo)    sbTempo.textContent  = data.tempo_estimado ? `~${data.tempo_estimado} min` : '';
    }

    /* ── hero floating card ── */
    const heroStatus = document.getElementById('heroStatusText');
    if (heroStatus) heroStatus.textContent = cfg.label;

    /* ── toast ao mudar status (não no primeiro poll) ── */
    if (_ultimoStatus && _ultimoStatus !== data.status) {
      showToast(`${cfg.label} — ${cfg.desc}`, 'info');
    }
    _ultimoStatus = data.status;

    /* ── para polling se finalizado ── */
    if (data.status === 'Entregue' || data.status === 'Cancelado') {
      clearInterval(pollingTimer);
      try { localStorage.removeItem('ws_pedido_id'); } catch(e) {}
    }
  } catch (e) {
    console.warn('Polling erro:', e);
  }
}

/* ─── EXPOSIÇÃO GLOBAL ───────────────────────────────── */
window.updateQty       = updateQty;
window.removeItem      = removeItem;
window.addToCart       = addToCart;
window.abrirModal      = abrirModal;
window.confirmarPedido = confirmarPedido;
// Alias de compatibilidade com markups antigos
window.finalizarPedido = abrirModal;
window.confirmarNome   = confirmarPedido;

/* ─── INIT ───────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
  /* refs DOM */
  cartItemsEl  = document.getElementById('cartItems');
  cartTotalEl  = document.getElementById('cartTotal');
  cartCountEl  = document.getElementById('cartCount');
  cartFab      = document.getElementById('cartFab');
  cartPanel    = document.getElementById('cartPanel');
  closeCart    = document.getElementById('closeCart');
  btnFinalizar = document.getElementById('btnFinalizar');

  modalOverlay  = document.getElementById('modalOverlay');
  modalTitulo   = document.getElementById('modalTitulo');
  modalSubtitulo= document.getElementById('modalSubtitulo');
  campoNome     = document.getElementById('campoNome');
  inputNome     = document.getElementById('inputNome');
  inputObs      = document.getElementById('inputObs');
  mesaGrid      = document.getElementById('mesaGrid');
  btnConfirmar  = document.getElementById('btnConfirmarModal');
  btnCancelar   = document.getElementById('btnCancelarModal');

  statusBar  = document.getElementById('statusBar');
  sbPedidoId = document.getElementById('sbPedidoId');
  sbBadge    = document.getElementById('sbBadge');
  sbMesa     = document.getElementById('sbMesa');
  sbTempo    = document.getElementById('sbTempo');

  /* eventos carrinho */
  document.addEventListener('click', e => {
    if (e.target.classList.contains('add-btn')) addToCart(Number(e.target.dataset.id));
  });
  if (cartFab)   cartFab.addEventListener('click',   () => cartPanel?.classList.add('open'));
  if (closeCart) closeCart.addEventListener('click', () => cartPanel?.classList.remove('open'));

  /* eventos botão pedir */
  if (btnFinalizar) btnFinalizar.addEventListener('click', abrirModal);

  /* eventos modal */
  if (btnConfirmar) btnConfirmar.addEventListener('click', confirmarPedido);
  if (btnCancelar)  btnCancelar.addEventListener('click',  () => modalOverlay?.classList.remove('show'));
  if (modalOverlay) modalOverlay.addEventListener('click', e => {
    if (e.target === modalOverlay) modalOverlay.classList.remove('show');
  });

  /* grade de mesas (1–15) — só cria se o grid existir e estiver vazio */
  if (mesaGrid && mesaGrid.children.length === 0) {
    for (let i = 1; i <= 15; i++) {
      const btn = document.createElement('button');
      btn.className    = 'mesa-btn';
      btn.textContent  = i;
      btn.dataset.mesa = i;
      btn.addEventListener('click', () => {
        mesaSelecionada = i;
        document.querySelectorAll('.mesa-btn').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
      });
      mesaGrid.appendChild(btn);
    }
  }

  /* botões de scroll */
  ['startOrderBtn','heroOrderBtn'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('click', e => {
      e.preventDefault();
      document.getElementById('cardapio')?.scrollIntoView({ behavior: 'smooth' });
    });
  });

  /* retoma pedido em andamento (após F5) */
  carregarPedidoLocal();

  /* início */
  verificarLogin();
  updateCart();
});
