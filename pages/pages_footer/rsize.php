<?php
require_once '../../includes/db_connect.php';
include '../../includes/header.php';
?>

<style>
    body {
        background: #F4F7FA;
        color: #1A2A3A;
        line-height: 1.6;
    }
    .rsize-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }
    .page-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .page-header h1 {
        font-size: 2.2rem;
        color: #0B1B2B;
        display: inline-block;
        border-bottom: 3px solid #FFD966;
        padding-bottom: 0.5rem;
    }
    .page-header p {
        margin-top: 0.8rem;
        color: #2C3E50;
        font-size: 1.1rem;
    }
    .steps-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin: 2rem 0;
    }
    .step-card {
        background: white;
        border-radius: 28px;
        padding: 1.8rem 1.2rem;
        text-align: center;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        transition: transform 0.2s;
    }
    .step-card:hover {
        transform: translateY(-5px);
    }
    .step-image {
        width: 100%;
        height: 180px;
        background: #F0F3F8;
        border-radius: 20px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .step-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .step-number {
        width: 45px;
        height: 45px;
        background: #0B1B2B;
        color: #FFD966;
        font-size: 1.6rem;
        font-weight: bold;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0.5rem auto 1rem;
    }
    .step-card h3 {
        font-size: 1.3rem;
        margin-bottom: 0.8rem;
        color: #0B1B2B;
    }
    .step-card p {
        color: #4a627a;
    }
    .tools-section {
        background: white;
        border-radius: 32px;
        padding: 2rem;
        margin: 2.5rem 0;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }
    .section-title {
        text-align: center;
        font-size: 1.7rem;
        margin-bottom: 2rem;
        color: #0B1B2B;
    }
    .flex-row {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        justify-content: center;
    }
    .chart-box {
        flex: 1;
        min-width: 250px;
        background: #F8FAFE;
        border-radius: 24px;
        padding: 1rem;
    }
    .chart-box h3 {
        text-align: center;
        margin-bottom: 1rem;
        color: #1E3A5F;
    }
    .chart-box table {
        width: 100%;
        border-collapse: collapse;
        text-align: center;
    }
    .chart-box th, .chart-box td {
        padding: 0.8rem;
        border-bottom: 1px solid #E2E8F0;
    }
    .chart-box th {
        background: #0B1B2B;
        color: white;
    }
    .chart-box tr:hover {
        background: #FFF3E0;
    }
    .size-calculator {
        flex: 1;
        min-width: 260px;
        background: #F8FAFE;
        border-radius: 24px;
        padding: 1.5rem;
        text-align: center;
    }
    .calc-input {
        margin: 1rem 0;
    }
    .calc-input label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .calc-input input {
        width: 100%;
        padding: 0.8rem;
        border: 2px solid #E2E8F0;
        border-radius: 50px;
        font-size: 1rem;
        text-align: center;
        font-family: inherit;
    }
    .calc-result {
        background: #E9ECF3;
        border-radius: 28px;
        padding: 1rem;
        margin-top: 1.2rem;
    }
    .result-ring-size {
        font-size: 2rem;
        font-weight: 800;
        color: #0B1B2B;
        background: #FFD966;
        display: inline-block;
        padding: 0.3rem 1.5rem;
        border-radius: 60px;
        margin-top: 0.5rem;
    }
    .size-calculator button {
        background: #0B1B2B;
        color: white;
        border: none;
        padding: 0.7rem 1.5rem;
        border-radius: 40px;
        cursor: pointer;
        font-weight: bold;
        transition: 0.2s;
        margin-top: 0.8rem;
    }
    .size-calculator button:hover {
        background: #1E3A5F;
        transform: scale(0.97);
    }
    .advice {
        background: #FFF8E7;
        border-right: 5px solid #FFD966;
        padding: 1rem;
        border-radius: 20px;
        margin-top: 1rem;
    }
    .footer-note {
        text-align: center;
        margin-top: 2rem;
        padding: 1rem;
        background: #E2E8F0;
        border-radius: 40px;
        color: #2C3E50;
    }
    @media (max-width: 700px) {
        .step-card { padding: 1.2rem; }
        .page-header h1 { font-size: 1.6rem; }
        .step-image { height: 150px; }
    }
</style>

<!-- المحتوى الرئيسي -->
<div class="rsize-container">
    <div class="page-header">
        <h1><i class="fas fa-ring"></i> كيف تعرف مقاس خاتمك بسهولة؟</h1>
        <p>طريقة الخيط والمسطرة – الأدق والأبسط في المنزل</p>
    </div>

    <div class="steps-grid">
        <div class="step-card">
            <div class="step-image">
                <img src="<?php echo $base_url; ?>assets/images/si1.png" alt="لف الخيط">
            </div>
            <div class="step-number">1</div>
            <h3>لف الخيط حول الإصبع</h3>
            <p>استخدم خيطاً غير مطاطي، لفه حول قاعدة الإصبع في أعرض منطقة (حيث سيرتدي الخاتم).</p>
        </div>
        <div class="step-card">
            <div class="step-image">
                <img src="<?php echo $base_url; ?>assets/images/si2.png" alt="تحديد العلامة">
            </div>
            <div class="step-number">2</div>
            <h3>حدد نقطة الالتقاء</h3>
            <p>ضع علامة بقلم عند نقطة التقاء طرفي الخيط. تأكد من أن الخيط ملامس للإصبع ولكن غير مشدود بإحكام.</p>
        </div>
        <div class="step-card">
            <div class="step-image">
                <img src="<?php echo $base_url; ?>assets/images/si3.png" alt="القياس بالمسطرة">
            </div>
            <div class="step-number">3</div>
            <h3>قس الطول بالمليمتر</h3>
            <p>افرد الخيط على مسطرة واقرأ الطول بالمليمتر – هذا هو محيط إصبعك.</p>
        </div>
    </div>

    <div class="tools-section">
        <h2 class="section-title"><i class="fas fa-chart-line"></i> جدول تحويل المقاسات</h2>
        <div class="flex-row">
            <div class="chart-box">
                <h3>محيط الإصبع مقابل مقاس الخاتم</h3>
                <table>
                    <thead><tr><th>محيط الإصبع (مم)</th><th>مقاس الخاتم</th></tr></thead>
                    <tbody>
                        <tr><td>44 - 46</td><td>44-46</td></tr>
                        <tr><td>47 - 49</td><td>47-49</td></tr>
                        <tr><td>50 - 52</td><td>50-52</td></tr>
                        <tr><td>53 - 55</td><td>53-55</td></tr>
                        <tr><td>56 - 58</td><td>56-58</td></tr>
                        <tr><td>59 - 61</td><td>59-61</td></tr>
                        <tr><td>62 - 64</td><td>62-64</td></tr>
                        <tr><td>65 - 67</td><td>65-67</td></tr>
                        <tr><td>68 - 70</td><td>68-70</td></tr>
                        <tr><td>71 - 73</td><td>71-73</td></tr>
                    </tbody>
                </table>
                <p class="advice" style="margin-top: 12px;"><i class="fas fa-info-circle"></i> للخواتم العريضة (أكثر من 6 مم)، اختر مقاساً أكبر بخطوة واحدة.</p>
            </div>
            <div class="size-calculator">
                <h3><i class="fas fa-calculator"></i> احسب مقاسك مباشرة</h3>
                <div class="calc-input">
                    <label>📏 أدخل محيط إصبعك (ملم)</label>
                    <input type="number" id="fingerCircumference" placeholder="مثال: 56" step="1">
                </div>
                <button id="calcBtn"><i class="fas fa-gem"></i> احصل على المقاس</button>
                <div class="calc-result">
                    <span>مقاسك المناسب :</span>
                    <div id="ringSizeResult" class="result-ring-size">--</div>
                </div>
                <div class="advice">
                    <i class="fas fa-lightbulb"></i> يُفضل القياس في نهاية اليوم (حجم الإصبع الطبيعي). كرر القياس مرتين للتأكد.
                </div>
            </div>
        </div>
    </div>

    <div style="background: #FFFFFF; border-radius: 28px; padding: 1.8rem; margin: 1rem 0;">
        <h3><i class="fas fa-star-of-life" style="color:#FFD966;"></i> نصائح ذهبية للحصول على مقاس دقيق</h3>
        <ul style="margin-right: 1.5rem; margin-top: 1rem;">
            <li><i class="fas fa-check-circle" style="color:#27ae60;"></i> استخدم خيطاً غير مطاطي أو شريط ورقي رفيع.</li>
            <li><i class="fas fa-check-circle" style="color:#27ae60;"></i> لا تشد الخيط بشدة – يجب أن يمر الخاتم بسهولة فوق المفصل.</li>
            <li><i class="fas fa-check-circle" style="color:#27ae60;"></i> قس نفس الإصبع 3 مرات وخذ المتوسط الحسابي.</li>
            <li><i class="fas fa-check-circle" style="color:#27ae60;"></i> إذا كنت بين مقاسين، اختر المقاس الأكبر قليلاً لراحة أفضل.</li>
        </ul>
    </div>

    <div class="footer-note">
        <i class="fas fa-magic"></i> الآن أنت جاهز لاختيار خاتمك المثالي من <strong>فضيات ابو عماد</strong> – خواتم فضة وأحجار كريمة بجودة عالية.
    </div>
</div>

<script>
    (function() {
        function getRingSizeFromCircumference(mm) {
            if (mm < 44) return "أصغر من 44";
            if (mm <= 46) return "44-46";
            if (mm <= 49) return "47-49";
            if (mm <= 52) return "50-52";
            if (mm <= 55) return "53-55";
            if (mm <= 58) return "56-58";
            if (mm <= 61) return "59-61";
            if (mm <= 64) return "62-64";
            if (mm <= 67) return "65-67";
            if (mm <= 70) return "68-70";
            if (mm <= 73) return "71-73";
            return "أكبر من 73 - تواصل معنا";
        }
        const input = document.getElementById('fingerCircumference');
        const btn = document.getElementById('calcBtn');
        const resultDiv = document.getElementById('ringSizeResult');
        function update() {
            let val = input.value.trim();
            if (val === "") { resultDiv.innerText = "--"; return; }
            let mm = parseFloat(val);
            if (isNaN(mm)) { resultDiv.innerText = "أدخل رقماً"; return; }
            resultDiv.innerText = getRingSizeFromCircumference(mm);
        }
        if (btn) btn.addEventListener('click', update);
        if (input) input.addEventListener('keypress', (e) => { if (e.key === 'Enter') update(); });
    })();
</script>

<?php include '../../includes/footer.php'; ?>