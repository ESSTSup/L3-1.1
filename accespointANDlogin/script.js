function validateEmail(email) {
    const pattern = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
    return pattern.test(email);
}

const mockDoctors = [
  {
    id: 1,
    name: "Dr. Meklati Kenza",
    specialty: "cardiology",
    distance: "2.5 km",
    night: true,
    weekend: true,
    urgent: "same-day",
    handicap: true,
    hygiene: 4.9,
    welcome: 4.7,
    competence: 4.8,
    punctuality: 4.6
  },
  {
    id: 2,
    name: "Dr. Lachi Maram",
    specialty: "general",
    distance: "1.3 km",
    night: false,
    weekend: true,
    urgent: "3-days",
    handicap: true,
    hygiene: 4.4,
    welcome: 4.8,
    competence: 4.2,
    punctuality: 4.1
  },
  {
    id: 3,
    name: "Dr. Majed",
    specialty: "dermatology",
    distance: "4.0 km",
    night: true,
    weekend: false,
    urgent: "7-days",
    handicap: false,
    hygiene: 4.2,
    welcome: 4.3,
    competence: 4.5,
    punctuality: 4.3
  }
];

const selectedCriteria = new Set();

function getSelectedMethod() {
  const m = document.querySelector("input[name='searchMethod']:checked");
  return m ? m.value : "method1";
}

document.querySelectorAll(".criteria-checkbox").forEach(cb => {
  cb.addEventListener("change", () => {
    const card = cb.closest(".criteria-card");
    const key = card.dataset.criteria;

    if (cb.checked) {
      selectedCriteria.add(key);
      card.classList.add("selected");
    } else {
      selectedCriteria.delete(key);
      card.classList.remove("selected");
    }

    document.getElementById("findBtn").disabled = selectedCriteria.size < 2;
  });
});

function filterDoctors() {
  const method = getSelectedMethod();

  const list = mockDoctors.filter(doc => {
    let match = 0;

    if (selectedCriteria.has("specialty")) {
      const sp = specialtySelect.value;
      if (sp && doc.specialty !== sp) return false;
      match++;
    }

    if (selectedCriteria.has("availability")) {
      if (night.checked && !doc.night) return false;
      if (weekend.checked && !doc.weekend) return false;
      match++;
    }

    if (selectedCriteria.has("handicap")) {
      if (!doc.handicap) return false;
      match++;
    }

    if (selectedCriteria.has("competence")) {
      if (doc.competence < competenceRange.value) return false;
      match++;
    }

    if (selectedCriteria.has("welcome")) {
      if (doc.welcome < welcomeRange.value) return false;
      match++;
    }

    if (selectedCriteria.has("hygiene")) {
      if (doc.hygiene < hygieneRange.value) return false;
      match++;
    }

    if (selectedCriteria.has("punctuality")) {
      if (doc.punctuality < punctualityRange.value) return false;
      match++;
    }

    if (selectedCriteria.has("appointment")) {
      const val = document.querySelector("input[name='appoint']:checked")?.value;
      if (val && doc.urgent !== val) return false;
      match++;
    }

    if (method === "method1") return match >= 2;
    if (method === "method2") return match >= 3;
    return match === selectedCriteria.size;
  });

  displayDoctors(list);
}

function displayDoctors(list) {
  const info = document.getElementById("resultsInfo");
  const out = document.getElementById("resultsList");

  if (!list.length) {
    info.textContent = "No doctors found.";
    out.innerHTML = "";
    return;
  }

  info.textContent = `Found ${list.length} doctor(s).`;

  out.innerHTML = list
    .map(doc => `
      <div class="doctor-card">
        <h3>${doc.name}</h3>
        <p><b>Specialty:</b> ${doc.specialty}</p>
        <p><b>Distance:</b> ${doc.distance}</p>
        <p><b>Night:</b> ${doc.night ? "✔" : "✘"}</p>
        <p><b>Weekend:</b> ${doc.weekend ? "✔" : "✘"}</p>

        <button class="btn-primary" onclick="location.href='account.html'">Make Appointment</button>
      </div>
    `)
    .join("");
}

document.getElementById("findBtn").addEventListener("click", filterDoctors);

document.getElementById("doctorSearchBtn")?.addEventListener("click", () => {
  const term = doctorSearchInput.value.toLowerCase();
  const r = mockDoctors.filter(d => d.name.toLowerCase().includes(term));
  displayDoctors(r);
});

