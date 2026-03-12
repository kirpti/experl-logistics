<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Experl Logistics — Giriş</title>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',Arial,sans-serif;background:#090e1a;color:#e2eaf5;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}
.card{background:#14213d;border:1px solid #1e304f;border-radius:20px;padding:44px 40px;width:100%;max-width:420px}
.logo{display:flex;align-items:center;gap:12px;margin-bottom:36px;justify-content:center}
.logo-icon{width:46px;height:46px;background:linear-gradient(135deg,#0f7bff,#00e5c7);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px}
.logo-text{font-size:22px;font-weight:800;letter-spacing:-.5px}
.logo-sub{font-size:11px;color:#5c7a9e;text-transform:uppercase;letter-spacing:1px}
h1{font-size:24px;font-weight:800;margin-bottom:6px;text-align:center}
.sub{font-size:13px;color:#5c7a9e;text-align:center;margin-bottom:28px}
.fg{margin-bottom:18px}
label{display:block;font-size:11px;font-weight:700;color:#5c7a9e;margin-bottom:6px;text-transform:uppercase;letter-spacing:.6px}
input{width:100%;padding:12px 14px;background:#0d1526;border:1px solid #1e304f;border-radius:10px;color:#e2eaf5;font-size:14px;font-family:inherit;outline:none;transition:border-color .15s}
input:focus{border-color:#0f7bff}
input::placeholder{color:#374151}
.err{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);border-radius:8px;padding:10px 14px;margin-bottom:16px;font-size:13px;color:#ef4444}
.remember{display:flex;align-items:center;gap:8px;font-size:13px;color:#5c7a9e;margin-bottom:20px}
.remember input{width:auto}
.btn{width:100%;padding:14px;background:linear-gradient(135deg,#0f7bff,#0050cc);color:#fff;border:none;border-radius:10px;font-size:15px;font-weight:700;cursor:pointer;font-family:inherit;transition:opacity .15s}
.btn:hover{opacity:.9}
.footer{text-align:center;margin-top:24px;font-size:12px;color:#374151}
</style>
</head>
<body>
<div class="card">
  <div class="logo">
    <div class="logo-icon">🚛</div>
    <div>
      <div class="logo-text">Experl</div>
      <div class="logo-sub">Logistics TMS</div>
    </div>
  </div>

  <h1>Hoş Geldiniz</h1>
  <div class="sub">Devam etmek için giriş yapın</div>

  @if($errors->any())
  <div class="err">{{ $errors->first() }}</div>
  @endif

  <form method="POST" action="{{ route('login.post') }}">
    @csrf
    <div class="fg">
      <label>E-posta Adresi</label>
      <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@firma.com" required autofocus>
    </div>
    <div class="fg">
      <label>Şifre</label>
      <input type="password" name="password" placeholder="••••••••" required>
    </div>
    <div class="remember">
      <input type="checkbox" name="remember" id="rem">
      <label for="rem" style="margin:0;text-transform:none;letter-spacing:0;font-size:13px">Beni hatırla</label>
    </div>
    <button type="submit" class="btn">Giriş Yap →</button>
  </form>

  <div class="footer">Experl Logistics TMS v1.0</div>
</div>
</body>
</html>
