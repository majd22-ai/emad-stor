<?php
$base_url = (strpos($base_url = '/emad-stor/';SERVER['HTTP_HOST'], 'localhost') !== false || strpos($base_url = '/emad-stor/';SERVER['HTTP_HOST'], '127.0.0.1') !== false) ? '/emad-stor/' : '/';
$header = '<?php $base_url = "/emad-stor/"; include "../../includes/header.php"; ?>';
$footer = '<?php include "../../includes/footer.php"; ?>';

// 1. rsize.php
$rsize_content = $header . '
<div class="page-content" style="max-width: 1200px; margin: 3rem auto; padding: 2rem; background: white; border-radius: 28px; box-shadow: 0 8px 20px rgba(0,0,0,0.05); text-align: center;">
    <h1 style="color: #0B1B2B; margin-bottom: 2rem;">ظƒظٹظپ طھط¹ط±ظپ ظ…ظ‚ط§ط³ظƒطں</h1>
    
    <div style="display: flex; flex-wrap: wrap; gap: 2rem; justify-content: center; margin-bottom: 3rem;">
        
        <div style="flex: 1; min-width: 300px; background: #F8FAFE; padding: 1.5rem; border-radius: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <div style="background: #0B1B2B; color: #FFD966; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; margin: 0 auto 1rem;">1</div>
            <h3 style="margin-bottom: 1rem; color: #1E3A5F;">ط§ظ„ط®ط·ظˆط© 1: ظ„ظپ ط§ظ„ط®ظٹط· ط­ظˆظ„ ط¥طµط¨ط¹ظƒ ظپظٹ ط£ط¹ط±ط¶ ظ…ظ†ط·ظ‚ط©.</h3>
            <img src="../../assets/images/size-step1.jpg" alt="ط§ظ„ط®ط·ظˆط© 1" style="width: 100%; border-radius: 12px; height: 200px; object-fit: cover;" onerror="this.src=\'https://placehold.co/600x400/0B1B2B/FFD966?text=Step+1\'">
        </div>

        <div style="flex: 1; min-width: 300px; background: #F8FAFE; padding: 1.5rem; border-radius: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <div style="background: #0B1B2B; color: #FFD966; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; margin: 0 auto 1rem;">2</div>
            <h3 style="margin-bottom: 1rem; color: #1E3A5F;">ط§ظ„ط®ط·ظˆط© 2: ط­ط¯ط¯ ظ†ظ‚ط·ط© ط§ظ„ط§ظ„طھظ‚ط§ط، ط¨ط§ظ„ظ‚ظ„ظ….</h3>
            <img src="../../assets/images/size-step2.jpg" alt="ط§ظ„ط®ط·ظˆط© 2" style="width: 100%; border-radius: 12px; height: 200px; object-fit: cover;" onerror="this.src=\'https://placehold.co/600x400/0B1B2B/FFD966?text=Step+2\'">
        </div>

        <div style="flex: 1; min-width: 300px; background: #F8FAFE; padding: 1.5rem; border-radius: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <div style="background: #0B1B2B; color: #FFD966; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; margin: 0 auto 1rem;">3</div>
            <h3 style="margin-bottom: 1rem; color: #1E3A5F;">ط§ظ„ط®ط·ظˆط© 3: ط¶ط¹ ط§ظ„ط®ظٹط· ط¹ظ„ظ‰ ط§ظ„ظ…ط³ط·ط±ط© ظˆط§ط¹ط±ظپ ط§ظ„ط·ظˆظ„ ط¨ط§ظ„ظ…ظ„ظٹظ…طھط±.</h3>
            <img src="../../assets/images/size-step3.jpg" alt="ط§ظ„ط®ط·ظˆط© 3" style="width: 100%; border-radius: 12px; height: 200px; object-fit: cover;" onerror="this.src=\'https://placehold.co/600x400/0B1B2B/FFD966?text=Step+3\'">
        </div>

    </div>
    
    <div style="background: #FFF8E7; padding: 2rem; border-radius: 20px; border-right: 5px solid #FFD966; text-align: right;">
        <h3 style="color: #0B1B2B; margin-bottom: 1rem;">ط¬ط¯ظˆظ„ ط§ظ„ظ…ظ‚ط§ط³ط§طھ</h3>
        <p style="color: #4A627A; line-height: 1.8;">
            ط¨ظ…ط¬ط±ط¯ ظ…ط¹ط±ظپط© ط§ظ„ط·ظˆظ„ ط¨ط§ظ„ظ…ظ„ظٹظ…طھط±طŒ ظٹظ…ظƒظ†ظƒ ظ…ط·ط§ط¨ظ‚طھظ‡ ظ…ط¹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ظ‚ط§ط³ط§طھ ط§ظ„ظ‚ظٹط§ط³ظٹ ط§ظ„ظ…ط±ظپظ‚ ظ…ط¹ ظƒظ„ ظ…ظ†طھط¬. <br>
            ط¥ط°ط§ ظƒظ†طھ طھظˆط§ط¬ظ‡ طµط¹ظˆط¨ط© ظپظٹ ط£ط®ط° ط§ظ„ظ…ظ‚ط§ط³طŒ ظٹظ…ظƒظ†ظƒ ط§ظ„طھظˆط§طµظ„ ظ…ط¹ظ†ط§ ط¹ط¨ط± ط§ظ„ظˆط§طھط³ط§ط¨ ظ„ظ…ط³ط§ط¹ط¯طھظƒ ط®ط·ظˆط© ط¨ط®ط·ظˆط©.
        </p>
    </div>
