<?php
// ── Live DB stats for landing page ──────────────────────────
$db_beneficiaries = 0;
$db_programs      = 0;
$db_partners      = 0;
$db_staff         = 0;
$db_activities    = 0;
$db_projects      = 0;

$dbPath = __DIR__ . '/db.php';
if (file_exists($dbPath)) {
    ob_start();
    include $dbPath;
    ob_end_clean();

    if (isset($conn) && $conn) {
        $queries = [
            'db_beneficiaries' => "SELECT COUNT(*) AS c FROM beneficiaries",
            'db_programs'      => "SELECT COUNT(*) AS c FROM programs",
            'db_partners'      => "SELECT COUNT(*) AS c FROM partners",
            'db_staff'         => "SELECT COUNT(*) AS c FROM users",
            'db_activities'    => "SELECT COUNT(*) AS c FROM activities",
            'db_projects'      => "SELECT COUNT(*) AS c FROM projects",
        ];
        foreach ($queries as $var => $sql) {
            try {
                $r = $conn->query($sql);
                if ($r) $$var = (int)$r->fetch_assoc()['c'];
            } catch (Throwable $e) {}
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>CEMS — Community Extension Management System</title>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
<style>
:root {
  --green:  #1DB954;
  --green2: #16a34a;
  --purple: #8B2FC9;
  --pink:   #E91E8C;
  --yellow: #F5A623;
  --red:    #E32636;
  --blue:   #1565C0;
  --bg:     #edfaf3;
  --card:   #f5fdf8;
  --dark:   #0a2e1a;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

html { scroll-behavior: smooth; }

body {
  font-family: 'Nunito', sans-serif;
  background: var(--bg);
  color: var(--dark);
  overflow-x: hidden;
}

/* ── NAV ── */
nav {
  position: fixed;
  top: 0; left: 0; right: 0;
  z-index: 100;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 5vw;
  background: rgba(237,250,243,0.85);
  backdrop-filter: blur(16px);
  border-bottom: 1px solid rgba(29,185,84,0.12);
}
.nav-logo {
  display: flex;
  align-items: center;
  gap: 10px;
  text-decoration: none;
}
.nav-logo img { width: 44px; height: 44px; object-fit: contain; }
.nav-logo-text {
  font-size: 1.2rem;
  font-weight: 900;
  color: var(--dark);
  letter-spacing: 0.04em;
}
.nav-logo-text span { color: var(--green); }
.nav-links {
  display: flex;
  gap: 2rem;
  list-style: none;
}
.nav-links a {
  font-size: 0.875rem;
  font-weight: 700;
  color: #555;
  text-decoration: none;
  letter-spacing: 0.03em;
  transition: color 0.2s;
  position: relative;
}
.nav-links a::after {
  content: '';
  position: absolute;
  bottom: -4px; left: 0; right: 0;
  height: 2px;
  background: var(--green);
  transform: scaleX(0);
  transition: transform 0.2s;
}
.nav-links a:hover { color: var(--green2); }
.nav-links a:hover::after { transform: scaleX(1); }
.nav-cta {
  background: var(--green);
  color: white;
  border: none;
  border-radius: 50px;
  padding: 0.6rem 1.5rem;
  font-size: 0.875rem;
  font-weight: 800;
  cursor: pointer;
  text-decoration: none;
  transition: all 0.2s;
  box-shadow: 0 4px 16px rgba(29,185,84,0.3);
}
.nav-cta:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 24px rgba(29,185,84,0.4);
  background: var(--green2);
}

/* ── HERO ── */
.hero {
  min-height: 100vh;
  padding: 8rem 5vw 4rem;
  display: grid;
  grid-template-columns: 1fr 1fr;
  align-items: center;
  gap: 3rem;
  position: relative;
  overflow: hidden;
}

/* Blob background like reference */
.hero-blob {
  position: absolute;
  border-radius: 50%;
  filter: blur(0);
  z-index: 0;
}
.blob-main {
  width: 560px; height: 560px;
  background: rgba(29,185,84,0.13);
  right: -60px; top: 50%;
  transform: translateY(-50%);
  border-radius: 60% 40% 55% 45% / 50% 60% 40% 50%;
  animation: morphBlob 8s ease-in-out infinite;
}
.blob-sm1 {
  width: 200px; height: 200px;
  background: rgba(245,166,35,0.2);
  right: 80px; top: 80px;
  border-radius: 50%;
  animation: floatBall 5s ease-in-out infinite;
}
.blob-sm2 {
  width: 140px; height: 140px;
  background: rgba(233,30,140,0.18);
  right: 460px; bottom: 80px;
  border-radius: 50%;
  animation: floatBall 7s ease-in-out infinite reverse;
}
.blob-sm3 {
  width: 100px; height: 100px;
  background: rgba(139,47,201,0.15);
  left: 5vw; top: 140px;
  border-radius: 50%;
  animation: floatBall 6s ease-in-out infinite 1s;
}
@keyframes morphBlob {
  0%,100% { border-radius: 60% 40% 55% 45% / 50% 60% 40% 50%; }
  33%      { border-radius: 40% 60% 45% 55% / 60% 40% 60% 40%; }
  66%      { border-radius: 55% 45% 60% 40% / 40% 55% 45% 60%; }
}
@keyframes floatBall {
  0%,100% { transform: translateY(0) scale(1); }
  50%      { transform: translateY(-20px) scale(1.06); }
}

.hero-left { position: relative; z-index: 1; }

.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(29,185,84,0.1);
  border: 1px solid rgba(29,185,84,0.25);
  border-radius: 50px;
  padding: 0.4rem 1rem;
  font-size: 0.75rem;
  font-weight: 800;
  color: var(--green2);
  letter-spacing: 0.1em;
  text-transform: uppercase;
  margin-bottom: 1.5rem;
  animation: fadeUp 0.6s ease both;
}
.hero-badge span {
  width: 8px; height: 8px;
  border-radius: 50%;
  background: var(--green);
  animation: pulse 2s ease infinite;
}
@keyframes pulse {
  0%,100% { box-shadow: 0 0 0 0 rgba(29,185,84,0.5); }
  50%      { box-shadow: 0 0 0 6px rgba(29,185,84,0); }
}

.hero-title {
  font-family: 'Playfair Display', serif;
  font-size: clamp(2.4rem, 4.5vw, 3.8rem);
  font-weight: 800;
  line-height: 1.15;
  color: var(--dark);
  margin-bottom: 1.2rem;
  animation: fadeUp 0.6s ease 0.1s both;
}
.hero-title .highlight {
  display: inline-block;
  background: var(--green);
  color: white;
  padding: 0 0.3em;
  border-radius: 6px;
  margin: 0 2px;
}
.hero-title .accent { color: var(--green); }

.hero-desc {
  font-size: 1rem;
  color: #5a7a68;
  line-height: 1.75;
  max-width: 480px;
  margin-bottom: 2rem;
  font-weight: 600;
  animation: fadeUp 0.6s ease 0.2s both;
}

.hero-actions {
  display: flex;
  gap: 1rem;
  align-items: center;
  flex-wrap: wrap;
  animation: fadeUp 0.6s ease 0.3s both;
}
.btn-hero-main {
  background: var(--green);
  color: white;
  border: none;
  border-radius: 50px;
  padding: 0.85rem 2rem;
  font-size: 0.95rem;
  font-weight: 800;
  cursor: pointer;
  text-decoration: none;
  transition: all 0.2s;
  box-shadow: 0 6px 24px rgba(29,185,84,0.35);
  letter-spacing: 0.02em;
}
.btn-hero-main:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 32px rgba(29,185,84,0.45);
}
.btn-hero-ghost {
  color: var(--dark);
  font-size: 0.9rem;
  font-weight: 700;
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 6px;
  transition: gap 0.2s;
}
.btn-hero-ghost:hover { gap: 10px; color: var(--green); }

.hero-stats {
  display: flex;
  gap: 2rem;
  margin-top: 2.5rem;
  animation: fadeUp 0.6s ease 0.4s both;
}
.stat { border-left: 3px solid var(--green); padding-left: 0.8rem; }
.stat-num {
  font-family: 'Playfair Display', serif;
  font-size: 1.6rem;
  font-weight: 800;
  color: var(--dark);
  line-height: 1;
}
.stat-label { font-size: 0.72rem; color: #7a9a88; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; }

/* Hero right — logo showcase */
.hero-right {
  position: relative;
  z-index: 1;
  display: flex;
  align-items: center;
  justify-content: center;
}
.logo-ring-wrap {
  position: relative;
  width: 420px;
  height: 420px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.logo-ring-bg {
  position: absolute;
  border-radius: 50%;
  border: 2px solid;
  animation: spinRing 20s linear infinite;
}
.ring-a {
  width: 420px; height: 420px;
  border-color: rgba(29,185,84,0.15);
  animation-duration: 25s;
}
.ring-b {
  width: 340px; height: 340px;
  border-color: rgba(29,185,84,0.2);
  animation-direction: reverse;
  animation-duration: 18s;
}
.ring-c {
  width: 260px; height: 260px;
  border-color: rgba(29,185,84,0.25);
  animation-duration: 12s;
}
@keyframes spinRing {
  from { transform: rotate(0deg); }
  to   { transform: rotate(360deg); }
}

/* Colored dots on ring */
.ring-dot {
  position: absolute;
  width: 14px; height: 14px;
  border-radius: 50%;
  border: 3px solid white;
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.hero-logo-img {
  width: 240px; height: 240px;
  object-fit: contain;
  position: relative;
  z-index: 2;
  filter: drop-shadow(0 12px 40px rgba(29,185,84,0.25));
  animation: logoPulse 5s ease-in-out infinite;
}
@keyframes logoPulse {
  0%,100% { transform: scale(1) rotate(-1deg); }
  50%      { transform: scale(1.04) rotate(1deg); }
}

/* Color orbit pills */
.orbit-pill {
  position: absolute;
  display: flex;
  align-items: center;
  gap: 6px;
  background: white;
  border-radius: 50px;
  padding: 0.4rem 0.8rem;
  font-size: 0.72rem;
  font-weight: 800;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  white-space: nowrap;
  z-index: 3;
  animation: floatBall 4s ease-in-out infinite;
}
.orbit-pill .dot {
  width: 10px; height: 10px;
  border-radius: 50%;
  flex-shrink: 0;
}

/* ── SECTION SHARED ── */
section {
  padding: 5rem 5vw;
  position: relative;
}
.section-label {
  display: inline-block;
  font-size: 0.7rem;
  font-weight: 800;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  color: var(--green2);
  margin-bottom: 0.6rem;
}
.section-title {
  font-family: 'Playfair Display', serif;
  font-size: clamp(1.8rem, 3vw, 2.6rem);
  font-weight: 800;
  color: var(--dark);
  line-height: 1.2;
  margin-bottom: 1rem;
}
.section-desc {
  font-size: 0.95rem;
  color: #5a7a68;
  font-weight: 600;
  max-width: 520px;
  line-height: 1.7;
}

/* ── FEATURES ── */
#features { background: white; }
.features-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 1.5rem;
  margin-top: 3rem;
}
.feature-card {
  background: var(--card);
  border-radius: 20px;
  padding: 2rem;
  border: 1.5px solid rgba(29,185,84,0.1);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}
.feature-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0;
  width: 100%; height: 4px;
  background: var(--accent-color, var(--green));
  border-radius: 20px 20px 0 0;
}
.feature-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 16px 48px rgba(0,0,0,0.08);
  border-color: var(--accent-color, var(--green));
}
.feature-icon {
  width: 52px; height: 52px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  margin-bottom: 1.2rem;
  color: white;
}
.feature-card h3 {
  font-size: 1rem;
  font-weight: 800;
  color: var(--dark);
  margin-bottom: 0.5rem;
}
.feature-card p {
  font-size: 0.85rem;
  color: #7a9a88;
  line-height: 1.65;
  font-weight: 600;
}

/* ── HOW IT WORKS ── */
#how { background: var(--bg); }
.steps-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 2rem;
  margin-top: 3rem;
  position: relative;
}
.steps-row::before {
  content: '';
  position: absolute;
  top: 36px; left: 10%; right: 10%;
  height: 2px;
  background: linear-gradient(90deg, var(--green), var(--yellow), var(--pink), var(--purple));
  border-radius: 2px;
  z-index: 0;
}
.step {
  text-align: center;
  position: relative;
  z-index: 1;
}
.step-num {
  width: 72px; height: 72px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Playfair Display', serif;
  font-size: 1.5rem;
  font-weight: 800;
  color: white;
  margin: 0 auto 1.2rem;
  box-shadow: 0 8px 24px rgba(0,0,0,0.15);
  border: 4px solid white;
}
.step h4 {
  font-size: 0.95rem;
  font-weight: 800;
  color: var(--dark);
  margin-bottom: 0.4rem;
}
.step p {
  font-size: 0.82rem;
  color: #7a9a88;
  font-weight: 600;
  line-height: 1.6;
}

