// Dynamic organization chart for Kepengurusan
(function () {
    var containerId = 'organizationChart';
    var el = document.getElementById(containerId);
    if (!el || typeof Highcharts === 'undefined') { return; }

    // Allow passing CABANG_KEY via data attribute on the container, e.g. <div id="organizationChart" data-cabang-key="..."></div>
    var CABANG_KEY = (el.dataset && el.dataset.cabangKey) ? el.dataset.cabangKey : null;

    // Backend endpoint (relative to dashboard/html root)
    var backendUrl = 'module/backend/transaksi/kepengurusan/t_kepengurusan.php';

    // Small helper to build IDs and safe fields
    function nid(prefix, id) { return prefix + '_' + String(id); }
    function nonEmpty(v, fallback) { return (v === undefined || v === null || v === '') ? fallback : v; }

    function buildSeries(payload) {
        var nodes = [];
        var links = [];

        // HIERARCHY REQUESTED:
        // 1. Ketua
        // 2. Pengurus (group node)
        // 3.x Koordinator_# (each koordinator member enumerated)
        // Assumption: all koordinator belong to the single pengurus chain.

        var ketua = payload.ketua || null;
        var ketuaId;
        if (ketua) {
            ketuaId = nid('KETUA', ketua.ANGGOTA_ID);
            nodes.push({
                id: ketuaId,
                title: nonEmpty(ketua.ANGGOTA_AKSES, 'Ketua'),
                name: ketua.ANGGOTA_NAMA,
                image: nonEmpty(ketua.ANGGOTA_PIC, './assets/images/daftaranggota/default/avatar.png'),
                anggotaId: ketua.ANGGOTA_ID,
                tingkatanSebutan: nonEmpty(ketua.TINGKATAN_SEBUTAN, ''),
                tingkatanNama: nonEmpty(ketua.TINGKATAN_NAMA, '')
            });
        }

        // Pengurus group node (even if there is one person, we still show grouping as requested numbering)
        var pengurusList = Array.isArray(payload.pengurus) ? payload.pengurus : [];
        var pengurusGroupId = 'PENGURUS_GROUP';
        nodes.push({
            id: pengurusGroupId,
            title: 'Pengurus',
            name: pengurusList.length === 1 ? pengurusList[0].ANGGOTA_NAMA : (pengurusList.length + ' Pengurus'),
            // Display first image if single; else leave out to use default styling
            image: (pengurusList.length === 1 ? nonEmpty(pengurusList[0].ANGGOTA_PIC, './assets/images/daftaranggota/default/avatar.png') : undefined),
            anggotaId: (pengurusList.length === 1 ? pengurusList[0].ANGGOTA_ID : '—'),
            tingkatanSebutan: (pengurusList.length === 1 ? nonEmpty(pengurusList[0].TINGKATAN_SEBUTAN, 'Pengurus') : 'Pengurus'),
            tingkatanNama: (pengurusList.length === 1 ? nonEmpty(pengurusList[0].TINGKATAN_NAMA, 'Pengurus') : 'Pengurus')
        });
        if (ketuaId) { links.push([ketuaId, pengurusGroupId]); }

        // Individual pengurus members below the group node if more than one (optional visualization)
        if (pengurusList.length > 1) {
            pengurusList.forEach(function (p) {
                var pid = nid('PENGURUS', p.ANGGOTA_ID);
                nodes.push({
                    id: pid,
                    title: nonEmpty(p.ANGGOTA_AKSES, 'Pengurus'),
                    name: p.ANGGOTA_NAMA,
                    image: nonEmpty(p.ANGGOTA_PIC, './assets/images/daftaranggota/default/avatar.png'),
                    anggotaId: p.ANGGOTA_ID,
                    tingkatanSebutan: nonEmpty(p.TINGKATAN_SEBUTAN, ''),
                    tingkatanNama: nonEmpty(p.TINGKATAN_NAMA, '')
                });
                links.push([pengurusGroupId, pid]);
            });
        }

        // Koordinator nodes enumerated (3.1, 3.2, ...)
        var koordinatorList = Array.isArray(payload.koordinator) ? payload.koordinator : [];
        koordinatorList.forEach(function (k, idx) {
            var kid = nid('KOORDINATOR', k.ANGGOTA_ID);
            nodes.push({
                id: kid,
                title: k.ANGGOTA_AKSES,
                name: k.ANGGOTA_NAMA,
                image: nonEmpty(k.ANGGOTA_PIC, './assets/images/daftaranggota/default/avatar.png'),
                anggotaId: k.ANGGOTA_ID,
                tingkatanSebutan: nonEmpty(k.TINGKATAN_SEBUTAN, ''),
                tingkatanNama: nonEmpty(k.TINGKATAN_NAMA, '')
            });
            links.push([pengurusGroupId, kid]);
        });

        // Fallback when no data at all
        if (!ketua && pengurusList.length === 0 && koordinatorList.length === 0) {
            nodes.push({ id: 'NO_DATA', title: '—', name: 'Belum ada data' });
            // Anchor the placeholder to a virtual root for layout stability
            links.push(['NO_DATA', 'NO_DATA']);
        }

        return { nodes: nodes, links: links };
    }

    function renderChart(payload) {
        var series = buildSeries(payload);
        Highcharts.chart(containerId, {
            chart: {
                height: Math.max(420, 180 + series.nodes.length * 36),
                inverted: true
            },
            title: {
                text: 'Struktur Kepengurusan CIPTA SEJATI<br>' + nonEmpty(payload.cabang && payload.cabang.nama, '')
            },
            accessibility: {
                point: {
                    descriptionFormat: '{add index 1}. {toNode.name}' +
                        '{#if (ne toNode.name toNode.id)}, {toNode.id}{/if}, ' +
                        'reports to {fromNode.id}'
                }
            },
            series: [{
                type: 'organization',
                name: 'Kepengurusan',
                keys: ['from', 'to'],
                data: series.links,
                levels: [{
                    level: 0, // Ketua
                    color: 'silver',
                    dataLabels: { color: 'black' },
                    height: 100
                }, {
                    level: 1, // Pengurus group
                    color: '#007ad0',
                    dataLabels: { color: 'white' },
                    height: 100
                }, {
                    level: 2, // Individual pengurus (if any)
                    color: '#359154',
                    height: 85
                }, {
                    level: 3, // Koordinator
                    color: '#980104',
                    height: 85
                }],
                nodes: series.nodes,
                colorByPoint: false,
                color: '#007ad0',
                dataLabels: {
                    useHTML: true,
                    crop: false,
                    overflow: 'allow',
                    allowOverlap: true,
                    style: { color: '#ffffff', textOutline: 'none' },
                    formatter: function () {
                        var p = this.point || {};
                        if (!p.isNode) { return this.key || ''; }
                        var imgHtml = p.image ? '<div style="margin-bottom:6px"><img src="' + p.image + '" style="width:36px;height:36px;object-fit:cover;border-radius:50%;border:2px solid rgba(255,255,255,.5)"/></div>' : '';
                        var nameHtml = p.name ? '<div style="font-weight:600;line-height:1.2">' + p.name + '</div>' : '';
                        var titleHtml = p.title ? '<div style="font-size:11px;opacity:.85;line-height:1.2">' + p.title + '</div>' : '';
                        var idHtml = (typeof p.anggotaId !== 'undefined') ? '<div style="font-size:10px;opacity:.9;line-height:1.2">ID: ' + p.anggotaId + '</div>' : '';
                        var tingkatanLabel = '';
                        if (p.tingkatanSebutan && p.tingkatanNama) {
                            tingkatanLabel = p.tingkatanSebutan + ' (' + p.tingkatanNama + ')';
                        } else if (p.tingkatanSebutan) {
                            tingkatanLabel = p.tingkatanSebutan;
                        } else if (p.tingkatanNama) {
                            tingkatanLabel = p.tingkatanNama;
                        }
                        var tingkatanHtml = tingkatanLabel ? '<div style="font-size:10px;opacity:.9;line-height:1.2">Tingkatan: ' + tingkatanLabel + '</div>' : '';
                        return '<div style="display:flex;flex-direction:column;align-items:center;gap:2px;padding:4px 6px;text-align:center">' + imgHtml + nameHtml + titleHtml + idHtml + tingkatanHtml + '</div>';
                    }
                },
                borderColor: 'white',
                nodeWidth: 90
            }],
            tooltip: {
                outside: true,
                useHTML: true,
                formatter: function () {
                    var p = this.point;
                    if (p.isNode) {
                        var html = '';
                        if (p.image) {
                            html += '<div style="margin-bottom:6px"><img src="' + p.image + '" style="width:52px;height:52px;object-fit:cover;border-radius:50%;border:2px solid rgba(0,0,0,.1)"/></div>';
                        }
                        html += '<div><strong>' + (p.name || '-') + '</strong></div>';
                        if (p.title) { html += '<div style="opacity:.8">' + p.title + '</div>'; }
                        if (typeof p.anggotaId !== 'undefined') { html += '<div>ID: ' + p.anggotaId + '</div>'; }
                        var tingkatanLabel = '';
                        if (p.tingkatanSebutan && p.tingkatanNama) {
                            tingkatanLabel = p.tingkatanSebutan + ' (' + p.tingkatanNama + ')';
                        } else if (p.tingkatanSebutan) {
                            tingkatanLabel = p.tingkatanSebutan;
                        } else if (p.tingkatanNama) {
                            tingkatanLabel = p.tingkatanNama;
                        }
                        if (tingkatanLabel) { html += '<div>Tingkatan: ' + tingkatanLabel + '</div>'; }
                        return html;
                    }
                    // For links, show simple from -> to
                    var from = (p.fromNode && p.fromNode.name) ? p.fromNode.name : (p.from || '');
                    var to = (p.toNode && p.toNode.name) ? p.toNode.name : (p.to || '');
                    return from + ' → ' + to;
                }
            },
            exporting: {
                allowHTML: true,
                sourceWidth: 800,
                sourceHeight: 600
            }
        });
    }

    function showError(msg) {
        if (!el) return;
        el.innerHTML = '<div style="padding:12px;color:#b00020;background:#fdecee;border:1px solid #f5c6cb;border-radius:6px">' +
            (msg || 'Gagal memuat data kepengurusan.') + '</div>';
    }

    // Load data from backend
    var params = new URLSearchParams();
    params.set('getOrganizationData', '1');
    if (CABANG_KEY) { params.set('CABANG_KEY', CABANG_KEY); }

    fetch(backendUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: params.toString()
    }).then(function (res) { return res.json(); })
      .then(function (json) {
          if (!json || json.status !== 'success') {
              showError(json && json.message ? json.message : 'Tidak ada data.');
              return;
          }
          renderChart(json);
      })
      .catch(function (err) {
          showError(err && err.message ? err.message : 'Terjadi kesalahan jaringan.');
      });
})();