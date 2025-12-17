// assets/js/main.js

// Generic helpers for UX and small utilities
export function qs(selector, scope = document) {
  return scope.querySelector(selector);
}

export function qsa(selector, scope = document) {
  return Array.from(scope.querySelectorAll(selector));
}

export function toast(message, type = 'info') {
  const el = document.createElement('div');
  el.textContent = message;
  el.style.position = 'fixed';
  el.style.bottom = '20px';
  el.style.right = '20px';
  el.style.padding = '10px 14px';
  el.style.borderRadius = '8px';
  el.style.background = type === 'error' ? '#dc2626' : (type === 'success' ? '#16a34a' : '#2563eb');
  el.style.color = '#fff';
  el.style.boxShadow = '0 2px 8px rgba(0,0,0,0.2)';
  document.body.appendChild(el);
  setTimeout(() => el.remove(), 2000);
}

export function fmtDate(str) {
  try { return new Date(str).toLocaleString(); } catch { return str; }
}