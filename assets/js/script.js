// ------------------------------
// CONFIG
// ------------------------------
let production_1_autoclicker = 0.5;  
let cookiesToSend = 0;  
const COOKIES_THRESHOLD = 10;  


// ------------------------------
// RÉFÉRENCES DOM
// ------------------------------
const cookie = document.getElementById("cookie");
const scoreDisplay = document.getElementById("scoreDisplay");
const autoClickerButton = document.getElementById("buy_AutoClicker");
const autoClickerDisplay = document.getElementById("autoClickerDSP");
const priceDisplay = document.getElementById("dsp_prix_autoclicker");
const debugScore = document.getElementById("debugScore");


// ------------------------------
// INITIALISATION DU SCORE
// ------------------------------
let score = parseFloat(scoreDisplay.textContent);
let NbAutoClicker = parseFloat(autoClickerDSP.textContent);




// ------------------------------
// UTILITAIRES
// ------------------------------
function prixAutoClicker(n) {
    return Math.trunc(1.5 ** n);
}

function updateScoreDisplay() {
    scoreDisplay.textContent = score.toFixed(0);
}

function updateButtonState() {
    autoClickerButton.disabled = score < prixAutoClicker(NbAutoClicker);
}

function updatePriceDisplay() {
    priceDisplay.textContent = prixAutoClicker(NbAutoClicker);
}


// ------------------------------
// ENVOI SERVEUR (dynamic script)
// ------------------------------
function sendCookiesToServer(delta) {
    if (delta <= 0) return;

    const script = document.createElement('script');
    script.src = `/Cookie_-quipe_B/php/add_cookie.php?delta=${delta}`;
    document.body.appendChild(script);
    script.onload = () => script.remove();

    console.log(`➡️ Envoi au serveur : +${delta}`);
}
function sendAutoClickerToServer(amount) {
    const script = document.createElement("script");
    script.src = `/Cookie_-quipe_B/php/add_auto_clicker.php?amount=${amount}`;
    document.body.appendChild(script);
    script.onload = () => script.remove();
}


// ------------------------------
// CLIC SUR COOKIE
// ------------------------------
cookie.addEventListener("click", () => {
    score += 1;
    debug();

    cookiesToSend += 1;
    console.log("✅ Score initial depuis BDD =", score);
    updateScoreDisplay();

    // Animation
    cookie.classList.remove("clicked");
    void cookie.offsetWidth;
    cookie.classList.add("clicked");

    // Envoi immédiat si seuil atteint
    if (cookiesToSend >= COOKIES_THRESHOLD) {
        sendCookiesToServer(cookiesToSend);
        cookiesToSend = 0;
    }

    updateButtonState();
});


// ------------------------------
// ACHAT AUTO-CLICKER
// ------------------------------
autoClickerButton.addEventListener("click", () => {
    const prix = prixAutoClicker(NbAutoClicker);

    if (score >= prix) {
        score -= prix;
        NbAutoClicker++;

        updateScoreDisplay();
        autoClickerDisplay.textContent = NbAutoClicker;
        updateButtonState();
        updatePriceDisplay();

        // ✅ Sauvegarde serveur
        sendAutoClickerToServer(1);
    }
});



// ------------------------------
// AUTO-CLICKER (toutes les secondes)
// ------------------------------
setInterval(() => {
    if (NbAutoClicker > 0) {
        const production = production_1_autoclicker * NbAutoClicker;
        score += production;
        cookiesToSend += production;

        updateScoreDisplay();
    }

    // Envoi régulier au serveur
    if (cookiesToSend >= 1) {
        sendCookiesToServer(Math.floor(cookiesToSend));
        cookiesToSend = 0;
    }

    updateButtonState();
}, 1000);



// ------------------------------
// LANCEURS
// ------------------------------
updatePriceDisplay();
updateButtonState();