/* ── IMPACT / STATS ── */
#impact {
  background: var(--dark);
  color: white;
}
#impact .section-title { color: white; }
#impact .section-desc { color: rgba(255,255,255,0.6); }
.impact-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 2rem;
  margin-top: 3rem;
}
.impact-card {
  background: rgba(255,255,255,0.06);
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 20px;
  padding: 2rem;
  text-align: center;
  transition: all 0.3s;
}
.impact-card:hover {
  background: rgba(255,255,255,0.1);
  transform: translateY(-4px);
}
.impact-num {
  font-family: 'Playfair Display', serif;
  font-size: 2.8rem;
  font-weight: 800;
  line-height: 1;
  margin-bottom: 0.4rem;
}
.impact-label {
  font-size: 0.82rem;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  opacity: 0.6;
}

/* ── PROGRAMS ── */
#programs { background: white; }
.programs-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 1.5rem;
  margin-top: 3rem;
}
.program-card {
  border-radius: 20px;
  padding: 2rem 1.5rem;
  color: white;
  position: relative;
  overflow: hidden;
  transition: all 0.3s;
  cursor: pointer;
}
.program-card::after {
  content: '';
  position: absolute;
  bottom: -40px; right: -40px;
  width: 140px; height: 140px;
  border-radius: 50%;
  background: rgba(255,255,255,0.12);
}
.program-card:hover {
  transform: translateY(-6px) scale(1.02);
  box-shadow: 0 20px 48px rgba(0,0,0,0.2);
}
.program-card i {
  font-size: 2rem;
  margin-bottom: 1rem;
  display: block;
}
.program-card h4 {
  font-size: 1rem;
  font-weight: 800;
  margin-bottom: 0.4rem;
}
.program-card p {
  font-size: 0.8rem;
  opacity: 0.85;
  font-weight: 600;
  line-height: 1.5;
}

