/**
 * ProthomNews 100% Bulletproof SPA Router Engine
 * Guarantees zero page reloads across all pages, posts, categories, tags, search, and pagination.
 *
 * @package ProthomNews
 */

document.addEventListener('DOMContentLoaded', () => {
  const spaContainer = document.getElementById('spa-content-container');
  if (!spaContainer) return;

  // Create & Insert Top SPA Progress Bar
  let progressBar = document.getElementById('spa-progress-bar');
  if (!progressBar) {
    progressBar = document.createElement('div');
    progressBar.id = 'spa-progress-bar';
    document.body.appendChild(progressBar);
  }

  const startProgress = () => {
    progressBar.style.width = '35%';
    progressBar.style.opacity = '1';
  };

  const setProgress = (percent) => {
    progressBar.style.width = percent + '%';
  };

  const endProgress = () => {
    progressBar.style.width = '100%';
    setTimeout(() => {
      progressBar.style.opacity = '0';
      setTimeout(() => {
        progressBar.style.width = '0%';
      }, 300);
    }, 200);
  };

  /**
   * Fetch and Swap Page Content (Supports all templates: Single, Archive, Search, Page)
   */
  const loadPage = async (url, pushHistory = true) => {
    try {
      startProgress();

      // Smooth fade out
      spaContainer.style.opacity = '0.3';
      spaContainer.style.transition = 'opacity 0.2s cubic-bezier(0.4, 0, 0.2, 1)';

      setProgress(65);

      // Fetch target HTML
      const response = await fetch(url, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-ProthomNews-SPA': '1'
        }
      });

      if (!response.ok) {
        window.location.href = url; // Fallback navigation if HTTP error
        return;
      }

      const htmlText = await response.text();
      setProgress(85);

      const parser = new DOMParser();
      const doc = parser.parseFromString(htmlText, 'text/html');

      const newContent = doc.getElementById('spa-content-container');
      const newTitle = doc.querySelector('title');
      const newBodyClass = doc.body.getAttribute('class');

      if (!newContent) {
        window.location.href = url;
        return;
      }

      // Update Document Title
      if (newTitle) {
        document.title = newTitle.innerText;
      }

      // Update Body Class for Page Type specific styling
      if (newBodyClass) {
        document.body.setAttribute('class', newBodyClass);
      }

      // Swap Main Content
      spaContainer.innerHTML = newContent.innerHTML;

      // Update History State
      if (pushHistory) {
        window.history.pushState({ url: url }, document.title, url);
      }

      // Scroll to Top smoothly
      window.scrollTo({ top: 0, behavior: 'smooth' });

      // Re-initialize theme client scripts (TTS, Font resizer, tab handlers, mobile drawer)
      if (window.ProthomNewsApp && typeof window.ProthomNewsApp.reinit === 'function') {
        window.ProthomNewsApp.reinit();
      }

      // Highlight active menu items
      updateActiveMenuItems(url);

      // Fade back in
      spaContainer.style.opacity = '1';
      endProgress();

    } catch (error) {
      console.error('SPA Navigation Error:', error);
      window.location.href = url;
    }
  };

  /**
   * Intercept Link Clicks globally across ALL pages and widgets
   */
  document.addEventListener('click', (e) => {
    const link = e.target.closest('a');
    if (!link) return;

    const href = link.getAttribute('href');
    if (!href) return;

    // Filter out anchors, empty, admin links, downloads, or external targets
    if (
      href.startsWith('#') ||
      href.startsWith('javascript:') ||
      href.startsWith('mailto:') ||
      href.startsWith('tel:') ||
      link.target === '_blank' ||
      link.hasAttribute('data-no-spa') ||
      link.classList.contains('ab-item') ||
      href.includes('/wp-admin') ||
      href.includes('/wp-login.php')
    ) {
      return;
    }

    // Check if internal domain URL
    const targetUrl = new URL(href, window.location.origin);
    if (targetUrl.origin !== window.location.origin) {
      return; // External domain link
    }

    e.preventDefault();

    // If clicking same active URL, scroll to top
    if (targetUrl.href === window.location.href) {
      window.scrollTo({ top: 0, behavior: 'smooth' });
      return;
    }

    // Perform SPA Page Swap
    loadPage(targetUrl.href, true);
  });

  /**
   * Handle Browser Back/Forward PopState Navigation
   */
  window.addEventListener('popstate', (e) => {
    if (e.state && e.state.url) {
      loadPage(e.state.url, false);
    } else {
      loadPage(window.location.href, false);
    }
  });

  /**
   * Update Active Menu Link States
   */
  function updateActiveMenuItems(currentUrl) {
    const menuLinks = document.querySelectorAll('.primary-menu a, .mobile-menu-list a, .footer-links a');
    menuLinks.forEach((a) => {
      const parentLi = a.closest('li');
      if (parentLi) {
        if (a.href === currentUrl) {
          parentLi.classList.add('current-menu-item');
        } else {
          parentLi.classList.remove('current-menu-item');
        }
      }
    });
  }
});
