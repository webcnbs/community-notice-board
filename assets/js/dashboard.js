/* assets/js/dashboard.js */
(function(){ 
  // This file intentionally duplicates admin.js so you don't have to change script tags.
  const body = document.body;
  const path = window.location.pathname.split("/").pop();
  document.querySelectorAll(".sidebar-nav a").forEach((a) => {
    const href = (a.getAttribute("href") || "").split("/").pop();
    if (href && href === path) a.classList.add("active");
  });
  const toggle = document.querySelector("[data-sidebar-toggle]");
  const overlay = document.querySelector("[data-sidebar-overlay]");
  function closeSidebar(){ body.classList.remove("sidebar-open"); }
  if (toggle) toggle.addEventListener("click", ()=> body.classList.toggle("sidebar-open"));
  if (overlay) overlay.addEventListener("click", closeSidebar);
  document.querySelectorAll(".sidebar-nav a").forEach((a) => {
    a.addEventListener("click", () => {
      if (window.matchMedia("(max-width: 720px)").matches) closeSidebar();
    });
  });
})();