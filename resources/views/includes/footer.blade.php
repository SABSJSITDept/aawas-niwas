<footer class="site-footer mt-auto">
    <div class="footer-top">
        <div class="container-fluid px-4">
            <div class="row align-items-center justify-content-between g-3">
                <div class="col-md-6 text-center text-md-start">
                    <div class="footer-brand">
                        <div>
                            <div class="footer-org-name">श्री अखिल भारतवर्षीय साधुमार्गी जैन संघ</div>
                            <div class="footer-org-sub">Shree Akhil Bharatvarshiye Sadhumargi Jain Sangh</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="footer-address">
                        <i class="bi bi-geo-alt-fill me-1"></i>
                        <span>Acharya Nanesh Marg, Bikaner, Rajasthan 334001</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container-fluid text-center">
            <span class="footer-designed-by">
                Designed &amp; Developed by &copy; {{ date('Y') }} IT Department
                <span class="footer-sep">|</span>
                Central Office &mdash; Shree Akhil Bharatvarshiye Sadhumargi Jain Sangh,
                Acharya Nanesh Marg, Bikaner, Rajasthan 334001
                <span class="footer-sep">|</span> </br>
                All Rights Reserved
            </span>
        </div>
    </div>
</footer>

<style>
/* ===== FOOTER STYLES ===== */
.site-footer {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    color: #e0e0e0;
    font-family: 'Segoe UI', sans-serif;
    /* Full-width breakout trick — works regardless of parent container padding */
    position: relative;
    left: 50%;
    right: 50%;
    margin-left: -50vw;
    margin-right: -50vw;
    width: 100vw;
    max-width: 100vw;
    box-sizing: border-box;
}

.footer-top {
    padding: 30px 0 24px;
    border-bottom: 1px solid rgba(255, 165, 0, 0.3);
}

.footer-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    justify-content: center;
}

@media (min-width: 768px) {
    .footer-brand {
        justify-content: flex-start;
    }
}

.footer-logo-icon {
    font-size: 2rem;
    line-height: 1;
    filter: drop-shadow(0 0 6px rgba(255, 165, 0, 0.7));
}

.footer-org-name {
    font-size: 0.92rem;
    font-weight: 700;
    color: #ffd700;
    line-height: 1.3;
    letter-spacing: 0.02em;
}

.footer-org-sub {
    font-size: 0.72rem;
    color: #aab8c2;
    letter-spacing: 0.04em;
    margin-top: 2px;
}

.footer-address {
    font-size: 0.78rem;
    color: #c0cfe0;
    line-height: 1.5;
}

.footer-address i {
    color: #ffa500;
}

.footer-bottom {
    background: rgba(0, 0, 0, 0.35);
    padding: 14px 0;
}

.footer-designed-by {
    font-size: 0.72rem;
    color: #90a4ae;
    letter-spacing: 0.03em;
    line-height: 1.6;
}

.footer-designed-by strong {
    color: #ffd700;
}

.footer-sep {
    margin: 0 8px;
    color: #ffa500;
    font-weight: bold;
}

/* Responsive */
@media (max-width: 767px) {
    .footer-top {
        padding: 22px 0 18px;
    }

    .footer-org-name {
        font-size: 0.82rem;
    }

    .footer-address,
    .footer-rights {
        font-size: 0.72rem;
    }

    .footer-designed-by {
        font-size: 0.65rem;
    }
}
</style>