</div>
' . $footer;

file_put_contents('c:/xampp/htdocs/emad-stor/pages/pages_footer/rsize.php', $rsize_content);


// 2. privacy.php
$privacy_content = $header . '
<div class="page-content" style="max-width: 1000px; margin: 3rem auto; padding: 2rem; background: white; border-radius: 28px; box-shadow: 0 8px 20px rgba(0,0,0,0.05);">
    <h1 style="color: #0B1B2B; border-right: 5px solid #FFD966; padding-right: 1rem; margin-bottom: 1.5rem;">ط³ظٹط§ط³ط© ط§ظ„ط®طµظˆطµظٹط©</h1>
    <p style="color: #4A627A; line-height: 1.8;">
        ظ†ط­ظ† ظپظٹ <strong>ظپط¶ظٹط§طھ ط§ط¨ظˆ ط¹ظ…ط§ط¯</strong> ظ†ط­طھط±ظ… ط®طµظˆطµظٹطھظƒ ظˆظ†ظ„طھط²ظ… ط¨ط­ظ…ط§ظٹط© ط¨ظٹط§ظ†ط§طھظƒ ط§ظ„ط´ط®طµظٹط©. <br><br>
        <strong>1. ط¬ظ…ط¹ ط§ظ„ظ…ط¹ظ„ظˆظ…ط§طھ:</strong><br>
        ظ†ط­ظ† ظ†ط¬ظ…ط¹ ظپظ‚ط· ط§ظ„ظ…ط¹ظ„ظˆظ…ط§طھ ط§ظ„ط¶ط±ظˆط±ظٹط© ظ„ط¥طھظ…ط§ظ… ط·ظ„ط¨ط§طھظƒ (ظ…ط«ظ„ ط§ظ„ط§ط³ظ…طŒ ط±ظ‚ظ… ط§ظ„ظ‡ط§طھظپطŒ ظˆط§ظ„ط¹ظ†ظˆط§ظ†).<br><br>
        <strong>2. ط­ظ…ط§ظٹط© ط§ظ„ط¨ظٹط§ظ†ط§طھ:</strong><br>
        ط¨ظٹط§ظ†ط§طھظƒ ظ…ط´ظپط±ط© ظˆظ…ط­ظپظˆط¸ط© ط¨ط³ط±ظٹط© طھط§ظ…ط© ظˆظ„ط§ ظٹطھظ… ظ…ط´ط§ط±ظƒطھظ‡ط§ ظ…ط¹ ط£ظٹ ط·ط±ظپ ط«ط§ظ„ط« ط¨ط§ط³طھط«ظ†ط§ط، ط´ط±ظƒط§طھ ط§ظ„ط´ط­ظ† ظ„ط¥ظٹطµط§ظ„ ط·ظ„ط¨ظƒ.<br><br>
        <strong>3. ظ…ظ„ظپط§طھ طھط¹ط±ظٹظپ ط§ظ„ط§ط±طھط¨ط§ط· (Cookies):</strong><br>
        ظ†ط³طھط®ط¯ظ… ظ…ظ„ظپط§طھ طھط¹ط±ظٹظپ ط§ظ„ط§ط±طھط¨ط§ط· ظ„طھط­ط³ظٹظ† طھط¬ط±ط¨طھظƒ ظپظٹ ط§ظ„ظ…ظˆظ‚ط¹ ظˆط­ظپط¸ ظ…ط­طھظˆظٹط§طھ ط³ظ„ط© ط§ظ„طھط³ظˆظ‚ ط§ظ„ط®ط§طµط© ط¨ظƒ.
    </p>
</div>
' . $footer;

file_put_contents('c:/xampp/htdocs/emad-stor/pages/pages_footer/privacy.php', $privacy_content);


