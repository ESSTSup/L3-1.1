// ================= AJAX SIGNIN + VALIDATION =================
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("loginForm");
    if (!form) return;

    form.addEventListener("submit", e => {
        e.preventDefault();

        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value.trim();
        const errorBox = document.getElementById("error");

        errorBox.innerText = "";

        //  VALIDATION FRONT (aligned with backend reality)
        if (email === "" || password === "") {
            errorBox.innerText = "Tous les champs sont obligatoires.";
            return;
        }

        if (!validateEmail(email)) {
            errorBox.innerText = "Email invalide.";
            return;
        }

        
        //  DB passwords are plain text (ex: 1234)
        // Blocking < 6 here was breaking valid logins

        //  AJAX
        const data = new FormData();
        data.append("ajax", "1");
        data.append("email", email);
        data.append("password", password);

        fetch("signin.php", {
            method: "POST",
            body: data
        })
        .then(res => res.json())
        .then(json => {
            if (json.success) {
                window.location.href = json.redirect;
            } else {
                errorBox.innerText = json.message;
            }
        })
        .catch(() => {
            errorBox.innerText = "Erreur serveur.";
        });
    });
});

// ================= ROLE ENTRY (doctor / assistant / patient) =================
 function goLogin(type) {
    fetch("Login.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "type=" + encodeURIComponent(type)
    })
    .then(() => {
        if (type === "patient") {
            window.location.href = "signin.php";
        } else {
            //  doctor & assistant MUST pass clinic login first
            window.location.href = "clinic_login.php";
        }
    });
}

// ================= PROFILE SELECTION =================
function selectUser(id) {
    

    fetch("profil.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "selected_user_id=" + encodeURIComponent(id)
    })
    .then(() => {
        window.location.href = "signin.php";

    });
}

// ================= EMAIL VALIDATION =================
function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}
