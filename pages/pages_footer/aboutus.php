<?php $base_url = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) ? '/emad-stor/' : '/'; include '../../includes/header.php'; ?>

<style>
  .about-us {
    padding: 0 0 4rem 0;
    background-color: #F4F7FA;
    font-family: 'Playpen Sans Arabic', sans-serif;
  }
  .about-hero {
    background: linear-gradient(135deg, #0B1B2B 0%, #1a365d 100%);
    color: white;
    text-align: center;
    padding: 6rem 2rem;
    border-radius: 0 0 50px 50px;
    margin-bottom: 4rem;
    box-shadow: 0 10px 30px rgba(11, 27, 43, 0.15);
  }
  .about-hero h1 {
    font-size: 3rem;
    color: #FFD966;
    margin-bottom: 1rem;
    font-weight: 800;
  }
  .about-hero p {
    font-size: 1.2rem;
    color: #E2E8F0;
    max-width: 600px;
    margin: 0 auto;
    opacity: 0.9;
  }
  .about-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
  }
  .about-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
    margin-bottom: 5rem;
  }
  .about-text h2 {
    color: #0B1B2B;
    font-size: 2.2rem;
    margin-bottom: 1.5rem;
    position: relative;
    padding-bottom: 10px;
  }
  .about-text h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    right: 0;
    width: 80px;
    height: 4px;
    background-color: #FFD966;
    border-radius: 2px;
  }
  .about-text p {
    font-size: 1.1rem;
    color: #4A5568;
    line-height: 1.8;
    margin-bottom: 1.2rem;
  }
  .about-text strong {
    color: #0B1B2B;
  }
  .about-image {
    position: relative;
  }
  .about-image img {
    width: 100%;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
  }
  .about-image:hover img {
    transform: translateY(-10px);
  }
  .about-image::before {
    content: '';
    position: absolute;
    top: -20px;
    right: -20px;
    width: 100%;
    height: 100%;
    border: 3px solid #FFD966;
    border-radius: 20px;
    z-index: -1;
  }
  
  .mission-values {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-bottom: 5rem;
  }
  .mission-card {
    background: white;
    padding: 2.5rem 2rem;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    border-top: 4px solid transparent;
  }
  .mission-card:hover {
    transform: translateY(-10px);
    border-top-color: #FFD966;
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
  }
  .mission-card h3 {
    font-size: 1.4rem;
    color: #0B1B2B;
    margin-bottom: 1rem;
  }
  .mission-card p {
    color: #718096;
    font-size: 1rem;
    line-height: 1.6;
  }
  
  .quality-section {
    background: #0B1B2B;
    border-radius: 30px;
    padding: 4rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: white;
    box-shadow: 0 20px 50px rgba(11, 27, 43, 0.2);
    flex-wrap: wrap;
    gap: 3rem;
  }
  .quality-text {
    flex: 1;
    min-width: 300px;
  }
  .quality-text h2 {
    font-size: 2.5rem;
    color: #FFD966;
    margin-bottom: 1rem;
  }
  .quality-text p {
    font-size: 1.1rem;
    color: #E2E8F0;
    margin-bottom: 2rem;
    opacity: 0.9;
    line-height: 1.8;
  }
  .btn-shop {
    display: inline-block;
    background-color: #FFD966;
    color: #0B1B2B;
    padding: 12px 30px;
    border-radius: 30px;
    font-weight: bold;
    text-decoration: none;
    font-size: 1.1rem;
    transition: all 0.3s ease;
  }
  .btn-shop:hover {
    background-color: #e6c35c;
    transform: scale(1.05);
  }
  .quality-stats {
    display: flex;
    gap: 3rem;
    flex: 1;
    justify-content: center;
    min-width: 300px;
  }
  .stat {
    text-align: center;
  }
  .stat h3 {
    font-size: 2.5rem;
    color: #FFD966;
    margin-bottom: 0.5rem;
    font-weight: 800;
  }
  .stat p {
    color: #E2E8F0;
    font-size: 1.1rem;
  }

  @media (max-width: 992px) {
    .about-grid {
      grid-template-columns: 1fr;
      gap: 3rem;
    }
    .quality-section {
      padding: 3rem 2rem;
      flex-direction: column;
      text-align: center;
    }
    .quality-stats {
      width: 100%;
      flex-wrap: wrap;
    }
  }
  @media (max-width: 768px) {
    .about-hero {
      padding: 4rem 1.5rem;
      border-radius: 0 0 30px 30px;
    }
    .about-hero h1 {
      font-size: 2.2rem;
    }
    .about-text h2 {
      font-size: 1.8rem;
    }
    .stat h3 {
      font-size: 2rem;
    }
    .quality-stats {
      gap: 1.5rem;
    }
  }
