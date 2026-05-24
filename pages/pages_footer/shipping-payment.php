<?php $base_url = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) ? '/emad-stor/' : '/'; include '../../includes/header.php'; ?>

<style>
    .shipping-page {
        padding: 4rem 2rem;
        background-color: #F4F7FA;
        font-family: 'Playpen Sans Arabic', sans-serif;
    }
    .page-content {
        max-width: 900px;
        margin: 0 auto;
        background: white;
        padding: 3rem;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .page-content h1 {
        color: #0B1B2B;
        font-size: 2.5rem;
        text-align: center;
        margin-bottom: 1rem;
        position: relative;
        padding-bottom: 15px;
    }
    .page-content h1::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background-color: #FFD966;
        border-radius: 2px;
    }
    .page-content > p {
        text-align: center;
        color: #4A5568;
        font-size: 1.1rem;
        margin-bottom: 3rem;
    }
    .page-content h2 {
        color: #0B1B2B;
        font-size: 1.8rem;
        margin: 2.5rem 0 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .method-card {
        display: flex;
        align-items: flex-start;
        background: #F8FAFC;
        padding: 1.5rem;
        border-radius: 15px;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        border: 1px solid #E2E8F0;
    }
    .method-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        border-color: #FFD966;
    }
    .method-icon {
        background: #0B1B2B;
        color: #FFD966;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-left: 1.5rem;
        flex-shrink: 0;
    }
    .method-text h3 {
        color: #0B1B2B;
        font-size: 1.3rem;
        margin-bottom: 0.5rem;
    }
    .method-text p {
        color: #4A5568;
        line-height: 1.7;
        font-size: 1rem;
        margin: 0;
    }
    .note {
        background: rgba(255, 217, 102, 0.15);
        border-right: 4px solid #FFD966;
        padding: 1.5rem;
        border-radius: 10px;
        margin: 2rem 0;
        color: #0B1B2B;
        line-height: 1.6;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    th, td {
        padding: 1rem;
        text-align: right;
        border-bottom: 1px solid #E2E8F0;
    }
    th {
        background: #0B1B2B;
        color: white;
        font-weight: bold;
    }
    tr:last-child td {
        border-bottom: none;
    }
    tr:nth-child(even) {
        background-color: #F8FAFC;
    }
    .page-content a {
        color: #0B1B2B;
        font-weight: bold;
        text-decoration: underline;
        text-decoration-color: #FFD966;
        text-decoration-thickness: 3px;
        text-underline-offset: 4px;
        transition: color 0.3s ease;
    }
    .page-content a:hover {
        color: #d4af37;
    }
    
    .payment-steps {
        background: #F8FAFC;
        border: 1px dashed #E2E8F0;
        border-radius: 15px;
        padding: 2rem;
        margin: 2.5rem 0;
        box-shadow: inset 0 2px 10px rgba(0,0,0,0.02);
    }
    .payment-steps h3 {
        color: #0B1B2B;
        margin-bottom: 1.5rem;
        font-size: 1.4rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .step-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 1.2rem;
    }
    .step-item:last-child {
        margin-bottom: 0;
    }
    .step-number {
        background: #0B1B2B;
        color: #FFD966;
        font-weight: bold;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.1rem;
    }
    .step-text p {
        margin: 0;
        color: #4A5568;
        font-size: 1.05rem;
        line-height: 1.6;
    }
    .step-text strong {
        color: #0B1B2B;
        display: block;
        margin-bottom: 3px;
        font-size: 1.1rem;
    }

    @media (max-width: 768px) {
        .page-content {
            padding: 2rem 1.5rem;
        }
        .method-card {
            flex-direction: column;
            text-align: center;
            align-items: center;
        }
        .method-icon {
            margin-left: 0;
            margin-bottom: 1rem;
        }
        table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
    }
</style>

<div class="shipping-page">
    <div class="page-content">
        <h1>💳 طرق الدفع والشحن</h1>
        <p>نوفر عدة خيارات مريحة وآمنة لتتمكن من الشراء بسهولة. اختر ما يناسبك.</p>

        <h2>📦 طرق الشحن والتوصيل</h2>
        <div class="method-card">
            <div class="method-icon"><i class="fas fa-motorcycle"></i></div>
            <div class="method-text">
                <h3>1. تطبيق توصيل</h3>
                <p>التوصيل السريع لطلبك عبر تطبيق توصيل.</p>
            </div>
        </div>
        <div class="method-card">
            <div class="method-icon"><i class="fas fa-motorcycle"></i></div>
            <div class="method-text">
                <h3>2. تطبيق تساهيل</h3>
                <p>التوصيل الموثوق لطلبك عبر تطبيق تساهيل.</p>
            </div>
        </div>
        <div class="method-card">
            <div class="method-icon"><i class="fas fa-box-open"></i></div>
            <div class="method-text">
                <h3>3. تطبيق ناس</h3>
                <p>استلم طلبك عبر تطبيق ناس لضمان أفضل خدمة.</p>
            </div>
        </div>

        <div class="note" style="background: rgba(11, 27, 43, 0.05); border-right: 4px solid #0B1B2B;">
            <i class="fas fa-cogs" style="color: #0B1B2B; font-size: 1.2rem; margin-left: 5px;"></i> <strong>آلية الشحن المتبعة:</strong><br>
            عند قيام العميل بطلب منتج من المتجر الإلكتروني يتم حفظ بيانات الطلب والعنوان ورقم الهاتف داخل قاعدة البيانات، ثم يقوم صاحب المتجر بتجهيز المنتج وتغليفه، وبعدها يفتح تطبيق التوصيل المختار (توصيل، تساهيل، أو ناس) ويطلب مندوبًا مع إدخال موقع الاستلام وموقع العميل ورقمه. فيأتي المندوب لاستلام المنتج من المتجر ثم يقوم بتوصيله إلى العميل، وغالبًا يتم الدفع عند الاستلام ثم تصل قيمة الطلب إلى صاحب المتجر حسب الاتفاق مع شركة التوصيل.
        </div>

        <h2>💰 طرق الدفع</h2>
        
        <div class="method-card">
            <div class="method-icon"><i class="fas fa-hand-holding-usd"></i></div>
            <div class="method-text">
                <h3>1. الدفع عند الاستلام</h3>
                <p>الدفع نقداً للمندوب عند استلام المنتج. متاح لبعض المدن الرئيسية.</p>
            </div>
        </div>
        
        <div class="method-card">
            <div class="method-icon"><i class="fas fa-wallet"></i></div>
            <div class="method-text">
                <h3>2. المحافظ الإلكترونية المحلية</h3>
                <p>نقبل الدفع عبر المحافظ التالية:<br>
                - جيب<br>
                - جوالي<br>
                - ون كاش<br>
                - فلوسك<br>
                <em>(رقم نقطة المتجر للتحويل هو: <strong>560570</strong>)</em></p>
            </div>
        </div>

        <div class="method-card">
            <div class="method-icon"><i class="fas fa-exchange-alt"></i></div>
            <div class="method-text">
                <h3>3. عبر شبكات الصرافة</h3>
                <p>يمكنك التوجه إلى أقرب محل صرافة والتحويل عبر أي شبكة محلية (مثل النجم، العامري، إلخ...)<br>
                باسم: <strong>عماد عادل يحي القرماني</strong><br>
                على الأرقام التالية:<br>
                - <strong>771771815</strong><br>
                - <strong>771771813</strong></p>
            </div>
        </div>

        <div class="method-card">
            <div class="method-icon"><i class="fas fa-university"></i></div>
            <div class="method-text">
                <h3>4. تحويل بنكي محلي</h3>
                <p>الدفع عبر الحسابات البنكية (بنك الكريمي أو بنك اليمن):<br>
                - <strong>3018901659</strong> (دولار)<br>
                - <strong>3025607607</strong> (سعودي)<br>
                - <strong>3011379418</strong> (ريال يمني)</p>
            </div>
        </div>

        <div class="payment-steps">
            <h3><i class="fas fa-clipboard-check" style="color:#FFD966;"></i> آلية إتمام الدفع (للتحويلات والمحافظ)</h3>
            
            <div class="step-item">
                <div class="step-number">1</div>
                <div class="step-text">
                    <strong>إتمام الطلب</strong>
                    <p>قم باختيار المنتجات التي ترغب بها وأكمل عملية الطلب من سلة المشتريات، مع اختيار طريقة الدفع المناسبة لك (تحويل بنكي، محفظة إلكترونية، أو عبر الصرافة).</p>
                </div>
            </div>
            
            <div class="step-item">
                <div class="step-number">2</div>
                <div class="step-text">
                    <strong>إرسال المبلغ</strong>
                    <p>قم بتحويل قيمة الطلب إلى الحساب أو الرقم الذي سيظهر لك بعد تأكيد الطلب، أو الذي سيتم تزويدك به عبر خدمة العملاء.</p>
                </div>
            </div>
            
            <div class="step-item">
                <div class="step-number">3</div>
                <div class="step-text">
                    <strong>التقاط الشاشة (Screenshot)</strong>
                    <p>بعد نجاح عملية التحويل، قم بالتقاط صورة لشاشة الهاتف (سند التحويل الإلكتروني أو إشعار الإيداع) كإثبات لعملية الدفع.</p>
                </div>
            </div>
            
            <div class="step-item">
                <div class="step-number">4</div>
                <div class="step-text">
                    <strong>إرفاق صورة السند في صفحة الدفع</strong>
                    <p>قم برفع صورة السند مباشرة <strong>أثناء إتمام الطلب في صفحة الدفع</strong> (في الخطوة الثانية من إتمام الطلب). لن تحتاج لإرسالها عبر الواتساب أو صفحة التواصل، سيتم إرفاقها مع طلبك تلقائياً ليتم مطابقة الدفع واعتماده فوراً للبدء في الشحن.</p>
                </div>
            </div>
        </div>

        <div class="note">
            <i class="fas fa-info-circle" style="color: #FFD966; font-size: 1.2rem; margin-left: 5px;"></i> <strong>ملاحظة هامة:</strong> بالنسبة للدفع عبر التحويل البنكي أو المحفظة، يتم شحن المنتج بعد تأكيد استلام المبلغ. نحرص على سرعة المعالجة.
        </div>

        <h2>🚚 سياسة الشحن بالتفصيل</h2>
        <table>
            <tr><th>طريقة الشحن</th><th>التكلفة المتوقعة</th><th>المدة المتوقعة</th></tr>
            <tr><td>تطبيق توصيل</td><td>حسب تسعيرة التطبيق</td><td>سريع جداً (نفس اليوم)</td></tr>
            <tr><td>تطبيق تساهيل</td><td>حسب تسعيرة التطبيق</td><td>سريع جداً (نفس اليوم)</td></tr>
            <tr><td>تطبيق ناس</td><td>حسب تسعيرة التطبيق</td><td>سريع جداً (نفس اليوم)</td></tr>
        </table>

        <p style="text-align: right; font-size: 0.95rem; color: #718096; margin-top: 10px;">⏱️ *ملاحظة: المدة قد تزيد قليلاً بسبب إجراءات الجمارك أو التأخير من شركة الشحن.*</p>

        <h2>❓ لديك سؤال؟</h2>
        <p style="text-align: right; margin-top: 10px;">يمكنك مراسلتنا عبر <a href="addr.php">صفحة التواصل</a> أو واتساب لحساب شحن مخصص.</p>
    </div>
</div>
 
<?php include '../../includes/footer.php'; ?>
