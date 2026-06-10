<?php
$title = 'Zeta';
$theme = 'dark';
$bodyClass = 'public-page';

require BASE_PATH . '/app/Views/layouts/public-header.php';
?>

<main class="landing-stage">
    <section class="container hero-frame">
        <div class="browser-bar" aria-hidden="true">
            <span></span><span></span><span></span>
            <div class="browser-address">https://zeta.local</div>
        </div>

        <div class="hero">
            <div class="hero-copy">
                <p class="hero-kicker">Infrastructure Consultation Platform</p>
                <h1 class="hero-title">Arsitektur teknologi yang siap diputuskan, bukan ditebak.</h1>
                <p class="hero-description">
                    Pilih layanan, validasi pembayaran, lalu konsultasikan network, database, server, dan virtualisasi dalam pipeline yang rapi.
                </p>
                <form class="hero-search" action="<?= e(url('/services')) ?>" method="get">
                    <span>AI</span>
                    <input aria-label="Cari kebutuhan konsultasi" placeholder="Cari layanan network, database, server">
                    <button class="btn btn-primary" type="submit">Explore</button>
                </form>
            </div>

            <div class="hero-visual" aria-hidden="true">
                <div class="tech-orbit tech-orbit-one"></div>
                <div class="tech-orbit tech-orbit-two"></div>
                <div class="tech-core"></div>
                <div class="tech-pipe pipe-a"></div>
                <div class="tech-pipe pipe-b"></div>
                <div class="tech-chip chip-a"></div>
                <div class="tech-chip chip-b"></div>
                <div class="hero-note">Ready for paid consultation?</div>
            </div>
        </div>

        <div class="hero-proof">
            <div>
                <strong>"Terarah"</strong>
                <p>Pipeline menjaga payment, chat, dan admin assignment tetap jelas.</p>
            </div>
            <div class="proof-logos">
                <span>Network</span>
                <span>Database</span>
                <span>Server</span>
            </div>
        </div>
    </section>

    <section class="container public-section">
        <div class="section-heading">
            <h2 class="section-title">Layanan Utama</h2>
            <p class="section-copy">Pilih area yang paling dekat dengan masalah teknis Anda. Setiap konsultasi memakai harga snapshot saat transaksi dibuat.</p>
        </div>
        <div class="service-grid">
            <article class="glass-card">
                <p class="service-card-meta">01 / Network</p>
                <h3>Network Architecture</h3>
                <p>Konsultasi desain jaringan kantor, VLAN, dan segmentasi.</p>
                <a class="btn btn-secondary" href="<?= e(url('/services')) ?>">Eksplorasi</a>
            </article>
            <article class="glass-card">
                <p class="service-card-meta">02 / Database</p>
                <h3>Database Architecture</h3>
                <p>Review schema, performa query, backup, dan strategi replikasi.</p>
                <a class="btn btn-secondary" href="<?= e(url('/services')) ?>">Eksplorasi</a>
            </article>
            <article class="glass-card">
                <p class="service-card-meta">03 / Server</p>
                <h3>Web Server & Virtualization</h3>
                <p>Review deployment web server, virtualisasi, dan hardening awal.</p>
                <a class="btn btn-secondary" href="<?= e(url('/services')) ?>">Eksplorasi</a>
            </article>
        </div>
    </section>

    <section class="container public-section">
        <div class="section-heading">
            <h2 class="section-title">Kompetensi Konsultan</h2>
            <p class="section-copy">Admin bekerja berdasarkan assignment layanan, sehingga pipeline konsultasi tetap terarah dan tidak tercampur antar domain.</p>
        </div>
        <div class="consultant-grid">
            <article class="glass-card">
                <h3>Network Specialist</h3>
                <p>Topologi jaringan, segmentasi, routing dasar, dan evaluasi keamanan jaringan internal.</p>
            </article>
            <article class="glass-card">
                <h3>Database Specialist</h3>
                <p>Struktur schema, indexing, backup, optimasi query, dan strategi pertumbuhan data.</p>
            </article>
            <article class="glass-card">
                <h3>Server Specialist</h3>
                <p>Deployment web server, virtualisasi, isolasi layanan, dan hardening awal.</p>
            </article>
        </div>
    </section>

    <section class="container public-section">
        <div class="section-heading">
            <h2 class="section-title">Pricing Preview</h2>
            <p class="section-copy">Harga final mengikuti sublayanan yang dipilih. Detail lengkap tersedia pada halaman pricing.</p>
        </div>
        <div class="pricing-preview-grid">
            <article class="glass-card">
                <p class="service-card-meta">Mulai dari</p>
                <h3>Network Review</h3>
                <p class="price-tag">Rp 250.000</p>
            </article>
            <article class="glass-card">
                <p class="service-card-meta">Mulai dari</p>
                <h3>Database Review</h3>
                <p class="price-tag">Rp 275.000</p>
            </article>
            <article class="glass-card">
                <p class="service-card-meta">Mulai dari</p>
                <h3>Server Review</h3>
                <p class="price-tag">Rp 350.000</p>
            </article>
        </div>
        <div style="margin-top: var(--space-5);">
            <a class="btn btn-primary" href="<?= e(url('/pricing')) ?>">Lihat Semua Harga</a>
        </div>
    </section>

    <section class="container public-section">
        <div class="section-heading">
            <h2 class="section-title">Alur Konsultasi</h2>
            <p class="section-copy">Flow dibuat sederhana supaya MVP tetap mudah diuji dari lokal sampai nanti siap dipindahkan ke server.</p>
        </div>
        <div class="flow-grid">
            <article class="glass-card flow-step">
                <span class="flow-number">1</span>
                <h3>Pilih Layanan</h3>
                <p>Pengguna memilih sublayanan sesuai masalah teknis yang ingin dibahas.</p>
            </article>
            <article class="glass-card flow-step">
                <span class="flow-number">2</span>
                <h3>Bayar via Midtrans</h3>
                <p>Payment dibuat dari backend, lalu status valid diproses melalui webhook atau refresh backend.</p>
            </article>
            <article class="glass-card flow-step">
                <span class="flow-number">3</span>
                <h3>Chat Konsultasi</h3>
                <p>Chat terbuka setelah payment paid dan consultation aktif. Setelah closed, chat menjadi read-only.</p>
            </article>
        </div>
    </section>

    <section class="container public-section">
        <div class="cta-band">
            <div>
                <p class="hero-kicker">Siap mulai?</p>
                <h2 class="section-title">Mulai dari layanan yang paling relevan dengan infrastruktur Anda.</h2>
            </div>
            <a class="btn btn-primary" href="<?= e(url('/register')) ?>">Register</a>
        </div>
    </section>
</main>

<?php require BASE_PATH . '/app/Views/layouts/public-footer.php'; ?>
