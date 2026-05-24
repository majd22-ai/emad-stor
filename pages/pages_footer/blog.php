<?php $base_url = "/emad-stor/"; include "../../includes/header.php"; ?>
<style>
    .blog-page {
        background-color: #F4F7FA;
        font-family: 'Playpen Sans Arabic', sans-serif;
        padding-bottom: 4rem;
    }
    .blog-hero {
        background: linear-gradient(135deg, #0B1B2B 0%, #1a365d 100%);
        color: white;
        text-align: center;
        padding: 5rem 2rem;
        margin-bottom: 4rem;
        border-radius: 0 0 40px 40px;
        box-shadow: 0 10px 30px rgba(11, 27, 43, 0.15);
    }
    .blog-hero h1 {
        font-size: 3rem;
        color: #FFD966;
        margin-bottom: 1rem;
        font-weight: 800;
    }
    .blog-hero p {
        font-size: 1.2rem;
        color: #E2E8F0;
        max-width: 600px;
        margin: 0 auto;
        opacity: 0.9;
    }
    .blog-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }
    .blog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 2.5rem;
    }
    .blog-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border: 1px solid #E2E8F0;
        display: flex;
        flex-direction: column;
    }
    .blog-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        border-color: #FFD966;
    }
    .blog-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        border-bottom: 3px solid #FFD966;
    }
    .blog-content {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .blog-date {
        font-size: 0.85rem;
        color: #8A9BB0;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .blog-title {
        font-size: 1.3rem;
        color: #0B1B2B;
        margin-bottom: 1rem;
        line-height: 1.5;
    }
    .blog-excerpt {
        color: #4A627A;
        font-size: 0.95rem;
        line-height: 1.7;
        margin-bottom: 1.5rem;
        flex: 1;
    }
    .blog-read-more {
        display: inline-block;
        color: #0B1B2B;
        font-weight: bold;
        text-decoration: none;
        border-bottom: 2px solid #FFD966;
        padding-bottom: 2px;
        align-self: flex-start;
        transition: color 0.3s ease;
    }
    .blog-read-more:hover {
        color: #d4af37;
    }
    .blog-coming-soon {
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        margin-top: 3rem;
        border: 1px dashed #E2E8F0;
    }
    .blog-coming-soon i {
        font-size: 3rem;
        color: #FFD966;
        margin-bottom: 1rem;
    }
    .blog-coming-soon h2 {
        color: #0B1B2B;
        margin-bottom: 0.5rem;
    }
    .blog-coming-soon p {
        color: #4A627A;
    }
    
    @media (max-width: 768px) {
        .blog-hero {
            padding: 3.5rem 1.5rem;
            border-radius: 0 0 30px 30px;
        }
        .blog-hero h1 {
            font-size: 2.2rem;
        }
        .blog-container {
            padding: 0 1.5rem;
        }
    }
</style>

<div class="blog-page">
    <div class="blog-hero">
        <h1>مدونة فضيات ابو عماد</h1>
        <p>نشاركك شغفنا ومعرفتنا بكل ما يخص الأحجار الكريمة، الفضة، وتاريخها العريق.</p>
    </div>

    <div class="blog-container">
        <!-- مقالات تجريبية (Placeholder) -->
        <div class="blog-grid">
            
            <div class="blog-card">
                <img src="<?php echo $base_url; ?>assets/images/منتجات/blog1.jpeg" alt="العقيق اليماني" class="blog-image" onerror="this.src='https://placehold.co/600x400/0B1B2B/FFD966?text=العقيق+اليماني'">
                <div class="blog-content">
                    <div class="blog-date"><i class="far fa-calendar-alt"></i> 18 مايو، 2026</div>
                    <h3 class="blog-title">كيف تميز العقيق اليماني الأصلي عن المقلد؟</h3>
                    <p class="blog-excerpt">دليل شامل لتعلم طرق التمييز بين حجر العقيق اليماني الطبيعي الأصيل وبين الأحجار الزجاجية أو المقلدة المنتشرة في الأسواق.</p>
                    <a href="article.php?id=1" class="blog-read-more">اقرأ المزيد <i class="fas fa-arrow-left" style="font-size: 0.8rem; margin-right: 5px;"></i></a>
                </div>
            </div>

            <div class="blog-card">
                <img src="<?php echo $base_url; ?>assets/images/منتجات/P22.jpg" alt="العناية بالفضة" class="blog-image" onerror="this.src='https://placehold.co/600x400/0B1B2B/FFD966?text=العناية+بالفضة'">
                <div class="blog-content">
                    <div class="blog-date"><i class="far fa-calendar-alt"></i> 10 أبريل، 2026</div>
                    <h3 class="blog-title">أفضل 5 طرق للعناية بالمجوهرات الفضية</h3>
                    <p class="blog-excerpt">تعرف على الطرق المنزلية البسيطة والآمنة لتنظيف خواتمك ومقتنياتك الفضية لتعود لامعة وبراقة كما كانت أول يوم.</p>
                    <a href="article.php?id=2" class="blog-read-more">اقرأ المزيد <i class="fas fa-arrow-left" style="font-size: 0.8rem; margin-right: 5px;"></i></a>
                </div>
            </div>

            <div class="blog-card">
                <img src="<?php echo $base_url; ?>assets/images/منتجات/p35.jpg" alt="تاريخ الفضة" class="blog-image" onerror="this.src='https://placehold.co/600x400/0B1B2B/FFD966?text=تاريخ+الفضة'">
                <div class="blog-content">
                    <div class="blog-date"><i class="far fa-calendar-alt"></i> 25 مارس، 2026</div>
                    <h3 class="blog-title">تاريخ الفضة وصياغتها في الثقافة اليمنية</h3>
                    <p class="blog-excerpt">رحلة عبر الزمن نستكشف فيها دور الفضة في التراث اليمني، وأسرار النقوش التقليدية التي يتوارثها الصاغة جيلاً بعد جيل.</p>
                    <a href="article.php?id=3" class="blog-read-more">اقرأ المزيد <i class="fas fa-arrow-left" style="font-size: 0.8rem; margin-right: 5px;"></i></a>
                </div>
            </div>

        </div>

        <div class="blog-coming-soon">
            <i class="fas fa-pen-fancy"></i>
            <h2>المزيد من المقالات قريباً...</h2>
            <p>نعمل حالياً على كتابة محتوى حصري وغني بالمعلومات. ابقوا بالقرب!</p>
        </div>
    </div>
</div>

<?php include "../../includes/footer.php"; ?>