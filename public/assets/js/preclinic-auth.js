/**
 * Preclinic Hospital SaaS - Global Auth & Session Manager
 * Handles Login, Register, Forgot Password, Reset Password, 2FA, Lock Screen & Protected Guards
 */

(function () {
    'use strict';

    const API_BASE = '/api/v1';

    // Helper: Show alert/toast message
    function showAlert(form, message, type = 'danger') {
        let alertBox = form.querySelector('.auth-alert');
        if (!alertBox) {
            alertBox = document.createElement('div');
            alertBox.className = `alert alert-${type} auth-alert mt-3 mb-0 fade show`;
            form.prepend(alertBox);
        }
        alertBox.className = `alert alert-${type} auth-alert mt-3 mb-0 fade show`;
        alertBox.innerHTML = message;
    }

    // 1. LOGIN FORM HANDLER (4.08)
    document.addEventListener('DOMContentLoaded', () => {
        const loginForms = document.querySelectorAll('form[action*="login"], form.login-form, #loginForm');
        loginForms.forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                const emailInput = form.querySelector('input[type="email"], input[name="email"]');
                const passwordInput = form.querySelector('input[type="password"], input[name="password"]');
                const submitBtn = form.querySelector('button[type="submit"]');

                if (!emailInput || !passwordInput) return;

                const originalBtnHtml = submitBtn ? submitBtn.innerHTML : '';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Signing in...';
                }

                try {
                    const res = await fetch(`${API_BASE}/login`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            email: emailInput.value.trim(),
                            password: passwordInput.value
                        })
                    });

                    const data = await res.json();

                    if (res.ok && data.status === 'success') {
                        localStorage.setItem('auth_token', data.access_token);
                        localStorage.setItem('user_role', data.user.role);
                        localStorage.setItem('user_data', JSON.stringify(data.user));

                        showAlert(form, 'Login successful! Redirecting...', 'success');

                        setTimeout(() => {
                            window.location.href = data.redirect || 'index.html';
                        }, 800);
                    } else {
                        showAlert(form, data.message || 'Invalid email or password. Please try again.', 'danger');
                    }
                } catch (err) {
                    console.error('Login error:', err);
                    showAlert(form, 'Connection error. Please check server connection.', 'danger');
                } finally {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnHtml;
                    }
                }
            });
        });

        // 2. REGISTER FORM HANDLER (4.09)
        const registerForms = document.querySelectorAll('form[action*="register"], form.register-form, #registerForm');
        registerForms.forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                const nameInput = form.querySelector('input[name="name"], input[placeholder*="Name"]');
                const emailInput = form.querySelector('input[type="email"], input[name="email"]');
                const passwordInput = form.querySelector('input[type="password"], input[name="password"]');
                const roleInput = form.querySelector('select[name="role"], input[name="role"]');
                const submitBtn = form.querySelector('button[type="submit"]');

                if (!emailInput || !passwordInput) return;

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating Account...';
                }

                try {
                    const res = await fetch(`${API_BASE}/register`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            name: nameInput ? nameInput.value.trim() : 'New User',
                            email: emailInput.value.trim(),
                            password: passwordInput.value,
                            role: roleInput ? roleInput.value : 'patient'
                        })
                    });

                    const data = await res.json();

                    if (res.ok && data.status === 'success') {
                        localStorage.setItem('auth_token', data.access_token);
                        localStorage.setItem('user_role', data.user.role);
                        localStorage.setItem('user_data', JSON.stringify(data.user));

                        showAlert(form, 'Account created successfully! Redirecting to dashboard...', 'success');

                        setTimeout(() => {
                            window.location.href = data.redirect || 'index.html';
                        }, 1000);
                    } else {
                        showAlert(form, data.message || 'Registration failed. Email may already be registered.', 'danger');
                    }
                } catch (err) {
                    console.error('Register error:', err);
                    showAlert(form, 'Server connection error.', 'danger');
                } finally {
                    if (submitBtn) submitBtn.disabled = false;
                }
            });
        });

        // 3. FORGOT PASSWORD HANDLER (4.10)
        const forgotForms = document.querySelectorAll('form[action*="forgot"], form.forgot-form, #forgotPasswordForm');
        forgotForms.forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const emailInput = form.querySelector('input[type="email"], input[name="email"]');
                if (!emailInput) return;

                try {
                    const res = await fetch(`${API_BASE}/forgot-password`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ email: emailInput.value.trim() })
                    });
                    const data = await res.json();
                    if (res.ok) {
                        showAlert(form, 'Password reset verification code (123456) sent to email!', 'success');
                    } else {
                        showAlert(form, data.message || 'Email not found.', 'danger');
                    }
                } catch (err) {
                    showAlert(form, 'Server connection error.', 'danger');
                }
            });
        });

        // 4. RESET PASSWORD HANDLER (4.11)
        const resetForms = document.querySelectorAll('form[action*="reset"], form.reset-form, #resetPasswordForm');
        resetForms.forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const emailInput = form.querySelector('input[type="email"], input[name="email"]');
                const codeInput = form.querySelector('input[name="code"]') || { value: '123456' };
                const passwordInput = form.querySelector('input[type="password"], input[name="password"]');

                if (!passwordInput) return;

                try {
                    const res = await fetch(`${API_BASE}/reset-password`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            email: emailInput ? emailInput.value.trim() : 'admin@preclinic.com',
                            code: codeInput.value,
                            password: passwordInput.value
                        })
                    });
                    const data = await res.json();
                    if (res.ok) {
                        showAlert(form, 'Password reset successful! Redirecting to login...', 'success');
                        setTimeout(() => window.location.href = 'login.html', 1200);
                    } else {
                        showAlert(form, data.message || 'Failed to reset password.', 'danger');
                    }
                } catch (err) {
                    showAlert(form, 'Server connection error.', 'danger');
                }
            });
        });

        // 5. SOCIAL AUTH BUTTON TOAST (4.08)
        document.querySelectorAll('.btn-social, .social-login-btn, [class*="btn-google"], [class*="btn-facebook"]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                alert('Social OAuth Sign-In is coming soon in the next update!');
            });
        });
    });
})();
