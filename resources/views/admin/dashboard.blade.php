<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — Experl Logistics</title>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--bg:#090e1a;--bg2:#0d1526;--surface:#14213d;--border:#1e304f;--accent:#0f7bff;--accent2:#00e5c7;--ok:#22c55e;--warn:#f59e0b;--text:#e2eaf5;--muted:#5c7a9e}
body{font-family:'Segoe UI',Arial,sans-serif;background:var(--bg);color:var(--text);display:flex;min-height:100vh}
/* Sidebar */
.sidebar{width:220px;background:var(--bg2);border-right:1px solid var(--border);display:flex;flex-direction:column;position:sticky;top:0;height:100vh;overflow-y:auto;flex-shrink:0}
.logo{padding:22px 18px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px}
.logo-icon{width:36px;height:36px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}
.logo-text{font-size:15px;font-weight:800}
.logo-sub{font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:1px}
nav{padding:14px 10px;flex:1}
.nav-sec{font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;padding:12px 8px 6px;font-weight:700}
.nav-item{display:flex;align-items:center;gap:10px;padding:9px 10px;border-radius:8px;cursor:pointer;font-size:13px;color:var(--muted);margin-bottom:2px;text-decoration:none;font-weight:500;transition:all .15s}
.nav-item:hover{background:#111d35;color:var(--text)}
.nav-item.active{background:rgba(15,123,255,.15);color:var(--accent)}
.sidebar-foot{padding:12px;border-top:1px solid var(--border)}
.support-row{display:flex;align-items:center;justify-content:space-between;background:#111d35;padding:10px 12px;border-radius:8px;font-size:12px}
.toggle{width:36px;height:20px;background:var(--border);border-radius:10px;cursor:pointer;position:relative;transition:background .2s;flex-shrink:0;border:none}
.toggle.on{background:var(--ok)}
.toggle::after{content:'';width:14px;height:14px;background:#fff;border-radius:50%;position:absolute;top:3px;left:3px;transition:left .2s}
.toggle.on::after{left:19px}
/* Main */
.main{flex:1;overflow-y:auto}
.topbar{display:flex;align-items:center;justify-content:space-between;padding:16px 28px;border-bottom:1px solid var(--border);background:var(--bg2);position:sticky;top:0;z-index:10}
.topbar h1{font-size:18px;font-weight:800}
.topbar p{font-size:12px;color:var(--muted)}
.user-pill{display:flex;align-items:center;gap:8px;background:var(--surface);border:1px solid var(--border);padding:7px 14px;border-radius:20px;font-size:13px}
.content{padding:24px 28px}
/* KPI */
.kpi-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:22px}
.kpi{background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:20px;position:relative;overflow:hidden}
.kpi::after{content:'';position:absolute;top:0;right:0;width:60px;height:60px;background:radial-gradient(circle at top right,rgba(15,123,255,.07),transparent 70%)}
.kpi-label{font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;font-weight:700}
.kpi-val{font-size:30px;font-weight:800;letter-spacing:-1px;margin:8px 0 4px;color:#fff}
.kpi-icon{position:absolute;top:16px;right:16px;font-size:24px;opacity:.35}
.kpi-sub{font-size:12px;color:var(--muted)}
/* Table */
.panel{background:var(--surface);border:1px solid var(--border);border-radius:12px;overflow:hidden}
.panel-head{display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid var(--border)}
.panel-title{font-size:14px;font-weight:700}
table{width:100%;border-collapse:collapse;font-size:13px}
th{text-align:left;padding:10px 16px;font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:.7px;border-bottom:1px solid var(--border)}
td{padding:11px 16px;border-bottom:1px solid rgba(30,48,79,.4)}
tr:last-child td{border-bottom:none}
.pill{display:inline-block;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:700}
.pill-delivered{background:rgba(34,197,94,.12);color:#22c55e}
.pill-transit{background:rgba(15,123,255,.12);color:#0f7bff}
.pill-pending{background:rgba(245,158,11,.12);color:#f59e0b}
.pill-draft{background:rgba(92,122,158,.12);color:#5c7a9e}
.logout-btn{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);color:#ef4444;padding:7px 14px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;font-family:inherit}
</style>
</head>
<body>

<aside class="sidebar">
  <div class="logo">
    <div class="logo-icon">🚛</div>
    <div>
      <div class="logo-text">Experl</div>
      <div class="logo-sub">Logistics TMS</div>
    </div>
  </div>
  <nav>
    <div class="nav-sec">Genel</div>
    <a href="/admin/dashboard" class="nav-item active">📊 Dashboard</a>
    <div class="nav-sec">Operasyon</div>
    <a href="/admin/shipments" class="nav-item">📦 Sevkiyatlar</a>
    <a href="/admin/customers" class="nav-item">👤 Müşteriler</a>
    <a href="/admin/branches" class="nav-item">🏢 Şubeler</a>
    <div class="nav-sec">Finans</div>
    <a href="/admin/finance" class="nav-item">💶 Cari Hesaplar</a>
    <div class="nav-sec">Yönetim</div>
    <a href="/admin/users" class="nav-item">👥 Kullanıcılar</a>
    <a href="/admin/settings" class="nav-item">⚙️ Ayarlar</a>
  </nav>
  <div class="sidebar-foot">
    <div class="support-row">
      <div>
        <div style="font-weight:600;color:var(--text);font-size:12px">🟢 Canlı Destek</div>
        <div style="color:var(--muted);font-size:10px">Online</div>
      </div>
      <button class="toggle on" onclick="this.classList.toggle('on')" title="Destek Durumu"></button>
    </div>
  </div>
</aside>

<main class="main">
  <div class="topbar">
    <div>
      <h1>Admin Dashboard</h1>
      <p>Hoş geldiniz, {{ auth()->user()->name }} · {{ now()->format('d.m.Y H:i') }}</p>
    </div>
    <div style="display:flex;gap:10px;align-items:center">
      <div class="user-pill">👤 {{ auth()->user()->name }}</div>
      <form method="POST" action="{{ route('logout') }}" style="margin:0">
        @csrf
        <button type="submit" class="logout-btn">Çıkış</button>
      </form>
    </div>
  </div>

  <div class="content">
    <div class="kpi-grid">
      <div class="kpi">
        <div class="kpi-icon">📦</div>
        <div class="kpi-label">Toplam Sevkiyat</div>
        <div class="kpi-val">{{ number_format($stats['total_shipments']) }}</div>
        <div class="kpi-sub">Tüm zamanlar</div>
      </div>
      <div class="kpi">
        <div class="kpi-icon">✅</div>
        <div class="kpi-label">Teslim Edildi</div>
        <div class="kpi-val">{{ number_format($stats['delivered']) }}</div>
        <div class="kpi-sub">Başarılı teslimat</div>
      </div>
      <div class="kpi">
        <div class="kpi-icon">💶</div>
        <div class="kpi-label">Toplam Gelir</div>
        <div class="kpi-val">€{{ number_format($stats['total_revenue'], 0) }}</div>
        <div class="kpi-sub">EUR cinsinden</div>
      </div>
      <div class="kpi">
        <div class="kpi-icon">🚛</div>
        <div class="kpi-label">Yolda</div>
        <div class="kpi-val">{{ number_format($stats['in_transit']) }}</div>
        <div class="kpi-sub">Aktif taşımalar</div>
      </div>
      <div class="kpi">
        <div class="kpi-icon">👤</div>
        <div class="kpi-label">Müşteriler</div>
        <div class="kpi-val">{{ number_format($stats['total_customers']) }}</div>
        <div class="kpi-sub">Kayıtlı müşteri</div>
      </div>
      <div class="kpi">
        <div class="kpi-icon">🏢</div>
        <div class="kpi-label">Şubeler</div>
        <div class="kpi-val">{{ number_format($stats['total_branches']) }}</div>
        <div class="kpi-sub">Aktif şube</div>
      </div>
    </div>

    <div class="panel">
      <div class="panel-head">
        <div class="panel-title">Son Sevkiyatlar</div>
        <a href="/admin/shipments" style="font-size:12px;color:var(--accent);text-decoration:none">Tümünü Gör →</a>
      </div>
      @if($recentShipments->isEmpty())
      <div style="padding:48px;text-align:center;color:var(--muted)">
        <div style="font-size:36px;margin-bottom:12px">📦</div>
        <div style="font-size:14px">Henüz sevkiyat yok. İlk sevkiyatı ekleyin!</div>
      </div>
      @else
      <table>
        <thead><tr>
          <th>Takip No</th><th>Alıcı</th><th>Varış</th><th>Ağırlık</th><th>Tutar</th><th>Durum</th>
        </tr></thead>
        <tbody>
          @foreach($recentShipments as $s)
          <tr>
            <td style="font-family:monospace;font-size:12px;color:var(--accent)">{{ $s->tracking_number }}</td>
            <td>{{ $s->receiver_name }}</td>
            <td>{{ $s->receiver_city }}, {{ $s->receiver_country }}</td>
            <td>{{ $s->chargeable_weight }} kg</td>
            <td style="color:var(--accent2);font-weight:700">€{{ number_format($s->total_amount,2) }}</td>
            <td>
              @php $pill = match($s->status){
                'delivered'=>'delivered','in_transit'=>'transit',
                'draft'=>'draft',default=>'pending'
              } @endphp
              <span class="pill pill-{{ $pill }}">{{ $s->status }}</span>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @endif
    </div>
  </div>
</main>

</body>
</html>
