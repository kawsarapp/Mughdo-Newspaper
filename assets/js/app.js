/**
 * ProthomNews App Engine - Client Features & Mobile Controls
 * Controls Dark Mode, Live Search Modal with Keyboard Navigation, Mobile Off-Canvas Drawer, Mobile App Bottom Bar, TTS, Copy Link, Reader Reactions, and Tabs.
 *
 * @package ProthomNews
 */

const ProthomNewsApp = {
  init() {
    this.initDarkMode();
    this.initLiveSearch();
    this.initTrendingTabs();
    this.initFontResizer();
    this.initVoiceReader();
    this.initCopyLink();
    this.initAdDismiss();
    this.initMobileDrawer();
    this.initSubCatTabs();
    this.initReaderReactions();
  },

  /**
   * Re-initialize features after SPA page swap
   */
  reinit() {
    this.initTrendingTabs();
    this.initFontResizer();
    this.initVoiceReader();
    this.initCopyLink();
    this.initAdDismiss();
    this.initSubCatTabs();
    this.initReaderReactions();
  },

  /**
   * 1. Dark Mode / Light Mode Theme Switcher
   */
  initDarkMode() {
    const toggleBtn = document.getElementById('theme-toggle-btn');
    const mobileThemeTrigger = document.getElementById('mobile-theme-trigger');
    const htmlEl = document.documentElement;

    const savedTheme = localStorage.getItem('prothom_theme') || 'light';
    htmlEl.setAttribute('data-theme', savedTheme);
    this.updateToggleBtnText(toggleBtn, savedTheme);

    const toggleTheme = () => {
      const currentTheme = htmlEl.getAttribute('data-theme');
      const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
      htmlEl.setAttribute('data-theme', newTheme);
      localStorage.setItem('prothom_theme', newTheme);
      this.updateToggleBtnText(toggleBtn, newTheme);
    };

    if (toggleBtn) {
      toggleBtn.addEventListener('click', toggleTheme);
    }
    if (mobileThemeTrigger) {
      mobileThemeTrigger.addEventListener('click', toggleTheme);
    }
  },

  updateToggleBtnText(btn, theme) {
    if (!btn) return;
    if (theme === 'dark') {
      btn.innerHTML = '☀️ <span>দিন</span>';
    } else {
      btn.innerHTML = '🌙 <span>রাত</span>';
    }
  },

  /**
   * 2. Live Search Modal with REST API Autocomplete & Keyboard Arrow Navigation
   */
  initLiveSearch() {
    const triggerBtn = document.getElementById('search-trigger-btn');
    const mobileSearchTrigger = document.getElementById('mobile-search-trigger');
    const modalOverlay = document.getElementById('search-modal-overlay');
    const closeBtn = document.getElementById('modal-close-btn');
    const searchInput = document.getElementById('live-search-input');
    const resultsGrid = document.getElementById('live-search-results');

    if (!modalOverlay) return;

    let selectedIndex = -1;

    const openSearch = () => {
      modalOverlay.classList.add('active');
      if (searchInput) searchInput.focus();
    };

    if (triggerBtn) triggerBtn.addEventListener('click', openSearch);
    if (mobileSearchTrigger) mobileSearchTrigger.addEventListener('click', openSearch);

    if (closeBtn) {
      closeBtn.addEventListener('click', () => {
        modalOverlay.classList.remove('active');
      });
    }

    document.addEventListener('keydown', (e) => {
      if (!modalOverlay.classList.contains('active')) return;

      if (e.key === 'Escape') {
        modalOverlay.classList.remove('active');
        return;
      }

      const items = resultsGrid.querySelectorAll('.sub-lead-item');
      if (items.length === 0) return;

      if (e.key === 'ArrowDown') {
        e.preventDefault();
        selectedIndex = (selectedIndex + 1) % items.length;
        this.highlightSearchItem(items, selectedIndex);
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        selectedIndex = (selectedIndex - 1 + items.length) % items.length;
        this.highlightSearchItem(items, selectedIndex);
      } else if (e.key === 'Enter' && selectedIndex >= 0 && selectedIndex < items.length) {
        e.preventDefault();
        items[selectedIndex].click();
      }
    });

    let searchTimeout = null;
    if (searchInput && resultsGrid) {
      searchInput.addEventListener('input', (e) => {
        const query = e.target.value.trim();
        clearTimeout(searchTimeout);
        selectedIndex = -1;

        if (query.length < 2) {
          resultsGrid.innerHTML = '';
          return;
        }

        resultsGrid.innerHTML = '<div style="padding: 1rem; text-align: center; color: var(--text-muted);">খোঁজা হচ্ছে...</div>';

        searchTimeout = setTimeout(async () => {
          try {
            const apiEndpoint = `${ProthomNewsData.apiUrl}/search?s=${encodeURIComponent(query)}`;
            const response = await fetch(apiEndpoint);
            const data = await response.json();

            if (data.results && data.results.length > 0) {
              let html = '';
              data.results.forEach((item) => {
                html += `
                  <a href="${item.link}" class="sub-lead-item">
                    <div class="sub-lead-img-wrapper">
                      <img src="${item.thumbnail}" alt="${item.title}" loading="lazy" />
                    </div>
                    <div>
                      <span style="font-size:0.75rem; color:var(--brand-red); font-weight:700;">${item.category}</span>
                      <h4 class="sub-lead-title" style="font-size:0.95rem;">${item.title}</h4>
                      <span style="font-size:0.75rem; color:var(--text-muted);">${item.date}</span>
                    </div>
                  </a>
                `;
              });
              resultsGrid.innerHTML = html;
            } else {
              resultsGrid.innerHTML = '<div style="padding: 1rem; text-align: center; color: var(--text-muted);">কোনো ফলাফল পাওয়া যায়নি</div>';
            }
          } catch (err) {
            console.error('Search error:', err);
            resultsGrid.innerHTML = '<div style="padding: 1rem; text-align: center; color: var(--brand-red);">ত্রুটি ঘটেছে! আবার চেষ্টা করুন।</div>';
          }
        }, 300);
      });
    }
  },

  highlightSearchItem(items, index) {
    items.forEach((item, i) => {
      if (i === index) {
        item.style.borderColor = 'var(--brand-red)';
        item.style.backgroundColor = 'var(--bg-secondary)';
        item.scrollIntoView({ block: 'nearest' });
      } else {
        item.style.borderColor = 'var(--border-color)';
        item.style.backgroundColor = 'var(--bg-card)';
      }
    });
  },

  /**
   * 3. Copy Link Helper
   */
  initCopyLink() {
    const copyBtn = document.getElementById('copy-link-btn');
    if (!copyBtn) return;

    copyBtn.addEventListener('click', () => {
      const url = copyBtn.getAttribute('data-url') || window.location.href;
      navigator.clipboard.writeText(url).then(() => {
        const originalText = copyBtn.innerHTML;
        copyBtn.innerHTML = '✅ <span>কপি করা হয়েছে</span>';
        setTimeout(() => {
          copyBtn.innerHTML = originalText;
        }, 2000);
      }).catch(err => {
        console.error('Copy failed', err);
      });
    });
  },

  /**
   * 4. Interactive Reader Reaction Buttons
   */
  initReaderReactions() {
    const reactionBtns = document.querySelectorAll('.reaction-btn');
    reactionBtns.forEach((btn) => {
      btn.addEventListener('click', () => {
        const type = btn.getAttribute('data-reaction');
        const postId = btn.getAttribute('data-post-id');
        const countSpan = btn.querySelector('.reaction-count');
        const storageKey = `react_${postId}_${type}`;

        if (localStorage.getItem(storageKey)) {
          alert('আপনি ইতিমধ্যে রিঅ্যাক্ট করেছেন!');
          return;
        }

        let currentCount = parseInt(countSpan.innerText, 10) || 0;
        currentCount++;
        countSpan.innerText = currentCount;
        btn.classList.add('active');
        localStorage.setItem(storageKey, 'true');
      });
    });
  },

  /**
   * 5. Mobile Off-Canvas Drawer Menu Controls
   */
  initMobileDrawer() {
    const mobileMenuTrigger = document.getElementById('mobile-menu-trigger');
    const mobileDrawer = document.getElementById('mobile-drawer');
    const mobileBackdrop = document.getElementById('mobile-drawer-backdrop');
    const mobileCloseBtn = document.getElementById('mobile-drawer-close');

    if (!mobileDrawer || !mobileBackdrop) return;

    const openDrawer = () => {
      mobileDrawer.classList.add('active');
      mobileBackdrop.classList.add('active');
      document.body.style.overflow = 'hidden';
    };

    const closeDrawer = () => {
      mobileDrawer.classList.remove('active');
      mobileBackdrop.classList.remove('active');
      document.body.style.overflow = '';
    };

    if (mobileMenuTrigger) mobileMenuTrigger.addEventListener('click', openDrawer);
    if (mobileCloseBtn) mobileCloseBtn.addEventListener('click', closeDrawer);
    if (mobileBackdrop) mobileBackdrop.addEventListener('click', closeDrawer);

    mobileDrawer.addEventListener('click', (e) => {
      if (e.target.closest('a')) {
        closeDrawer();
      }
    });
  },

  /**
   * 6. Interactive Sub-Category Tabs Filter
   */
  initSubCatTabs() {
    const tabBtns = document.querySelectorAll('.sub-tab-btn');
    tabBtns.forEach((btn) => {
      btn.addEventListener('click', () => {
        const parentSection = btn.closest('.tabbed-cat-wrapper');
        if (!parentSection) return;

        const siblings = parentSection.querySelectorAll('.sub-tab-btn');
        siblings.forEach((s) => s.classList.remove('active'));
        btn.classList.add('active');
      });
    });
  },

  /**
   * 7. Tab Switcher for Trending Widget
   */
  initTrendingTabs() {
    const tabBtns = document.querySelectorAll('.trending-tabs .tab-btn');
    const trendingLists = document.querySelectorAll('.trending-list-container');

    tabBtns.forEach((btn) => {
      btn.addEventListener('click', () => {
        const targetType = btn.getAttribute('data-tab');

        tabBtns.forEach((b) => b.classList.remove('active'));
        btn.classList.add('active');

        const activeList = document.getElementById(`trending-list-${targetType}`);
        if (activeList) {
          trendingLists.forEach((l) => (l.style.display = 'none'));
          activeList.style.display = 'flex';
        }
      });
    });
  },

  /**
   * 8. Font Resizer (+A / -A) for Post Content
   */
  initFontResizer() {
    const articleBody = document.querySelector('.article-body-content');
    const fontPlusBtn = document.getElementById('font-increase-btn');
    const fontMinusBtn = document.getElementById('font-decrease-btn');
    const fontResetBtn = document.getElementById('font-reset-btn');

    if (!articleBody) return;

    let currentSize = 1.15;

    if (fontPlusBtn) {
      fontPlusBtn.addEventListener('click', () => {
        if (currentSize < 1.6) {
          currentSize += 0.1;
          articleBody.style.fontSize = `${currentSize}rem`;
        }
      });
    }

    if (fontMinusBtn) {
      fontMinusBtn.addEventListener('click', () => {
        if (currentSize > 0.9) {
          currentSize -= 0.1;
          articleBody.style.fontSize = `${currentSize}rem`;
        }
      });
    }

    if (fontResetBtn) {
      fontResetBtn.addEventListener('click', () => {
        currentSize = 1.15;
        articleBody.style.fontSize = `${currentSize}rem`;
      });
    }
  },

  /**
   * 9. Bengali Text-to-Speech (Voice Reader)
   */
  initVoiceReader() {
    const ttsBtn = document.getElementById('tts-play-btn');
    const articleBody = document.querySelector('.article-body-content');

    if (!ttsBtn || !articleBody) return;

    let isPlaying = false;
    let synth = window.speechSynthesis;

    ttsBtn.addEventListener('click', () => {
      if (!synth) {
        alert('আপনার ব্রাউজারে অডিও স্পিচ সাপোর্ট করে না।');
        return;
      }

      if (isPlaying) {
        synth.cancel();
        isPlaying = false;
        ttsBtn.innerHTML = '🔊 <span>খবর শুনুন</span>';
      } else {
        const textToRead = articleBody.innerText;
        const utterance = new SpeechSynthesisUtterance(textToRead);
        utterance.lang = 'bn-BD';
        utterance.rate = 0.95;

        utterance.onend = () => {
          isPlaying = false;
          ttsBtn.innerHTML = '🔊 <span>খবর শুনুন</span>';
        };

        synth.speak(utterance);
        isPlaying = true;
        ttsBtn.innerHTML = '⏹️ <span>বন্ধ করুন</span>';
      }
    });
  },

  /**
   * 10. Sticky Bottom Ad Dismiss
   */
  initAdDismiss() {
    const closeBtn = document.querySelector('.ad-sticky-bottom .ad-close-btn');
    if (closeBtn) {
      closeBtn.addEventListener('click', () => {
        const parentAd = closeBtn.closest('.ad-sticky-bottom');
        if (parentAd) parentAd.style.display = 'none';
      });
    }
  }
};

window.ProthomNewsApp = ProthomNewsApp;

document.addEventListener('DOMContentLoaded', () => {
  ProthomNewsApp.init();
});
