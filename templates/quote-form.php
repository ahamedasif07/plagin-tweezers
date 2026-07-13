<?php

/**
 * Root wizard shell. All steps are rendered into the DOM up front (so no
 * data is ever lost or re-fetched on Back), but only the active step is
 * visible. quote.js drives visibility, validation and AJAX submission.
 *
 * @var array $settings
 */
if (! defined('ABSPATH')) {
	exit;
}
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<div class="ttq-wizard" id="ttq-wizard" data-step="1">



    <div class="ttq-shell">

        <!-- ============================================================
		     LEFT SIDEBAR RAIL
		     ============================================================ -->
        <aside class="ttq-rail" aria-label="<?php esc_attr_e('Quote progress', 'ttq'); ?>">

            <!-- Clipboard icon badge at very top -->
            <span class="ttq-rail__icon-main" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <rect x="8" y="2" width="8" height="4" rx="1" />
                    <path d="M8 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-2" />
                    <line x1="9" y1="12" x2="15" y2="12" />
                    <line x1="9" y1="16" x2="13" y2="16" />
                </svg>
            </span>



            <!-- STEP X OF 3 -->
            <span class="ttq-rail__stepof">
                <span class="ttq-rail__stepof-label"><?php esc_html_e('STEP', 'ttq'); ?></span>
                <span class="ttq-rail__stepof-num ttq-js-rail-current">1</span>
                <span class="ttq-rail__stepof-of ttq-js-rail-of"><?php esc_html_e('OF 3', 'ttq'); ?></span>
            </span>

            <!-- Three step dots with vertical connector line -->
            <ol class="ttq-rail__dots" aria-label="<?php esc_attr_e('Steps', 'ttq'); ?>">
                <li class="ttq-rail__dot is-current" data-step-index="1" title="<?php esc_attr_e('Product', 'ttq'); ?>">
                </li>
                <li class="ttq-rail__dot is-disabled" data-step-index="2"
                    title="<?php esc_attr_e('Customization', 'ttq'); ?>"></li>
                <li class="ttq-rail__dot is-disabled" data-step-index="3"
                    title="<?php esc_attr_e('Contact', 'ttq'); ?>"></li>
            </ol>

            <!-- Tick bug decoration at bottom -->
            <span class="ttq-rail__bug" aria-hidden="true">
                <svg viewBox="0 0 64 64" width="24" height="24" fill="currentColor">
                    <ellipse cx="32" cy="38" rx="13" ry="16" />
                    <circle cx="32" cy="20" r="7" />
                    <line x1="20" y1="29" x2="6" y2="21" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" />
                    <line x1="20" y1="38" x2="4" y2="38" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" />
                    <line x1="20" y1="47" x2="6" y2="55" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" />
                    <line x1="44" y1="29" x2="58" y2="21" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" />
                    <line x1="44" y1="38" x2="60" y2="38" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" />
                    <line x1="44" y1="47" x2="58" y2="55" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" />
                </svg>
            </span>
        </aside>

        <!-- ============================================================
		     MAIN CONTENT COLUMN
		     ============================================================ -->
        <div class="ttq-content">

            <!-- Top bar: step label (left) + trust badge (right) -->
            <header class="ttq-topbar">
                <div class="ttq-topbar__title">
                    <div>
                        <button type="button" class="ttq-topprogress__back ttq-js-back-btn"
                            aria-label="<?php esc_attr_e('Go back', 'ttq'); ?>" hidden>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="15 18 9 12 15 6" />
                            </svg>
                        </button>
                        <span
                            class="ttq-topbar__eyebrow ttq-js-step-eyebrow"><?php esc_html_e('Step 1 of 3', 'ttq'); ?></span>
                    </div>
                    <h2 class="ttq-topbar__heading ttq-js-step-heading">
                        <?php esc_html_e('Choose Your Product', 'ttq'); ?></h2>
                </div>

                <!-- Horizontal step progress tracker (1 → 2 → 3) -->
                <nav class="ttq-step-track" aria-label="<?php esc_attr_e('Progress', 'ttq'); ?>">

                    <div class="ttq-step-track__item is-active" data-track-step="1">
                        <span class="ttq-step-track__bubble">1</span>
                        <span class="ttq-step-track__label"><?php esc_html_e('Product', 'ttq'); ?></span>
                    </div>
                    <div class="ttq-step-track__line"></div>
                    <div class="ttq-step-track__item" data-track-step="2">
                        <span class="ttq-step-track__bubble">2</span>
                        <span class="ttq-step-track__label"><?php esc_html_e('Customization', 'ttq'); ?></span>
                    </div>
                    <div class="ttq-step-track__line"></div>
                    <div class="ttq-step-track__item" data-track-step="3">
                        <span class="ttq-step-track__bubble">3</span>
                        <span class="ttq-step-track__label"><?php esc_html_e('Review', 'ttq'); ?></span>
                    </div>
                </nav>

                <!-- Trust badge (right) -->
                <p class="ttq-topbar__trust ttq-js-trust-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                    <?php esc_html_e('Trusted by 500+ Health Departments, Parks, Schools & Outdoor Organizations', 'ttq'); ?>
                </p>



                <!-- 100% Completed badge — shown only on review step -->
                <span class="ttq-topbar__completed ttq-js-completed-badge" hidden>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        <polyline points="9 12 11 14 15 10" />
                    </svg>
                    <?php esc_html_e('100% Completed', 'ttq'); ?>
                </span>
            </header>

            <!-- ============================================================
	     TOP PROGRESS BAR — sits above everything else.
	     Nothing below this was changed.
	     ============================================================ -->
            <div class="ttq-topprogress">

                <div class="ttq-topprogress__bar">
                    <div class="ttq-topprogress__track">
                        <div class="ttq-topprogress__fill ttq-js-progress-fill" style="width:0%"></div>
                    </div>
                    <span
                        class="ttq-topprogress__label ttq-js-progress-label"><?php esc_html_e('0% COMPLETED', 'ttq'); ?></span>
                </div>
            </div>

            <!-- All four panels -->
            <form id="ttq-form" novalidate>
                <?php wp_nonce_field('ttq_quote_nonce', 'ttq_nonce_field'); ?>

                <section class="ttq-panel" data-panel="1" aria-current="step">
                    <?php include TTQ_PATH . 'templates/step-1.php'; ?>
                </section>

                <section class="ttq-panel" data-panel="2" hidden>
                    <?php include TTQ_PATH . 'templates/step-2.php'; ?>
                </section>

                <section class="ttq-panel" data-panel="3" hidden>
                    <?php include TTQ_PATH . 'templates/step-3.php'; ?>
                </section>

                <section class="ttq-panel" data-panel="4" hidden>
                    <?php include TTQ_PATH . 'templates/review.php'; ?>
                </section>
            </form>

        </div><!-- .ttq-content -->
    </div><!-- .ttq-shell -->

    <?php include TTQ_PATH . 'templates/success.php'; ?>

