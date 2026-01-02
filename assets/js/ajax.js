// assets/js/ajax.js

// If main.js is not loaded as a module, we re-implement minimal helpers here.
function toast(message, type = 'info') {
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

async function fetchJSON(url, options = {}) {
  const res = await fetch(url, options);
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

// Notices list loading with filters
async function fetchNotices(params = {}) {
  const qs = new URLSearchParams(params).toString();
  
  // ✅ Points directly to router
  const json = await fetchJSON(`route.php?action=get-notices&${qs}`);
  
  const list = document.getElementById('notice-list');
  if (!list) return;
  list.innerHTML = '';
  
  json.forEach(n => {
    const li = document.createElement('li');
    li.innerHTML = `
      <div class="notice-item">
        <a href="view-notice.php?id=${n.notice_id}" class="notice-title">${n.title}</a>
        <br>
        <small class="notice-meta">
          ${n.category || 'Uncategorized'} • 
          <span class="priority-${n.priority.toLowerCase()}">${n.priority}</span> • 
          ${new Date(n.created_at).toLocaleString()}
        </small>
      </div>
    `;
    list.appendChild(li);
  });
}

// Wire filters on home page
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('filter-go');
  if (btn) {
    btn.addEventListener('click', () => {
      fetchNotices({
        category_id: document.getElementById('filter-category').value,
        priority: document.getElementById('filter-priority').value,
        q: document.getElementById('filter-q').value,
        active_only: 1,
        page: 1,
        limit: 10
      }).catch(() => toast('Failed to load notices', 'error'));
    });
    fetchNotices({ active_only: 1, page: 1, limit: 10 }).catch(() => toast('Failed to load notices', 'error'));
  }
});

// Comments load and post
async function loadComments(noticeId) {
  try {
    const json = await fetchJSON(`api/comments.php?notice_id=${noticeId}`);
    const div = document.getElementById('comments');
    if (!div) return;
    div.innerHTML = (json.data || []).map(c => `
      <div class="comment">
        <p><strong>${c.username}:</strong> ${c.content}</p>
        <small>${c.created_at}</small>
      </div>
    `).join('');
  } catch {
    toast('Failed to load comments', 'error');
  }
}

document.addEventListener('click', async (e) => {
  if (e.target && e.target.id === 'comment-send') {
    const noticeId = e.target.dataset.noticeId;
    const contentEl = document.getElementById('comment-input');
    const content = (contentEl && contentEl.value || '').trim();
    if (!content) return toast('Comment cannot be empty', 'error');

    try {
      const json = await fetchJSON('api/comments.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ notice_id: noticeId, content })
      });
      if (json.ok) {
        toast('Comment submitted for approval', 'success');
        contentEl.value = '';
        loadComments(noticeId);
      } else {
        toast('Unable to submit comment', 'error');
      }
    } catch {
      toast('Unable to submit comment', 'error');
    }
  }
});

// Expose globally for inline usage in view-notice.php
window.loadComments = loadComments;
window.fetchNotices = fetchNotices;