/* ── CTA ── */
#cta {
  background: linear-gradient(135deg, #e8f8f0, #d4f3e4);
  text-align: center;
}
.cta-inner {
  max-width: 640px;
  margin: 0 auto;
}
.cta-inner .section-title { font-size: 2.2rem; margin-bottom: 1rem; }
.cta-inner .section-desc { margin: 0 auto 2rem; text-align: center; }
.cta-btns {
  display: flex;
  justify-content: center;
  gap: 1rem;
  flex-wrap: wrap;
}
.btn-cta-main {
  background: var(--green);
  color: white;
  border: none;
  border-radius: 50px;
  padding: 0.9rem 2.4rem;
  font-size: 1rem;
  font-weight: 800;
  cursor: pointer;
  text-decoration: none;
  transition: all 0.2s;
  box-shadow: 0 6px 24px rgba(29,185,84,0.35);
}
.btn-cta-main:hover { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(29,185,84,0.45); }
.btn-cta-sec {
  border: 2px solid var(--dark);
  color: var(--dark);
  background: transparent;
  border-radius: 50px;
  padding: 0.9rem 2.4rem;
  font-size: 1rem;
  font-weight: 800;
  cursor: pointer;
  text-decoration: none;
  transition: all 0.2s;
}
.btn-cta-sec:hover { background: var(--dark); color: white; }

