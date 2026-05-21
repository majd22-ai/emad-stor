<?php 
$base_url = '/emad-stor/';
include '../includes/header.php'; 
?>
<style>
/* ========== LOGIN / REGISTER ========== */
.login-main { flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem; }
.login-container { width: 100%; max-width: 480px; margin: 0 auto; }
.login-card { background: #FFFFFF; border-radius: 32px; box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2); padding: 2rem 1.8rem; transition: transform 0.2s; }
.login-header { text-align: center; margin-bottom: 2rem; }
.login-header i { font-size: 3rem; color: #0B1B2B; background: #E2E8F0; padding: 0.8rem; border-radius: 60px; }
.login-header h2 { font-size: 1.8rem; color: #0B1B2B; margin: 0.8rem 0 0.3rem; }
.login-header p { color: #4A627A; font-size: 0.9rem; }
.login-form .input-group { position: relative; margin-bottom: 1.2rem; }
.input-group i:first-child { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #8A9BB0; font-size: 1rem; }
.input-group input { width: 100%; padding: 0.9rem 2.8rem 0.9rem 1rem; border: 1.5px solid #E2E8F0; border-radius: 50px; font-size: 1rem; font-family: inherit; transition: 0.2s; background: #F9FBFD; }
.input-group input:focus { outline: none; border-color: #0B1B2B; background: white; }
.toggle-password { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #8A9BB0; font-size: 1rem; }
.form-options { display: flex; justify-content: space-between; align-items: center; margin: 1rem 0 1.8rem; font-size: 0.85rem; }
.checkbox-label { display: flex; align-items: center; gap: 6px; cursor: pointer; }
.forgot-link { color: #0B1B2B; text-decoration: none; font-weight: 500; }
.forgot-link:hover { text-decoration: underline; }
.login-btn { width: 100%; background: #0B1B2B; color: white; border: none; padding: 0.9rem; border-radius: 50px; font-size: 1rem; font-weight: bold; cursor: pointer; transition: 0.2s; }
.login-btn:hover { background: #1E3A5F; transform: translateY(-2px); }
.social-divider { text-align: center; margin: 1.8rem 0 1.2rem; position: relative; }
.social-divider::before, .social-divider::after { content: ""; position: absolute; top: 50%; width: 40%; height: 1px; background: #E2E8F0; }
.social-divider::before { left: 0; }
.social-divider::after { right: 0; }
.social-divider span { background: white; padding: 0 1rem; color: #8A9BB0; font-size: 0.85rem; }
.social-login { display: flex; gap: 1rem; margin-bottom: 1.5rem; }
.social-btn { flex: 1; display: flex; align-items: center; justify-content: center; gap: 10px; padding: 0.7rem; border-radius: 50px; border: 1px solid #E2E8F0; background: white; font-weight: 500; cursor: pointer; transition: 0.2s; }
.social-btn.google { color: #DB4437; }
.social-btn.facebook { color: #4267B2; }
.social-btn:hover { background: #F4F7FA; transform: translateY(-2px); }
@media (max-width: 520px) { .login-card { padding: 1.5rem; } .social-login { flex-direction: column; } }
</style>

<!-- ==================== نموذج التسجيل (متمركز عمودياً) ==================== -->
<main class="login-main">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-user-plus"></i>
                <h2>إنشاء حساب جديد</h2>
            </div>

            <?php
            if (isset($_SESSION['error'])) {
                echo '<div style="background-color: #ffebee; color: #c62828; padding: 10px; border-radius: 8px; text-align: center; margin-bottom: 15px;">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
            if (isset($_SESSION['success'])) {
                echo '<div style="background-color: #e8f5e9; color: #2e7d32; padding: 10px; border-radius: 8px; text-align: center; margin-bottom: 15px;">' . $_SESSION['success'] . '</div>';
                unset($_SESSION['success']);
            }
            ?>

            <form action="../auth/register_process.php" method="POST" class="login-form">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="name" placeholder="الاسم الكامل" required>
                </div> 
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="البريد الإلكتروني" required>
                </div> 
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="كلمة المرور" required>
                </div> 
                <button type="submit" class="login-btn">إنشاء حساب</button>
                <div style="text-align:center; margin-top:15px;">
                    <a href="login.php" style="color: #0B1B2B; text-decoration: none;">لديك حساب بالفعل؟ سجل دخولك</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
