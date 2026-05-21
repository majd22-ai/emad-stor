<?php 
$base_url = '/emad-stor/';
include '../includes/header.php'; 
?>
<style>
/* ========== LOGIN / REGISTER STYLE ========== */
.login-main { flex: 1; display: flex; align-items: center; justify-content: center; padding: 4rem 2rem; }
.login-container { width: 100%; max-width: 480px; margin: 0 auto; }
.login-card { background: #FFFFFF; border-radius: 32px; box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2); padding: 3rem 2rem; text-align: center; }
.login-header { margin-bottom: 2rem; }
.login-header i { font-size: 3rem; color: #0B1B2B; background: #E2E8F0; padding: 1rem; border-radius: 60px; margin-bottom: 1rem; }
.login-header h2 { font-size: 1.8rem; color: #0B1B2B; margin: 0; }
.login-header p { color: #4A627A; font-size: 0.95rem; margin-top: 10px; line-height: 1.6; }
.login-form .input-group { position: relative; margin-bottom: 1.5rem; text-align: right; }
.input-group i:first-child { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #8A9BB0; font-size: 1.1rem; }
.input-group input { width: 100%; padding: 1rem 3rem 1rem 1rem; border: 1.5px solid #E2E8F0; border-radius: 50px; font-size: 1rem; font-family: inherit; transition: 0.3s; background: #F9FBFD; outline: none; }
.input-group input:focus { border-color: #0B1B2B; background: white; box-shadow: 0 0 0 4px rgba(11, 27, 43, 0.1); }
.login-btn { width: 100%; background: #0B1B2B; color: #FFD966; border: none; padding: 1rem; border-radius: 50px; font-size: 1.1rem; font-weight: bold; cursor: pointer; transition: 0.3s; margin-top: 1rem; }
.login-btn:hover { background: #1E3A5F; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(11, 27, 43, 0.2); }
</style>

<main class="login-main">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-envelope-open-text"></i>
                <h2>نسيت كلمة المرور؟</h2>
                <p>أدخل بريدك الإلكتروني المسجل لدينا وسنقوم بإرسال كود التحقق (7 أرقام) لتتمكن من إعادة تعيين كلمة المرور.</p>
            </div>

            <?php
            if (isset($_SESSION['error'])) {
                echo '<div style="background-color: #ffebee; color: #c62828; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;"><i class="fas fa-exclamation-circle"></i> ' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
            if (isset($_SESSION['success'])) {
                echo '<div style="background-color: #e8f5e9; color: #2e7d32; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;"><i class="fas fa-check-circle"></i> ' . $_SESSION['success'] . '</div>';
                unset($_SESSION['success']);
            }
            ?>

            <form action="../auth/forgot_process.php" method="POST" class="login-form">
                <div class="input-group">
                    <i class="fas fa-at"></i>
                    <input type="email" name="email" placeholder="أدخل بريدك الإلكتروني" required>
                </div> 
                <button type="submit" class="login-btn">إرسال كود التحقق</button>
                <div style="margin-top: 20px;">
                    <a href="login.php" style="color: #4A627A; text-decoration: none; font-size: 0.95rem; font-weight: bold;"><i class="fas fa-arrow-right"></i> العودة لتسجيل الدخول</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
