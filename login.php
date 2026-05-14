<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - WS Kitchen</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
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
      --radius-lg: 24px;
      --radius-md: 18px;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #0F0F0F, #171717);
      color: var(--text);
      min-height: 100vh;
      display: grid;
      place-items: center;
      padding: 20px;
    }

    .card {
      width: 100%;
      max-width: 460px;
      background: var(--bg-2);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 38px;
      box-shadow: 0 20px 60px rgba(0,0,0,.35);
    }

    .logo {
      width: 64px;
      height: 64px;
      border-radius: 20px;
      background: var(--primary);
      color: #111;
      display: grid;
      place-items: center;
      font-family: 'Poppins', sans-serif;
      font-weight: 800;
      font-size: 1.2rem;
      margin-bottom: 18px;
    }

    h1 {
      font-family: 'Poppins', sans-serif;
      font-size: 2rem;
      margin-bottom: 8px;
    }

    p {
      color: var(--text-soft);
      margin-bottom: 28px;
      line-height: 1.7;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
    }

    input {
      width: 100%;
      padding: 14px 16px;
      border-radius: 16px;
      border: 1px solid var(--border);
      background: var(--bg-3);
      color: var(--text);
      outline: none;
      margin-bottom: 18px;
    }

    .btn {
      width: 100%;
      padding: 15px;
      border: none;
      border-radius: 16px;
      background: var(--primary);
      color: #111;
      font-weight: 800;
      cursor: pointer;
      transition: .2s;
      margin-top: 8px;
    }

    .btn:hover {
      background: var(--primary-strong);
      color: white;
    }

    .links {
      margin-top: 22px;
      text-align: center;
      display: grid;
      gap: 12px;
    }

    .links a {
      color: var(--primary);
      text-decoration: none;
      font-weight: 600;
    }

    .back {
      display: inline-block;
      margin-top: 8px;
      color: var(--text-soft) !important;
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="logo">WS</div>
    <h1>Entrar</h1>
    <p>Acesse sua conta para acompanhar pedidos e ter uma experiência completa no WS Kitchen.</p>

    <form action="php/login.php" method="POST">
      <label for="email">E-mail</label>
      <input type="email" name="email" id="email" placeholder="seuemail@email.com" required>

      <label for="senha">Senha</label>
      <input type="password" name="senha" id="senha" placeholder="********" required>

      <button type="submit" class="btn">Entrar</button>
    </form>

    <div class="links">
      <a href="cadastro.php">Não tem conta? Cadastre-se</a>
      <a href="index.php" class="back">← Voltar para o site</a>
    </div>
  </div>
</body>
</html>