<?php 
$base_url = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) ? '/emad-stor/' : '/';
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

<!-- ==================== ظ†ظ…ظˆط°ط¬ طھط³ط¬ظٹظ„ ط§ظ„ط¯ط®ظˆظ„ (ظ…طھظ…ط±ظƒط² ط¹ظ…ظˆط¯ظٹط§ظ‹) ==================== -->
<main class="login-main">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-gem"></i>
                <h2>ظ…ط±ط­ط¨ط§ظ‹ ط¨ظƒ ظپظٹ ظ…طھط¬ط± ط§ط¨ظˆ ط¹ظ…ط§ط¯</h2>
                <p>ط³ط¬ظ„ ط§ظ„ط¯ط®ظˆظ„ ظ„ظ„ظˆطµظˆظ„ ط¥ظ„ظ‰ ط·ظ„ط¨ط§طھظƒ ظˆط§ظ„ظ…ظپط¶ظ„ط©</p>
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

            <form action="../auth/login_process.php" method="POST" class="login-form">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="ط§ظ„ط¨ط±ظٹط¯ ط§ظ„ط¥ظ„ظƒطھط±ظˆظ†ظٹ" required>
                </div> 
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="ظƒظ„ظ…ط© ط§ظ„ظ…ط±ظˆط±" required>
                </div> 
                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        <span>طھط°ظƒط±ظ†ظٹ</span>
                    </label>
                    <a href="forgot_password.php" class="forgot-link">ظ†ط³ظٹطھ ظƒظ„ظ…ط© ط§ظ„ظ…ط±ظˆط±طں</a>
                </div>
                <button type="submit" class="login-btn">طھط³ط¬ظٹظ„ ط§ظ„ط¯ط®ظˆظ„</button>
                <div style="text-align:center; margin-top:15px;">
                    <a href="register.php" style="color: #0B1B2B; text-decoration: none;">ظ„ظٹط³ ظ„ط¯ظٹظƒ ط­ط³ط§ط¨طں ط¥ظ†ط´ط§ط، ط­ط³ط§ط¨ ط¬ط¯ظٹط¯</a>
                </div>
            </form>

            <div class="social-divider">
                <span>ط£ظˆ ط¹ط¨ط±</span>
            </div>

            <div class="social-login">
                <button type="button" class="social-btn google" id="btn-google-login">
                    <i class="fab fa-google"></i> Google
                </button>
                <button type="button" class="social-btn facebook" id="btn-facebook-login">
                    <i class="fab fa-facebook-f"></i> Facebook
                </button>
            </div>
        </div>
    </div>
</main>

<!-- Firebase SDK Integration -->
<script type="module">
  import { initializeApp } from "https://www.gstatic.com/firebasejs/12.13.0/firebase-app.js";
  import { getAuth, signInWithPopup, GoogleAuthProvider, FacebookAuthProvider } from "https://www.gstatic.com/firebasejs/12.13.0/firebase-auth.js";
  import { getAnalytics } from "https://www.gstatic.com/firebasejs/12.13.0/firebase-analytics.js";

  const firebaseConfig = {
    apiKey: "AIzaSyCcKnmmsViJmJk5IRCmyZUGrO2t_0usq5g",
    authDomain: "emad-store-434d8.firebaseapp.com",
    projectId: "emad-store-434d8",
    storageBucket: "emad-store-434d8.firebasestorage.app",
    messagingSenderId: "109730229844",
    appId: "1:109730229844:web:5ea31c23df17b29571c7c1",
    measurementId: "G-KQND5EV3LM"
  };

  const app = initializeApp(firebaseConfig);
  const analytics = getAnalytics(app);
  const auth = getAuth(app);

  const googleProvider = new GoogleAuthProvider();
  const facebookProvider = new FacebookAuthProvider();

  function handleFirebaseLogin(provider) {
    signInWithPopup(auth, provider)
      .then((result) => {
        const user = result.user;
        
        // ط¥ط±ط³ط§ظ„ ط§ظ„ط¨ظٹط§ظ†ط§طھ ظ„ظ…ط¹ط§ظ„ط¬طھظ‡ط§ ظپظٹ ط§ظ„ط³ظٹط±ظپط± ظ„ظپطھط­ ط¬ظ„ط³ط© PHP
        fetch('../auth/firebase_auth.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                uid: user.uid,
                email: user.email,
                displayName: user.displayName,
                photoURL: user.photoURL
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                window.location.href = '../index.php'; // طھظˆط¬ظٹظ‡ ظ„ظ„ط±ط¦ظٹط³ظٹط© ط¨ط¹ط¯ ظ†ط¬ط§ط­ ط§ظ„ط¯ط®ظˆظ„
            } else {
                alert('ظپط´ظ„ طھط³ط¬ظٹظ„ ط§ظ„ط¯ط®ظˆظ„: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error communicating with server:', error);
            alert('ط­ط¯ط« ط®ط·ط£ ط£ط«ظ†ط§ط، ظ…ط¹ط§ظ„ط¬ط© ط¨ظٹط§ظ†ط§طھ ط§ظ„ط¯ط®ظˆظ„ ظپظٹ ط§ظ„ط³ظٹط±ظپط±.');
        });
      }).catch((error) => {
        console.error('Firebase Auth Error:', error);
        if(error.code !== 'auth/popup-closed-by-user' && error.code !== 'auth/cancelled-popup-request') {
            alert('ط®ط·ط£ ظپظٹ ظ…طµط§ط¯ظ‚ط© Firebase: ' + error.message);
        }
      });
  }

  document.getElementById('btn-google-login').addEventListener('click', function() {
      handleFirebaseLogin(googleProvider);
  });
  document.getElementById('btn-facebook-login').addEventListener('click', function() {
      handleFirebaseLogin(facebookProvider);
  });
</script>

<?php include '../includes/footer.php'; ?>
