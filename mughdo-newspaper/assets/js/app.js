/**
 * Mughdo Newspaper App Engine - Client Features & Mobile Controls
 * Controls Dark Mode, Live Search Modal with Bengali Voice Search, Mobile Off-Canvas Drawer, Mobile App Bottom Bar, TTS, Copy Link, Reader Reactions, Bookmarks, Reading Progress Bar & Floating Scroll to Top.
 *
 * @package MughdoNewspaper
 * @author Kawsar Ahmed
 */

const ProthomNewsApp = {
  init() {
    this.initDarkMode();
    this.initLiveSearch();
    this.initVoiceSearch();
    this.initTrendingTabs();
    this.initFontResizer();
    this.initVoiceReader();
    this.initCopyLink();
    this.initAdDismiss();
    this.initMobileDrawer();
    this.initSubCatTabs();
    this.initReaderReactions();
    this.initBookmarkSystem();
    this.initScrollToTop();
    this.initReadingProgressBar();
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
    this.initBookmarkSystem();
    this.initReadingProgressBar();
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
   * 2. Live Search Modal with REST API Autocomplete & Keyboard Navigation
   */
  initLiveSearch() {
    const triggerBtn = document.getElementById('search-trigger-btn');
    const mobileSearchTrigger = document.getElementById('mobile-search-trigger');
    const modalOverlay = document.getElementById('search-modal-overlay');
    const closeBtn = document.getElementById('modal-close-btn');
    const searchInput = document.getElementById('live-search-input');
    const resultsGrid = document.getElementById('live-search-results');

    if (!modalOverlay) return;

    const openSearch = () => {
      modalOverlay.classList.add('active');
      if (searchInput) searchInput.focus();
    };

    const closeSearch = () => {
      modalOverlay.classList.remove('active');
    };

    if (triggerBtn) triggerBtn.addEventListener('click', openSearch);
    if (mobileSearchTrigger) mobileSearchTrigger.addEventListener('click', openSearch);
    if (closeBtn) closeBtn.addEventListener('click', closeSearch);

    modalOverlay.addEventListener('click', (e) => {
      if (e.target === modalOverlay) closeSearch();
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && modalOverlay.classList.contains('active')) {
        closeSearch();
      }
    });

    let debounceTimer;
    if (searchInput && resultsGrid) {
      searchInput.addEventListener('input', (e) => {
        clearTimeout(debounceTimer);
        const query = e.target.value.trim();

        if (query.length < 2) {
          resultsGrid.innerHTML = '';
          return;
        }

        debounceTimer = setTimeout(() => {
          resultsGrid.innerHTML = '<div style="text-align:center; padding:1rem; color:var(--text-muted);">খোঁজা হচ্ছে...</div>';
          
          const apiUrl = window.ProthomNewsData ? window.ProthomNewsData.apiUrl : '/wp-json/prothom-news/v1';
          fetch(`${apiUrl}/search?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
              if (!data || data.length === 0) {
                resultsGrid.innerHTML = '<div style="text-align:center; padding:1rem; color:var(--text-muted);">কোনো সংবাদ পাওয়া যায়নি।</div>';
                return;
              }

              resultsGrid.innerHTML = data.map(item => `
                <a href="${item.url}" class="live-search-item" style="display:flex; gap:1rem; padding:0.5rem; border-bottom:1px solid var(--border-color); text-decoration:none; color:inherit;">
                  <img src="${item.thumbnail}" alt="" style="width:80px; height:50px; object-fit:cover; border-radius:4px;" />
                  <div>
                    <h4 style="font-size:0.95rem; margin:0 0 0.2rem 0; color:var(--text-primary);">${item.title}</h4>
                    <span style="font-size:0.75rem; color:var(--brand-red); font-weight:bold;">${item.category}</span>
                  </div>
                </a>
              `).join('');
            })
            .catch(() => {
              resultsGrid.innerHTML = '<div style="text-align:center; padding:1rem; color:var(--text-muted);">সার্চের সময় সমস্যা হয়েছে।</div>';
            });
        }, 300);
      });
    }
  },

  /**
   * 3. Bengali Speech-to-Text Voice Search Engine
   */
  initVoiceSearch() {
    const voiceBtn = document.getElementById('voice-search-btn');
    const searchInput = document.getElementById('live-search-input');

    if (!voiceBtn || !searchInput) return;

    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRecognition) {
      voiceBtn.style.display = 'none';
      return;
    }

    const recognition = new SpeechRecognition();
    recognition.lang = 'bn-BD';
    recognition.interimResults = false;

    voiceBtn.addEventListener('click', () => {
      voiceBtn.classList.add('listening');
      voiceBtn.innerText = '🎙️ শুনছি...';
      recognition.start();
    });

    recognition.onresult = (e) => {
      const transcript = e.results[0][0].transcript;
      searchInput.value = transcript;
      searchInput.dispatchEvent(new Event('input'));
      voiceBtn.classList.remove('listening');
      voiceBtn.innerText = '🎙️ কথা বলে খুঁজুন';
    };

    recognition.onerror = () => {
      voiceBtn.classList.remove('listening');
      voiceBtn.innerText = '🎙️ কথা বলে খুঁজুন';
    };
  },

  /**
   * 4. Trending Tabs (সর্বশেষ / পঠিত)
   */
  initTrendingTabs() {
    const tabBtns = document.querySelectorAll('.trending-tab-btn');
    const tabContents = document.querySelectorAll('.trending-tab-content');

    if (tabBtns.length === 0) return;

    tabBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        const target = btn.getAttribute('data-tab');
        
        tabBtns.forEach(b => b.classList.remove('active'));
        tabContents.forEach(c => c.classList.remove('active'));

        btn.classList.add('active');
        const activeContent = document.getElementById(`tab-${target}`);
        if (activeContent) activeContent.classList.add('active');
      });
    });
  },

  /**
   * 5. Sub-Category Tabs Filter Driver
   */
  initSubCatTabs() {
    const subCatBtns = document.querySelectorAll('.tabbed-cat-btn');

    subCatBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        const targetCat = btn.getAttribute('data-cat');
        const parentBlock = btn.closest('.category-block-wrapper');

        if (!parentBlock) return;

        parentBlock.querySelectorAll('.tabbed-cat-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const cards = parentBlock.querySelectorAll('.tabbed-news-card');
        cards.forEach(card => {
          if (targetCat === 'all' || card.getAttribute('data-cat') === targetCat) {
            card.style.display = 'block';
          } else {
            card.style.display = 'none';
          }
        });
      });
    });
  },

  /**
   * 6. Reader Reactions Driver
   */
  initReaderReactions() {
    const reactBtns = document.querySelectorAll('.reaction-btn');

    reactBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        const reaction = btn.getAttribute('data-reaction');
        const postId   = btn.getAttribute('data-post-id');
        const countEl  = btn.querySelector('.reaction-count');

        if (!countEl) return;

        let currentCount = parseInt(countEl.innerText) || 0;
        countEl.innerText = currentCount + 1;
        btn.style.borderColor = 'var(--brand-red)';

        const apiUrl = window.ProthomNewsData ? window.ProthomNewsData.apiUrl : '/wp-json/prothom-news/v1';
        fetch(`${apiUrl}/react`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ post_id: postId, reaction: reaction })
        }).catch(() => {});
      });
    });
  },

  /**
   * 7. Reader Bookmarks ("পরে পড়ুন") Driver
   */
  initBookmarkSystem() {
    const bookmarkBtn = document.getElementById('bookmark-article-btn');

    if (!bookmarkBtn) return;

    const postId = bookmarkBtn.getAttribute('data-post-id');
    const title  = bookmarkBtn.getAttribute('data-title');
    const url    = bookmarkBtn.getAttribute('data-url');

    let bookmarks = JSON.parse(localStorage.getItem('prothom_bookmarks') || '[]');
    const isBookmarked = bookmarks.some(b => b.id === postId);

    if (isBookmarked) {
      bookmarkBtn.classList.add('bookmarked');
      bookmarkBtn.innerHTML = '📌 <span>সংরক্ষিত</span>';
    }

    bookmarkBtn.addEventListener('click', () => {
      bookmarks = JSON.parse(localStorage.getItem('prothom_bookmarks') || '[]');
      const index = bookmarks.findIndex(b => b.id === postId);

      if (index > -1) {
        bookmarks.splice(index, 1);
        bookmarkBtn.classList.remove('bookmarked');
        bookmarkBtn.innerHTML = '📌 <span>বুকমার্ক করুন</span>';
      } else {
        bookmarks.push({ id: postId, title: title, url: url });
        bookmarkBtn.classList.add('bookmarked');
        bookmarkBtn.innerHTML = '📌 <span>সংরক্ষিত</span>';
      }
      localStorage.setItem('prothom_bookmarks', JSON.stringify(bookmarks));
    });
  },

  /**
   * 8. Copy Article Link Button Driver
   */
  initCopyLink() {
    const copyBtn = document.getElementById('copy-link-btn');
    if (!copyBtn) return;

    copyBtn.addEventListener('click', () => {
      const url = copyBtn.getAttribute('data-url') || window.location.href;
      navigator.clipboard.writeText(url).then(() => {
        const originalText = copyBtn.innerHTML;
        copyBtn.innerHTML = '✓ <span>কপি হয়েছে</span>';
        setTimeout(() => {
          copyBtn.innerHTML = originalText;
        }, 2000);
      });
    });
  },

  /**
   * 9. Off-Canvas Mobile Drawer Driver
   */
  initMobileDrawer() {
    const triggerBtn = document.getElementById('mobile-menu-trigger');
    const bottomBarTrigger = document.querySelector('.bottom-bar-item #mobile-menu-trigger') || document.querySelectorAll('#mobile-menu-trigger')[1];
    const drawer = document.getElementById('mobile-drawer');
    const backdrop = document.getElementById('mobile-drawer-backdrop');
    const closeBtn = document.getElementById('mobile-drawer-close');

    if (!drawer || !backdrop) return;

    const openDrawer = () => {
      drawer.classList.add('active');
      backdrop.classList.add('active');
    };

    const closeDrawer = () => {
      drawer.classList.remove('active');
      backdrop.classList.remove('active');
    };

    if (triggerBtn) triggerBtn.addEventListener('click', openDrawer);
    if (bottomBarTrigger) bottomBarTrigger.addEventListener('click', openDrawer);
    if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
    backdrop.addEventListener('click', closeDrawer);

    // Accordion Toggle for Mobile Drawer Submenus (Level 1, 2, 3)
    document.querySelectorAll('.mobile-drawer .menu-item-has-children > a').forEach(parentLink => {
      parentLink.addEventListener('click', (e) => {
        const subMenu = parentLink.nextElementSibling;
        if (subMenu && subMenu.classList.contains('sub-menu')) {
          e.preventDefault();
          subMenu.classList.toggle('active-accordion');
          parentLink.classList.toggle('accordion-open');
        }
      });
    });
  },

  /**
   * 10. Single Article Font Size Adjuster (A+, A-, A)
   */
  initFontResizer() {
    const fontPlusBtn  = document.getElementById('font-increase-btn');
    const fontMinusBtn = document.getElementById('font-decrease-btn');
    const fontResetBtn = document.getElementById('font-reset-btn');
    const articleBody  = document.querySelector('.article-body-content');

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
   * 11. Bengali Text-to-Speech (Voice Reader)
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
   * 12. Sticky Bottom Ad Dismiss
   */
  initAdDismiss() {
    const closeBtn = document.querySelector('.ad-sticky-bottom .ad-close-btn');
    if (closeBtn) {
      closeBtn.addEventListener('click', () => {
        const parentAd = closeBtn.closest('.ad-sticky-bottom');
        if (parentAd) parentAd.style.display = 'none';
      });
    }
  },

  /**
   * 13. Scroll To Top Floating Button Driver
   */
  initScrollToTop() {
    let topBtn = document.getElementById('scroll-to-top');
    if (!topBtn) {
      topBtn = document.createElement('button');
      topBtn.id = 'scroll-to-top';
      topBtn.className = 'scroll-to-top-btn';
      topBtn.title = 'উপরে যান';
      topBtn.setAttribute('aria-label', 'Scroll to top');
      topBtn.innerHTML = '↑';
      document.body.appendChild(topBtn);
    }

    window.addEventListener('scroll', () => {
      if (window.scrollY > 350) {
        topBtn.classList.add('visible');
      } else {
        topBtn.classList.remove('visible');
      }
    });

    topBtn.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  },

  /**
   * 14. Single Article Reading Progress Bar Driver
   */
  initReadingProgressBar() {
    const progressBar = document.getElementById('article-reading-progress');
    const article = document.querySelector('.main-article-content');

    if (!progressBar || !article) return;

    window.addEventListener('scroll', () => {
      const articleBox = article.getBoundingClientRect();
      const articleHeight = article.offsetHeight;
      const windowHeight = window.innerHeight;
      
      const scrolled = Math.max(0, windowHeight - articleBox.top);
      const total = articleHeight;
      const percentage = Math.min(100, Math.max(0, (scrolled / total) * 100));

      progressBar.style.width = `${percentage}%`;
    });
  }
};

window.ProthomNewsApp = ProthomNewsApp;

document.addEventListener('DOMContentLoaded', () => {
  ProthomNewsApp.init();
});