</style>

<section class="about-us">
  <div class="about-hero">
    <h1>من نحن</h1>
    <p>حكاية عشق للفضة والعقيق تروى بلمسة عصرية</p>
  </div>

  <div class="about-container">
    <div class="about-grid">
      <div class="about-text">
        <h2>فضيات ابو عماد .. حيث تروي الأحجار قصتها</h2>
        <p>
          منذ أكثر من <strong>١٥ عاماً</strong> ونحن نسعى لتقديم أرقى
          تشكيلات الخواتم والأحجار الكريمة والعقيق اليمني الأصيل. تأسست
          <strong>فضيات ابو عماد</strong> على يد فريق من خبراء الأحجار الكريمة وعشاق الجمال.
        </p>
        <p>
          كل قطعة يتم اختيارها بعناية من أفضل المناجم حول العالم وتصميمها بأيدي محترفين.
        </p>
        <p>
          نؤمن أن الحجر الكريم يعكس شخصية صاحبه ونقدم <strong>شهادات أصالة</strong> لكل قطعة.
        </p>
      </div>
      <div class="about-image">
        <img src="../../assets/images/abut.jpeg" alt="خواتم وأحجار كريمة" onerror="this.src='https://placehold.co/600x400/0B1B2B/FFD966?text=فضيات+ابو+عماد'">
      </div>
    </div>

    <div class="mission-values">
      <div class="mission-card">
        <h3>✨ رسالتنا</h3>
        <p>تقديم أحجار أصلية وتجربة تسوق موثوقة بكل شفافية.</p>
      </div>
      <div class="mission-card">
        <h3>🔍 أصالة مضمونة</h3>
        <p>فحص دقيق وشهادات معتمدة لكل حجر كريم.</p>
      </div>
      <div class="mission-card">
        <h3>💎 تصاميم حصرية</h3>
        <p>تصاميم فريدة تعبر عن شخصيتك وذوقك الخاص.</p>
      </div>
      <div class="mission-card">
        <h3>🚚 خدمة مميزة</h3>
        <p>تغليف فاخر وشحن سريع مع متابعة شحن لحظة بلحظة.</p>
      </div>
    </div>

    <div class="quality-section">
      <div class="quality-text">
        <h2>جودة لا تُضاهى</h2>
        <p>
          نقدم عقيق يماني، زمرد، ياقوت،   وأحجار كريمة اخرى بأعلى درجات الجودة والأصالة.
        </p>
        <a href="../../index.php" class="btn-shop">تسوق الآن</a>
      </div>
      <div class="quality-stats">
        <div class="stat">
          <h3>+٢٬٠٠٠</h3>
          <p>عميل سعيد</p>
        </div>
        <div class="stat">
          <h3>+١٥</h3>
          <p>سنة خبرة</p>
        </div>
        <div class="stat">
          <h3>١٠٠٪</h3>
          <p>أصالة مضمونة</p>
        </div>
      </div>
    </div>
  </div>
</section>
 
<?php include '../../includes/footer.php'; ?>
