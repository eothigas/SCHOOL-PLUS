document.addEventListener("DOMContentLoaded", () => {
    const progressBar = document.querySelector(".progress-bar");
    const startLogo = document.querySelector(".start-logo");
    const duration = 4000; // 2 segundos
    const intervalTime = 5; // Intervalo em milissegundos

    let progress = 0; // Progresso inicial
    const step = (100 / (duration / intervalTime)); // Passo de incremento

    const interval = setInterval(() => {
        progress += step; // Aumenta o progresso
        progressBar.style.width = `${progress}%`; // Atualiza a largura
        progressBar.setAttribute("aria-valuenow", Math.min(progress, 100));

        if (progress >= 100) {
            clearInterval(interval);
            progressBar.style.width = "100%"; 
            startLogo.classList.add('invisible'); 
            window.location.href = 'login.html';
        }
    }, intervalTime);
});