</div><!-- .ttq-wizard -->

<style>
/* Top progress bar */
.ttq-topprogress {
    display: flex;
    align-items: center;

}

.ttq-topprogress__back {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: transparent;
    color: #d5314a;
    cursor: pointer;
    padding: 0;
    margin-bottom: 5px;
    border: 1px solid #d5314a;
}

.ttq-topprogress__back:hover {
    opacity: .75;
}

.ttq-topprogress__bar {
    flex: 1 1 auto;
}

.ttq-topprogress__track {
    width: 100%;
    height: 8px;
    border-radius: 999px;
    background: #eef0f4;
    overflow: hidden;
}

.ttq-topprogress__fill {
    height: 100%;
    border-radius: 999px;
    background: #d5314a;
    transition: width .35s ease;
}

.ttq-topprogress__label {
    display: block;
    text-align: center;
    margin-top: 6px;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: .04em;
    color: #6b7280;
    padding: 10px;
}
</style>

<script>
(function() {
    var TOTAL_STEPS = 3; // Product, Customization, Contact — review panel (4) = 100%
    var wizard = document.getElementById('ttq-wizard');
    if (!wizard) return;

    var backBtn = wizard.querySelector('.ttq-js-back-btn');
    var fillEl = wizard.querySelector('.ttq-js-progress-fill');
    var labelEl = wizard.querySelector('.ttq-js-progress-label');

    function currentStep() {
        return parseInt(wizard.getAttribute('data-step'), 10) || 1;
    }

    function render() {
        var step = currentStep();

        // Percentage reflects steps COMPLETED so far (step - 1),
        // so Step 1 itself shows 0% and only moves once you leave it.
        //   Step 1 (current)        -> 0%
        //   Step 1 done, on Step 2  -> 33%
        //   Step 2 done, on Step 3  -> 66%
        //   Step 3 done / Review    -> 100%
        var completed = Math.max(0, Math.min(step - 1, TOTAL_STEPS));
        var pct = Math.round((completed / TOTAL_STEPS) * 100);

        if (fillEl) fillEl.style.width = pct + '%';
        if (labelEl) labelEl.textContent = pct + '% COMPLETED';
        if (backBtn) backBtn.hidden = step <= 1;
    }

    // Stay in sync no matter how quote.js switches steps.
    new MutationObserver(render).observe(wizard, {
        attributes: true,
        attributeFilter: ['data-step']
    });
    render();

    if (backBtn) {
        backBtn.addEventListener('click', function() {
            // Prefer quote.js's own back handler if it exists, so
            // validation/state stays consistent with Next/Submit.
            if (typeof window.ttqGoToStep === 'function') {
                window.ttqGoToStep(currentStep() - 1);
                return;
            }
            var existing = document.querySelector('.ttq-js-back, [data-ttq-back]');
            if (existing) {
                existing.click();
                return;
            }

            // Fallback: move back one panel ourselves.
            var step = currentStep();
            if (step <= 1) return;
            var prevStep = step - 1;
            wizard.querySelectorAll('.ttq-panel').forEach(function(p) {
                p.hidden = parseInt(p.getAttribute('data-panel'), 10) !== prevStep;
            });
            wizard.setAttribute('data-step', prevStep);
        });
    }
})();
</script>