/* ── FOOTER ── */
footer {
  background: var(--dark);
  color: rgba(255,255,255,0.6);
  padding: 2.5rem 5vw;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  font-size: 0.82rem;
  font-weight: 600;
  gap: 1rem;
}
footer .footer-logo {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  color: white;
  font-weight: 800;
  font-size: 1rem;
}
footer .footer-logo img { width: 32px; height: 32px; object-fit: contain; }
.footer-copyright { color: rgba(255,255,255,0.4); font-size: 0.78rem; }
.footer-colors {
  display: flex;
  justify-content: center;
  gap: 6px;
}
.footer-colors span {
  width: 10px; height: 10px;
  border-radius: 50%;
}

/* ── Animations ── */
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0); }
}

.reveal {
  opacity: 0;
  transform: translateY(30px);
  transition: opacity 0.6s ease, transform 0.6s ease;
}
.reveal.visible {
  opacity: 1;
  transform: translateY(0);
}

/* Dash decorations */
.dash {
  display: inline-block;
  width: 36px; height: 3px;
  background: var(--green);
  border-radius: 2px;
  margin: 0 8px;
  vertical-align: middle;
}

/* Responsive */
@media (max-width: 768px) {
  .hero { grid-template-columns: 1fr; padding-top: 6rem; text-align: center; }
  .hero-right { display: none; }
  .hero-stats { justify-content: center; }
  .hero-actions { justify-content: center; }
  .hero-desc { margin-left: auto; margin-right: auto; }
  .steps-row::before { display: none; }
  .nav-links { display: none; }
}
</style>
</head>
<body>