// 3. returns.php
$returns_content = $header . '
<div class="page-content" style="max-width: 1000px; margin: 3rem auto; padding: 2rem; background: white; border-radius: 28px; box-shadow: 0 8px 20px rgba(0,0,0,0.05);">
    <h1 style="color: #0B1B2B; border-right: 5px solid #FFD966; padding-right: 1rem; margin-bottom: 1.5rem;">ط³ظٹط§ط³ط© ط§ظ„ط§ط³طھط¨ط¯ط§ظ„ ظˆط§ظ„ط§ط³طھط±ط¬ط§ط¹</h1>
    <p style="color: #4A627A; line-height: 1.8;">
        ط±ط¶ط§ظƒظ… ظ‡ظˆ ظ‡ط¯ظپظ†ط§ ط§ظ„ط£ظˆظ„. ط¥ط°ط§ ظ„ظ… طھظƒظ† ط±ط§ط¶ظٹط§ظ‹ ط¹ظ† ط§ظ„ظ…ظ†طھط¬طŒ ظٹظ…ظƒظ†ظƒ ط§ط³طھط¨ط¯ط§ظ„ظ‡ ط£ظˆ ط¥ط±ط¬ط§ط¹ظ‡ ظˆظپظ‚ ط§ظ„ط´ط±ظˆط· ط§ظ„طھط§ظ„ظٹط©:<br><br>
        <strong>1. ط§ظ„ظ…ط¯ط© ط§ظ„ظ…ط³ظ…ظˆط­ط©:</strong><br>
        ظٹظ…ظƒظ†ظƒ طھظ‚ط¯ظٹظ… ط·ظ„ط¨ ط§ظ„ط§ط³طھط±ط¬ط§ط¹ ط£ظˆ ط§ظ„ط§ط³طھط¨ط¯ط§ظ„ ط®ظ„ط§ظ„ 3 ط£ظٹط§ظ… ظ…ظ† طھط§ط±ظٹط® ط§ط³طھظ„ط§ظ… ط§ظ„ط·ظ„ط¨.<br><br>
        <strong>2. ط­ط§ظ„ط© ط§ظ„ظ…ظ†طھط¬:</strong><br>
        ظٹط¬ط¨ ط£ظ† ظٹظƒظˆظ† ط§ظ„ظ…ظ†طھط¬ ظپظٹ ط­ط§ظ„طھظ‡ ط§ظ„ط£طµظ„ظٹط©طŒ ط؛ظٹط± ظ…ط³طھط®ط¯ظ…طŒ ظˆظپظٹ ط؛ظ„ط§ظپظ‡ ط§ظ„ط£طµظ„ظٹ ظ…ط¹ ط¬ظ…ظٹط¹ ط§ظ„ظ…ظ„ط­ظ‚ط§طھ ظˆط§ظ„ط´ظ‡ط§ط¯ط§طھ.<br><br>
        <strong>3. طھظƒط§ظ„ظٹظپ ط§ظ„ط´ط­ظ†:</strong><br>
        ظٹطھط­ظ…ظ„ ط§ظ„ط¹ظ…ظٹظ„ طھظƒط§ظ„ظٹظپ ط´ط­ظ† ط§ظ„ط¥ط±ط¬ط§ط¹طŒ ط¥ظ„ط§ ط¥ط°ط§ ظƒط§ظ† ط§ظ„ظ…ظ†طھط¬ ط¨ظ‡ ط¹ظٹط¨ ظ…طµظ†ط¹ظٹ ط£ظˆ ط®ط·ط£ ظپظٹ ط§ظ„ط·ظ„ط¨.
    </p>
</div>
' . $footer;

file_put_contents('c:/xampp/htdocs/emad-stor/pages/pages_footer/returns.php', $returns_content);


// 4. blog.php
$blog_content = $header . '
<div class="page-content" style="max-width: 1000px; margin: 3rem auto; padding: 2rem; background: white; border-radius: 28px; box-shadow: 0 8px 20px rgba(0,0,0,0.05);">
    <h1 style="color: #0B1B2B; border-right: 5px solid #FFD966; padding-right: 1rem; margin-bottom: 1.5rem;">ط§ظ„ظ…ط¯ظˆظ†ط©</h1>
    <p style="color: #4A627A; line-height: 1.8;">
        ظ…ط±ط­ط¨ط§ظ‹ ط¨ظƒ ظپظٹ ظ…ط¯ظˆظ†ط© ظپط¶ظٹط§طھ ط§ط¨ظˆ ط¹ظ…ط§ط¯طŒ ط­ظٹط« ظ†ط´ط§ط±ظƒظƒ ط´ط؛ظپظ†ط§ ظˆظ…ط¹ط±ظپطھظ†ط§ ط¨ظƒظ„ ظ…ط§ ظٹط®طµ ط§ظ„ط£ط­ط¬ط§ط± ط§ظ„ظƒط±ظٹظ…ط© ظˆط§ظ„ظپط¶ط©.<br><br>
        <em>ط§ظ„ظ…ظ‚ط§ظ„ط§طھ ظ‚ط±ظٹط¨ط§ظ‹... ط§ط¨ظ‚ظˆط§ ظ…ط¹ظ†ط§!</em>
    </p>
