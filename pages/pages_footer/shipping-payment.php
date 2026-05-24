<?php $base_url = (strpos(<?php $base_url = '/emad-stor/'; include '../../includes/header.php'; ?>SERVER['HTTP_HOST'], 'localhost') !== false || strpos(<?php $base_url = '/emad-stor/'; include '../../includes/header.php'; ?>SERVER['HTTP_HOST'], '127.0.0.1') !== false) ? '/emad-stor/' : '/'; include '../../includes/header.php'; ?>

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
        <h1>ًں’³ ط·ط±ظ‚ ط§ظ„ط¯ظپط¹ ظˆط§ظ„ط´ط­ظ†</h1>
        <p>ظ†ظˆظپط± ط¹ط¯ط© ط®ظٹط§ط±ط§طھ ظ…ط±ظٹط­ط© ظˆط¢ظ…ظ†ط© ظ„طھطھظ…ظƒظ† ظ…ظ† ط§ظ„ط´ط±ط§ط، ط¨ط³ظ‡ظˆظ„ط©. ط§ط®طھط± ظ…ط§ ظٹظ†ط§ط³ط¨ظƒ.</p>

        <h2>ًں“¦ ط·ط±ظ‚ ط§ظ„ط´ط­ظ† ظˆط§ظ„طھظˆطµظٹظ„</h2>
        <div class="method-card">
            <div class="method-icon"><i class="fas fa-motorcycle"></i></div>
            <div class="method-text">
                <h3>1. طھط·ط¨ظٹظ‚ طھظˆطµظٹظ„</h3>
                <p>ط§ظ„طھظˆطµظٹظ„ ط§ظ„ط³ط±ظٹط¹ ظ„ط·ظ„ط¨ظƒ ط¹ط¨ط± طھط·ط¨ظٹظ‚ طھظˆطµظٹظ„.</p>
            </div>
        </div>
        <div class="method-card">
            <div class="method-icon"><i class="fas fa-motorcycle"></i></div>
            <div class="method-text">
                <h3>2. طھط·ط¨ظٹظ‚ طھط³ط§ظ‡ظٹظ„</h3>
                <p>ط§ظ„طھظˆطµظٹظ„ ط§ظ„ظ…ظˆط«ظˆظ‚ ظ„ط·ظ„ط¨ظƒ ط¹ط¨ط± طھط·ط¨ظٹظ‚ طھط³ط§ظ‡ظٹظ„.</p>
            </div>
        </div>
        <div class="method-card">
            <div class="method-icon"><i class="fas fa-box-open"></i></div>
            <div class="method-text">
                <h3>3. طھط·ط¨ظٹظ‚ ظ†ط§ط³</h3>
                <p>ط§ط³طھظ„ظ… ط·ظ„ط¨ظƒ ط¹ط¨ط± طھط·ط¨ظٹظ‚ ظ†ط§ط³ ظ„ط¶ظ…ط§ظ† ط£ظپط¶ظ„ ط®ط¯ظ…ط©.</p>
            </div>
        </div>

        <div class="note" style="background: rgba(11, 27, 43, 0.05); border-right: 4px solid #0B1B2B;">
            <i class="fas fa-cogs" style="color: #0B1B2B; font-size: 1.2rem; margin-left: 5px;"></i> <strong>ط¢ظ„ظٹط© ط§ظ„ط´ط­ظ† ط§ظ„ظ…طھط¨ط¹ط©:</strong><br>
            ط¹ظ†ط¯ ظ‚ظٹط§ظ… ط§ظ„ط¹ظ…ظٹظ„ ط¨ط·ظ„ط¨ ظ…ظ†طھط¬ ظ…ظ† ط§ظ„ظ…طھط¬ط± ط§ظ„ط¥ظ„ظƒطھط±ظˆظ†ظٹ ظٹطھظ… ط­ظپط¸ ط¨ظٹط§ظ†ط§طھ ط§ظ„ط·ظ„ط¨ ظˆط§ظ„ط¹ظ†ظˆط§ظ† ظˆط±ظ‚ظ… ط§ظ„ظ‡ط§طھظپ ط¯ط§ط®ظ„ ظ‚ط§ط¹ط¯ط© ط§ظ„ط¨ظٹط§ظ†ط§طھطŒ ط«ظ… ظٹظ‚ظˆظ… طµط§ط­ط¨ ط§ظ„ظ…طھط¬ط± ط¨طھط¬ظ‡ظٹط² ط§ظ„ظ…ظ†طھط¬ ظˆطھط؛ظ„ظٹظپظ‡طŒ ظˆط¨ط¹ط¯ظ‡ط§ ظٹظپطھط­ طھط·ط¨ظٹظ‚ ط§ظ„طھظˆطµظٹظ„ ط§ظ„ظ…ط®طھط§ط± (طھظˆطµظٹظ„طŒ طھط³ط§ظ‡ظٹظ„طŒ ط£ظˆ ظ†ط§ط³) ظˆظٹط·ظ„ط¨ ظ…ظ†ط¯ظˆط¨ظ‹ط§ ظ…ط¹ ط¥ط¯ط®ط§ظ„ ظ…ظˆظ‚ط¹ ط§ظ„ط§ط³طھظ„ط§ظ… ظˆظ…ظˆظ‚ط¹ ط§ظ„ط¹ظ…ظٹظ„ ظˆط±ظ‚ظ…ظ‡. ظپظٹط£طھظٹ ط§ظ„ظ…ظ†ط¯ظˆط¨ ظ„ط§ط³طھظ„ط§ظ… ط§ظ„ظ…ظ†طھط¬ ظ…ظ† ط§ظ„ظ…طھط¬ط± ط«ظ… ظٹظ‚ظˆظ… ط¨طھظˆطµظٹظ„ظ‡ ط¥ظ„ظ‰ ط§ظ„ط¹ظ…ظٹظ„طŒ ظˆط؛ط§ظ„ط¨ظ‹ط§ ظٹطھظ… ط§ظ„ط¯ظپط¹ ط¹ظ†ط¯ ط§ظ„ط§ط³طھظ„ط§ظ… ط«ظ… طھطµظ„ ظ‚ظٹظ…ط© ط§ظ„ط·ظ„ط¨ ط¥ظ„ظ‰ طµط§ط­ط¨ ط§ظ„ظ…طھط¬ط± ط­ط³ط¨ ط§ظ„ط§طھظپط§ظ‚ ظ…ط¹ ط´ط±ظƒط© ط§ظ„طھظˆطµظٹظ„.
        </div>

        <h2>ًں’° ط·ط±ظ‚ ط§ظ„ط¯ظپط¹</h2>
        
        <div class="method-card">
            <div class="method-icon"><i class="fas fa-hand-holding-usd"></i></div>
            <div class="method-text">
                <h3>1. ط§ظ„ط¯ظپط¹ ط¹ظ†ط¯ ط§ظ„ط§ط³طھظ„ط§ظ…</h3>
                <p>ط§ظ„ط¯ظپط¹ ظ†ظ‚ط¯ط§ظ‹ ظ„ظ„ظ…ظ†ط¯ظˆط¨ ط¹ظ†ط¯ ط§ط³طھظ„ط§ظ… ط§ظ„ظ…ظ†طھط¬. ظ…طھط§ط­ ظ„ط¨ط¹ط¶ ط§ظ„ظ…ط¯ظ† ط§ظ„ط±ط¦ظٹط³ظٹط©.</p>
            </div>
        </div>
        
        <div class="method-card">
            <div class="method-icon"><i class="fas fa-wallet"></i></div>
            <div class="method-text">
                <h3>2. ط§ظ„ظ…ط­ط§ظپط¸ ط§ظ„ط¥ظ„ظƒطھط±ظˆظ†ظٹط© ط§ظ„ظ…ط­ظ„ظٹط©</h3>
                <p>ظ†ظ‚ط¨ظ„ ط§ظ„ط¯ظپط¹ ط¹ط¨ط± ط§ظ„ظ…ط­ط§ظپط¸ ط§ظ„طھط§ظ„ظٹط©:<br>
                - ط¬ظٹط¨<br>
                - ط¬ظˆط§ظ„ظٹ<br>
                - ظˆظ† ظƒط§ط´<br>
                - ظپظ„ظˆط³ظƒ<br>
                <em>(ط±ظ‚ظ… ظ†ظ‚ط·ط© ط§ظ„ظ…طھط¬ط± ظ„ظ„طھط­ظˆظٹظ„ ظ‡ظˆ: <strong>560570</strong>)</em></p>
            </div>
        </div>

        <div class="method-card">
            <div class="method-icon"><i class="fas fa-exchange-alt"></i></div>
            <div class="method-text">
                <h3>3. ط¹ط¨ط± ط´ط¨ظƒط§طھ ط§ظ„طµط±ط§ظپط©</h3>
                <p>ظٹظ…ظƒظ†ظƒ ط§ظ„طھظˆط¬ظ‡ ط¥ظ„ظ‰ ط£ظ‚ط±ط¨ ظ…ط­ظ„ طµط±ط§ظپط© ظˆط§ظ„طھط­ظˆظٹظ„ ط¹ط¨ط± ط£ظٹ ط´ط¨ظƒط© ظ…ط­ظ„ظٹط© (ظ…ط«ظ„ ط§ظ„ظ†ط¬ظ…طŒ ط§ظ„ط¹ط§ظ…ط±ظٹطŒ ط¥ظ„ط®...)<br>
                ط¨ط§ط³ظ…: <strong>ط¹ظ…ط§ط¯ ط¹ط§ط¯ظ„ ظٹط­ظٹ ط§ظ„ظ‚ط±ظ…ط§ظ†ظٹ</strong><br>
                ط¹ظ„ظ‰ ط§ظ„ط£ط±ظ‚ط§ظ… ط§ظ„طھط§ظ„ظٹط©:<br>
                - <strong>771771815</strong><br>
                - <strong>771771813</strong></p>
            </div>
        </div>

        <div class="method-card">
            <div class="method-icon"><i class="fas fa-university"></i></div>
            <div class="method-text">
                <h3>4. طھط­ظˆظٹظ„ ط¨ظ†ظƒظٹ ظ…ط­ظ„ظٹ</h3>
                <p>ط§ظ„ط¯ظپط¹ ط¹ط¨ط± ط§ظ„ط­ط³ط§ط¨ط§طھ ط§ظ„ط¨ظ†ظƒظٹط© (ط¨ظ†ظƒ ط§ظ„ظƒط±ظٹظ…ظٹ ط£ظˆ ط¨ظ†ظƒ ط§ظ„ظٹظ…ظ†):<br>
                - <strong>3018901659</strong> (ط¯ظˆظ„ط§ط±)<br>
                - <strong>3025607607</strong> (ط³ط¹ظˆط¯ظٹ)<br>
                - <strong>3011379418</strong> (ط±ظٹط§ظ„ ظٹظ…ظ†ظٹ)</p>
            </div>
        </div>

        <div class="payment-steps">
            <h3><i class="fas fa-clipboard-check" style="color:#FFD966;"></i> ط¢ظ„ظٹط© ط¥طھظ…ط§ظ… ط§ظ„ط¯ظپط¹ (ظ„ظ„طھط­ظˆظٹظ„ط§طھ ظˆط§ظ„ظ…ط­ط§ظپط¸)</h3>
            
            <div class="step-item">
                <div class="step-number">1</div>
                <div class="step-text">
                    <strong>ط¥طھظ…ط§ظ… ط§ظ„ط·ظ„ط¨</strong>
                    <p>ظ‚ظ… ط¨ط§ط®طھظٹط§ط± ط§ظ„ظ…ظ†طھط¬ط§طھ ط§ظ„طھظٹ طھط±ط؛ط¨ ط¨ظ‡ط§ ظˆط£ظƒظ…ظ„ ط¹ظ…ظ„ظٹط© ط§ظ„ط·ظ„ط¨ ظ…ظ† ط³ظ„ط© ط§ظ„ظ…ط´طھط±ظٹط§طھطŒ ظ…ط¹ ط§ط®طھظٹط§ط± ط·ط±ظٹظ‚ط© ط§ظ„ط¯ظپط¹ ط§ظ„ظ…ظ†ط§ط³ط¨ط© ظ„ظƒ (طھط­ظˆظٹظ„ ط¨ظ†ظƒظٹطŒ ظ…ط­ظپط¸ط© ط¥ظ„ظƒطھط±ظˆظ†ظٹط©طŒ ط£ظˆ ط¹ط¨ط± ط§ظ„طµط±ط§ظپط©).</p>
                </div>
            </div>
            
            <div class="step-item">
                <div class="step-number">2</div>
                <div class="step-text">
                    <strong>ط¥ط±ط³ط§ظ„ ط§ظ„ظ…ط¨ظ„ط؛</strong>
                    <p>ظ‚ظ… ط¨طھط­ظˆظٹظ„ ظ‚ظٹظ…ط© ط§ظ„ط·ظ„ط¨ ط¥ظ„ظ‰ ط§ظ„ط­ط³ط§ط¨ ط£ظˆ ط§ظ„ط±ظ‚ظ… ط§ظ„ط°ظٹ ط³ظٹط¸ظ‡ط± ظ„ظƒ ط¨ط¹ط¯ طھط£ظƒظٹط¯ ط§ظ„ط·ظ„ط¨طŒ ط£ظˆ ط§ظ„ط°ظٹ ط³ظٹطھظ… طھط²ظˆظٹط¯ظƒ ط¨ظ‡ ط¹ط¨ط± ط®ط¯ظ…ط© ط§ظ„ط¹ظ…ظ„ط§ط،.</p>
                </div>
            </div>
            
            <div class="step-item">
                <div class="step-number">3</div>
                <div class="step-text">
                    <strong>ط§ظ„طھظ‚ط§ط· ط§ظ„ط´ط§ط´ط© (Screenshot)</strong>
                    <p>ط¨ط¹ط¯ ظ†ط¬ط§ط­ ط¹ظ…ظ„ظٹط© ط§ظ„طھط­ظˆظٹظ„طŒ ظ‚ظ… ط¨ط§ظ„طھظ‚ط§ط· طµظˆط±ط© ظ„ط´ط§ط´ط© ط§ظ„ظ‡ط§طھظپ (ط³ظ†ط¯ ط§ظ„طھط­ظˆظٹظ„ ط§ظ„ط¥ظ„ظƒطھط±ظˆظ†ظٹ ط£ظˆ ط¥ط´ط¹ط§ط± ط§ظ„ط¥ظٹط¯ط§ط¹) ظƒط¥ط«ط¨ط§طھ ظ„ط¹ظ…ظ„ظٹط© ط§ظ„ط¯ظپط¹.</p>
                </div>
            </div>
            
            <div class="step-item">
                <div class="step-number">4</div>
                <div class="step-text">
                    <strong>ط¥ط±ظپط§ظ‚ طµظˆط±ط© ط§ظ„ط³ظ†ط¯ ظپظٹ طµظپط­ط© ط§ظ„ط¯ظپط¹</strong>
                    <p>ظ‚ظ… ط¨ط±ظپط¹ طµظˆط±ط© ط§ظ„ط³ظ†ط¯ ظ…ط¨ط§ط´ط±ط© <strong>ط£ط«ظ†ط§ط، ط¥طھظ…ط§ظ… ط§ظ„ط·ظ„ط¨ ظپظٹ طµظپط­ط© ط§ظ„ط¯ظپط¹</strong> (ظپظٹ ط§ظ„ط®ط·ظˆط© ط§ظ„ط«ط§ظ†ظٹط© ظ…ظ† ط¥طھظ…ط§ظ… ط§ظ„ط·ظ„ط¨). ظ„ظ† طھط­طھط§ط¬ ظ„ط¥ط±ط³ط§ظ„ظ‡ط§ ط¹ط¨ط± ط§ظ„ظˆط§طھط³ط§ط¨ ط£ظˆ طµظپط­ط© ط§ظ„طھظˆط§طµظ„طŒ ط³ظٹطھظ… ط¥ط±ظپط§ظ‚ظ‡ط§ ظ…ط¹ ط·ظ„ط¨ظƒ طھظ„ظ‚ط§ط¦ظٹط§ظ‹ ظ„ظٹطھظ… ظ…ط·ط§ط¨ظ‚ط© ط§ظ„ط¯ظپط¹ ظˆط§ط¹طھظ…ط§ط¯ظ‡ ظپظˆط±ط§ظ‹ ظ„ظ„ط¨ط¯ط، ظپظٹ ط§ظ„ط´ط­ظ†.</p>
                </div>
            </div>
        </div>

        <div class="note">
            <i class="fas fa-info-circle" style="color: #FFD966; font-size: 1.2rem; margin-left: 5px;"></i> <strong>ظ…ظ„ط§ط­ط¸ط© ظ‡ط§ظ…ط©:</strong> ط¨ط§ظ„ظ†ط³ط¨ط© ظ„ظ„ط¯ظپط¹ ط¹ط¨ط± ط§ظ„طھط­ظˆظٹظ„ ط§ظ„ط¨ظ†ظƒظٹ ط£ظˆ ط§ظ„ظ…ط­ظپط¸ط©طŒ ظٹطھظ… ط´ط­ظ† ط§ظ„ظ…ظ†طھط¬ ط¨ط¹ط¯ طھط£ظƒظٹط¯ ط§ط³طھظ„ط§ظ… ط§ظ„ظ…ط¨ظ„ط؛. ظ†ط­ط±طµ ط¹ظ„ظ‰ ط³ط±ط¹ط© ط§ظ„ظ…ط¹ط§ظ„ط¬ط©.
        </div>

        <h2>ًںڑڑ ط³ظٹط§ط³ط© ط§ظ„ط´ط­ظ† ط¨ط§ظ„طھظپطµظٹظ„</h2>
        <table>
            <tr><th>ط·ط±ظٹظ‚ط© ط§ظ„ط´ط­ظ†</th><th>ط§ظ„طھظƒظ„ظپط© ط§ظ„ظ…طھظˆظ‚ط¹ط©</th><th>ط§ظ„ظ…ط¯ط© ط§ظ„ظ…طھظˆظ‚ط¹ط©</th></tr>
            <tr><td>طھط·ط¨ظٹظ‚ طھظˆطµظٹظ„</td><td>ط­ط³ط¨ طھط³ط¹ظٹط±ط© ط§ظ„طھط·ط¨ظٹظ‚</td><td>ط³ط±ظٹط¹ ط¬ط¯ط§ظ‹ (ظ†ظپط³ ط§ظ„ظٹظˆظ…)</td></tr>
            <tr><td>طھط·ط¨ظٹظ‚ طھط³ط§ظ‡ظٹظ„</td><td>ط­ط³ط¨ طھط³ط¹ظٹط±ط© ط§ظ„طھط·ط¨ظٹظ‚</td><td>ط³ط±ظٹط¹ ط¬ط¯ط§ظ‹ (ظ†ظپط³ ط§ظ„ظٹظˆظ…)</td></tr>
            <tr><td>طھط·ط¨ظٹظ‚ ظ†ط§ط³</td><td>ط­ط³ط¨ طھط³ط¹ظٹط±ط© ط§ظ„طھط·ط¨ظٹظ‚</td><td>ط³ط±ظٹط¹ ط¬ط¯ط§ظ‹ (ظ†ظپط³ ط§ظ„ظٹظˆظ…)</td></tr>
        </table>

        <p style="text-align: right; font-size: 0.95rem; color: #718096; margin-top: 10px;">âڈ±ï¸ڈ *ظ…ظ„ط§ط­ط¸ط©: ط§ظ„ظ…ط¯ط© ظ‚ط¯ طھط²ظٹط¯ ظ‚ظ„ظٹظ„ط§ظ‹ ط¨ط³ط¨ط¨ ط¥ط¬ط±ط§ط،ط§طھ ط§ظ„ط¬ظ…ط§ط±ظƒ ط£ظˆ ط§ظ„طھط£ط®ظٹط± ظ…ظ† ط´ط±ظƒط© ط§ظ„ط´ط­ظ†.*</p>

        <h2>â‌“ ظ„ط¯ظٹظƒ ط³ط¤ط§ظ„طں</h2>
        <p style="text-align: right; margin-top: 10px;">ظٹظ…ظƒظ†ظƒ ظ…ط±ط§ط³ظ„طھظ†ط§ ط¹ط¨ط± <a href="addr.php">طµظپط­ط© ط§ظ„طھظˆط§طµظ„</a> ط£ظˆ ظˆط§طھط³ط§ط¨ ظ„ط­ط³ط§ط¨ ط´ط­ظ† ظ…ط®طµطµ.</p>
    </div>
</div>
 
<?php include '../../includes/footer.php'; ?>
