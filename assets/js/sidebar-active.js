document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById("sidebar");
    const foldMenubarSwitch = document.getElementById('foldMenubarSwitch');
    const foldBtn = document.getElementById('sidebarFoldBtn');
    const navbarToggler = document.querySelector('.navbar-toggler');
  
    if (foldMenubarSwitch) {
        sidebar.classList.remove('sidebar-unfolded')
    }
    // Helper to determine if sidebar is unfolded
    function isSidebarUnfolded() {
      // Sidebar must have the 'sidebar-unfolded' class AND the fold switch must be unchecked
      return !sidebar.classList.contains('sidebar-unfolded') && foldMenubarSwitch.checked;
    }
  
    // Core accordion logic
    function initAccordion() {
      // console.log("Sidebar script initialized");
      if (isSidebarUnfolded()) {
        // console.log("Sidebar is folded — aborting accordion logic");
        return;
      }
    //   console.log("Sidebar is unfolded — running accordion logic");
  
      // Hide all submenus
      const submenus = sidebar.querySelectorAll(".inline-submenu");
      submenus.forEach((menu, i) => {
        menu.style.display = "none";
        // console.log(`Submenu[${i}] hidden`);
      });
  
      // Attach click handlers to main links
      sidebar.querySelectorAll(".main-link").forEach((mainLink, idx) => {
        mainLink.addEventListener("click", (e) => {
        //   console.log(`Clicked main-link[${idx}]: ${mainLink.textContent.trim()}`);
          const submenu = mainLink.nextElementSibling;
          if (!submenu || !submenu.classList.contains("inline-submenu")) {
            // console.log(" → No inline-submenu, normal navigation");
              // Redirect to the link's href
              window.location.href = mainLink.href;
            return;
          }
          e.preventDefault();
  
          const isVisible = submenu.style.display === "block";
        //   console.log(" → Currently visible?", isVisible);
  
          // Close all
          submenus.forEach((menu, j) => {
            menu.style.display = "none";
            // console.log(`   Closed submenu[${j}]`);
          });
  
          // Toggle this one
          submenu.style.display = isVisible ? "none" : "block";
          // console.log(" → submenu toggled to:", submenu.style.display);
        });
      });
    }
  
    // Initial run
    initAccordion();
  
    // Respond to the Fold Menubar switch
    foldMenubarSwitch.addEventListener('change', () => {
    //   console.log('foldMenubarSwitch changed:', foldMenubarSwitch.checked);
      if (foldMenubarSwitch.checked) {
        sidebar.classList.remove('sidebar-unfolded');
      } else {
        sidebar.classList.add('sidebar-unfolded');
      }
      initAccordion();
    });
  
    // Respond to Fold/Unfold button
    foldBtn.addEventListener('click', (e) => {
      e.preventDefault();
      const nowUnfolded = !sidebar.classList.toggle('sidebar-unfolded');
      // Keep switch in sync
      foldMenubarSwitch.checked = !nowUnfolded;
    //   console.log('sidebar-unfolded toggled, now unfolded?', nowUnfolded);
      initAccordion();
    });
  
    // Re-run accordion logic when navbar toggler is used (e.g., mobile collapse)
    if (navbarToggler) {
      navbarToggler.addEventListener('click', () => {
        console.log('navbar-toggler clicked');
        // The sidebar state may have changed; re-check
        setTimeout(initAccordion, 300);
        // Core accordion logic
        function initAccordion() {
          // console.log("Sidebar script initialized");
          if (isSidebarUnfolded()) {
            // console.log("Sidebar is folded — aborting accordion logic");
            return;
          }
        //   console.log("Sidebar is unfolded — running accordion logic");
      
          // Hide all submenus
          const submenus = sidebar.querySelectorAll(".inline-submenu");
          submenus.forEach((menu, i) => {
            menu.style.display = "none";
            // console.log(`Submenu[${i}] hidden`);
          });
      
          // Attach click handlers to main links
          sidebar.querySelectorAll(".main-link").forEach((mainLink, idx) => {
            mainLink.addEventListener("click", (e) => {
            //   console.log(`Clicked main-link[${idx}]: ${mainLink.textContent.trim()}`);
              const submenu = mainLink.nextElementSibling;
              if (!submenu || !submenu.classList.contains("inline-submenu")) {
                // console.log(" → No inline-submenu, normal navigation");
                  // Redirect to the link's href
                  window.location.href = mainLink.href;
                return;
              }
              e.preventDefault();
      
              const isVisible = submenu.style.display === "block";
            //   console.log(" → Currently visible?", isVisible);
      
              // Close all
              submenus.forEach((menu, j) => {
                menu.style.display = "none";
                // console.log(`   Closed submenu[${j}]`);
              });
      
              // Toggle this one
              submenu.style.display = isVisible ? "none" : "block";
              // console.log(" → submenu toggled to:", submenu.style.display);
            });
          });
        }
      
        // Initial run
        initAccordion();
      
        // Respond to the Fold Menubar switch
        foldMenubarSwitch.addEventListener('change', () => {
        //   console.log('foldMenubarSwitch changed:', foldMenubarSwitch.checked);
          if (foldMenubarSwitch.checked) {
            sidebar.classList.remove('sidebar-unfolded');
          } else {
            sidebar.classList.add('sidebar-unfolded');
          }
          initAccordion();
        });
      });
    }
  });
  