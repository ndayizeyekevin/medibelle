// Toggle folded state
const sidebar = document.getElementById('sidebar');
const mainWrapper = document.getElementById('main-wrapper');
const header = document.getElementById('header');
const footer = document.getElementById('footer');
const foldBtn = document.getElementById('sidebarFoldBtn');
foldBtn.addEventListener('click', function(e) {
  e.preventDefault();
  sidebar.classList.toggle('folded');
  mainWrapper.classList.toggle('folded');
  header.classList.toggle('folded');
  footer.classList.toggle('folded');
});

// Sidebar Toggle for small screens
const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
sidebarToggleBtn.addEventListener('click', function() {
  sidebar.classList.remove('show');
});
document.querySelector('.navbar-toggler').addEventListener('click', function() {
  sidebar.classList.toggle('show');
});

// Customizer Toggle
const customizerToggle = document.getElementById('customizerToggle');
const appCustomizer = document.getElementById('app-customizer');
customizerToggle.addEventListener('click', function() {
  appCustomizer.classList.toggle('open');
});

// Fold Menubar Switch in Customizer
const foldMenubarSwitch = document.getElementById('foldMenubarSwitch');
foldMenubarSwitch.addEventListener('change', function() {
  if (this.checked) {
    sidebar.classList.add('folded');
    mainWrapper.classList.add('folded');
    header.classList.add('folded');
    footer.classList.add('folded');
  } else {
    sidebar.classList.remove('folded');
    mainWrapper.classList.remove('folded');
    header.classList.remove('folded');
    footer.classList.remove('folded');
  }
});

// Toggle inline submenu on click in unfolded mode
document.querySelectorAll('.menu-item > .main-link').forEach(link => {
  link.addEventListener('click', function(e) {
    if (!sidebar.classList.contains('folded')) {
      const parent = this.parentElement;
      if (parent.querySelector('.inline-submenu')) {
        e.preventDefault();
        parent.classList.toggle('active');
      }
    }
  });
});

// JavaScript-based hover for popouts in folded mode with positioning.
document.querySelectorAll('.menu-item').forEach(item => {
  item.addEventListener('mouseenter', function() {
    if (sidebar.classList.contains('folded')) {
      console.log("Menu hovered while sidebar is folded.");
      const popout = item.querySelector('.hover-menu');
      if (popout) {
        // Get the menu item's bounding rectangle relative to the viewport
        const rect = item.getBoundingClientRect();
        // Position the popout fixed next to the menu item:
        popout.style.position = 'fixed';
        popout.style.top = rect.top + 'px';
        // popout.style.left = (rect.right + 0) + 'px'; // 80px margin left from menu item
        popout.style.display = 'block';
        popout.style.zIndex = '10000';
        console.log("Submenu popped out at top:", rect.top, "left:", rect.right + 80);
      }
    }
  });
  item.addEventListener('mouseleave', function() {
    if (sidebar.classList.contains('folded')) {
      const popout = item.querySelector('.hover-menu');
      if (popout) {
        popout.style.display = 'none';
        console.log("Submenu hidden on mouse leave.");
      }
    }
  });
});

// Menubar Color Radio Buttons
const menubarRadios = document.getElementsByName('menubarColor');
const menubarLightTextBg = ['bg-primary','bg-secondary','bg-success','bg-danger','bg-dark'];
const menubarDarkTextBg = ['bg-warning','bg-info','bg-light'];
menubarRadios.forEach(radio => {
  radio.addEventListener('change', function() {
    sidebar.classList.remove('bg-primary','bg-secondary','bg-success','bg-danger',
                             'bg-warning','bg-info','bg-light','bg-dark');
    sidebar.classList.add(this.value);
    sidebar.classList.remove('text-white','text-dark');
    if (menubarLightTextBg.includes(this.value)) {
      sidebar.classList.add('text-white');
    } else if (menubarDarkTextBg.includes(this.value)) {
      sidebar.classList.add('text-dark');
    }
  });
});

// Header and Footer Color Radio Buttons
const headerRadios = document.getElementsByName('headerColor');
const headerDarkTextColors = ['bg-warning','bg-info','bg-light'];
const headerLightTextColors = ['bg-primary','bg-secondary','bg-success','bg-danger','bg-dark'];
headerRadios.forEach(radio => {
  radio.addEventListener('change', function() {
    header.classList.remove('bg-primary','bg-secondary','bg-success','bg-danger',
                            'bg-warning','bg-info','bg-light','bg-dark');
    footer.classList.remove('bg-primary','bg-secondary','bg-success','bg-danger',
                            'bg-warning','bg-info','bg-light','bg-dark');
    header.classList.add(this.value);
    footer.classList.add(this.value);
    header.classList.remove('text-white','text-dark','navbar-light','navbar-dark');
    footer.classList.remove('text-white','text-dark');
    if (headerDarkTextColors.includes(this.value)) {
      header.classList.add('text-dark','navbar-light');
      footer.classList.add('text-dark');
    } else if (headerLightTextColors.includes(this.value)) {
      header.classList.add('text-white','navbar-dark');
      footer.classList.add('text-white');
    }
  });
});