</div>
' . $footer;

file_put_contents('c:/xampp/htdocs/emad-stor/pages/pages_footer/blog.php', $blog_content);


// 5. addr.php (ط§ظ„طھظˆط§طµظ„)
$addr_content = $header . '
<div class="page-content" style="max-width: 1000px; margin: 3rem auto; padding: 2rem; background: white; border-radius: 28px; box-shadow: 0 8px 20px rgba(0,0,0,0.05);">
    <h1 style="color: #0B1B2B; border-right: 5px solid #FFD966; padding-right: 1rem; margin-bottom: 1.5rem;">طھظˆط§طµظ„ ظ…ط¹ظ†ط§</h1>
    <p style="color: #4A627A; line-height: 1.8;">
        ظ†ط³ط¹ط¯ ط¯ط§ط¦ظ…ط§ظ‹ ط¨طھظˆط§طµظ„ظƒظ… ظ…ط¹ظ†ط§ ظ„ظ„ط±ط¯ ط¹ظ„ظ‰ ط§ط³طھظپط³ط§ط±ط§طھظƒظ… ط£ظˆ طھظ„ظ‚ظٹ ظ…ظ‚طھط±ط­ط§طھظƒظ….<br><br>
        <strong>ظˆط§طھط³ط§ط¨:</strong> <a href="https://wa.me/967772885397" style="color: #0056b3; text-decoration: none;">+967 772 885 397</a><br>
        <strong>ط§ظ„ط¹ظ†ظˆط§ظ†:</strong> طµظ†ط¹ط§ط،طŒ ط§ظ„ظٹظ…ظ†.<br>
        <strong>ط£ظˆظ‚ط§طھ ط§ظ„ط¹ظ…ظ„:</strong> ظ…ظ† ط§ظ„ط³ط¨طھ ط¥ظ„ظ‰ ط§ظ„ط®ظ…ظٹط³ (9 طµط¨ط§ط­ط§ظ‹ - 9 ظ…ط³ط§ط،ظ‹).
    </p>
</div>
' . $footer;

file_put_contents('c:/xampp/htdocs/emad-stor/pages/pages_footer/addr.php', $addr_content);


// 6. terms.php
$terms_content = $header . '
<div class="page-content" style="max-width: 1000px; margin: 3rem auto; padding: 2rem; background: white; border-radius: 28px; box-shadow: 0 8px 20px rgba(0,0,0,0.05);">
    <h1 style="color: #0B1B2B; border-right: 5px solid #FFD966; padding-right: 1rem; margin-bottom: 1.5rem;">ط§ظ„ط´ط±ظˆط· ظˆط§ظ„ط£ط­ظƒط§ظ…</h1>
    <p style="color: #4A627A; line-height: 1.8;">
        ظٹط±ط¬ظ‰ ظ‚ط±ط§ط،ط© ط§ظ„ط´ط±ظˆط· ظˆط§ظ„ط£ط­ظƒط§ظ… ط¨ط¹ظ†ط§ظٹط© ظ‚ط¨ظ„ ط§ط³طھط®ط¯ط§ظ… ظ…ظˆظ‚ط¹ ظپط¶ظٹط§طھ ط§ط¨ظˆ ط¹ظ…ط§ط¯.<br><br>
        ط§ط³طھط®ط¯ط§ظ…ظƒ ظ„ظ„ظ…ظˆظ‚ط¹ ظٹط¹ظ†ظٹ ظ…ظˆط§ظپظ‚طھظƒ ط§ظ„ظƒط§ظ…ظ„ط© ط¹ظ„ظ‰ ط¬ظ…ظٹط¹ ط³ظٹط§ط³ط§طھظ†ط§طŒ ط¨ظ…ط§ ظپظٹظ‡ط§ ط³ظٹط§ط³ط© ط§ظ„ط®طµظˆطµظٹط© ظˆط§ظ„ط§ط³طھط±ط¬ط§ط¹.<br>
        ظ†ط­طھظپط¸ ط¨ط§ظ„ط­ظ‚ ظپظٹ طھط¹ط¯ظٹظ„ ط§ظ„ط£ط³ط¹ط§ط± ظˆطھظˆط§ظپط± ط§ظ„ظ…ظ†طھط¬ط§طھ ط¯ظˆظ† ط¥ط´ط¹ط§ط± ظ…ط³ط¨ظ‚.
    </p>
</div>
' . $footer;

file_put_contents('c:/xampp/htdocs/emad-stor/pages/pages_footer/terms.php', $terms_content);

echo "All pages fixed and refactored to use header.php and footer.php";
?>