function detectLocation() {
  if (!navigator.geolocation) {
    alert("Your browser does not support geolocation.");
    return;
  }

  locationInput.placeholder = "Getting location...";

  navigator.geolocation.getCurrentPosition(async pos => {
    const lat = pos.coords.latitude;
    const lon = pos.coords.longitude;

    try {
      const response = await fetch(
        `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`,
        { headers: { "User-Agent": "DoctorFinderApp" } }
      );

      const data = await response.json();

      locationInput.value = data.display_name || `${lat}, ${lon}`;
    } catch {
      locationInput.placeholder = "Enter your address";
    }
  });
}

function selectRole(role, page) {
    localStorage.setItem("userRole", role);
    window.location.href = page;
}

function selectRoleLogin(role, page) {
    localStorage.setItem("userRole", role);
    window.location.href = page;
}

function selectRoleFromProfile(role) {
    const storedRole = localStorage.getItem("userRole");
    
    if (storedRole === "doctor") {
        window.location.href = "../doctor/dashboard.html";
    } else if (storedRole === "assistant") {
        window.location.href = "../assistant/assistandeshb.html";
    }
}

function handleLogin(event) {
    event.preventDefault();

    const email = document.getElementById("email").value.trim();

    if (!validateEmail(email)) {
        alert("Veuillez entrer un email valide.");
        return false;
    }

    const role = localStorage.getItem("userRole");

    if (role === "doctor" || role === "assistant") {
        window.location.href = "profil.html";
        return false;
    }

    alert("Rôle invalide. Retournez au choix du compte.");
    window.location.href = "login.html";
    return false;
}

function loginUser() {
    const email = document.getElementById("email").value.trim();
    const pass = document.getElementById("password").value.trim();

    if (email === "" || pass === "") {
        alert("Veuillez remplir tous les champs.");
        return;
    }

    if (!validateEmail(email)) {
        alert("Email invalide ajouter @.");
        return;
    }

    localStorage.setItem("loggedIn", "true");
    localStorage.setItem("userRole", "patient");
    window.location.href = "../patient/dashboard.html";
}

function validatePersonalInfo() {
    const first = document.querySelector("input[name='firstName']").value.trim();
    const last = document.querySelector("input[name='lastName']").value.trim();

    if (first === "" || last === "") {
        alert("Veuillez remplir le prénom et le nom.");
        return false;
    }
    return true;
}

function handleClinicIDLogin(event) {
    event.preventDefault();

    const id = document.getElementById("clinic-id").value.trim();
    const pass = document.getElementById("clinic-password").value.trim();

    if (id === "") {
        alert("Veuillez saisir votre ID clinique.");
        return false;
    }

    if (pass === "") {
        alert("Veuillez saisir votre mot de passe.");
        return false;
    }

    if (pass.length < 6) {
        alert("Le mot de passe doit contenir au moins 6 caractères.");
        return false;
    }

    const role = localStorage.getItem("userRole");

    if (!role) {
        alert("Rôle introuvable. Retournez au choix du compte.");
        window.location.href = "Login.html";
        return false;
    }

    window.location.href = "profil.html";
    return false;
}

function validateContact() {
    const email = document.querySelector("input[name='email']").value.trim();
    const phone = document.querySelector("input[name='phone']").value.trim();
    const address = document.querySelector("input[name='address']").value.trim();
    const city = document.querySelector("input[name='city']").value.trim();
    const postal = document.querySelector("input[name='postalCode']").value.trim();

    if (!email || !phone || !address || !city || !postal) {
        alert("Veuillez remplir tous les champs.");
        return false;
    }

    if (!validateEmail(email)) {
        alert("Email invalide. Veuillez ajouter @ et un domaine (ex: gmail.com).");
        return false;
    }

    if (phone.length < 10) {
        alert("Numéro de téléphone trop court.");
        return false;
    }

    window.location.href = "securitypassword.html";
    return false;
}

function validateSecurity() {
    const pass = document.getElementById("password").value.trim();
    const confirm = document.getElementById("confirm").value.trim();

    if (pass.length < 8) {
        alert("Minimum 8 caractères.");
        return false;
    }

    if (pass !== confirm) {
        alert("Les mots de passe ne correspondent pas.");
        return false;
    }

    return true;
}

function validateMedical() {
    const name = document.querySelector("input[name='emergencyName']").value.trim();
    const phone = document.querySelector("input[name='emergencyPhone']").value.trim();

    if (name === "" || phone === "") {
        alert("Champs d'urgence vides.");
        return false;
    }

    return true;
}

function handleLogout() {
    localStorage.clear();
    window.location.href = "signin.html";
}