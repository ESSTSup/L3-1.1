// clinics.js - JavaScript functionality for clinics management

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing clinics management...');
    loadClinics();
    setupModals();
    setupForm();
});

// ========== CLINICS LIST MANAGEMENT ==========
async function loadClinics() {
    const tbody = document.getElementById('clinicsTableBody');
    const loading = document.getElementById('loading');
    
    if (loading) loading.style.display = 'block';
    
    try {
        console.log('Loading clinics from API...');
        
        const response = await fetch('api.php');
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        console.log('API response:', result);
        
        if (loading) loading.style.display = 'none';
        
        if (result.success && result.data) {
            renderClinics(result.data);
        } else {
            showError(`Failed to load clinics: ${result.message || 'Unknown error'}`);
        }
    } catch (error) {
        if (loading) loading.style.display = 'none';
        showError(`Connection error: ${error.message}`);
        console.error('Full error:', error);
    }
}

function renderClinics(clinics) {
    const tbody = document.getElementById('clinicsTableBody');
    if (!tbody) return;
    
    if (!clinics || clinics.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="empty-state">
                    No clinics found. Click "Add New Clinic" to create your first clinic.
                </td>
            </tr>
        `;
        return;
    }
    
    let html = '';
    clinics.forEach(clinic => {
        html += `
            <tr>
                <td>${clinic.clinic_id || 'N/A'}</td>
                <td><strong>${clinic.clinic_name || 'Unnamed'}</strong></td>
                <td>${clinic.clinic_email || 'No email'}</td>
                <td>${clinic.clinic_phone || ''}</td>
                <td>${clinic.city || ''}, ${clinic.state || ''}</td>
                <td>${clinic.doctor_count || 0}</td>
                <td><span class="plan-badge plan-${clinic.subscription_plan || 'free'}">
                    ${(clinic.subscription_plan || 'Free').toUpperCase()}
                </span></td>
                <td><span class="status ${clinic.archived ? 'archived' : 'active'}">
                    ${clinic.archived ? 'Archived' : 'Active'}
                </span></td>
                <td>
                    <button class="btn btn-sm btn-outline" onclick="openEditModal(${clinic.clinic_id})">
                        Edit
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="archiveClinic(${clinic.clinic_id}, '${escapeString(clinic.clinic_name)}')">
                        Archive
                    </button>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
}

function showError(message) {
    const tbody = document.getElementById('clinicsTableBody');
    if (tbody) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="empty-state" style="color: #dc3545;">
                    ❌ ${message}
                    <br><br>
                    <button class="btn btn-sm btn-outline" onclick="debugAPI()">
                        Debug API Connection
                    </button>
                    <button class="btn btn-sm btn-primary" onclick="loadClinics()">
                        Retry
                    </button>
                </td>
            </tr>
        `;
    }
}

// ========== MODAL MANAGEMENT ==========
function setupModals() {
    const addBtn = document.getElementById('addClinicBtn');
    const debugBtn = document.getElementById('debugAPIBtn');
    const addModal = document.getElementById('addClinicModal');
    const viewModal = document.getElementById('viewClinicModal');
    const editModal = document.getElementById('editClinicModal');
    const closeBtns = document.querySelectorAll('.modal-close');
    
    console.log('Setting up modals:', {addBtn, addModal, viewModal, editModal});
    
    // Add Clinic Modal
    if (addBtn && addModal) {
        addBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Add button clicked, showing modal');
            addModal.classList.add('active');
            document.getElementById('addClinicForm').reset();
        });
    }
    
    // Debug API Button
    if (debugBtn) {
        debugBtn.addEventListener('click', function(e) {
            e.preventDefault();
            debugAPI();
        });
    }
    
    // Close buttons for all modals
    closeBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Close button clicked');
            document.querySelectorAll('.modal').forEach(m => {
                m.classList.remove('active');
            });
        });
    });
    
    // Close modals when clicking outside
    [addModal, viewModal, editModal].forEach(modalEl => {
        if (modalEl) {
            modalEl.addEventListener('click', function(e) {
                if (e.target === modalEl) {
                    modalEl.classList.remove('active');
                }
            });
        }
    });
}

// ========== ADD CLINIC FORM ==========
function setupForm() {
    const saveBtn = document.getElementById('saveClinicBtn');
    const form = document.getElementById('addClinicForm');
    
    console.log('Setting up form:', {saveBtn, form});
    
    if (!saveBtn || !form) return;
    
    saveBtn.addEventListener('click', async function(e) {
        e.preventDefault();
        await submitClinicForm();
    });
    
    form.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.target.matches('button, [type="submit"]')) {
            e.preventDefault();
            saveBtn.click();
        }
    });
}

async function submitClinicForm() {
    const formData = {
        clinic_name: document.getElementById('clinicName').value.trim(),
        clinic_email: document.getElementById('clinicEmail').value.trim(),
        clinic_password: document.getElementById('clinicPassword').value,
        clinic_phone: document.getElementById('clinicPhone').value.trim(),
        address: document.getElementById('address').value.trim(),
        city: document.getElementById('city').value.trim(),
        state: document.getElementById('state').value.trim(),
        postal_code: document.getElementById('postalCode').value.trim(),
        subscription_plan: document.getElementById('subscriptionPlan').value,
        handicap_accessible: document.getElementById('handicapAccessible').value,
        number_of_doctors: parseInt(document.getElementById('numberOfDoctors').value) || 1,
        principal_doctor_name: document.getElementById('doctorFirstName').value.trim(),
        principal_doctor_lname: document.getElementById('doctorLastName').value.trim(),
        principal_doctor_email: document.getElementById('doctorEmail').value.trim(),
        principal_doctor_password: document.getElementById('doctorPassword').value,
        principal_doctor_specialite: document.getElementById('doctorSpecialite').value.trim()
    };
    
    console.log('Form data:', formData);
    
    // Validation
    if (!validateClinicForm(formData)) return;
    
    // Phone validation
    const phoneRegex = /^(05|06|07)[0-9]{8}$/;
    const cleanPhone = formData.clinic_phone.replace(/\D/g, '');
    
    if (!phoneRegex.test(cleanPhone)) {
        alert('Phone must be 10 digits starting with 05, 06, or 07 (e.g., 0512345678)');
        document.getElementById('clinicPhone').focus();
        return;
    }
    
    formData.clinic_phone = cleanPhone;
    
    // Confirm before submission
    if (!confirm(`Create new clinic "${formData.clinic_name}"?\n\nClinic Email: ${formData.clinic_email}\nDoctor Email: ${formData.principal_doctor_email}\nPlan: ${formData.subscription_plan}`)) {
        return;
    }
    
    const saveBtn = document.getElementById('saveClinicBtn');
    const originalText = saveBtn.textContent;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<div class="spinner"></div> Creating...';
    
    try {
        console.log('Sending data to API:', formData);
        
        const response = await fetch('api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        });
        
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const responseText = await response.text();
        console.log('Raw response text:', responseText);
        
        let result;
        try {
            result = JSON.parse(responseText);
        } catch (jsonError) {
            console.error('JSON parse error:', jsonError);
            console.error('Response was:', responseText);
            throw new Error('Invalid JSON response from server');
        }
        
        console.log('Parsed result:', result);
        
        if (result.success) {
            const creds = result.data.credentials;
            const message = `✅ CLINIC CREATED SUCCESSFULLY!\n\n🏥 CLINIC LOGIN:\nEmail: ${creds.clinic.email}\nPassword: ${creds.clinic.password}\n\n👨‍⚕️ DOCTOR ADMIN LOGIN:\nEmail: ${creds.doctor.email}\nPassword: ${creds.doctor.password}\n\n⚠️ IMPORTANT: Save these credentials! They cannot be retrieved later.`;
            
            alert(message);
            
            document.getElementById('addClinicModal').classList.remove('active');
            document.getElementById('addClinicForm').reset();
            
            loadClinics();
        } else {
            alert(`❌ Error: ${result.message || 'Failed to create clinic'}`);
        }
    } catch (error) {
        console.error('Network error:', error);
        alert(`❌ Error: ${error.message}\n\nCheck console for details.`);
    } finally {
        saveBtn.disabled = false;
        saveBtn.textContent = originalText;
    }
}

function validateClinicForm(formData) {
    const required = ['clinic_name', 'clinic_email', 'clinic_password', 'clinic_phone',
                    'address', 'city', 'state', 'postal_code',
                    'principal_doctor_name', 'principal_doctor_lname', 
                    'principal_doctor_email', 'principal_doctor_password'];
    
    for (const field of required) {
        if (!formData[field]) {
            alert(`Please fill in all required fields. Missing: ${field.replace(/_/g, ' ')}`);
            return false;
        }
    }
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(formData.clinic_email)) {
        alert('Invalid clinic email address');
        document.getElementById('clinicEmail').focus();
        return false;
    }
    
    if (!emailRegex.test(formData.principal_doctor_email)) {
        alert('Invalid doctor email address');
        document.getElementById('doctorEmail').focus();
        return false;
    }
    
    if (formData.clinic_password.length < 6) {
        alert('Clinic password must be at least 6 characters long');
        document.getElementById('clinicPassword').focus();
        return false;
    }
    
    if (formData.principal_doctor_password.length < 6) {
        alert('Doctor password must be at least 6 characters long');
        document.getElementById('doctorPassword').focus();
        return false;
    }
    
    return true;
}

// ========== ARCHIVE CLINIC ==========
async function archiveClinic(id, clinicName) {
    if (!confirm(`Are you sure you want to archive the clinic "${clinicName}"?\n\nArchived clinics can be restored from the Archived page.`)) {
        return;
    }
    
    try {
        const response = await fetch(`api.php?action=archive&id=${id}`);
        const result = await response.json();
        
        if (result.success) {
            alert(`✅ Clinic "${clinicName}" archived successfully`);
            loadClinics();
        } else {
            alert(`❌ Error: ${result.message || 'Archive failed'}`);
        }
    } catch (error) {
        alert(`❌ Network error: ${error.message}`);
    }
}

// ========== EDIT CLINIC MODAL ==========
async function openEditModal(clinicId) {
    const modal = document.getElementById('editClinicModal');
    const loading = document.getElementById('editLoading');
    const form = document.getElementById('editClinicForm');
    const errorDiv = document.getElementById('editError');
    
    form.style.display = 'none';
    errorDiv.style.display = 'none';
    loading.style.display = 'block';
    modal.classList.add('active');
    
    try {
        const response = await fetch(`api.php?action=view&id=${clinicId}`);
        const result = await response.json();
        
        loading.style.display = 'none';
        
        if (result.success && result.data) {
            const clinic = result.data.clinic;
            
            // Fill form fields
            document.getElementById('editClinicId').value = clinic.clinic_id;
            document.getElementById('editClinicName').value = clinic.clinic_name || '';
            document.getElementById('editClinicEmail').value = clinic.clinic_email || '';
            document.getElementById('editClinicPhone').value = clinic.clinic_phone || '';
            document.getElementById('editAddress').value = clinic.address || '';
            document.getElementById('editCity').value = clinic.city || '';
            document.getElementById('editState').value = clinic.state || '';
            document.getElementById('editPostalCode').value = clinic.postal_code || '';
            document.getElementById('editHandicapAccessible').value = clinic.handicap_accessible || 'not-accessible';
            document.getElementById('editSubscriptionPlan').value = clinic.subscription_plan || 'free';
            document.getElementById('editNumberOfDoctors').value = clinic.number_of_doctors || 1;
            
            // Set status
            const statusEl = document.getElementById('editClinicStatus');
            if (clinic.archived) {
                statusEl.textContent = 'Archived';
                statusEl.className = 'status archived';
            } else {
                statusEl.textContent = 'Active';
                statusEl.className = 'status active';
            }
            
            form.style.display = 'block';
            
            // Setup save button
            const saveBtn = document.getElementById('saveEditBtn');
            saveBtn.onclick = function() {
                saveClinicChanges(clinicId);
            };
        } else {
            errorDiv.style.display = 'block';
            document.getElementById('editErrorMessage').textContent = result.message || 'Failed to load clinic details';
        }
    } catch (error) {
        loading.style.display = 'none';
        errorDiv.style.display = 'block';
        document.getElementById('editErrorMessage').textContent = `Connection error: ${error.message}`;
    }
}

async function saveClinicChanges(clinicId) {
    const formData = {
        clinic_id: clinicId,
        clinic_name: document.getElementById('editClinicName').value.trim(),
        clinic_email: document.getElementById('editClinicEmail').value.trim(),
        clinic_phone: document.getElementById('editClinicPhone').value.trim(),
        address: document.getElementById('editAddress').value.trim(),
        city: document.getElementById('editCity').value.trim(),
        state: document.getElementById('editState').value.trim(),
        postal_code: document.getElementById('editPostalCode').value.trim(),
        handicap_accessible: document.getElementById('editHandicapAccessible').value,
        subscription_plan: document.getElementById('editSubscriptionPlan').value,
        number_of_doctors: parseInt(document.getElementById('editNumberOfDoctors').value) || 1
    };
    
    // Validate
    if (!formData.clinic_name || !formData.clinic_email) {
        alert('Clinic name and email are required');
        return;
    }
    
    const saveBtn = document.getElementById('saveEditBtn');
    const originalText = saveBtn.textContent;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<div class="spinner"></div> Saving...';
    
    try {
        const response = await fetch('api.php?action=update_clinic', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('✅ Clinic updated successfully!');
            document.getElementById('editClinicModal').classList.remove('active');
            loadClinics();
        } else {
            alert(`❌ Error: ${result.message}`);
        }
    } catch (error) {
        alert(`❌ Network error: ${error.message}`);
    } finally {
        saveBtn.disabled = false;
        saveBtn.textContent = originalText;
    }
}

// ========== VIEW CLINIC MODAL ==========
async function viewClinic(id) {
    const modal = document.getElementById('viewClinicModal');
    const loading = document.getElementById('viewLoading');
    const details = document.getElementById('clinicDetails');
    const errorDiv = document.getElementById('viewError');
    
    details.style.display = 'none';
    errorDiv.style.display = 'none';
    loading.style.display = 'block';
    modal.classList.add('active');
    
    try {
        const response = await fetch(`api.php?action=view&id=${id}`);
        const result = await response.json();
        
        loading.style.display = 'none';
        
        if (result.success && result.data) {
            const clinic = result.data.clinic;
            const doctors = result.data.doctors || [];
            
            updateClinicDetails(clinic, doctors);
            
            // Setup archive button
            const archiveBtn = document.getElementById('archiveFromViewBtn');
            if (clinic.archived) {
                archiveBtn.style.display = 'none';
            } else {
                archiveBtn.style.display = 'block';
                archiveBtn.onclick = function() {
                    modal.classList.remove('active');
                    archiveClinic(id, clinic.clinic_name);
                };
            }
            
            details.style.display = 'block';
        } else {
            errorDiv.style.display = 'block';
            document.getElementById('errorMessage').textContent = result.message || 'Failed to load clinic details';
        }
    } catch (error) {
        loading.style.display = 'none';
        errorDiv.style.display = 'block';
        document.getElementById('errorMessage').textContent = `Connection error: ${error.message}`;
        console.error('Error loading clinic details:', error);
    }
}

function updateClinicDetails(clinic, doctors) {
    document.getElementById('detailClinicName').textContent = clinic.clinic_name;
    document.getElementById('detailClinicId').textContent = clinic.clinic_id;
    document.getElementById('detailClinicEmail').textContent = clinic.clinic_email;
    document.getElementById('detailClinicPhone').textContent = clinic.clinic_phone;
    document.getElementById('detailClinicAddress').textContent = clinic.address;
    document.getElementById('detailClinicLocation').textContent = `${clinic.city}, ${clinic.state}`;
    document.getElementById('detailClinicPostal').textContent = clinic.postal_code;
    document.getElementById('detailClinicAccess').textContent = clinic.handicap_accessible ? clinic.handicap_accessible.replace('-', ' ') : 'N/A';
    document.getElementById('detailClinicCreated').textContent = clinic.created_at ? clinic.created_at.split(' ')[0] : 'N/A';
    document.getElementById('detailClinicDoctors').textContent = clinic.doctor_count || doctors.length;
    
    const planBadge = document.getElementById('detailClinicPlan');
    planBadge.textContent = clinic.subscription_plan ? clinic.subscription_plan.toUpperCase() : 'FREE';
    planBadge.className = 'plan-badge plan-' + (clinic.subscription_plan || 'free');
    
    const statusEl = document.getElementById('detailClinicStatus');
    if (clinic.archived) {
        statusEl.innerHTML = '<span class="status archived">Archived</span>';
    } else {
        statusEl.innerHTML = '<span class="status active">Active</span>';
    }
    
    updateDoctorsList(doctors);
}

function updateDoctorsList(doctors) {
    const doctorsList = document.getElementById('doctorsList');
    const doctorsCountBadge = document.getElementById('doctorsCountBadge');
    
    if (doctors.length > 0) {
        doctorsCountBadge.textContent = doctors.length;
        let doctorsHtml = '';
        
        doctors.forEach(doctor => {
            const isPrincipal = doctor.is_principal || doctor.doc_role === 'admin';
            doctorsHtml += `
                <div class="doctor-card ${isPrincipal ? 'principal' : ''}">
                    <div class="doctor-info">
                        <h5>Dr. ${doctor.doc_name} ${doctor.doc_lname}</h5>
                        <p>${doctor.doc_email}</p>
                        <p>Specialty: ${doctor.doc_specialite || 'General'}</p>
                    </div>
                    <div>
                        <span class="doctor-role ${doctor.doc_role === 'admin' ? 'role-admin' : 'role-doctor'}">
                            ${isPrincipal ? 'PRINCIPAL DOCTOR' : doctor.doc_role || 'DOCTOR'}
                        </span>
                    </div>
                </div>
            `;
        });
        
        doctorsList.innerHTML = doctorsHtml;
    } else {
        doctorsCountBadge.textContent = '0';
        doctorsList.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">No doctors found</p>';
    }
}

// ========== DEBUG FUNCTIONS ==========
async function debugAPI() {
    const debugOutput = document.getElementById('debugOutput');
    const debugInfo = document.getElementById('debugInfo');
    
    debugInfo.style.display = 'block';
    debugOutput.textContent = 'Testing API connection...\n\n';
    
    try {
        debugOutput.textContent += 'Fetching from: api.php\n';
        
        const startTime = Date.now();
        const response = await fetch('api.php');
        const endTime = Date.now();
        
        debugOutput.textContent += `Response time: ${endTime - startTime}ms\n`;
        debugOutput.textContent += `Status: ${response.status} ${response.statusText}\n\n`;
        
        const result = await response.json();
        debugOutput.textContent += `API Response:\n`;
        debugOutput.textContent += JSON.stringify(result, null, 2);
        
        if (result.success) {
            debugOutput.textContent += `\n\n✅ API is working correctly!`;
            debugOutput.textContent += `\nFound ${result.data ? result.data.length : 0} clinics`;
            
            setTimeout(() => {
                loadClinics();
                debugOutput.textContent += `\n\nReloading clinics list...`;
            }, 500);
        } else {
            debugOutput.textContent += `\n\n❌ API error: ${result.message}`;
        }
    } catch (error) {
        debugOutput.textContent += `\n\n❌ Fetch Error: ${error.message}`;
    }
}

// ========== UTILITY FUNCTIONS ==========
function escapeString(str) {
    if (!str) return '';
    return str.replace(/'/g, "\\'");
}