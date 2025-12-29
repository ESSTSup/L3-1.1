// ================= SIGN IN FORM SUBMISSION =================
  // ================= SIGN IN FORM SUBMISSION =================
document.addEventListener("DOMContentLoaded", () => {

  const form = document.getElementById("loginForm");
  const errorBox = document.getElementById("error");

  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();

      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value.trim();

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
  }

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
            // ✅ doctor & assistant MUST pass clinic login first
            window.location.href = "clinic_login.php";
        }
    });
}

// ================= PROFILE SELECTION =================
function selectUser(id) {
    // ❌ sessionStorage was useless for PHP
    // PHP CANNOT read sessionStorage

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
// =================CONTACTANDADRESSN =================
function goBack() {
  window.location.href = "personalInformation.php";
}

function goNext() {
  window.location.href = "securitypassword.php";
}
/* =========================
   COMMON HELPERS
========================= */

function showError(msg) {
  alert(msg);
  return false;
}

function isValidPhone(phone) {
  return /^[+0-9\s]{8,}$/.test(phone);
}

/* =========================
   PERSONAL INFORMATION
========================= */

function validatePersonalInfo() {
  const first = document.querySelector('[name="firstName"]');
  const last = document.querySelector('[name="lastName"]');

  if (!first.value.trim())
    return showError("First name is required");

  if (!last.value.trim())
    return showError("Last name is required");

  if (!/^[a-zA-Z\s]+$/.test(first.value))
    return showError("First name must contain only letters");

  if (!/^[a-zA-Z\s]+$/.test(last.value))
    return showError("Last name must contain only letters");

  return true;
}

/* =========================
   CONTACT & ADDRESS
========================= */

function validateContact() {
  const email = document.querySelector('input[type="email"]');
  const phone = document.querySelector('input[type="tel"]');
  const address = document.querySelector('[placeholder*="Address"], [name="address"]');
  const city = document.querySelector('[name="city"]');
  const postal = document.querySelector('[name="postal"]');

  if (!email.value || !email.value.includes("@"))
    return showError("Enter a valid email address");

  if (!isValidPhone(phone.value))
    return showError("Enter a valid phone number");

  if (address && !address.value.trim())
    return showError("Address is required");

  if (city && !city.value.trim())
    return showError("City is required");

  if (postal && !/^[0-9]{4,6}$/.test(postal.value))
    return showError("Postal code must be 4 to 6 digits");

  return true;
}

/* =========================
   SECURITY & PASSWORD
========================= */

function validateSecurity() {
  const pass = document.getElementById("password");
  const confirm = document.getElementById("confirm");

  if (pass.value.length < 4)
    return showError("Password must be at least 4 characters");

  if (!/[A-Z]/.test(pass.value))
    return showError("Password must contain at least one uppercase letter");

  if (!/[0-9]/.test(pass.value))
    return showError("Password must contain at least one number");

  if (pass.value !== confirm.value)
    return showError("Passwords do not match");

  return true;
}
function handleSecuritySubmit() {
  if (!validateSecurity()) {
    return false;
  }

  // ✅ validation passed
  window.location.href = "medicalinformation.php";
  return false;
}


/* =========================
   MEDICAL INFORMATION
========================= */

function validateMedical() {
  const name = document.getElementById("emergencyName");
  const phone = document.getElementById("emergencyPhone");

  if (!name.value.trim())
    return showError("Emergency contact name is required");

  if (!isValidPhone(phone.value))
    return showError("Enter a valid emergency phone number");

  return true;
}
