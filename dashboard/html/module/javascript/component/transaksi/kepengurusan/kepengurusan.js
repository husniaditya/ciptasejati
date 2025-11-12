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

    // Inject minimal CSS to round built-in Highcharts node images (SVG <image>)
    function ensureRoundedImageStyle() {
        var styleId = 'orgchart-rounded-image-style';
        if (document.getElementById(styleId)) return;
        var css = '' +
            '.highcharts-organization-series .highcharts-point-image {' +
            '  clip-path: circle(50% at 50% 50%);' +
            '  -webkit-clip-path: circle(50% at 50% 50%);' +
            '  overflow: hidden;' +
            '  transform-box: fill-box;' +
            '  transform-origin: 50% 50%;' +
            '}' ;
        var styleTag = document.createElement('style');
        styleTag.id = styleId;
        styleTag.type = 'text/css';
        styleTag.appendChild(document.createTextNode(css));
        document.head.appendChild(styleTag);
    }

    // Force Highcharts built-in node images to be square and truly circular via clip-path
    function fixPointImages() {
        try {
            var container = document.getElementById(containerId);
            if (!container) return;
            var imgs = container.querySelectorAll('.highcharts-organization-series .highcharts-point-image');
            imgs.forEach(function (img) {
                // Read current size/position
                var widthAttr = parseFloat(img.getAttribute('width'));
                var heightAttr = parseFloat(img.getAttribute('height'));
                // Fallback to computed bbox when attributes are missing
                var bbox = (typeof img.getBBox === 'function') ? img.getBBox() : null;
                var w = isNaN(widthAttr) ? (bbox ? bbox.width : null) : widthAttr;
                var h = isNaN(heightAttr) ? (bbox ? bbox.height : null) : heightAttr;
                if (!w || !h) return;
                // Make square based on the smaller dimension, but cap to desired tooltip-like size
                var desired = 56; // match tooltip avatar size
                var size = Math.min(desired, w, h);
                var x = parseFloat(img.getAttribute('x')) || 0;
                var y = parseFloat(img.getAttribute('y')) || 0;
                var dx = (w - size) / 2;
                var dy = (h - size) / 2;
                img.setAttribute('width', size);
                img.setAttribute('height', size);
                img.setAttribute('x', (x + dx).toString());
                img.setAttribute('y', (y + dy).toString());
                // Ensure the internal bitmap doesn't stretch
                img.setAttribute('preserveAspectRatio', 'xMidYMid slice');
                // Re-apply circular clipping for safety
                img.style.clipPath = 'circle(50% at 50% 50%)';
                img.style.webkitClipPath = 'circle(50% at 50% 50%)';
                img.style.overflow = 'hidden';
                // Hide original image to prevent oval artifact; we'll draw an HTML overlay instead
                img.style.opacity = '0';
            });
        } catch (e) {
            // no-op: visual enhancement only
        }
    }

    // Overlay HTML avatars above the SVG images to avoid non-uniform SVG scaling (ensures true circle like tooltip)
    function overlayHtmlAvatars() {
        try {
            var host = document.getElementById(containerId);
            if (!host) return;
            var chartContainer = host.querySelector('.highcharts-container') || host;
            var containerRect = chartContainer.getBoundingClientRect();
            // Ensure overlay root
            var overlayRoot = chartContainer.querySelector('.orgchart-avatar-overlays');
            if (!overlayRoot) {
                overlayRoot = document.createElement('div');
                overlayRoot.className = 'orgchart-avatar-overlays';
                overlayRoot.style.position = 'absolute';
                overlayRoot.style.left = '0';
                overlayRoot.style.top = '0';
                overlayRoot.style.width = '100%';
                overlayRoot.style.height = '100%';
                overlayRoot.style.pointerEvents = 'none';
                overlayRoot.style.zIndex = '5';
                chartContainer.appendChild(overlayRoot);
            } else {
                overlayRoot.innerHTML = '';
            }

            var imgs = chartContainer.querySelectorAll('.highcharts-organization-series .highcharts-point-image');
            imgs.forEach(function (img) {
                var rect = img.getBoundingClientRect();
                var w = rect.width;
                var h = rect.height;
                if (!w || !h) return;
                var size = Math.min(w, h);
                var left = rect.left - containerRect.left + (w - size) / 2;
                var top = rect.top - containerRect.top + (h - size) / 2;
                var href = img.getAttribute('href') || img.getAttributeNS('http://www.w3.org/1999/xlink', 'href') || '';

                var wrap = document.createElement('div');
                wrap.style.position = 'absolute';
                wrap.style.left = left + 'px';
                wrap.style.top = top + 'px';
                wrap.style.width = size + 'px';
                wrap.style.height = size + 'px';
                wrap.style.borderRadius = '50%';
                wrap.style.boxShadow = '0 0 0 1px rgba(0,0,0,0.15)';
                wrap.style.overflow = 'hidden';

                var avatar = document.createElement('img');
                avatar.src = href;
                avatar.alt = '';
                avatar.style.width = '100%';
                avatar.style.height = '100%';
                avatar.style.objectFit = 'cover';
                avatar.style.borderRadius = '50%';
                avatar.style.border = '2px solid #ffffff';
                avatar.style.display = 'block';

                wrap.appendChild(avatar);
                overlayRoot.appendChild(wrap);
            });
        } catch (e) {
            // overlay is best-effort only
        }
    }

    function buildSeries(payload) {
        var nodes = [];
        var links = [];

    // HIERARCHY REQUESTED:
    // 1. Ketua
    // 2. Koordinator (group node)
    // 3.x Pengurus_# (each pengurus member enumerated)
    // Assumption: all pengurus belong to the single koordinator chain.

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

        // NEW HIERARCHY: 1) Ketua -> 2) Koordinator (group node) -> 3) Pengurus (enumerated)
        var koordinatorList = Array.isArray(payload.koordinator) ? payload.koordinator : [];
        var koordinatorGroupId = 'KOORDINATOR_GROUP';
        nodes.push({
            id: koordinatorGroupId,
            title: 'Koordinator',
            name: koordinatorList.length === 1 ? koordinatorList[0].ANGGOTA_NAMA : (koordinatorList.length + ' Koordinator'),
            image: (koordinatorList.length === 1 ? nonEmpty(koordinatorList[0].ANGGOTA_PIC, './assets/images/daftaranggota/default/avatar.png') : undefined),
            anggotaId: (koordinatorList.length === 1 ? koordinatorList[0].ANGGOTA_ID : '—'),
            tingkatanSebutan: (koordinatorList.length === 1 ? nonEmpty(koordinatorList[0].TINGKATAN_SEBUTAN, 'Koordinator') : 'Koordinator'),
            tingkatanNama: (koordinatorList.length === 1 ? nonEmpty(koordinatorList[0].TINGKATAN_NAMA, 'Koordinator') : 'Koordinator'),
            column: 1
        });
        if (ketuaId) { links.push([ketuaId, koordinatorGroupId]); }

        // Enumerated Pengurus under Koordinator group
        var pengurusList = Array.isArray(payload.pengurus) ? payload.pengurus : [];
        pengurusList.forEach(function (p, idx) {
            var pid = nid('PENGURUS', p.ANGGOTA_ID);
            nodes.push({
                id: pid,
                title: nonEmpty(p.ANGGOTA_AKSES, 'Pengurus_' + (idx + 1)),
                name: p.ANGGOTA_NAMA,
                image: nonEmpty(p.ANGGOTA_PIC, './assets/images/daftaranggota/default/avatar.png'),
                anggotaId: p.ANGGOTA_ID,
                tingkatanSebutan: nonEmpty(p.TINGKATAN_SEBUTAN, ''),
                tingkatanNama: nonEmpty(p.TINGKATAN_NAMA, ''),
                column: 2
            });
            links.push([koordinatorGroupId, pid]);
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
    ensureRoundedImageStyle();
        Highcharts.chart(containerId, {
            chart: {
                height: Math.max(420, 180 + series.nodes.length * 36),
                inverted: true,
                events: {
                    load: function () { fixPointImages(); overlayHtmlAvatars(); },
                    render: function () { fixPointImages(); overlayHtmlAvatars(); }
                }
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
                    level: 1, // Koordinator group
                    color: '#007ad0',
                    dataLabels: { color: 'white' },
                    height: 100
                }, {
                    level: 2, // Pengurus (enumerated)
                    color: '#359154',
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
                        // Show image using Highcharts built-in node image (front-left); avoid duplicating inside label
                        var imgHtml = '';
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
                        return '<div style="display:flex;flex-direction:column;align-items:center;gap:2px;padding:6px 6px;text-align:center">' + imgHtml + nameHtml + titleHtml + idHtml + tingkatanHtml + '</div>';
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
                            html += '<div style="margin-bottom:6px"><img src="' + p.image + '" style="width:56px;height:56px;object-fit:cover;border-radius:50%;border:2px solid #ffffff;box-shadow:0 0 0 1px rgba(0,0,0,.15);"/></div>';
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