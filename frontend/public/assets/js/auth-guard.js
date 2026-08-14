/**
 * NepXMedica Auth Guard - Protected Page Session Enforcer (Item 4.15)
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

    // Protect non-auth dashboard pages: redirect to login if unauthenticated
    if (!isAuthPage) {
        if (!token) {
            window.location.href = 'login.html';
            return;
        }

        // Verify token with backend
        fetch('/api/v1/me', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                // Token invalid or expired
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user_role');
                localStorage.removeItem('user_data');
                window.location.href = 'login.html';
            }
        })
        .catch(err => {
            console.error('Auth check error:', err);
        });
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