<!-- NAV -->
<nav>
  <a href="#" class="nav-logo">
    <img src="./img/Community Logo.png" alt="CEMS">
    <div class="nav-logo-text"><span>CEMS</span></div>
  </a>
  <ul class="nav-links">
    <li><a href="#features">Features</a></li>
    <li><a href="#how">How It Works</a></li>
    <li><a href="#programs">Programs</a></li>
    <li><a href="#impact">Impact</a></li>
  </ul>
  <a href="login.php" class="nav-cta">Get Started →</a>
</nav>

<!-- HERO -->
<section class="hero" id="home">
  <!-- BG Blobs -->
  <div class="hero-blob blob-main"></div>
  <div class="hero-blob blob-sm1"></div>
  <div class="hero-blob blob-sm2"></div>
  <div class="hero-blob blob-sm3"></div>

  <!-- Left -->
  <div class="hero-left">
    <div class="hero-badge">
      <span></span>
      Community Extension Management
    </div>

    <h1 class="hero-title">
      Building Stronger<br>
      <span class="highlight">Communities</span><br>
      <span class="accent">Together.</span>
    </h1>

    <p class="hero-desc">
      CEMS empowers organizations to plan, track, and evaluate community extension programs — connecting beneficiaries, partners, volunteers, and resources in one powerful platform.
    </p>

    <div class="hero-actions">
      <a href="login.php" class="btn-hero-main">Get Started Free</a>
      <a href="#features" class="btn-hero-ghost">Explore Features <i class="ri-arrow-right-line"></i></a>
    </div>

    <div class="hero-stats">
      <div class="stat">
        <div class="stat-num"><?php echo $db_beneficiaries ?: '0'; ?>+</div>
        <div class="stat-label">Beneficiaries</div>
      </div>
      <div class="stat">
        <div class="stat-num"><?php echo $db_programs ?: '0'; ?>+</div>
        <div class="stat-label">Programs</div>
      </div>
      <div class="stat">
        <div class="stat-num"><?php echo $db_partners ?: '0'; ?>+</div>
        <div class="stat-label">Partners</div>
      </div>
    </div>
  </div>

  <!-- Right: Logo showcase -->
  <div class="hero-right">
    <div class="logo-ring-wrap">
      <!-- Spinning rings -->
      <div class="logo-ring-bg ring-a"></div>
      <div class="logo-ring-bg ring-b"></div>
      <div class="logo-ring-bg ring-c"></div>

      <!-- Colored ring dots -->
      <div class="ring-dot" style="background:var(--purple);top:8px;left:50%;transform:translateX(-50%);"></div>
      <div class="ring-dot" style="background:var(--pink);bottom:8px;left:50%;transform:translateX(-50%);"></div>
      <div class="ring-dot" style="background:var(--yellow);top:50%;right:8px;transform:translateY(-50%);"></div>
      <div class="ring-dot" style="background:var(--blue);top:50%;left:8px;transform:translateY(-50%);"></div>
      <div class="ring-dot" style="background:var(--red);top:18%;left:10%;"></div>
      <div class="ring-dot" style="background:var(--green);bottom:18%;right:10%;"></div>

      <!-- Main logo -->
      <img src="./img/Community Logo.png" alt="CEMS Logo" class="hero-logo-img">

      <!-- Floating info pills -->
      <div class="orbit-pill" style="top:30px;left:-30px;animation-delay:0s;">
        <div class="dot" style="background:var(--purple);"></div>
        Program Tracking
      </div>
      <div class="orbit-pill" style="bottom:50px;right:-40px;animation-delay:1.5s;">
        <div class="dot" style="background:var(--blue);"></div>
        Resource Management
      </div>
      <div class="orbit-pill" style="bottom:130px;left:-50px;animation-delay:0.8s;">
        <div class="dot" style="background:var(--pink);"></div>
        Impact Analytics
      </div>
    </div>
  </div>
