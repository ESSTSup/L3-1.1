// Modal functionality
const addClinicBtn = document.getElementById('addClinicBtn');
const addClinicModal = document.getElementById('addClinicModal');
const credentialsModal = document.getElementById('credentialsModal');
const modalCloseBtns = document.querySelectorAll('.modal-close');

if (addClinicBtn) {
    addClinicBtn.addEventListener('click', () => {
        if (addClinicModal) {
            addClinicModal.classList.add('active');
        }
    });
}

modalCloseBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.modal').forEach(modal => {
            modal.classList.remove('active');
        });
    });
});


document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('active');
        }
    });
});


function togglePassword(fieldId) {
    const passwordField = document.getElementById(fieldId);
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
    } else {
        passwordField.type = 'password';
    }
}

function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    const text = element.textContent;
    
    navigator.clipboard.writeText(text).then(() => {
     
        const originalText = element.textContent;
        element.textContent = 'Copied!';
        setTimeout(() => {
            element.textContent = originalText;
        }, 2000);
    });
}

document.querySelectorAll('.view-credentials').forEach(btn => {
    btn.addEventListener('click', () => {
       
        if (credentialsModal) {
            credentialsModal.classList.add('active');
        }
    });
});


document.querySelectorAll('.edit-clinic').forEach(btn => {
    btn.addEventListener('click', () => {
        alert('Edit clinic functionality would open here');
    });
});

document.querySelectorAll('.delete-clinic').forEach(btn => {
    btn.addEventListener('click', () => {
        if (confirm('Are you sure you want to delete this clinic? This action cannot be undone.')) {
            alert('Clinic deleted successfully!');
        }
    });
});

const saveClinicBtn = document.getElementById('saveClinicBtn');
if (saveClinicBtn) {
    saveClinicBtn.addEventListener('click', () => {
        const clinicId = document.getElementById('clinicId').value;
        const clinicPassword = document.getElementById('clinicPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        
       
        if (clinicId === 'CL001' || clinicId === 'CL002' || clinicId === 'CL003') {
           
            document.getElementById('clinicIdExists').style.display = 'flex';
            return;
        }
        
        if (clinicPassword !== confirmPassword) {
            alert('Passwords do not match!');
            return;
        }
        
       
        alert('Clinic saved successfully!');
        if (addClinicModal) {
            addClinicModal.classList.remove('active');
        }
        const addClinicForm = document.getElementById('addClinicForm');
        if (addClinicForm) {
            addClinicForm.reset();
        }
        
      
        if (credentialsModal) {
            document.getElementById('credentialClinicName').textContent = document.getElementById('clinicName').value;
            document.getElementById('credentialClinicId').textContent = clinicId;
            document.getElementById('credentialPassword').textContent = clinicPassword;
            credentialsModal.classList.add('active');
        }
    });
}


document.querySelectorAll('.approve-request').forEach(btn => {
    btn.addEventListener('click', () => {
        if (confirm('Are you sure you want to approve this subscription change request?')) {
            alert('Request approved successfully!');
        }
    });
});

document.querySelectorAll('.reject-request').forEach(btn => {
    btn.addEventListener('click', () => {
        if (confirm('Are you sure you want to reject this subscription change request?')) {
            alert('Request rejected successfully!');
        }
    });
});


document.querySelectorAll('.restore-clinic').forEach(btn => {
    btn.addEventListener('click', () => {
        if (confirm('Are you sure you want to restore this clinic from archive?')) {
            alert('Clinic restored successfully!');
        }
    });
});


document.addEventListener('DOMContentLoaded', function() {
    const currentPage = window.location.pathname.split('/').pop() || 'index.html';
    const menuItems = document.querySelectorAll('.sidebar-menu a');
    
    menuItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href === currentPage) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });
    
  
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('error') === 'clinic_id_exists') {
        document.getElementById('clinicIdExists').style.display = 'flex';
    }
});