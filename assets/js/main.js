/**
 * mcar main.js
 * Handles general interactivity, mobile menu, and dynamic UI updates.
 */

document.addEventListener('DOMContentLoaded', () => {
    console.log('mcar: Platform initialized.');

    // FAQ Accordion logic (if not using <details>)
    // The current implementation uses <details>, which is native and efficient.
    
    // Dynamic Grid AJAX Logic
    const gridForm = document.querySelector('.filter-sidebar form');
    const gridSortSelect = document.querySelector('.grid-toolbar-left select[name="sort"]');
    const gridResultsContainer = document.querySelector('.grid-results');

    if (gridResultsContainer && (gridForm || gridSortSelect)) {
        // Intercept form submissions
        const handleFilterChange = (e) => {
            if (e) e.preventDefault();
            
            // Show skeleton loaders immediately
            const numSkeletons = 6;
            let skeletonHtml = '';
            for(let i=0; i<numSkeletons; i++) {
                skeletonHtml += `
                <div class="deal-card grid-card" style="padding: 24px; min-height: 400px; display: flex; flex-direction: column; gap: 16px;">
                    <div class="skeleton" style="height: 140px; border-radius: var(--r-md); width: 100%;"></div>
                    <div class="skeleton" style="height: 24px; width: 60%;"></div>
                    <div class="skeleton" style="height: 16px; width: 40%;"></div>
                    <div style="flex: 1;"></div>
                    <div class="skeleton" style="height: 48px; border-radius: var(--r-md); width: 100%;"></div>
                </div>`;
            }
            gridResultsContainer.innerHTML = skeletonHtml;
            
            // Collect all filter data
            let formData = new FormData(gridForm);
            let params = new URLSearchParams(formData);
            if (gridSortSelect) params.set('sort', gridSortSelect.value);
            params.set('ajax', '1');
            
            const newUrl = 'grid.php?' + params.toString();
            window.history.pushState({path: newUrl}, '', newUrl.replace('&ajax=1', ''));
            
            fetch(newUrl)
                .then(res => res.json())
                .then(data => {
                    gridResultsContainer.innerHTML = data.html;
                    
                    // Filter updates
                    const statsCount = document.querySelectorAll('.grid-hero-stats .stat .k')[0];
                    const statsMin = document.querySelectorAll('.grid-hero-stats .stat .k')[1];
                    const statsAvg = document.querySelectorAll('.grid-hero-stats .stat .k')[2];
                    const toolbarCount = document.querySelector('.grid-toolbar-right strong');
                    
                    if (statsCount) statsCount.innerHTML = data.count;
                    if (statsMin) statsMin.innerHTML = '<span class="sym">₪</span>' + data.min_price;
                    if (statsAvg) statsAvg.innerHTML = '<span class="sym">₪</span>' + data.avg_price;
                    if (toolbarCount) toolbarCount.textContent = data.count + ' רכבים';
                })
                .catch(err => {
                    console.error('Failed to fetch grid data', err);
                    window.location.reload();
                });
        };

        // Attach listeners
        if (gridForm) {
            gridForm.addEventListener('change', handleFilterChange);
            gridForm.addEventListener('submit', handleFilterChange);
            // Handle pill clicks (they are links in HTML, we should intercept them or rely on standard navigations)
            // For true SPA, we'd intercept a.pill clicks. For now, since pills are links, it's easier to change them to buttons or just intercept clicks.
            const pills = gridForm.querySelectorAll('.filter-pills a.pill');
            pills.forEach(pill => {
                pill.addEventListener('click', (e) => {
                    e.preventDefault();
                    // Update UI state
                    const group = pill.closest('.filter-pills');
                    group.querySelectorAll('.pill').forEach(p => p.classList.remove('on'));
                    pill.classList.add('on');
                    
                    // Extract value from URL and set a hidden input to trigger the form
                    const url = new URL(pill.href);
                    const entries = url.searchParams.entries();
                    for(let [key, val] of entries) {
                        let input = gridForm.querySelector(`input[name="${key}"]`);
                        if (!input) {
                            input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = key;
                            gridForm.appendChild(input);
                        }
                        input.value = val;
                    }
                    handleFilterChange();
                });
            });
        }
        if (gridSortSelect) {
            gridSortSelect.addEventListener('change', handleFilterChange);
            // Remove inline onchange
            gridSortSelect.removeAttribute('onchange');
        }
        
        // Remove inline handlers from radio buttons and range to prevent double firing
        document.querySelectorAll('input[onchange="this.form.submit()"]').forEach(el => el.removeAttribute('onchange'));
    }
    
    // Social Proof Notification System
    const spToast = document.getElementById('sp-toast');
    const spMsg = document.getElementById('sp-msg');
    
    if (spToast && spMsg) {
        const models = ['Nova Prime 7', 'Aero X5', 'Velox 9', 'Stratos EQ', 'Orbit XL'];
        const cities = ['תל אביב', 'חיפה', 'ראשון לציון', 'ירושלים', 'נתניה', 'אשדוד', 'באר שבע', 'פתח תקווה'];
        
        const showToast = () => {
            if (spToast.classList.contains('show')) return;
            const model = models[Math.floor(Math.random() * models.length)];
            const city = cities[Math.floor(Math.random() * cities.length)];
            
            spMsg.innerHTML = `משתמש(ת) מ<strong>${city}</strong> בודק/ת עכשיו רכב <strong>${model}</strong>`;
            
            spToast.classList.add('show');
            setTimeout(() => {
                spToast.classList.remove('show');
            }, 6000); // Hide after 6s
        };
        
        setTimeout(() => {
            showToast();
            setInterval(showToast, 25000 + Math.random() * 10000);
        }, 3000); // Trigger first notification after 3 seconds for better effect
    }

    // Form handling
    const contactForm = document.querySelector('form[action="contact.php"]');
    if (contactForm) {
        contactForm.addEventListener('submit', () => {
            const submitBtn = contactForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'שולח...';
            }
        });
    }

    // Scroll Progress Indicator Logic
    const progressLine = document.getElementById('scroll-progress');
    if (progressLine) {
        window.addEventListener('scroll', () => {
            const windowHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (window.scrollY / windowHeight) * 100;
            progressLine.style.width = scrolled + '%';
        });
    }

    // Scroll effect for header
    const header = document.getElementById('site-header');
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }

    // VIP Offer modal — intercept any link/button with data-offer-modal
    const offerModal = document.getElementById('offerModal');
    const offerForm = document.getElementById('offerModalForm');
    const offerSource = document.getElementById('offerModalSource');
    const offerName = document.getElementById('offerName');

    function openOfferModal(trigger) {
        if (!offerModal || !offerForm) return;
        // Preserve the trigger's ?car=…/?pkg=…/?type=… context as form action
        let action = 'contact.php';
        if (trigger) {
            const href = trigger.getAttribute('href');
            if (href && href.startsWith('contact.php')) action = href;
            const src = trigger.getAttribute('data-offer-source') || trigger.textContent.trim().slice(0, 40);
            if (offerSource) offerSource.value = src || 'modal';
        }
        offerForm.setAttribute('action', action);
        if (typeof offerModal.showModal === 'function') {
            offerModal.showModal();
        } else {
            offerModal.setAttribute('open', '');
        }
        setTimeout(() => offerName && offerName.focus(), 60);
    }

    function closeOfferModal() {
        if (!offerModal) return;
        if (typeof offerModal.close === 'function' && offerModal.open) {
            offerModal.close();
        } else {
            offerModal.removeAttribute('open');
        }
    }

    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('[data-offer-modal]');
        if (trigger) {
            e.preventDefault();
            openOfferModal(trigger);
            return;
        }
        if (e.target.closest('[data-offer-close]')) {
            closeOfferModal();
            return;
        }
        // Click on backdrop (dialog element itself) closes
        if (offerModal && e.target === offerModal) {
            closeOfferModal();
        }
    });

    // ---- Tweaks panel ----
    const tweaksModal = document.getElementById('tweaksModal');
    const tweaksToggle = document.getElementById('tweaks-toggle');

    function loadTweaks() {
        try { return JSON.parse(localStorage.getItem('mcar_tweaks') || '{}'); }
        catch(e) { return {}; }
    }
    function saveTweaks(t) {
        try { localStorage.setItem('mcar_tweaks', JSON.stringify(t)); } catch(e) {}
    }
    function applyTweaks(t) {
        const root = document.documentElement;
        if (t.mode)   root.setAttribute('data-mode',   t.mode);
        if (t.accent) root.setAttribute('data-accent', t.accent);
        if (t.radius) root.setAttribute('data-radius', t.radius);
        // Sync pill UI: for each tweak key, mark the pill matching the live attribute
        ['mode','accent','radius'].forEach(k => {
            const current = root.getAttribute('data-' + k);
            document.querySelectorAll('[data-tweak-' + k + ']').forEach(p => {
                const isOn = p.getAttribute('data-tweak-' + k) === current;
                p.classList.toggle('on', isOn);
                p.setAttribute('aria-checked', isOn ? 'true' : 'false');
            });
        });
    }

    if (tweaksModal) {
        // Initial sync
        applyTweaks(loadTweaks());

        if (tweaksToggle) {
            tweaksToggle.addEventListener('click', () => {
                applyTweaks(loadTweaks());
                if (typeof tweaksModal.showModal === 'function') tweaksModal.showModal();
                else tweaksModal.setAttribute('open', '');
            });
        }

        tweaksModal.addEventListener('click', (e) => {
            if (e.target === tweaksModal) {
                if (typeof tweaksModal.close === 'function') tweaksModal.close();
                else tweaksModal.removeAttribute('open');
            }
            if (e.target.closest('[data-tweaks-close]')) {
                if (typeof tweaksModal.close === 'function') tweaksModal.close();
                else tweaksModal.removeAttribute('open');
            }
            if (e.target.closest('[data-tweaks-reset]')) {
                localStorage.removeItem('mcar_tweaks');
                document.documentElement.setAttribute('data-mode', 'light');
                document.documentElement.setAttribute('data-accent', 'teal');
                document.documentElement.setAttribute('data-radius', 'large');
                applyTweaks({});
            }
            const pill = e.target.closest('.tweaks-pill');
            if (pill) {
                const t = loadTweaks();
                ['mode','accent','radius'].forEach(k => {
                    const v = pill.getAttribute('data-tweak-' + k);
                    if (v) t[k] = v;
                });
                saveTweaks(t);
                applyTweaks(t);
            }
        });
    }

    // Mobile nav toggle
    const navToggle = document.getElementById('nav-toggle');
    const navLinks = document.getElementById('nav-links');
    if (navToggle && navLinks) {
        navToggle.addEventListener('click', () => {
            const isOpen = navLinks.classList.toggle('open');
            navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
        document.addEventListener('click', (e) => {
            if (!navLinks.contains(e.target) && !navToggle.contains(e.target)) {
                navLinks.classList.remove('open');
                navToggle.setAttribute('aria-expanded', 'false');
            }
        });
    }
});