</section>

<!-- FEATURES -->
<section id="features">
  <div class="reveal">
    <div class="section-label">What We Offer</div>
    <h2 class="section-title">Everything You Need to<br>Manage Community Programs</h2>
    <p class="section-desc">From planning to evaluation, CEMS covers the full lifecycle of community extension work.</p>
  </div>

  <div class="features-grid">
    <div class="feature-card reveal" style="--accent-color:var(--green);" >
      <div class="feature-icon" style="background:var(--green);">
        <i class="ri-user-heart-line"></i>
      </div>
      <h3>Beneficiary Management</h3>
      <p>Track, profile, and monitor all community beneficiaries with comprehensive records and progress history.</p>
    </div>
    <div class="feature-card reveal" style="--accent-color:var(--purple);">
      <div class="feature-icon" style="background:var(--purple);">
        <i class="ri-projector-2-line"></i>
      </div>
      <h3>Program & Project Tracking</h3>
      <p>Plan, execute, and monitor programs and projects with timelines, budgets, and milestone tracking.</p>
    </div>
    <div class="feature-card reveal" style="--accent-color:var(--blue);">
      <div class="feature-icon" style="background:var(--blue);">
        <i class="ri-team-line"></i>
      </div>
      <h3>Staff & Volunteer Management</h3>
      <p>Coordinate coordinators, staff, and volunteers with role assignments and performance metrics.</p>
    </div>
    <div class="feature-card reveal" style="--accent-color:var(--yellow);">
      <div class="feature-icon" style="background:var(--yellow);">
        <i class="ri-hand-heart-line"></i>
      </div>
      <h3>Partner & Donor Tracking</h3>
      <p>Manage organizational partners and donors with contribution records and relationship history.</p>
    </div>
    <div class="feature-card reveal" style="--accent-color:var(--pink);">
      <div class="feature-icon" style="background:var(--pink);">
        <i class="ri-survey-line"></i>
      </div>
      <h3>Evaluation & Monitoring</h3>
      <p>Assess program impact with surveys, needs assessments, and real-time progress monitoring tools.</p>
    </div>
    <div class="feature-card reveal" style="--accent-color:var(--red);">
      <div class="feature-icon" style="background:var(--red);">
        <i class="ri-bar-chart-grouped-line"></i>
      </div>
      <h3>Reports & Analytics</h3>
      <p>Generate insightful reports and visualize your community impact with powerful analytics dashboards.</p>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section id="how">
  <div style="text-align:center;" class="reveal">
    <div class="section-label">Simple Process</div>
    <h2 class="section-title">How CEMS Works</h2>
    <p class="section-desc" style="margin:0 auto;">Get your community extension programs running in four easy steps.</p>
  </div>

  <div class="steps-row">
    <div class="step reveal">
      <div class="step-num" style="background:var(--green);">1</div>
      <h4>Register & Setup</h4>
      <p>Create your organization account and configure your programs and team structure.</p>
    </div>
    <div class="step reveal">
      <div class="step-num" style="background:var(--yellow);">2</div>
      <h4>Add Beneficiaries</h4>
      <p>Enroll community members and assign them to relevant programs and activities.</p>
    </div>
    <div class="step reveal">
      <div class="step-num" style="background:var(--pink);">3</div>
      <h4>Run Programs</h4>
      <p>Execute activities, track resources, and coordinate your staff and volunteers in real time.</p>
    </div>
    <div class="step reveal">
      <div class="step-num" style="background:var(--purple);">4</div>
      <h4>Evaluate Impact</h4>
      <p>Measure outcomes, generate reports, and share your community impact with stakeholders.</p>
    </div>
  </div>
