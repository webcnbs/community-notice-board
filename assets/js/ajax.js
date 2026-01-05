// assets/js/ajax.js

/**
 * Helper: Show a temporary notification toast
 */
function toast(message, type = 'info') {
    const el = document.createElement('div');
    el.textContent = message;
    el.style.position = 'fixed';
    el.style.bottom = '20px';
    el.style.right = '20px';
    el.style.padding = '10px 14px';
    el.style.borderRadius = '8px';
    el.style.zIndex = '9999';
    el.style.background = type === 'error' ? '#dc2626' : (type === 'success' ? '#16a34a' : '#2563eb');
    el.style.color = '#fff';
    el.style.boxShadow = '0 2px 8px rgba(0,0,0,0.2)';
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 3000);
}

/**
 * Helper: Perform a fetch and return parsed JSON
 */
async function fetchJSON(url, options = {}) {
    const res = await fetch(url, options);
    if (!res.ok) throw new Error(`HTTP Error: ${res.status}`);
    return res.json();
}

/**
 * Fetch and render the list of notices
 */
async function fetchNotices(params = {}) {
    try {
        const qs = new URLSearchParams(params).toString();
        // ✅ Points directly to route.php
        const json = await fetchJSON(`route.php?action=get-notices&${qs}`);
        
        const list = document.getElementById('notice-list');
        if (!list) return;
        list.innerHTML = '';

        // FIX: Access the 'notices' array inside the json object
        const notices = json.notices || [];
        const total = json.total || 0;

        // Update the Counter UI
        const meta = document.getElementById('results-meta');
        const countSpan = document.getElementById('results-count');
        if (meta && countSpan) {
            // Show the counter only if a search or filter is actually being used
            const isFiltered = params.q || params.category_id || params.priority;
            meta.style.display = isFiltered ? 'block' : 'none';
            countSpan.textContent = total;
        }

        if (notices.length === 0) {
            list.innerHTML = '<p class="text-muted">No notices found.</p>';
            return;
        }

        // FIX: Loop through 'notices' instead of 'json'
        notices.forEach(n => {
            // Logic to highlight the search term
            let displayTitle = n.title;
            if (params.q) {
                const regex = new RegExp(`(${params.q})`, 'gi');
                displayTitle = n.title.replace(regex, '<mark>$1</mark>');
            }

            const li = document.createElement('li');
            li.className = 'notice-item'; 
            li.innerHTML = `
                <div class="notice-card">
                    <a href="view-notice.php?id=${n.notice_id}" class="notice-title">
                        <strong>${displayTitle}</strong>
                    </a>
                    <br>
                    <small class="notice-meta">
                        ${n.category || 'General'} • 
                        <span class="priority-${n.priority.toLowerCase()}">${n.priority}</span> • 
                        ${new Date(n.created_at).toLocaleDateString()}
                    </small>
                </div>
            `;
            list.appendChild(li);
        });

        // Optional: Shows a success toast with the total count
        if (params.q) {
            toast(`Found ${total} result(s)`, 'success');
        }
    } catch (err) {
        console.error("Fetch Notices Error:", err);
        toast('Failed to load notices', 'error');
    }
}

/**
 * Load comments for a specific notice
 */
async function loadComments(noticeId) {
    try {
        // ✅ Points to route.php instead of api/
        const json = await fetchJSON(`route.php?action=get-comments&notice_id=${noticeId}`);
        const div = document.getElementById('comments');
        if (!div) return;

        if (json.length === 0) {
            div.innerHTML = '<p>No comments yet.</p>';
            return;
        }

        div.innerHTML = json.map(c => `
            <div class="comment">
                <p><strong>${c.username}:</strong> ${c.content}</p>
                <small>${new Date(c.created_at).toLocaleString()}</small>
            </div>
        `).join('');
    } catch (err) {
        console.error("Load Comments Error:", err);
        toast('Failed to load comments', 'error');
    }
}

/**
 * Event Listeners: Filtering and Comment Submission
 */
/**
 * Event Listeners: Filtering and Comment Submission
 */
document.addEventListener('DOMContentLoaded', () => {
    const filterBtn = document.getElementById('filter-go');
    const clearBtn = document.getElementById('filter-clear'); // Get the new Clear button
    
    // 1. Wire up "Apply" button
    // 1. Wire up "Apply" button
    if (filterBtn) {
    // Add 'e' here to capture the click event
    filterBtn.addEventListener('click', (e) => {
        // PREVENT PAGE REFRESH: This is the critical line
        e.preventDefault(); 

        fetchNotices({
            category_id: document.getElementById('filter-category').value,
            priority: document.getElementById('filter-priority').value,
            q: document.getElementById('filter-q').value
        });
    });
}

    // 2. Wire up "Clear" button (The "something" to add)
    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            // Reset all input fields
            document.getElementById('filter-category').value = '';
            document.getElementById('filter-priority').value = '';
            document.getElementById('filter-q').value = '';
            
            // Hide the counter metadata
            const meta = document.getElementById('results-meta');
            if (meta) meta.style.display = 'none';

            // Fetch the original full list
            fetchNotices({});
        });
    }

    // Initial load of notices
    fetchNotices();
});

// Global click listener for comment sending
document.addEventListener('click', async (e) => {
    if (e.target && e.target.id === 'comment-send') {
        const noticeId = e.target.dataset.noticeId;
        const contentEl = document.getElementById('comment-input');
        const content = (contentEl && contentEl.value || '').trim();

        if (!content) {
            toast('Comment cannot be empty', 'error');
            return;
        }

        try {
            // ✅ Points to route.php instead of api/
            const json = await fetchJSON('route.php?action=add-comment', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ notice_id: noticeId, content })
            });

            if (json.ok) {
                toast('Comment submitted for approval', 'success');
                contentEl.value = '';
                loadComments(noticeId);
            } else {
                toast(json.message || 'Unable to submit comment', 'error');
            }
        } catch (err) {
            console.error("Add Comment Error:", err);
            toast('Error connecting to server', 'error');
        }
    }
});

// Expose functions to window for inline HTML calls (like view-notice.php)
window.loadComments = loadComments;
window.fetchNotices = fetchNotices;