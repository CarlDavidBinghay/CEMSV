<?php
session_start();

if(isset($_SESSION['user_id'])){
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<title>CEMS - Sign Up</title>
<style>
    * { font-family: 'Poppins', sans-serif; }

    :root {
        --green:  #1DB954;
        --purple: #8B2FC9;
        --pink:   #E91E8C;
        --yellow: #F5A623;
        --red:    #E32636;
        --blue:   #1565C0;
        --teal:   #00B4A0;
    }

    body {
        background: #f0faf6;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        overflow: hidden;
    }

    .blob-bg {
        position: fixed;
        inset: 0;
        pointer-events: none;
        overflow: hidden;
        z-index: 0;
    }
    .blob-bg span {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.18;
    }

    .register-card {
        position: relative;
        z-index: 1;
        background: white;
        border-radius: 28px;
        box-shadow: 0 20px 80px rgba(0,0,0,0.12), 0 4px 16px rgba(0,0,0,0.06);
        width: 100%;
        max-width: 960px;
        min-height: 580px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        overflow: hidden;
    }

    /* RIGHT PANEL */
    .panel-right {
        position: relative;
        background: linear-gradient(145deg, #e8f8f2 0%, #d0f0e8 40%, #c5eee4 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2.5rem 2rem;
        overflow: hidden;
    }

    .ring {
        position: absolute;
        border-radius: 50%;
    }
    .ring-1 { width: 520px; height: 520px; background: rgba(29,185,84,0.12); bottom: -180px; right: -120px; }
    .ring-2 { width: 400px; height: 400px; background: rgba(29,185,84,0.10); bottom: -120px; right: -60px; }
    .ring-3 { width: 280px; height: 280px; background: rgba(29,185,84,0.08); bottom: -60px; right: 10px; }

    .orb {
        position: absolute;
        border-radius: 50%;
        opacity: 0.85;
        animation: floatOrb 6s ease-in-out infinite;
    }
    @keyframes floatOrb {
        0%,100% { transform: translateY(0) scale(1); }
        50%      { transform: translateY(-12px) scale(1.04); }
    }

    .logo-showcase {
        position: relative;
        z-index: 10;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }
    .logo-showcase img {
        width: 200px;
        height: 200px;
        object-fit: contain;
        filter: drop-shadow(0 8px 24px rgba(0,0,0,0.15));
        animation: logoFloat 5s ease-in-out infinite;
    }
    @keyframes logoFloat {
        0%,100% { transform: translateY(0) rotate(-1deg); }
        50%      { transform: translateY(-10px) rotate(1deg); }
    }

    .logo-tagline {
        text-align: center;
        color: #1a7a52;
        font-weight: 700;
        font-size: 1rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }
    .logo-tagline span {
        display: block;
        font-size: 0.7rem;
        font-weight: 400;
        color: #5aad80;
        letter-spacing: 0.12em;
        margin-top: 2px;
    }

    .dot-purple { width: 48px; height: 48px; background: var(--purple); top: 60px; left: 30px; animation-delay: 0s; }
    .dot-pink    { width: 36px; height: 36px; background: var(--pink);   top: 130px; left: 70px; animation-delay: 1s; }
    .dot-yellow  { width: 52px; height: 52px; background: var(--yellow); top: 40px; right: 50px; animation-delay: 0.5s; }
    .dot-red     { width: 30px; height: 30px; background: var(--red);    bottom: 120px; left: 40px; animation-delay: 1.5s; }
    .dot-blue    { width: 42px; height: 42px; background: var(--blue);   bottom: 80px; right: 40px; animation-delay: 0.8s; }
    .dot-green   { width: 24px; height: 24px; background: var(--green);  bottom: 160px; right: 90px; animation-delay: 2s; }

    /* LEFT PANEL */
    .panel-left {
        padding: 2.5rem 3.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .brand-top {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 1.8rem;
        flex-wrap: wrap;
    }
    .brand-top img { width: 44px; height: 44px; object-fit: contain; }
    .brand-top .brand-name {
        font-size: 1.3rem;
        font-weight: 800;
        color: #0a3d26;
        letter-spacing: 0.04em;
    }
    .brand-top .brand-name span { color: var(--green); }

    .page-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0a3d26;
        margin-bottom: 0.3rem;
    }
    .page-sub {
        font-size: 0.8rem;
        color: #aaa;
        margin-bottom: 1.5rem;
    }

    .form-group { margin-bottom: 0.9rem; }
    .form-label {
        display: block;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        color: #888;
        margin-bottom: 0.4rem;
        text-transform: uppercase;
    }
    .form-input-wrap { position: relative; }
    .form-input-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #bbb;
        font-size: 1rem;
        transition: color 0.2s;
        pointer-events: none;
    }
    .form-input {
        width: 100%;
        border: 1.5px solid #e8e8e8;
        border-radius: 12px;
        padding: 0.7rem 0.9rem 0.7rem 2.6rem;
        font-size: 0.875rem;
        color: #222;
        background: #fafafa;
        transition: all 0.2s;
        font-family: 'Poppins', sans-serif;
        outline: none;
    }
    .form-input:focus {
        border-color: var(--green);
        background: white;
        box-shadow: 0 0 0 3px rgba(29,185,84,0.12);
    }
    .form-input-wrap:focus-within i { color: var(--green); }
    select.form-input { appearance: none; -webkit-appearance: none; background-color: white; cursor: pointer; }
    select.form-input option { background: white; color: #1a1a1a; }

    .btn-submit {
        width: 100%;
        background: linear-gradient(135deg, #1DB954, #16a34a);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.85rem;
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
        letter-spacing: 0.04em;
        transition: all 0.2s;
        margin-top: 0.4rem;
        font-family: 'Poppins', sans-serif;
        box-shadow: 0 4px 16px rgba(29,185,84,0.3);
    }
    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 24px rgba(29,185,84,0.4);
    }
    .btn-submit:active { transform: translateY(0); }

    .btn-back {
        width: 100%;
        border: 1.5px solid #e8e8e8;
        background: white;
        color: #555;
        border-radius: 12px;
        padding: 0.8rem;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: block;
        text-align: center;
        font-family: 'Poppins', sans-serif;
        margin-top: 0.7rem;
    }
    .btn-back:hover {
        border-color: var(--green);
        color: var(--green);
        background: #f0fdf4;
    }

    .alert {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 0.7rem 0.9rem;
        border-radius: 10px;
        font-size: 0.8rem;
        margin-bottom: 1rem;
    }
    .alert-error   { background: #fef2f2; color: #dc2626; }
    .alert-success { background: #f0fdf4; color: #16a34a; }

    .footer-text {
        text-align: center;
        font-size: 0.7rem;
        color: #ccc;
        margin-top: 1.2rem;
    }

    @media (max-width: 720px) {
        .register-card { grid-template-columns: 1fr; max-width: 420px; }
        .panel-right { display: none; }
        .panel-left { padding: 2.5rem 2rem; }
    }

    .fade-in { animation: fadeIn 0.4s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
</style>
</head>
<body>

<!-- Background blobs -->
<div class="blob-bg">
    <span style="width:500px;height:500px;background:var(--green);top:-200px;right:-100px;"></span>
    <span style="width:400px;height:400px;background:var(--teal);bottom:-150px;left:-100px;"></span>
    <span style="width:300px;height:300px;background:var(--yellow);top:40%;left:30%;"></span>
</div>

<div class="register-card fade-in">

    <!-- LEFT: Form Panel -->
    <div class="panel-left">

        <div class="brand-top">
            <img src="./img/Community Logo.png" alt="CEMS">
            <div class="brand-name"><span>CEMS</span></div>
            <a href="index.php"
               style="margin-left:auto; display:inline-flex; align-items:center; gap:5px;
                      font-size:0.75rem; font-weight:700; color:#555; text-decoration:none;
                      background:#f0fdf4; border:1.5px solid rgba(29,185,84,0.25);
                      border-radius:50px; padding:0.35rem 0.85rem; transition:all 0.2s;"
               onmouseover="this.style.background='var(--green)';this.style.color='white';this.style.borderColor='var(--green)';"
               onmouseout="this.style.background='#f0fdf4';this.style.color='#555';this.style.borderColor='rgba(29,185,84,0.25)';">
                <i class="ri-home-4-line"></i> Home
            </a>
        </div>

        <div class="page-title">Create Account</div>
        <div class="page-sub">Join CEMS and start managing your community programs</div>

        <?php if(isset($_SESSION['register_error'])): ?>
            <div class="alert alert-error"><i class="ri-error-warning-line"></i><?php echo $_SESSION['register_error']; unset($_SESSION['register_error']); ?></div>
        <?php elseif(isset($_SESSION['register_success'])): ?>
            <div class="alert alert-success"><i class="ri-checkbox-circle-line"></i><?php echo $_SESSION['register_success']; unset($_SESSION['register_success']); ?></div>
        <?php endif; ?>

        <form method="POST" action="register_process.php">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <div class="form-input-wrap">
                    <i class="ri-user-line"></i>
                    <input type="text" name="fullname" class="form-input" placeholder="Enter your full name" required
                           value="<?php echo isset($_SESSION['old_fullname']) ? htmlspecialchars($_SESSION['old_fullname']) : ''; ?>">
                    <?php unset($_SESSION['old_fullname']); ?>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="form-input-wrap">
                    <i class="ri-mail-line"></i>
                    <input type="email" name="email" class="form-input" placeholder="Enter your email" required
                           value="<?php echo isset($_SESSION['old_reg_email']) ? htmlspecialchars($_SESSION['old_reg_email']) : ''; ?>">
                    <?php unset($_SESSION['old_reg_email']); ?>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="form-input-wrap">
                    <i class="ri-lock-line"></i>
                    <input type="password" name="password" class="form-input" placeholder="Create a password" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <div class="form-input-wrap">
                    <i class="ri-shield-check-line"></i>
                    <input type="password" name="confirm_password" class="form-input" placeholder="Confirm your password" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">I am registering as</label>
                <div class="form-input-wrap">
                    <i class="ri-shield-user-line"></i>
                    <select name="role" class="form-input" style="padding-left:2.6rem;cursor:pointer;" required>
                        <option value="Teaching Staff">Teaching Staff</option>
                        <option value="Non-Teaching Staff">Non-Teaching Staff</option>
                        <option value="Student">Student</option>
                        <option value="Donor/Partner">Donor / Partner</option>
                        <option value="Beneficiary" selected>Beneficiary</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-submit">Create Account →</button>
            <a href="login.php" class="btn-back"><i class="ri-arrow-left-line"></i> Back to Sign In</a>
        </form>

        <div class="footer-text">© 2024 CEMS · Community Extension Management System</div>
    </div>

    <!-- RIGHT: Illustration Panel -->
    <div class="panel-right">
        <div class="ring ring-1"></div>
        <div class="ring ring-2"></div>
        <div class="ring ring-3"></div>

        <div class="orb dot-purple"></div>
        <div class="orb dot-pink"></div>
        <div class="orb dot-yellow"></div>
        <div class="orb dot-red"></div>
        <div class="orb dot-blue"></div>
        <div class="orb dot-green"></div>

        <div class="logo-showcase">
            <img src="./img/Community Logo.png" alt="CEMS Logo">
            <div class="logo-tagline">
                Community Extension
                <span>Management System</span>
            </div>
        </div>

        <div style="position:absolute;bottom:2rem;text-align:center;z-index:10;">
            <div style="display:flex;gap:8px;justify-content:center;margin-bottom:6px;">
                <span style="width:8px;height:8px;border-radius:50%;background:var(--purple);display:inline-block;"></span>
                <span style="width:8px;height:8px;border-radius:50%;background:var(--green);display:inline-block;"></span>
                <span style="width:8px;height:8px;border-radius:50%;background:var(--pink);display:inline-block;"></span>
                <span style="width:8px;height:8px;border-radius:50%;background:var(--yellow);display:inline-block;"></span>
                <span style="width:8px;height:8px;border-radius:50%;background:var(--red);display:inline-block;"></span>
                <span style="width:8px;height:8px;border-radius:50%;background:var(--blue);display:inline-block;"></span>
            </div>
            <p style="font-size:0.7rem;color:#5aad80;font-weight:500;letter-spacing:0.08em;">EMPOWERING COMMUNITIES TOGETHER</p>
        </div>
    </div>

</div>
</body>
</html>