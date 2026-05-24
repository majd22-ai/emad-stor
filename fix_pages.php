<?php
$base_url = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) ? '/emad-stor/' : '/';
$header = '<?php $base_url = "/emad-stor/"; include "../../includes/header.php"; ?>';
$footer = '<?php include "../../includes/footer.php"; ?>';

// 1. rsize.php
$rsize_content = $header . '
<div class="page-content" style="max-width: 1200px; margin: 3rem auto; padding: 2rem; background: white; border-radius: 28px; box-shadow: 0 8px 20px rgba(0,0,0,0.05); text-align: center;">
    <h1 style="color: #0B1B2B; margin-bottom: 2rem;">كيف تعرف مقاسك؟</h1>
    
    <div style="display: flex; flex-wrap: wrap; gap: 2rem; justify-content: center; margin-bottom: 3rem;">
        
        <div style="flex: 1; min-width: 300px; background: #F8FAFE; padding: 1.5rem; border-radius: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <div style="background: #0B1B2B; color: #FFD966; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; margin: 0 auto 1rem;">1</div>
            <h3 style="margin-bottom: 1rem; color: #1E3A5F;">الخطوة 1: لف الخيط حول إصبعك في أعرض منطقة.</h3>
            <img src="../../assets/images/size-step1.jpg" alt="الخطوة 1" style="width: 100%; border-radius: 12px; height: 200px; object-fit: cover;" onerror="this.src=\'https://placehold.co/600x400/0B1B2B/FFD966?text=Step+1\'">
        </div>

        <div style="flex: 1; min-width: 300px; background: #F8FAFE; padding: 1.5rem; border-radius: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <div style="background: #0B1B2B; color: #FFD966; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; margin: 0 auto 1rem;">2</div>
            <h3 style="margin-bottom: 1rem; color: #1E3A5F;">الخطوة 2: حدد نقطة الالتقاء بالقلم.</h3>
            <img src="../../assets/images/size-step2.jpg" alt="الخطوة 2" style="width: 100%; border-radius: 12px; height: 200px; object-fit: cover;" onerror="this.src=\'https://placehold.co/600x400/0B1B2B/FFD966?text=Step+2\'">
        </div>

        <div style="flex: 1; min-width: 300px; background: #F8FAFE; padding: 1.5rem; border-radius: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <div style="background: #0B1B2B; color: #FFD966; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; margin: 0 auto 1rem;">3</div>
            <h3 style="margin-bottom: 1rem; color: #1E3A5F;">الخطوة 3: ضع الخيط على المسطرة واعرف الطول بالمليمتر.</h3>
            <img src="../../assets/images/size-step3.jpg" alt="الخطوة 3" style="width: 100%; border-radius: 12px; height: 200px; object-fit: cover;" onerror="this.src=\'https://placehold.co/600x400/0B1B2B/FFD966?text=Step+3\'">
        </div>

    </div>
    
    <div style="background: #FFF8E7; padding: 2rem; border-radius: 20px; border-right: 5px solid #FFD966; text-align: right;">
        <h3 style="color: #0B1B2B; margin-bottom: 1rem;">جدول المقاسات</h3>
        <p style="color: #4A627A; line-height: 1.8;">
            بمجرد معرفة الطول بالمليمتر، يمكنك مطابقته مع جدول المقاسات القياسي المرفق مع كل منتج. <br>
            إذا كنت تواجه صعوبة في أخذ المقاس، يمكنك التواصل معنا عبر الواتساب لمساعدتك خطوة بخطوة.
        </p>
    </div>
</div>
' . $footer;

file_put_contents('c:/xampp/htdocs/emad-stor/pages/pages_footer/rsize.php', $rsize_content);


// 2. privacy.php
$privacy_content = $header . '
<div class="page-content" style="max-width: 1000px; margin: 3rem auto; padding: 2rem; background: white; border-radius: 28px; box-shadow: 0 8px 20px rgba(0,0,0,0.05);">
    <h1 style="color: #0B1B2B; border-right: 5px solid #FFD966; padding-right: 1rem; margin-bottom: 1.5rem;">سياسة الخصوصية</h1>
    <p style="color: #4A627A; line-height: 1.8;">
        نحن في <strong>فضيات ابو عماد</strong> نحترم خصوصيتك ونلتزم بحماية بياناتك الشخصية. <br><br>
        <strong>1. جمع المعلومات:</strong><br>
        نحن نجمع فقط المعلومات الضرورية لإتمام طلباتك (مثل الاسم، رقم الهاتف، والعنوان).<br><br>
        <strong>2. حماية البيانات:</strong><br>
        بياناتك مشفرة ومحفوظة بسرية تامة ولا يتم مشاركتها مع أي طرف ثالث باستثناء شركات الشحن لإيصال طلبك.<br><br>
        <strong>3. ملفات تعريف الارتباط (Cookies):</strong><br>
        نستخدم ملفات تعريف الارتباط لتحسين تجربتك في الموقع وحفظ محتويات سلة التسوق الخاصة بك.
    </p>
