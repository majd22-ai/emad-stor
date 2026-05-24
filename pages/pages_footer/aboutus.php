<?php $base_url = (strpos(<?php $base_url = '/emad-stor/'; include '../../includes/header.php'; ?>SERVER['HTTP_HOST'], 'localhost') !== false || strpos(<?php $base_url = '/emad-stor/'; include '../../includes/header.php'; ?>SERVER['HTTP_HOST'], '127.0.0.1') !== false) ? '/emad-stor/' : '/'; include '../../includes/header.php'; ?>

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
    <h1>ظ…ظ† ظ†ط­ظ†</h1>
    <p>ط­ظƒط§ظٹط© ط¹ط´ظ‚ ظ„ظ„ظپط¶ط© ظˆط§ظ„ط¹ظ‚ظٹظ‚ طھط±ظˆظ‰ ط¨ظ„ظ…ط³ط© ط¹طµط±ظٹط©</p>
  </div>

  <div class="about-container">
    <div class="about-grid">
      <div class="about-text">
        <h2>ظپط¶ظٹط§طھ ط§ط¨ظˆ ط¹ظ…ط§ط¯ .. ط­ظٹط« طھط±ظˆظٹ ط§ظ„ط£ط­ط¬ط§ط± ظ‚طµطھظ‡ط§</h2>
        <p>
          ظ…ظ†ط° ط£ظƒط«ط± ظ…ظ† <strong>ظ،ظ¥ ط¹ط§ظ…ط§ظ‹</strong> ظˆظ†ط­ظ† ظ†ط³ط¹ظ‰ ظ„طھظ‚ط¯ظٹظ… ط£ط±ظ‚ظ‰
          طھط´ظƒظٹظ„ط§طھ ط§ظ„ط®ظˆط§طھظ… ظˆط§ظ„ط£ط­ط¬ط§ط± ط§ظ„ظƒط±ظٹظ…ط© ظˆط§ظ„ط¹ظ‚ظٹظ‚ ط§ظ„ظٹظ…ظ†ظٹ ط§ظ„ط£طµظٹظ„. طھط£ط³ط³طھ
          <strong>ظپط¶ظٹط§طھ ط§ط¨ظˆ ط¹ظ…ط§ط¯</strong> ط¹ظ„ظ‰ ظٹط¯ ظپط±ظٹظ‚ ظ…ظ† ط®ط¨ط±ط§ط، ط§ظ„ط£ط­ط¬ط§ط± ط§ظ„ظƒط±ظٹظ…ط© ظˆط¹ط´ط§ظ‚ ط§ظ„ط¬ظ…ط§ظ„.
        </p>
        <p>
          ظƒظ„ ظ‚ط·ط¹ط© ظٹطھظ… ط§ط®طھظٹط§ط±ظ‡ط§ ط¨ط¹ظ†ط§ظٹط© ظ…ظ† ط£ظپط¶ظ„ ط§ظ„ظ…ظ†ط§ط¬ظ… ط­ظˆظ„ ط§ظ„ط¹ط§ظ„ظ… ظˆطھطµظ…ظٹظ…ظ‡ط§ ط¨ط£ظٹط¯ظٹ ظ…ط­طھط±ظپظٹظ†.
        </p>
        <p>
          ظ†ط¤ظ…ظ† ط£ظ† ط§ظ„ط­ط¬ط± ط§ظ„ظƒط±ظٹظ… ظٹط¹ظƒط³ ط´ط®طµظٹط© طµط§ط­ط¨ظ‡ ظˆظ†ظ‚ط¯ظ… <strong>ط´ظ‡ط§ط¯ط§طھ ط£طµط§ظ„ط©</strong> ظ„ظƒظ„ ظ‚ط·ط¹ط©.
        </p>
      </div>
      <div class="about-image">
        <img src="../../assets/images/abut.jpeg" alt="ط®ظˆط§طھظ… ظˆط£ط­ط¬ط§ط± ظƒط±ظٹظ…ط©" onerror="this.src='https://placehold.co/600x400/0B1B2B/FFD966?text=ظپط¶ظٹط§طھ+ط§ط¨ظˆ+ط¹ظ…ط§ط¯'">
      </div>
    </div>

    <div class="mission-values">
      <div class="mission-card">
        <h3>âœ¨ ط±ط³ط§ظ„طھظ†ط§</h3>
        <p>طھظ‚ط¯ظٹظ… ط£ط­ط¬ط§ط± ط£طµظ„ظٹط© ظˆطھط¬ط±ط¨ط© طھط³ظˆظ‚ ظ…ظˆط«ظˆظ‚ط© ط¨ظƒظ„ ط´ظپط§ظپظٹط©.</p>
      </div>
      <div class="mission-card">
        <h3>ًں”چ ط£طµط§ظ„ط© ظ…ط¶ظ…ظˆظ†ط©</h3>
        <p>ظپط­طµ ط¯ظ‚ظٹظ‚ ظˆط´ظ‡ط§ط¯ط§طھ ظ…ط¹طھظ…ط¯ط© ظ„ظƒظ„ ط­ط¬ط± ظƒط±ظٹظ….</p>
      </div>
      <div class="mission-card">
        <h3>ًں’ژ طھطµط§ظ…ظٹظ… ط­طµط±ظٹط©</h3>
        <p>طھطµط§ظ…ظٹظ… ظپط±ظٹط¯ط© طھط¹ط¨ط± ط¹ظ† ط´ط®طµظٹطھظƒ ظˆط°ظˆظ‚ظƒ ط§ظ„ط®ط§طµ.</p>
      </div>
      <div class="mission-card">
        <h3>ًںڑڑ ط®ط¯ظ…ط© ظ…ظ…ظٹط²ط©</h3>
        <p>طھط؛ظ„ظٹظپ ظپط§ط®ط± ظˆط´ط­ظ† ط³ط±ظٹط¹ ظ…ط¹ ظ…طھط§ط¨ط¹ط© ط´ط­ظ† ظ„ط­ط¸ط© ط¨ظ„ط­ط¸ط©.</p>
      </div>
    </div>

    <div class="quality-section">
      <div class="quality-text">
        <h2>ط¬ظˆط¯ط© ظ„ط§ طھظڈط¶ط§ظ‡ظ‰</h2>
        <p>
          ظ†ظ‚ط¯ظ… ط¹ظ‚ظٹظ‚ ظٹظ…ط§ظ†ظٹطŒ ط²ظ…ط±ط¯طŒ ظٹط§ظ‚ظˆطھطŒ   ظˆط£ط­ط¬ط§ط± ظƒط±ظٹظ…ط© ط§ط®ط±ظ‰ ط¨ط£ط¹ظ„ظ‰ ط¯ط±ط¬ط§طھ ط§ظ„ط¬ظˆط¯ط© ظˆط§ظ„ط£طµط§ظ„ط©.
        </p>
        <a href="../../index.php" class="btn-shop">طھط³ظˆظ‚ ط§ظ„ط¢ظ†</a>
      </div>
      <div class="quality-stats">
        <div class="stat">
          <h3>+ظ¢ظ¬ظ ظ ظ </h3>
          <p>ط¹ظ…ظٹظ„ ط³ط¹ظٹط¯</p>
        </div>
        <div class="stat">
          <h3>+ظ،ظ¥</h3>
          <p>ط³ظ†ط© ط®ط¨ط±ط©</p>
        </div>
        <div class="stat">
          <h3>ظ،ظ ظ ظھ</h3>
          <p>ط£طµط§ظ„ط© ظ…ط¶ظ…ظˆظ†ط©</p>
        </div>
      </div>
    </div>
  </div>
</section>
 
<?php include '../../includes/footer.php'; ?>
