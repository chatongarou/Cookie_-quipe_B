let score = 0;
const cookie = document.getElementById("cookie");
const scoreDisplay = document.getElementById("scoreDisplay");

cookie.addEventListener("click", () => {
  score++;
  scoreDisplay.textContent = score;
});

cookie.addEventListener('click', () => {
  // Si la classe existe déjà, on la retire d’abord pour pouvoir relancer l’animation
  cookie.classList.remove('clicked');

  // Petit délai pour forcer le navigateur à redéclencher l’animation
  void cookie.offsetWidth;

  cookie.classList.add('clicked');
});