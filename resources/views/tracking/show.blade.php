<!DOCTYPE html><html lang="tr"><head><meta charset="UTF-8"><title>Takip: {{ $number }} — Experl</title>
<style>*{box-sizing:border-box;margin:0;padding:0}body{font-family:'Segoe UI',Arial,sans-serif;background:#090e1a;color:#e2eaf5;display:flex;align-items:center;justify-content:center;min-height:100vh;padding:24px}
.card{background:#14213d;border:1px solid #1e304f;border-radius:20px;padding:36px;width:100%;max-width:560px}
h1{font-size:20px;font-weight:800;margin-bottom:20px}
.row{display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #1e304f;font-size:13px}
.row:last-child{border-bottom:none}.key{color:#5c7a9e;font-weight:600}.val{font-weight:600}
.pill{padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;background:rgba(15,123,255,.15);color:#0f7bff}
.back{display:inline-block;margin-top:20px;color:#0f7bff;font-size:13px;text-decoration:none}</style>
</head><body><div class="card">
@if($shipment)
  <h1>📦 {{ $shipment->tracking_number }}</h1>
  <div class="row"><span class="key">Durum</span><span class="pill">{{ $shipment->status }}</span></div>
  <div class="row"><span class="key">Alıcı</span><span class="val">{{ $shipment->receiver_name }}</span></div>
  <div class="row"><span class="key">Varış</span><span class="val">{{ $shipment->receiver_city }}, {{ $shipment->receiver_country }}</span></div>
  <div class="row"><span class="key">Ağırlık</span><span class="val">{{ $shipment->chargeable_weight }} kg</span></div>
@else
  <h1>❌ Bulunamadı</h1>
  <p style="color:#5c7a9e">"{{ $number }}" numaralı sevkiyat bulunamadı.</p>
@endif
<a href="/track" class="back">← Yeni Sorgulama</a>
</div></body></html>