</div>
' . $footer;

file_put_contents('c:/xampp/htdocs/emad-stor/pages/pages_footer/privacy.php', $privacy_content);


// 3. returns.php
$returns_content = $header . '
<div class="page-content" style="max-width: 1000px; margin: 3rem auto; padding: 2rem; background: white; border-radius: 28px; box-shadow: 0 8px 20px rgba(0,0,0,0.05);">
    <h1 style="color: #0B1B2B; border-right: 5px solid #FFD966; padding-right: 1rem; margin-bottom: 1.5rem;">سياسة الاستبدال والاسترجاع</h1>
    <p style="color: #4A627A; line-height: 1.8;">
        رضاكم هو هدفنا الأول. إذا لم تكن راضياً عن المنتج، يمكنك استبداله أو إرجاعه وفق الشروط التالية:<br><br>
        <strong>1. المدة المسموحة:</strong><br>
        يمكنك تقديم طلب الاسترجاع أو الاستبدال خلال 3 أيام من تاريخ استلام الطلب.<br><br>
        <strong>2. حالة المنتج:</strong><br>
        يجب أن يكون المنتج في حالته الأصلية، غير مستخدم، وفي غلافه الأصلي مع جميع الملحقات والشهادات.<br><br>
        <strong>3. تكاليف الشحن:</strong><br>
        يتحمل العميل تكاليف شحن الإرجاع، إلا إذا كان المنتج به عيب مصنعي أو خطأ في الطلب.
    </p>
</div>
' . $footer;

file_put_contents('c:/xampp/htdocs/emad-stor/pages/pages_footer/returns.php', $returns_content);


// 4. blog.php
$blog_content = $header . '
<div class="page-content" style="max-width: 1000px; margin: 3rem auto; padding: 2rem; background: white; border-radius: 28px; box-shadow: 0 8px 20px rgba(0,0,0,0.05);">
    <h1 style="color: #0B1B2B; border-right: 5px solid #FFD966; padding-right: 1rem; margin-bottom: 1.5rem;">المدونة</h1>
    <p style="color: #4A627A; line-height: 1.8;">
        مرحباً بك في مدونة فضيات ابو عماد، حيث نشاركك شغفنا ومعرفتنا بكل ما يخص الأحجار الكريمة والفضة.<br><br>
        <em>المقالات قريباً... ابقوا معنا!</em>
    </p>
</div>
' . $footer;

file_put_contents('c:/xampp/htdocs/emad-stor/pages/pages_footer/blog.php', $blog_content);


// 5. addr.php (التواصل)
$addr_content = $header . '
<div class="page-content" style="max-width: 1000px; margin: 3rem auto; padding: 2rem; background: white; border-radius: 28px; box-shadow: 0 8px 20px rgba(0,0,0,0.05);">
    <h1 style="color: #0B1B2B; border-right: 5px solid #FFD966; padding-right: 1rem; margin-bottom: 1.5rem;">تواصل معنا</h1>
    <p style="color: #4A627A; line-height: 1.8;">
        نسعد دائماً بتواصلكم معنا للرد على استفساراتكم أو تلقي مقترحاتكم.<br><br>
        <strong>واتساب:</strong> <a href="https://wa.me/967772885397" style="color: #0056b3; text-decoration: none;">+967 772 885 397</a><br>
        <strong>العنوان:</strong> صنعاء، اليمن.<br>
        <strong>أوقات العمل:</strong> من السبت إلى الخميس (9 صباحاً - 9 مساءً).
    </p>
</div>
' . $footer;

file_put_contents('c:/xampp/htdocs/emad-stor/pages/pages_footer/addr.php', $addr_content);


// 6. terms.php
$terms_content = $header . '
<div class="page-content" style="max-width: 1000px; margin: 3rem auto; padding: 2rem; background: white; border-radius: 28px; box-shadow: 0 8px 20px rgba(0,0,0,0.05);">
    <h1 style="color: #0B1B2B; border-right: 5px solid #FFD966; padding-right: 1rem; margin-bottom: 1.5rem;">الشروط والأحكام</h1>
    <p style="color: #4A627A; line-height: 1.8;">
        يرجى قراءة الشروط والأحكام بعناية قبل استخدام موقع فضيات ابو عماد.<br><br>
        استخدامك للموقع يعني موافقتك الكاملة على جميع سياساتنا، بما فيها سياسة الخصوصية والاسترجاع.<br>
        نحتفظ بالحق في تعديل الأسعار وتوافر المنتجات دون إشعار مسبق.
    </p>
</div>
' . $footer;

file_put_contents('c:/xampp/htdocs/emad-stor/pages/pages_footer/terms.php', $terms_content);

echo "All pages fixed and refactored to use header.php and footer.php";
?>
