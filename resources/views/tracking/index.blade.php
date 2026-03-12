<!DOCTYPE html><html lang="tr"><head><meta charset="UTF-8"><title>Kargo Takip — Experl</title>
<style>*{box-sizing:border-box;margin:0;padding:0}body{font-family:'Segoe UI',Arial,sans-serif;background:#090e1a;color:#e2eaf5;display:flex;align-items:center;justify-content:center;min-height:100vh;padding:24px}
.card{background:#14213d;border:1px solid #1e304f;border-radius:20px;padding:44px;width:100%;max-width:480px;text-align:center}
h1{font-size:28px;font-weight:800;margin-bottom:8px}p{color:#5c7a9e;margin-bottom:28px}
input{width:100%;padding:13px 16px;background:#0d1526;border:1px solid #1e304f;border-radius:10px;color:#e2eaf5;font-size:15px;font-family:inherit;outline:none;margin-bottom:12px}
input:focus{border-color:#0f7bff}
.btn{width:100%;padding:13px;background:linear-gradient(135deg,#0f7bff,#0050cc);color:#fff;border:none;border-radius:10px;font-size:15px;font-weight:700;cursor:pointer}</style>
</head><body><div class="card">
<div style="font-size:48px;margin-bottom:16px">📦</div>
<h1>Kargo Takip</h1>
<p>Takip numaranızı girerek sevkiyatınızı sorgulayın</p>
<form method="GET" action="/track/search">
  <input name="q" placeholder="EXP-TR-24-000001" required>
  <button type="submit" class="btn">Sorgula →</button>
</form>
</div></body></html>
