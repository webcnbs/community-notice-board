/* assets/js/admin.js
   Small UI helpers: active link, mobile sidebar toggle.
*/
(function () {
  const body = document.body;

  // Mark active nav link based on current file name
  const path = window.location.pathname.split("/").pop();
  document.querySelectorAll(".sidebar-nav a").forEach((a) => {
    const href = (a.getAttribute("href") || "").split("/").pop();
    if (href && href === path) a.classList.add("active");
  });

  // Mobile sidebar toggle
  const toggle = document.querySelector("[data-sidebar-toggle]");
  const overlay = document.querySelector("[data-sidebar-overlay]");

  function openSidebar() { body.classList.add("sidebar-open"); }
  function closeSidebar() { body.classList.remove("sidebar-open"); }

  if (toggle) toggle.addEventListener("click", () => {
    body.classList.toggle("sidebar-open");
  });
  if (overlay) overlay.addEventListener("click", closeSidebar);

  // Close sidebar on navigation (mobile)
  document.querySelectorAll(".sidebar-nav a").forEach((a) => {
    a.addEventListener("click", () => {
      if (window.matchMedia("(max-width: 720px)").matches) closeSidebar();
    });
  });
})();