</section>

<!-- PROGRAMS -->
<section id="programs">
  <div class="reveal">
    <div class="section-label">Our Focus Areas</div>
    <h2 class="section-title">Community Programs We Support</h2>
    <p class="section-desc">CEMS supports a wide range of community extension initiatives across multiple sectors.</p>
  </div>

  <div class="programs-grid">
    <div class="program-card reveal" style="background:linear-gradient(135deg,#1DB954,#16a34a);">
      <i class="ri-heart-pulse-line"></i>
      <h4>Health & Wellness</h4>
      <p>Medical outreach, health screenings, and wellness programs for communities.</p>
    </div>
    <div class="program-card reveal" style="background:linear-gradient(135deg,#8B2FC9,#6d24a8);">
      <i class="ri-graduation-cap-line"></i>
      <h4>Education & Literacy</h4>
      <p>Scholarship programs, tutoring, and adult literacy campaigns.</p>
    </div>
    <div class="program-card reveal" style="background:linear-gradient(135deg,#F5A623,#e08c0a);">
      <i class="ri-plant-line"></i>
      <h4>Livelihood & Skills</h4>
      <p>Vocational training, microfinance, and skills development workshops.</p>
    </div>
    <div class="program-card reveal" style="background:linear-gradient(135deg,#E91E8C,#c4176f);">
      <i class="ri-leaf-line"></i>
      <h4>Environment</h4>
      <p>Tree planting, waste management, and environmental awareness campaigns.</p>
    </div>
    <div class="program-card reveal" style="background:linear-gradient(135deg,#1565C0,#0d47a1);">
      <i class="ri-building-2-line"></i>
      <h4>Infrastructure</h4>
      <p>Community facility improvements, housing support, and public works.</p>
    </div>
    <div class="program-card reveal" style="background:linear-gradient(135deg,#E32636,#b81c28);">
      <i class="ri-shield-user-line"></i>
      <h4>Social Services</h4>
      <p>Disaster relief, elder care, youth development, and family support programs.</p>
    </div>
  </div>
