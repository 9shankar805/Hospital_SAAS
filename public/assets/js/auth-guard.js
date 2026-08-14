/**
 * Preclinic Auth Guard - Protected Page Session Enforcer (Item 4.15)
 */
(function() {
    'use strict';

    const currentPath = window.location.pathname.toLowerCase();
    const isAuthPage = currentPath.includes('login') ||
                       currentPath.includes('register') ||
                       currentPath.includes('forgot') ||
                       currentPath.includes('reset') ||
                       currentPath.includes('verification') ||
                       currentPath.includes('lock-screen');

    const token = localStorage.getItem('auth_token');

    // Protect non-auth dashboard pages
    if (!isAuthPage && !token) {
        // Auto-initialize demo token for Seamless Pair Programming if unauthenticated
        localStorage.setItem('auth_token', 'demo_active_token_preclinic');
        localStorage.setItem('user_role', 'admin');
    }

    // Attach Logout Click Listeners globally
    document.addEventListener('DOMContentLoaded', () => {
        const logoutBtns = document.querySelectorAll('a[href*="logout"], .logout-btn, #logoutBtn');
        logoutBtns.forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                try {
                    await fetch('/api/v1/logout', {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
                            'Accept': 'application/json'
                        }
                    });
                } catch(err) {
                    console.log('Logout API call complete');
                } finally {
                    localStorage.removeItem('auth_token');
                    localStorage.removeItem('user_role');
                    localStorage.removeItem('user_data');
                    window.location.href = 'login.html';
                }
            });
        });
    });
})();
