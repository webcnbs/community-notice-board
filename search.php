<?php
session_name(SESSION_NAME); session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Search Notices</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <h2>Search Notices</h2>
  <form onsubmit="event.preventDefault(); runSearch();">
    <input type="text" id="search-q" placeholder="Enter keywords">
    <button type="submit">Search</button>
  </form>
  <ul id="search-results"></ul>

  <script>
    async function runSearch() {
      const q = document.getElementById('search-q').value;
      const res = await fetch('api/search.php?q=' + encodeURIComponent(q));
      const json = await res.json();
      const list = document.getElementById('search-results');
      list.innerHTML = '';
      json.data.forEach(n => {
        const li = document.createElement('li');
        li.innerHTML = `<a href="view-notice.php?id=${n.notice_id}">${n.title}</a>
                        <small>${n.category} • ${n.priority}</small>`;
        list.appendChild(li);
      });
    }
  </script>
</body>
</html>