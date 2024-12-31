document.addEventListener("DOMContentLoaded", function () {
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const passwordField = document.getElementById('inputPassword');
    const passwordConfirmField = document.getElementById('inputConfirmPassword');
    const eyeIcon = document.getElementById('eyeIcon');
    const eyeIconConfirm = document.getElementById('eyeIconConfirm');

    togglePassword.addEventListener('click', function () {
        // Alterna o tipo do campo entre 'password' e 'text'
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;

        // Alterna o ícone entre 'bi-eye' e 'bi-eye-slash'
        eyeIcon.classList.toggle('bi-eye');
        eyeIcon.classList.toggle('bi-eye-slash');
    });

    toggleConfirmPassword.addEventListener('click', function () {
        
        const typeConfirm = passwordConfirmField.type === 'password' ? 'text' : 'password';
        passwordConfirmField.type = typeConfirm;

        eyeIconConfirm.classList.toggle('bi-eye');
        eyeIconConfirm.classList.toggle('bi-eye-slash');

    });

});

const swiper = new Swiper('.swiper', {
    // Optional parameters
    direction: 'horizontal',
    loop: false,
  
    // If we need pagination
    pagination: {
      el: '.swiper-pagination',
      clickable: true, 
    },
});

// Obtém o menu de navegação
const navBarmain = document.getElementById('navbar-main');
const barShow = document.getElementById('showMenu');

// Função para esconder o menu
function hideMenu(evento, callback) {
    if (evento === 'click') {
        callback();
    }
}

// Adiciona o evento de clique no botão para esconder o menu
document.getElementById('hide-menu').addEventListener('click', function() {
    hideMenu('click', function() {
        navBarmain.classList.add('hidenav'); // Esconde o menu
        barShow.style.display = 'flex';
    });
});

document.getElementById('showMenu').addEventListener('click', function() {
    hideMenu('click', function() {
        navBarmain.classList.remove('hidenav'); // Esconde o menu
        barShow.style.display = 'none';
    });
});