</section>

<!-- IMPACT -->
<section id="impact">
  <div style="text-align:center;" class="reveal">
    <div class="section-label" style="color:rgba(255,255,255,0.5);">Our Impact</div>
    <h2 class="section-title">Making a Real Difference</h2>
    <p class="section-desc" style="margin:0 auto;">Numbers that reflect our commitment to community empowerment.</p>
  </div>

  <div class="impact-grid">
    <div class="impact-card reveal">
      <div class="impact-num" style="color:var(--green);"><?php echo $db_beneficiaries; ?>+</div>
      <div class="impact-label">Beneficiaries Served</div>
    </div>
    <div class="impact-card reveal">
      <div class="impact-num" style="color:var(--yellow);"><?php echo $db_programs; ?>+</div>
      <div class="impact-label">Active Programs</div>
    </div>
    <div class="impact-card reveal">
      <div class="impact-num" style="color:var(--pink);"><?php echo $db_staff; ?>+</div>
      <div class="impact-label">Team Members</div>
    </div>
    <div class="impact-card reveal">
      <div class="impact-num" style="color:var(--purple);"><?php echo $db_partners; ?>+</div>
      <div class="impact-label">Partner Organizations</div>
    </div>
  </div>
</section>

<!-- CTA -->
<section id="cta">
  <div class="cta-inner reveal">
    <div class="section-label">Ready to Start?</div>
    <h2 class="section-title">Join the Community<br>Extension Movement</h2>
    <p class="section-desc">Start managing your programs more effectively today. Free to get started, powerful at scale.</p>
    <div class="cta-btns">
      <a href="login.php" class="btn-cta-main">Sign In to Dashboard</a>
      <a href="register.php" class="btn-cta-sec">Create Free Account</a>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-logo">
    <img src="./img/Community Logo.png" alt="CEMS">
    CEMS — Community Extension Management System
  </div>
</footer>

<script>
// Scroll reveal
const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry, i) => {
    if (entry.isIntersecting) {
      setTimeout(() => {
        entry.target.classList.add('visible');
      }, 80 * (Array.from(entry.target.parentElement?.children || []).indexOf(entry.target)));
      observer.unobserve(entry.target);
    }
  });
}, { threshold: 0.1 });

document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

// Animated counters
function animateCount(el, target, suffix='') {
  let start = 0;
  const duration = 1800;
  const step = (timestamp) => {
    if (!start) start = timestamp;
    const progress = Math.min((timestamp - start) / duration, 1);
    const eased = 1 - Math.pow(1 - progress, 3);
    el.textContent = Math.floor(eased * target).toLocaleString() + suffix;
    if (progress < 1) requestAnimationFrame(step);
  };
  requestAnimationFrame(step);
}

const counterObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const el = entry.target;
      const text = el.textContent;
      const num = parseInt(text.replace(/[^0-9]/g, ''));
      const suffix = text.includes('+') ? '+' : '';
      animateCount(el, num, suffix);
      counterObserver.unobserve(el);
    }
  });
}, { threshold: 0.5 });

document.querySelectorAll('.impact-num').forEach(el => counterObserver.observe(el));

// Smooth nav active state
const sections = document.querySelectorAll('section[id]');
window.addEventListener('scroll', () => {
  const scrollY = window.pageYOffset;
  sections.forEach(sec => {
    const top = sec.offsetTop - 80;
    const h = sec.offsetHeight;
    const id = sec.getAttribute('id');
    const link = document.querySelector(`.nav-links a[href="#${id}"]`);
    if (scrollY >= top && scrollY < top + h) {
      document.querySelectorAll('.nav-links a').forEach(a => a.style.color = '');
      if (link) link.style.color = 'var(--green)';
    }
  });
});
</script>

</body>
</html>