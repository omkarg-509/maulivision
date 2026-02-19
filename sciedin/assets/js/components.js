function loadComponent(id, file, callback) {
  fetch(file)
    .then(response => response.text())
    .then(data => {
      const el = document.getElementById(id);
      if (el) {
        el.innerHTML = data;
      }
      if (callback) callback();
    })
    .catch(error => console.error('Error loading component:', error));
}

function updateNavbarLoginUI() {
  const navActions = document.getElementById('navActions');

  if (!navActions) {
    console.log('navActions not found');
    return;
  }

  const params = new URLSearchParams(window.location.search);
  const isLogin = params.get('isLogin');

  if (isLogin === 'true') {
    navActions.innerHTML = `
      <img src="/assets/img/profile.png" class="profile-img" onclick="location.href='/profile.html'">
      <button class="btn-outline" onclick="location.href='/auth/logout.html'">Logout</button>
    `;
  } else {
    navActions.innerHTML = `
      <button class="btn-outline" onclick="location.href='/auth/login.html'">Login</button>
      <button class="btn-primary" onclick="location.href='/auth/register.html'">Get Started</button>
    `;
  }
}

document.addEventListener('DOMContentLoaded', function () {
  loadComponent('header', '/components/header.html', updateNavbarLoginUI);
  loadComponent('footer', '/components/footer.html');
  loadComponent('video', 'video.html');
});
