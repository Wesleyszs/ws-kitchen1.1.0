<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>WS Kitchen</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg: #0F0F0F;
      --bg-2: #171717;
      --bg-3: #1F1F1F;
      --primary: #F97316;
      --primary-strong: #EA580C;
      --text: #F5F5F5;
      --text-soft: #A3A3A3;
      --border: #2A2A2A;
      --shadow: 0 20px 60px rgba(0,0,0,0.35);
      --radius-xl: 32px;
      --radius-lg: 24px;
      --radius-md: 18px;
      --container: 1240px;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); line-height: 1.6; }
    img { max-width: 100%; display: block; }
    a { text-decoration: none; color: inherit; }
    .container { width: min(100% - 40px, var(--container)); margin: 0 auto; }
    .section { padding: 90px 0; }
    .section-title { font-family: 'Poppins', sans-serif; font-size: clamp(2rem, 4vw, 2.7rem); line-height: 1.15; margin-top: 14px; }
    .section-subtitle { color: var(--text-soft); max-width: 700px; margin-top: 16px; font-size: 1.02rem; line-height: 1.9; }
    .eyebrow { color: var(--primary); text-transform: uppercase; letter-spacing: .25em; font-size: .82rem; font-weight: 700; }
    .btn { display: inline-flex; align-items: center; justify-content: center; padding: 15px 28px; border-radius: 18px; border: 1px solid transparent; font-weight: 700; transition: .25s ease; cursor: pointer; }
    .btn-primary { background: var(--primary); color: #111; }
    .btn-primary:hover { background: var(--primary-strong); color: white; transform: translateY(-2px); }
    .btn-secondary { background: transparent; color: var(--text); border-color: var(--border); }
    .btn-secondary:hover { border-color: var(--primary); color: var(--primary); transform: translateY(-2px); }
    .card { background: var(--bg-2); border: 1px solid var(--border); border-radius: var(--radius-lg); box-shadow: var(--shadow); }
    header { position: sticky; top: 0; z-index: 100; backdrop-filter: blur(14px); background: rgba(15, 15, 15, 0.9); border-bottom: 1px solid var(--border); }
    .nav { min-height: 90px; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
    .brand { display: flex; align-items: center; gap: 14px; }
    .brand-mark { width: 52px; height: 52px; border-radius: 18px; background: var(--primary); color: #111; display: grid; place-items: center; font-weight: 800; font-family: 'Poppins', sans-serif; box-shadow: 0 10px 30px rgba(249, 115, 22, .25); }
    .brand small { color: var(--text-soft); display: block; margin-top: 2px; }
    .nav-links { display: flex; align-items: center; gap: 30px; color: var(--text-soft); font-weight: 500; }
    .nav-links a:hover { color: var(--primary); }
    .hero { position: relative; overflow: hidden; padding: 70px 0 40px; }
    .glow, .glow-2 { position: absolute; border-radius: 50%; background: rgba(249, 115, 22, 0.18); filter: blur(90px); pointer-events: none; }
    .glow { width: 360px; height: 360px; right: -80px; top: 80px; }
    .glow-2 { width: 280px; height: 280px; left: 50%; bottom: 60px; }
    .hero-grid { display: grid; grid-template-columns: 1.05fr .95fr; align-items: center; gap: 56px; }
    .pill { display: inline-flex; padding: 10px 16px; border-radius: 999px; background: var(--bg-2); border: 1px solid var(--border); color: var(--primary); font-size: .92rem; margin-bottom: 22px; }
    .hero h1 { font-family: 'Poppins', sans-serif; font-size: clamp(2.6rem, 6vw, 4.4rem); line-height: 1.08; max-width: 12ch; }
    .hero p { margin-top: 22px; color: var(--text-soft); font-size: 1.08rem; max-width: 600px; line-height: 1.9; }
    .hero-actions, .hero-points { display: flex; flex-wrap: wrap; gap: 16px; margin-top: 34px; }
    .hero-points span { color: #d7d7d7; font-size: .96rem; }
    .hero-image-wrap { position: relative; }
    .hero-image { overflow: hidden; border-radius: var(--radius-xl); border: 1px solid var(--border); background: var(--bg-2); }
    .hero-image img { width: 100%; height: 620px; object-fit: cover; }
    .floating-card { position: absolute; background: rgba(23, 23, 23, 0.92); border: 1px solid var(--border); border-radius: 22px; padding: 18px; backdrop-filter: blur(14px); min-width: 220px; box-shadow: var(--shadow); }
    .floating-card small { color: var(--text-soft); text-transform: uppercase; letter-spacing: .16em; font-size: .72rem; }
    .floating-card strong { display: block; margin: 6px 0 2px; font-size: 1rem; }
    .floating-top { top: 22px; left: 22px; }
    .floating-bottom { right: 22px; bottom: 22px; }
    .benefits-grid,.products-grid,.steps-grid,.footer-grid,.drinks-grid { display: grid; gap: 24px; }
    .benefits-grid { grid-template-columns: repeat(3, 1fr); }
    .benefit-card,.step-card { padding: 30px; transition: .25s ease; }
    .benefit-card:hover,.product-card:hover,.step-card:hover { transform: translateY(-6px); border-color: rgba(249, 115, 22, .35); }
    .benefit-icon { font-size: 2rem; margin-bottom: 18px; }
    .benefit-card h3,.product-card h3,.step-card h3 { font-size: 1.35rem; margin-bottom: 10px; }
    .benefit-card p,.step-card p,.product-card p,.about-copy p,.cta p,footer li,footer p { color: var(--text-soft); line-height: 1.9; }
    .products-header { display: flex; align-items: end; justify-content: space-between; gap: 24px; margin-bottom: 34px; }
    .products-grid { grid-template-columns: repeat(3, 1fr); }
    .drinks-grid { grid-template-columns: repeat(4, 1fr); margin-top: 28px; }
    .product-card { overflow: hidden; transition: .25s ease; }
    .product-card img { width: 100%; height: 250px; object-fit: cover; }
    .product-content { padding: 24px; }
    .product-top { display: flex; align-items: start; justify-content: space-between; gap: 12px; }
    .price-tag { background: rgba(249, 115, 22, .15); color: var(--primary); padding: 8px 12px; border-radius: 999px; font-size: .9rem; font-weight: 700; white-space: nowrap; }
    .about-box { display: grid; grid-template-columns: 1fr 1fr; gap: 42px; padding: 42px; align-items: center; }
    .juice-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; margin-top: 28px; }
    .juice-item { background: var(--bg-3); border: 1px solid var(--border); border-radius: 18px; padding: 16px 18px; }
    .about-image { overflow: hidden; border-radius: 28px; border: 1px solid var(--border); }
    .about-image img { width: 100%; height: 420px; object-fit: cover; }

    /* CART */
    .cart-fab {
      position: fixed; right: 24px; bottom: 24px; background: var(--primary); color: #111;
      border: none; width: 70px; height: 70px; border-radius: 50%; font-size: 1.5rem;
      font-weight: 800; cursor: pointer; box-shadow: 0 20px 40px rgba(249,115,22,.25);
      z-index: 120; display: none; place-items: center;
    }
    .cart-count {
      position: absolute; top: -2px; right: -2px; width: 26px; height: 26px;
      border-radius: 50%; background: #fff; color: #111; font-size: .85rem;
      display: grid; place-items: center;
    }
    .cart-panel {
      position: fixed; top: 0; right: -420px; width: 400px; max-width: 100%;
      height: 100vh; background: #111; border-left: 1px solid var(--border);
      z-index: 130; transition: .3s ease; display: flex; flex-direction: column;
    }
    .cart-panel.open { right: 0; }
    .cart-header, .cart-footer { padding: 22px; border-bottom: 1px solid var(--border); }
    .cart-footer { border-top: 1px solid var(--border); border-bottom: none; margin-top: auto; }
    .cart-items { padding: 18px; overflow-y: auto; display: flex; flex-direction: column; gap: 14px; }
    .cart-item { background: var(--bg-2); border: 1px solid var(--border); border-radius: 18px; padding: 16px; }
    .cart-item-top { display: flex; justify-content: space-between; gap: 12px; }
    .qty-controls { display: flex; align-items: center; gap: 10px; margin-top: 12px; }
    .qty-btn { width: 34px; height: 34px; border-radius: 10px; border: 1px solid var(--border); background: var(--bg-3); color: var(--text); cursor: pointer; }
    .btn-pedir {
      width: 100%; padding: 15px 20px; border: none; border-radius: 18px;
      background: var(--primary); color: #111; font-weight: 800; font-size: 1rem;
      cursor: pointer; transition: .25s ease;
    }
    .btn-pedir:hover { background: var(--primary-strong); color: white; transform: translateY(-2px); }
    .btn-pedir:disabled { opacity: .6; cursor: not-allowed; transform: none; }

    /* ÁREA USUÁRIO */
    .area-usuario { display: flex; align-items: center; gap: 12px; background: var(--bg-2); border: 1px solid var(--border); padding: 10px 14px; border-radius: 16px; }
    .usuario-logado { font-weight: 600; color: var(--text); }
    .btn-login { color: var(--primary); font-weight: 700; }

    /* MODAL */
    .modal-overlay {
      position: fixed; inset: 0; background: rgba(0,0,0,.75);
      display: none; align-items: center; justify-content: center;
      z-index: 200; padding: 16px;
    }
    .modal-overlay.show { display: flex; }
    .modal-box {
      background: #171717; padding: 32px; border-radius: 22px;
      width: 100%; max-width: 420px; text-align: center;
      border: 1px solid var(--border); box-shadow: 0 30px 80px rgba(0,0,0,.5);
    }
    .modal-box h2 { font-family: 'Poppins', sans-serif; margin-bottom: 6px; }
    .modal-box p { color: var(--text-soft); margin-bottom: 24px; font-size: .95rem; }
    .modal-input {
      width: 100%; padding: 13px 16px; border-radius: 14px;
      border: 1px solid var(--border); background: var(--bg-3);
      color: var(--text); font-size: 1rem; margin-bottom: 14px; outline: none;
      transition: border-color .2s;
    }
    .modal-input:focus { border-color: var(--primary); }
    .mesa-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; margin-bottom: 20px; }
    .mesa-btn {
      aspect-ratio: 1; border-radius: 14px; border: 2px solid var(--border);
      background: var(--bg-3); color: var(--text); font-size: 1rem; font-weight: 700;
      cursor: pointer; transition: .2s;
    }
    .mesa-btn:hover { border-color: var(--primary); color: var(--primary); }
    .mesa-btn.selected { background: var(--primary); border-color: var(--primary); color: #111; }
    .modal-obs { width: 100%; padding: 13px 16px; border-radius: 14px; border: 1px solid var(--border); background: var(--bg-3); color: var(--text); font-size: .95rem; margin-bottom: 14px; outline: none; resize: none; font-family: 'Inter', sans-serif; transition: border-color .2s; }
    .modal-obs:focus { border-color: var(--primary); }
    .modal-footer { display: flex; gap: 10px; margin-top: 4px; }
    .btn-cancel { flex: 1; padding: 13px; border-radius: 14px; background: transparent; border: 1px solid var(--border); color: var(--text-soft); cursor: pointer; font-weight: 600; transition: .2s; }
    .btn-cancel:hover { border-color: var(--text-soft); color: var(--text); }

    /* STATUS REALTIME */
    .status-bar {
      position: fixed; bottom: 110px; right: 24px; z-index: 119;
      background: var(--bg-2); border: 1px solid var(--border);
      border-radius: 20px; padding: 14px 20px; min-width: 270px;
      box-shadow: 0 20px 50px rgba(0,0,0,.4); display: none;
      flex-direction: column; gap: 8px;
    }
    .status-bar.show { display: flex; }
    .status-bar-title { font-size: .78rem; text-transform: uppercase; letter-spacing: .15em; color: var(--text-soft); font-weight: 700; }
    .status-bar-row { display: flex; justify-content: space-between; align-items: center; gap: 12px; }
    .status-badge {
      padding: 4px 12px; border-radius: 999px; font-size: .82rem; font-weight: 700;
    }
    .badge-Pendente   { background: rgba(250,204,21,.15); color: #fbbf24; }
    .badge-Preparando { background: rgba(96,165,250,.15); color: #60a5fa; }
    .badge-Saiu       { background: rgba(251,146,60,.15); color: #fb923c; }
    .badge-Entregue   { background: rgba(52,211,153,.15); color: #34d399; }
    .badge-Cancelado  { background: rgba(248,113,113,.15); color: #f87171; }
    .status-tempo { font-size: .82rem; color: var(--text-soft); }

    /* RESPONSIVO */
    @media (max-width: 1100px) {
      .hero-grid,.about-box,.benefits-grid { grid-template-columns: 1fr 1fr; }
      .products-grid { grid-template-columns: repeat(2, 1fr); }
      .drinks-grid { grid-template-columns: repeat(2, 1fr); }
      .hero-image img { height: 500px; }
    }
    @media (max-width: 820px) {
      .nav-links { display: none; }
      .hero-grid,.about-box,.benefits-grid,.products-grid,.drinks-grid,.juice-grid { grid-template-columns: 1fr; }
      .products-header { flex-direction: column; align-items: start; }
      .hero { padding-top: 40px; }
      .hero-image img { height: 420px; }
      .floating-card { position: static; margin-top: 16px; }
      .hero-image-wrap { display: flex; flex-direction: column; }
      .section { padding: 70px 0; }
      .about-box,.benefit-card,.product-content { padding: 26px; }
      .cart-panel { width: 100%; }
      .status-bar { right: 16px; bottom: 100px; min-width: 240px; }
    }
  </style>
</head>
<body>

  <!-- ===== HEADER ===== -->
  <header>
    <div class="container nav">
      <div class="brand">
        <div class="brand-mark">WS</div>
        <div><strong>WS Kitchen</strong><small>Sabores do Brasil</small></div>
      </div>
      <nav class="nav-links">
        <a href="#inicio">Início</a>
        <a href="#cardapio">Cardápio</a>
        <a href="#sobre">Sobre</a>
      </nav>
      <div id="areaUsuario" class="area-usuario">
        <a href="login.php" class="btn-login">Entrar</a>
      </div>
      <a href="#cardapio" id="startOrderBtn" class="btn btn-primary">Fazer Pedido</a>
    </div>
  </header>

  <!-- ===== MAIN ===== -->
  <main>

    <!-- HERO -->
    <section class="hero" id="inicio">
      <div class="glow"></div><div class="glow-2"></div>
      <div class="container hero-grid">
        <div>
          <span class="pill">Sistema inteligente para pedidos e comandas</span>
          <h1>Sabores do Brasil, com pedido simples e moderno.</h1>
          <p>Descubra pratos tradicionais brasileiros em uma experiência digital leve, prática e organizada. Faça seu pedido com cadastro ou anonimamente.</p>
          <div class="hero-actions">
            <a href="#cardapio" id="heroOrderBtn" class="btn btn-primary">Ver Cardápio</a>
            <a href="#sobre" class="btn btn-secondary">Conhecer Mais</a>
          </div>
          <div class="hero-points">
            <span>✔ Pedido sem cadastro</span>
            <span>✔ Atualização em tempo real</span>
            <span>✔ Cozinha organizada</span>
          </div>
        </div>
        <div class="hero-image-wrap">
          <div class="hero-image">
            <img src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=1200&q=80" alt="Prato brasileiro do WS Kitchen">
          </div>
          <div class="floating-card floating-top"><small>Pedido em andamento</small><strong id="heroStatusText">Em preparo</strong><span>Atualizado agora</span></div>
          <div class="floating-card floating-bottom"><small id="heroMesaText">Mesa —</small><strong id="heroItensText">Aguardando pedido</strong><span>WS Comanda</span></div>
        </div>
      </div>
    </section>

    <!-- BENEFITS -->
    <section class="section">
      <div class="container benefits-grid">
        <div class="card benefit-card"><div class="benefit-icon">⚡</div><h3>Pedido rápido</h3><p>Escolha seus itens e envie seu pedido em poucos passos.</p></div>
        <div class="card benefit-card"><div class="benefit-icon">🕒</div><h3>Acompanhamento em tempo real</h3><p>Saiba exatamente em qual etapa seu pedido está.</p></div>
        <div class="card benefit-card"><div class="benefit-icon">📋</div><h3>Fluxo organizado</h3><p>Sistema pensado para melhorar a operação da cozinha.</p></div>
      </div>
    </section>

    <!-- CARDÁPIO -->
    <section class="section" id="cardapio">
      <div class="container">
        <div class="products-header">
          <div>
            <p class="eyebrow">Cardápio em destaque</p>
            <h2 class="section-title">Pratos Brasileiros</h2>
            <p class="section-subtitle">Escolha seus pratos e bebidas e monte seu pedido do seu jeito.</p>
          </div>
        </div>
        <div class="products-grid" id="foodGrid"></div>

        <div style="margin-top:70px;">
          <p class="eyebrow">Bebidas</p>
          <h2 class="section-title">Sucos Naturais</h2>
          <p class="section-subtitle">Complete seu pedido com bebidas leves e brasileiras.</p>
          <div class="drinks-grid" id="drinkGrid"></div>
        </div>
      </div>
    </section>

    <!-- SOBRE -->
    <section class="section" id="sobre">
      <div class="container"><div class="card about-box">
        <div class="about-copy">
          <p class="eyebrow">Sabor Brasileiro</p>
          <h2 class="section-title">Tradição brasileira em cada prato</h2>
          <p>O WS Kitchen valoriza sabores típicos do Brasil com uma apresentação moderna, unindo comida de verdade, conforto e praticidade em uma experiência digital elegante.</p>
          <div class="juice-grid">
            <div class="juice-item">Suco de Maracujá</div>
            <div class="juice-item">Suco de Acerola</div>
            <div class="juice-item">Suco de Caju</div>
            <div class="juice-item">Suco de Manga</div>
          </div>
        </div>
        <div class="about-image">
          <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1200&q=80" alt="Culinária brasileira">
        </div>
      </div></div>
    </section>

  </main>

  <!-- ===== CARRINHO FAB ===== -->
  <button class="cart-fab" id="cartFab">🛒<span class="cart-count" id="cartCount">0</span></button>

  <!-- ===== STATUS BAR REALTIME ===== -->
  <div class="status-bar" id="statusBar">
    <div class="status-bar-title">Seu pedido</div>
    <div class="status-bar-row">
      <span id="sbPedidoId" style="font-weight:700;font-size:.9rem;">—</span>
      <span id="sbBadge" class="status-badge badge-Pendente">Pendente</span>
    </div>
    <div class="status-bar-row">
      <span id="sbMesa" class="status-tempo">—</span>
      <span id="sbTempo" class="status-tempo"></span>
    </div>
  </div>

  <!-- ===== CART PANEL ===== -->
  <aside class="cart-panel" id="cartPanel">
    <div class="cart-header">
      <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;">
        <div>
          <h2 style="font-family:Poppins,sans-serif;">Seu Carrinho</h2>
          <p style="color:var(--text-soft);">Adicione ou remova seus produtos</p>
        </div>
        <button class="qty-btn" id="closeCart">✕</button>
      </div>
    </div>
    <div class="cart-items" id="cartItems"></div>
    <div class="cart-footer">
      <div style="display:flex;justify-content:space-between;margin-bottom:16px;">
        <strong>Total</strong>
        <strong id="cartTotal">R$ 0,00</strong>
      </div>
      <button id="btnFinalizar" class="btn-pedir">Pedir</button>
    </div>
  </aside>

  <!-- ===== MODAL MESA / NOME ===== -->
  <div class="modal-overlay" id="modalOverlay">
    <div class="modal-box">
      <h2 id="modalTitulo">Dados do Pedido</h2>
      <p id="modalSubtitulo">Informe seus dados para enviar o pedido.</p>

      <!-- Campo nome (só anônimos) -->
      <div id="campoNome">
        <input class="modal-input" id="inputNome" type="text" placeholder="Seu nome / comanda">
      </div>

      <!-- Grade de mesas -->
      <div class="mesa-grid" id="mesaGrid"></div>

      <!-- Observação opcional -->
      <textarea class="modal-obs" id="inputObs" placeholder="Observação (opcional)..." rows="2"></textarea>

      <div class="modal-footer">
        <button class="btn-cancel" id="btnCancelarModal">Cancelar</button>
        <button class="btn-pedir" id="btnConfirmarModal" style="flex:2;">Confirmar Pedido</button>
      </div>
    </div>
  </div>

  <!-- ===== SCRIPT ===== -->
  <script>
    /* ──────────────────────────────────── PRODUTOS ── */
    const foods = [
      { id:1, name:'Feijoada',       price:32.9,  desc:'Feijão preto com carnes, arroz, farofa, couve refogada e laranja.',      image:'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=900&q=80', category:'comida' },
      { id:2, name:'Moqueca',        price:38.9,  desc:'Ensopado cremoso com leite de coco, dendê, arroz e pirão.',               image:'https://images.unsplash.com/photo-1515003197210-e0cd71810b5f?auto=format&fit=crop&w=900&q=80', category:'comida' },
      { id:3, name:'Feijão Tropeiro', price:30.9, desc:'Feijão com farinha de milho, linguiça, bacon, ovos e torresmo.',          image:'https://images.unsplash.com/photo-1526318896980-cf78c088247c?auto=format&fit=crop&w=900&q=80', category:'comida' },
      { id:4, name:'Baião de Dois',  price:29.9,  desc:'Arroz com feijão, carne seca, queijo coalho e cheiro-verde.',            image:'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=900&q=80', category:'comida' },
      { id:5, name:'Bobó de Camarão',price:41.9,  desc:'Creme de mandioca com camarão, leite de coco e dendê.',                  image:'https://images.unsplash.com/photo-1559847844-5315695dadae?auto=format&fit=crop&w=900&q=80', category:'comida' },
      { id:6, name:'Picadinho',      price:27.9,  desc:'Carne refogada com legumes, batata, cenoura, arroz e farofa.',           image:'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=900&q=80', category:'comida' },
    ];
    const drinks = [
      { id:7,  name:'Suco de Maracujá',price:8.9, desc:'Suco natural gelado e refrescante.',        image:'https://images.unsplash.com/photo-1622597467836-f3285f2131b8?auto=format&fit=crop&w=900&q=80', category:'bebida' },
      { id:8,  name:'Suco de Acerola', price:8.9, desc:'Sabor marcante e cheio de frescor.',        image:'https://images.unsplash.com/photo-1600271886742-f049cd5bba3f?auto=format&fit=crop&w=900&q=80', category:'bebida' },
      { id:9,  name:'Suco de Caju',    price:8.9, desc:'Clássico brasileiro leve e saboroso.',      image:'https://images.unsplash.com/photo-1542444459-db63cfcf3d1d?auto=format&fit=crop&w=900&q=80', category:'bebida' },
      { id:10, name:'Suco de Manga',   price:8.9, desc:'Doce, tropical e super cremoso.',           image:'https://images.unsplash.com/photo-1623065422902-30a2d299bbe4?auto=format&fit=crop&w=900&q=80', category:'bebida' },
    ];
    const allProducts = [...foods, ...drinks];

    /* ──────────────────────────────────── DOM REFS ── */
    const foodGrid    = document.getElementById('foodGrid');
    const drinkGrid   = document.getElementById('drinkGrid');
    const cartItemsEl = document.getElementById('cartItems');
    const cartTotalEl = document.getElementById('cartTotal');
    const cartCountEl = document.getElementById('cartCount');
    const cartFab     = document.getElementById('cartFab');
    const cartPanel   = document.getElementById('cartPanel');
    const closeCart   = document.getElementById('closeCart');
    const btnFinalizar= document.getElementById('btnFinalizar');

    const modalOverlay     = document.getElementById('modalOverlay');
    const modalTitulo      = document.getElementById('modalTitulo');
    const modalSubtitulo   = document.getElementById('modalSubtitulo');
    const campoNome        = document.getElementById('campoNome');
    const inputNome        = document.getElementById('inputNome');
    const inputObs         = document.getElementById('inputObs');
    const mesaGrid         = document.getElementById('mesaGrid');
    const btnConfirmar     = document.getElementById('btnConfirmarModal');
    const btnCancelar      = document.getElementById('btnCancelarModal');

    const statusBar  = document.getElementById('statusBar');
    const sbPedidoId = document.getElementById('sbPedidoId');
    const sbBadge    = document.getElementById('sbBadge');
    const sbMesa     = document.getElementById('sbMesa');
    const sbTempo    = document.getElementById('sbTempo');

    /* ──────────────────────────────────── ESTADO ── */
    let cart          = [];
    let mesaSelecionada = null;
    let dadosLogin    = { logado: false };
    let pedidoAtual   = null;   // { id, status }
    let pollingTimer  = null;

    /* ──────────────────────────────────── UTILS ── */
    const money = v => v.toLocaleString('pt-BR',{style:'currency',currency:'BRL'});

    /* ──────────────────────────────────── RENDER CARDS ── */
    function createCard(p) {
      return `
        <article class="card product-card">
          <img src="${p.image}" alt="${p.name}" loading="lazy">
          <div class="product-content">
            <div class="product-top">
              <h3>${p.name}</h3>
              <span class="price-tag">${money(p.price)}</span>
            </div>
            <p>${p.desc}</p>
            <button class="btn btn-primary add-btn" data-id="${p.id}" style="margin-top:22px;width:100%;">
              Adicionar ao pedido
            </button>
          </div>
        </article>`;
    }

    foodGrid.innerHTML  = foods.map(createCard).join('');
    drinkGrid.innerHTML = drinks.map(createCard).join('');

    /* ──────────────────────────────────── CARRINHO ── */
    document.addEventListener('click', e => {
      if (e.target.classList.contains('add-btn')) {
        addToCart(Number(e.target.dataset.id));
      }
    });

    function addToCart(id) {
      const p   = allProducts.find(x => x.id === id);
      const ex  = cart.find(x => x.id === id);
      if (ex) ex.qty += 1;
      else cart.push({...p, qty:1});
      updateCart();
      cartFab.style.display = 'grid';
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

      const total = cart.reduce((s,i) => s + i.price * i.qty, 0);
      const count = cart.reduce((s,i) => s + i.qty, 0);
      cartTotalEl.textContent = money(total);
      cartCountEl.textContent = count;
      cartFab.style.display   = count > 0 ? 'grid' : 'none';
    }

    window.updateQty  = updateQty;
    window.removeItem = removeItem;

    /* ──────────────────────────────────── MODAL MESA ── */
    // Cria botões das mesas (1–15)
    for (let i = 1; i <= 15; i++) {
      const btn = document.createElement('button');
      btn.className   = 'mesa-btn';
      btn.textContent = i;
      btn.dataset.mesa = i;
      btn.addEventListener('click', () => {
        mesaSelecionada = i;
        document.querySelectorAll('.mesa-btn').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
      });
      mesaGrid.appendChild(btn);
    }

    function abrirModal() {
      if (cart.length === 0) { showToast('Seu carrinho está vazio.', 'warn'); return; }
      inputNome.value = '';
      inputObs.value  = '';
      mesaSelecionada = null;
      document.querySelectorAll('.mesa-btn').forEach(b => b.classList.remove('selected'));

      if (dadosLogin.logado) {
        modalTitulo.textContent    = `Olá, ${dadosLogin.nome}!`;
        modalSubtitulo.textContent = 'Escolha sua mesa e confirme o pedido.';
        campoNome.style.display    = 'none';
      } else {
        modalTitulo.textContent    = 'Dados do Pedido';
        modalSubtitulo.textContent = 'Informe seu nome e escolha a mesa.';
        campoNome.style.display    = 'block';
      }
      modalOverlay.classList.add('show');
    }

    async function confirmarPedido() {
      // Validação: nome (se anônimo)
      if (!dadosLogin.logado) {
        const nome = inputNome.value.trim();
        if (!nome) {
          inputNome.focus();
          inputNome.style.borderColor = 'var(--primary)';
          return;
        }
      }
      // Validação: mesa obrigatória
      if (!mesaSelecionada) {
        mesaGrid.style.outline = '2px solid var(--primary)';
        mesaGrid.style.borderRadius = '14px';
        setTimeout(() => mesaGrid.style.outline = '', 1500);
        return;
      }

      btnConfirmar.disabled    = true;
      btnConfirmar.textContent = 'Enviando...';

      const total = cart.reduce((s,i) => s + i.price * i.qty, 0);
      const body  = {
        itens:         cart.map(i => ({ nome: i.name, preco: i.price, quantidade: i.qty })),
        total:         total,
        mesa:          mesaSelecionada,
        observacao:    inputObs.value.trim(),
        nome_anonimo:  dadosLogin.logado ? '' : inputNome.value.trim(),
      };

      try {
        const res  = await fetch('php/salvar_pedido.php', {
          method:  'POST',
          headers: { 'Content-Type': 'application/json' },
          body:    JSON.stringify(body),
        });
        const data = await res.json();

        if (data.sucesso) {
          modalOverlay.classList.remove('show');
          cart = [];
          updateCart();
          cartPanel.classList.remove('open');

          pedidoAtual = { id: data.pedido_id, mesa: mesaSelecionada };

          // Persiste no localStorage para sobreviver a F5
          try { localStorage.setItem('ws_pedido_id', data.pedido_id); } catch(e) {}

          iniciarPolling();

          // ── Notificação visual (logado E anônimo) ──
          showToastSucesso(data.pedido_id, mesaSelecionada);

          // Atualiza floating cards do hero
          document.getElementById('heroMesaText').textContent  = `Mesa ${mesaSelecionada}`;
          document.getElementById('heroItensText').textContent = `Pedido #${String(data.pedido_id).padStart(3,'0')} enviado`;
        } else {
          showToast('Erro: ' + (data.mensagem || 'falha desconhecida'), 'error');
        }
      } catch(e) {
        showToast('Erro ao enviar pedido. Tente novamente.', 'error');
        console.error(e);
      } finally {
        btnConfirmar.disabled    = false;
        btnConfirmar.textContent = 'Confirmar Pedido';
      }
    }

    btnFinalizar.addEventListener('click', abrirModal);
    btnConfirmar.addEventListener('click', confirmarPedido);
    btnCancelar.addEventListener('click',  () => modalOverlay.classList.remove('show'));
    modalOverlay.addEventListener('click', e => { if (e.target === modalOverlay) modalOverlay.classList.remove('show'); });

    /* ──────────────────────────────────── TOAST ── */
    const _STATUS_CFG = {
      'Pendente':   { label:'⏳ Pendente',         desc:'Seu pedido foi recebido!',              badge:'badge-Pendente'   },
      'Preparando': { label:'🍳 Em preparo',        desc:'A cozinha está preparando seu pedido.', badge:'badge-Preparando' },
      'Saiu':       { label:'🛵 Saiu para entrega', desc:'Seu pedido está a caminho!',            badge:'badge-Saiu'       },
      'Entregue':   { label:'✅ Entregue',           desc:'Bom apetite! 😄',                       badge:'badge-Entregue'   },
      'Cancelado':  { label:'❌ Cancelado',          desc:'Pedido cancelado.',                     badge:'badge-Cancelado'  },
    };

    function _injectToastCSS() {
      if (document.getElementById('ws-toast-css')) return;
      const s = document.createElement('style');
      s.id = 'ws-toast-css';
      s.textContent = '@keyframes wsSlideUp{from{transform:translateY(18px);opacity:0}to{transform:translateY(0);opacity:1}}';
      document.head.appendChild(s);
    }

    function _fireToast(html, borderColor, duration = 4500) {
      document.getElementById('ws-toast-el')?.remove();
      _injectToastCSS();
      const t = document.createElement('div');
      t.id = 'ws-toast-el';
      t.innerHTML = html;
      t.style.cssText = `position:fixed;bottom:28px;right:28px;z-index:500;background:#171717;border:1px solid #2a2a2a;border-left:4px solid ${borderColor};border-radius:18px;padding:18px 22px;box-shadow:0 20px 60px rgba(0,0,0,.55);animation:wsSlideUp .3s ease;max-width:340px;cursor:pointer;`;
      t.onclick = () => t.remove();
      document.body.appendChild(t);
      setTimeout(() => t.remove(), duration);
    }

    function showToast(msg, type = 'info') {
      const colors = { info:'#3b82f6', warn:'#f59e0b', error:'#ef4444', success:'#22c55e' };
      _fireToast(`<span style="font-size:.95rem;">${msg}</span>`, colors[type] || colors.info);
    }

    function showToastSucesso(pedidoId, mesa) {
      _fireToast(`
        <div style="display:flex;align-items:center;gap:14px;">
          <div style="width:46px;height:46px;border-radius:50%;background:#22c55e22;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;">✅</div>
          <div>
            <strong style="display:block;font-size:1rem;margin-bottom:3px;">Pedido #${String(pedidoId).padStart(3,'0')} enviado!</strong>
            <span style="color:#a3a3a3;font-size:.88rem;">Mesa ${mesa} — acompanhe o status abaixo 👇</span>
          </div>
        </div>`, '#22c55e', 7000);
    }

    /* ──────────────────────────────────── REALTIME POLLING ── */
    let _ultimoStatus = null;

    function iniciarPolling() {
      if (pollingTimer) clearInterval(pollingTimer);
      atualizarStatus();
      pollingTimer = setInterval(atualizarStatus, 5000);
    }

    async function atualizarStatus() {
      if (!pedidoAtual || !pedidoAtual.id) return;
      try {
        const res  = await fetch(`php/buscar_status.php?id=${pedidoAtual.id}`);
        const data = await res.json();
        if (!data.sucesso) return;

        const cfg = _STATUS_CFG[data.status] || { label: data.status, desc: '', badge: '' };

        // Barra de status flutuante
        statusBar.classList.add('show');
        sbPedidoId.textContent = `Pedido #${String(data.id).padStart(3,'0')}`;
        sbBadge.className      = `status-badge ${cfg.badge}`;
        sbBadge.textContent    = cfg.label;
        sbMesa.textContent     = data.mesa ? `Mesa ${data.mesa}` : '—';
        sbTempo.textContent    = data.tempo_estimado ? `~${data.tempo_estimado} min` : '';

        // Hero floating card
        const heroStatusText = document.getElementById('heroStatusText');
        if (heroStatusText) heroStatusText.textContent = cfg.label;

        // Toast ao mudar status (não no primeiro poll)
        if (_ultimoStatus && _ultimoStatus !== data.status) {
          showToast(`${cfg.label} — ${cfg.desc}`, 'info');
        }
        _ultimoStatus = data.status;

        // Para polling se finalizado
        if (data.status === 'Entregue' || data.status === 'Cancelado') {
          clearInterval(pollingTimer);
          try { localStorage.removeItem('ws_pedido_id'); } catch(e) {}
        }
      } catch(e) {
        console.warn('Polling erro:', e);
      }
    }

    // Retoma pedido após F5
    function carregarPedidoLocal() {
      try {
        const id = parseInt(localStorage.getItem('ws_pedido_id'));
        if (id > 0) { pedidoAtual = { id }; iniciarPolling(); }
      } catch(e) {}
    }

    /* ──────────────────────────────────── LOGIN CHECK ── */
    async function verificarLogin() {
      try {
        const res   = await fetch('php/verificar_login.php');
        dadosLogin  = await res.json();
        const area  = document.getElementById('areaUsuario');
        if (!area) return;
        if (dadosLogin.logado) {
          area.innerHTML = `<span class="usuario-logado">Olá, ${dadosLogin.nome}</span>
                            <a href="logout.php" class="btn-login">Sair</a>`;
        } else {
          area.innerHTML = `<a href="login.php" class="btn-login">Entrar</a>`;
        }
      } catch(e) { console.error(e); }
    }

    /* ──────────────────────────────────── SCROLL BTNS ── */
    document.getElementById('startOrderBtn').addEventListener('click', e => {
      e.preventDefault();
      document.getElementById('cardapio').scrollIntoView({behavior:'smooth'});
    });
    document.getElementById('heroOrderBtn').addEventListener('click', e => {
      e.preventDefault();
      document.getElementById('cardapio').scrollIntoView({behavior:'smooth'});
    });

    cartFab.addEventListener('click',   () => cartPanel.classList.add('open'));
    closeCart.addEventListener('click', () => cartPanel.classList.remove('open'));

    /* ──────────────────────────────────── INIT ── */
    carregarPedidoLocal(); // retoma pedido ativo após F5
    verificarLogin();
    updateCart();
  </script>

</body>
</html>
