let score = 0;
let NbAutoClicker=0;
let production_1_autoclicker = 0.5
const cookie = document.getElementById("cookie");
const scoreDisplay = document.getElementById("scoreDisplay");
const autoClicker_button=document.getElementById("buy_AutoClicker");
const autoClickerDisplay=document.getElementById("autoClickerDSP")




cookie.addEventListener("click", () => {
  score++;
  scoreDisplay.textContent = score.toFixed(0);
});

autoClicker_button.addEventListener("click", () => {
  let prix=prix_autoclicker(NbAutoClicker);
  NbAutoClicker++;
  autoClickerDisplay.textContent = NbAutoClicker;
  updateButton();
  updateScore(-prix);
  update_prix_autoclicker();
});

cookie.addEventListener('click', () => {
  // Si la classe existe déjà, on la retire d’abord pour pouvoir relancer l’animation
  cookie.classList.remove('clicked');

  // Petit délai pour forcer le navigateur à redéclencher l’animation
  void cookie.offsetWidth;

  cookie.classList.add('clicked');
});

function updateScore(n) {
  //Modifie le score en ajoutan n au score
  score += n;
  scoreDisplay.textContent = score.toFixed(0); // .toFixed(1) pour 1 décimale
  
}

function prix_autoclicker(n){
  //donne le prix pour acheter 1 autoclicker sachant qu'on en a deja n
  return Math.trunc(1.5**n)
}

function updateButton() {
  if (score < prix_autoclicker(NbAutoClicker)) {
    autoClicker_button.classList.add('locked');
    autoClicker_button.disabled = true; // désactive le clic
  } else {
    autoClicker_button.classList.remove('locked');
    autoClicker_button.disabled = false; // réactive le clic
  }
}

function update_prix_autoclicker(){
  let display=document.getElementById("dsp_prix_autoclicker")
  display.textContent=prix_autoclicker(NbAutoClicker).toFixed(0)
}

update_prix_autoclicker()
setInterval(updateButton, 1000);
setInterval(updateScore, 1000,production_1_autoclicker*NbAutoClicker);
