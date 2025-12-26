<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Assistant Médical</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1800px;
            margin: 0 auto;
        }

        .header {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .user-details h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .user-details p {
            color: #666;
            font-size: 14px;
        }

        .logout-btn {
            background: #ff4757;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: #ee5a6f;
            transform: translateY(-2px);
        }

        .nav-tabs {
            background: white;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .tab-btn {
            background: transparent;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            color: #666;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            position: relative;
        }

        .tab-btn:hover {
            background: #f0f0f0;
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .badge {
            background: #ff4757;
            color: white;
            border-radius: 12px;
            padding: 2px 8px;
            font-size: 12px;
            font-weight: bold;
        }

        .content {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            min-height: 500px;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .section-title {
            font-size: 28px;
            color: #333;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-success {
            background: #00d2d3;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.3s;
        }

        .btn-success:hover {
            background: #00b8b9;
        }

        .btn-danger {
            background: #ff4757;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.3s;
        }

        .btn-danger:hover {
            background: #ee5a6f;
        }

        .btn-warning {
            background: #ffa502;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.3s;
        }

        .btn-warning:hover {
            background: #ff9000;
        }

        .btn-info {
            background: #5f27cd;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.3s;
        }

        .btn-info:hover {
            background: #4a1fa8;
        }

        .doctors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .doctor-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 25px;
            color: white;
            position: relative;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }

        .doctor-card.available {
            background: linear-gradient(135deg, #00d2d3 0%, #00b8b9 100%);
        }

        .doctor-card.busy {
            background: linear-gradient(135deg, #ff4757 0%, #ee5a6f 100%);
        }

        .doctor-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(255,255,255,0.3);
        }

        .doctor-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
        }

        .doctor-info h3 {
            font-size: 20px;
            margin-bottom: 5px;
        }

        .doctor-info p {
            font-size: 14px;
            opacity: 0.9;
        }

        .doctor-status {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: white;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .doctor-stats {
            background: rgba(255,255,255,0.2);
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
        }

        .doctor-stat {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .doctor-stat:last-child {
            margin-bottom: 0;
        }

        .current-patient {
            background: rgba(255,255,255,0.2);
            border-radius: 8px;
            padding: 12px;
            margin-top: 15px;
            font-size: 13px;
        }

        .current-patient strong {
            display: block;
            margin-bottom: 5px;
        }

        .queue-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }

        .queue-column {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 12px;
            padding: 20px;
            min-height: 400px;
        }

        .queue-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid;
        }

        .queue-waiting {
            border-left: 5px solid #ffa502;
        }

        .queue-waiting .queue-header {
            border-color: #ffa502;
        }

        .queue-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .queue-count {
            background: white;
            color: #333;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
        }

        .patient-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }

        .patient-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }

        .patient-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 10px;
        }

        .patient-name {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .patient-time {
            font-size: 12px;
            color: #666;
        }

        .patient-priority {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }

        .priority-normal {
            background: #dfe6e9;
            color: #2d3436;
        }

        .priority-urgent {
            background: #ff4757;
            color: white;
        }

        .patient-info {
            font-size: 13px;
            color: #666;
            margin: 8px 0;
        }

        .doctor-assignment {
            background: #f0f0f0;
            padding: 10px;
            border-radius: 6px;
            margin: 10px 0;
            font-size: 13px;
        }

        .doctor-assignment select {
            width: 100%;
            padding: 8px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 13px;
            margin-top: 5px;
        }

        .assigned-doctor {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px;
            background: #e8d6ff;
            border-radius: 6px;
            margin: 10px 0;
            font-size: 13px;
            color: #5f27cd;
        }

        .patient-actions {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }

        .patient-actions button {
            flex: 1;
            font-size: 12px;
            padding: 8px;
        }

        .search-bar {
            margin-bottom: 20px;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 12px 40px 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
        }

        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
            color: #333;
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }

        .status-active {
            background: #d4f4dd;
            color: #00b894;
        }

        .status-waiting {
            background: #fff4e6;
            color: #ffa502;
        }

        .status-incabinet {
            background: #e8d6ff;
            color: #5f27cd;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 15px;
            padding: 30px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .modal-title {
            font-size: 24px;
            color: #333;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #666;
            line-height: 1;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        .form-input, .form-select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 25px;
        }

        .btn-secondary {
            background: #e0e0e0;
            color: #333;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-secondary:hover {
            background: #d0d0d0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-info h3 {
            font-size: 32px;
            margin-bottom: 5px;
        }

        .stat-info p {
            font-size: 14px;
            opacity: 0.9;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .empty-state svg {
            width: 60px;
            height: 60px;
            margin-bottom: 15px;
            opacity: 0.3;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            display: none;
            align-items: center;
            gap: 15px;
            z-index: 2000;
            max-width: 400px;
            animation: slideIn 0.3s ease-out;
        }

        .notification.active {
            display: flex;
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .notification-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .notification-message {
            font-size: 14px;
            color: #666;
        }

        @media (max-width: 768px) {
            .queue-container {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .header {
                flex-direction: column;
                gap: 15px;
            }

            .doctors-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="user-info">
                <div class="avatar">MA</div>
                <div class="user-details">
                    <h2>Marie Assistante</h2>
                    <p>Assistante Médicale</p>
                </div>
            </div>
            <button class="logout-btn" onclick="logout()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Déconnexion
            </button>
        </div>

        <!-- Navigation Tabs -->
        <div class="nav-tabs">
            <button class="tab-btn active" onclick="switchTab('queue')">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
                File d'Attente
                <span class="badge" id="queueBadge">0</span>
            </button>
            <button class="tab-btn" onclick="switchTab('doctors')">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                Médecins
            </button>
            <button class="tab-btn" onclick="switchTab('patients')">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                Tous les Patients
            </button>
            <button class="tab-btn" onclick="switchTab('add')">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="8.5" cy="7" r="4"/>
                    <line x1="20" y1="8" x2="20" y2="14"/>
                    <line x1="23" y1="11" x2="17" y2="11"/>
                </svg>
                Ajouter Patient
            </button>
        </div>

        <!-- Content Area -->
        <div class="content">
            <!-- Queue Tab -->
            <div id="queue" class="tab-content active">
                <div class="section-title">
                    <span>Gestion de la File d'Attente</span>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <h3 id="totalWaiting">0</h3>
                            <p>Salle d'Attente</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                <polyline points="9 22 9 12 15 12 15 22"/>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <h3 id="totalInCabinet">0</h3>
                            <p>Chez les Médecins</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <h3 id="totalCompleted">0</h3>
                            <p>Terminés Aujourd'hui</p>
                        </div>
                    </div>
                </div>

                <div class="queue-container">
                    <!-- Waiting Room -->
                    <div class="queue-column queue-waiting">
                        <div class="queue-header">
                            <div class="queue-title">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                                Salle d'Attente
                            </div>
                            <div class="queue-count" id="waitingCount">0</div>
                        </div>
                        <div id="waitingRoom">
                            <!-- Waiting patients will be rendered here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Doctors Tab -->
            <div id="doctors" class="tab-content">
                <div class="section-title">
                    <span>Gestion des Médecins</span>
                    <button class="btn-primary" onclick="openAddDoctorModal()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Ajouter Médecin
                    </button>
                </div>

                <div class="doctors-grid" id="doctorsGrid">
                    <!-- Doctors will be rendered here -->
                </div>
            </div>

            <!-- All Patients Tab -->
            <div id="patients" class="tab-content">
                <div class="section-title">
                    <span>Liste de Tous les Patients</span>
                </div>

                <div class="search-bar">
                    <input type="text" class="search-input" id="searchInput" placeholder="Rechercher un patient par nom, téléphone ou email...">
                    <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Nom Complet</th>
                                <th>Téléphone</th>
                                <th>Email</th>
                                <th>Date de Naissance</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="patientsTable">
                            <!-- Patients will be rendered here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add Patient Tab -->
            <div id="add" class="tab-content">
                <div class="section-title">
                    <span>Ajouter un Nouveau Patient (Sans Rendez-vous)</span>
                </div>

                <form id="addPatientForm" onsubmit="addPatient(event)">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Prénom *</label>
                            <input type="text" class="form-input" name="firstName" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nom *</label>
                            <input type="text" class="form-input" name="lastName" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Date de Naissance *</label>
                            <input type="date" class="form-input" name="dateOfBirth" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sexe *</label>
                            <select class="form-select" name="gender" required>
                                <option value="">Sélectionner...</option>
                                <option value="M">Masculin</option>
                                <option value="F">Féminin</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Téléphone *</label>
                            <input type="tel" class="form-input" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-input" name="email">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Adresse</label>
                        <input type="text" class="form-input" name="address">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Groupe Sanguin</label>
                            <select class="form-select" name="bloodType">
                                <option value="">Sélectionner...</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Priorité *</label>
                            <select class="form-select" name="priority" required>
                                <option value="normal">Normal</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Médecin Assigné</label>
                        <select class="form-select" name="doctorId" id="doctorSelect">
                            <option value="">Auto-assigner au médecin disponible</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Raison de la Visite</label>
                        <input type="text" class="form-input" name="reason" placeholder="Ex: Consultation générale, Douleur...">
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="btn-secondary" onclick="resetForm()">Réinitialiser</button>
                        <button type="submit" class="btn-primary">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="8.5" cy="7" r="4"/>
                                <line x1="20" y1="8" x2="20" y2="14"/>
                                <line x1="23" y1="11" x2="17" y2="11"/>
                            </svg>
                            Ajouter à la File d'Attente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Doctor Modal -->
    <div id="addDoctorModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Ajouter un Médecin</h2>
                <button class="close-btn" onclick="closeAddDoctorModal()">×</button>
            </div>
            <form id="addDoctorForm" onsubmit="addDoctor(event)">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Prénom *</label>
                        <input type="text" class="form-input" name="firstName" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nom *</label>
                        <input type="text" class="form-input" name="lastName" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Spécialité *</label>
                    <input type="text" class="form-input" name="specialty" required placeholder="Ex: Généraliste, Cardiologue...">
                </div>

                <div class="form-group">
                    <label class="form-label">Téléphone</label>
                    <input type="tel" class="form-input" name="phone">
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeAddDoctorModal()">Annuler</button>
                    <button type="submit" class="btn-primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notification -->
    <div id="notification" class="notification">
        <div class="notification-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </svg>
        </div>
        <div class="notification-content">
            <div class="notification-title" id="notifTitle">Notification</div>
            <div class="notification-message" id="notifMessage">Message</div>
        </div>
    </div>

    <script>
        // Data storage
        let doctors = [
            {
                id: 1,
                firstName: "Youssef",
                lastName: "Amrani",
                specialty: "Médecin Généraliste",
                phone: "+213 555 100 200",
                status: "available",
                currentPatient: null,
                patientsToday: 0,
                totalPatients: 0
            },
            {
                id: 2,
                firstName: "Samira",
                lastName: "Meziani",
                specialty: "Cardiologue",
                phone: "+213 555 200 300",
                status: "available",
                currentPatient: null,
                patientsToday: 0,
                totalPatients: 0
            },
            {
                id: 3,
                firstName: "Rachid",
                lastName: "Boudiaf",
                specialty: "Pédiatre",
                phone: "+213 555 300 400",
                status: "available",
                currentPatient: null,
                patientsToday: 0,
                totalPatients: 0
            }
        ];

        let patients = [
            {
                id: 1,
                firstName: "Ahmed",
                lastName: "Benali",
                phone: "+213 555 123 456",
                email: "ahmed.benali@email.com",
                dateOfBirth: "1985-05-15",
                gender: "M",
                bloodType: "O+",
                address: "12 Rue Didouche Mourad, Alger",
                status: "active",
                registrationDate: "2024-01-15"
            },
            {
                id: 2,
                firstName: "Fatima",
                lastName: "Cherif",
                phone: "+213 555 789 012",
                email: "fatima.cherif@email.com",
                dateOfBirth: "1992-08-22",
                gender: "F",
                bloodType: "A+",
                address: "45 Avenue de l'Indépendance, Alger",
                status: "active",
                registrationDate: "2024-02-20"
            }
        ];

        let queuePatients = [];

        let nextPatientId = 3;
        let nextQueueId = 1;
        let nextDoctorId = 4;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            renderQueue();
            renderPatientsTable();
            renderDoctors();
            updateStats();
            updateDoctorSelect();
            initializeSampleQueue();
            
            // Search functionality
            document.getElementById('searchInput').addEventListener('input', function(e) {
                filterPatients(e.target.value);
            });
        });

        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            document.getElementById(tabName).classList.add('active');
            event.target.closest('.tab-btn').classList.add('active');
        }

        function renderDoctors() {
            const grid = document.getElementById('doctorsGrid');
            
            if (doctors.length === 0) {
                grid.innerHTML = '<div class="empty-state"><p>Aucun médecin enregistré</p></div>';
                return;
            }

            grid.innerHTML = doctors.map(doctor => {
                const statusClass = doctor.status === 'available' ? 'available' : 'busy';
                const statusText = doctor.status === 'available' ? 'Disponible' : 'Occupé';
                const hasPatientInCabinet = queuePatients.find(p => p.doctorId === doctor.id && p.status === 'incabinet');
                
                return `
                    <div class="doctor-card ${statusClass}">
                        <div class="doctor-header">
                            <div class="doctor-avatar">${doctor.firstName.charAt(0)}${doctor.lastName.charAt(0)}</div>
                            <div class="doctor-info">
                                <h3>Dr. ${doctor.firstName} ${doctor.lastName}</h3>
                                <p>${doctor.specialty}</p>
                            </div>
                        </div>
                        
                        <div class="doctor-status">
                            <span class="status-dot"></span>
                            ${statusText}
                        </div>

                        ${doctor.currentPatient ? `
                            <div class="current-patient">
                                <strong>Patient actuel:</strong>
                                ${doctor.currentPatient}
                            </div>
                        ` : ''}

                        <div class="doctor-stats">
                            <div class="doctor-stat">
                                <span>Patients aujourd'hui:</span>
                                <strong>${doctor.patientsToday}</strong>
                            </div>
                            <div class="doctor-stat">
                                <span>Total patients:</span>
                                <strong>${doctor.totalPatients}</strong>
                            </div>
                            <div class="doctor-stat">
                                <span>Téléphone:</span>
                                <strong>${doctor.phone}</strong>
                            </div>
                        </div>
                        
                        ${hasPatientInCabinet ? `
                            <div style="margin-top: 15px;">
                                <button class="btn-success" style="width: 100%;" onclick="completeVisit(${doctor.id})">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                    Terminer la consultation
                                </button>
                            </div>
                        ` : ''}
                    </div>
                `;
            }).join('');
        }

        function renderQueue() {
            const waiting = queuePatients.filter(p => p.status === 'waiting');

            // Waiting Room
            const waitingRoom = document.getElementById('waitingRoom');
            if (waiting.length === 0) {
                waitingRoom.innerHTML = '<div class="empty-state"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg><p>Aucun patient en attente</p></div>';
            } else {
                waitingRoom.innerHTML = waiting.map(patient => {
                    const doctor = patient.doctorId ? doctors.find(d => d.id === patient.doctorId) : null;
                    
                    return `
                        <div class="patient-card">
                            <div class="patient-header">
                                <div>
                                    <div class="patient-name">${patient.name}</div>
                                    <div class="patient-time">Arrivée: ${patient.arrivalTime}</div>
                                </div>
                                <span class="patient-priority priority-${patient.priority}">
                                    ${patient.priority === 'urgent' ? '⚠️ URGENT' : '● Normal'}
                                </span>
                            </div>
                            <div class="patient-info">
                                <strong>Raison:</strong> ${patient.reason}
                            </div>
                            
                            ${doctor ? `
                                <div class="assigned-doctor">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                    Dr. ${doctor.firstName} ${doctor.lastName}
                                </div>
                            ` : `
                                <div class="doctor-assignment">
                                    <strong>Assigner à un médecin:</strong>
                                    <select onchange="assignDoctor(${patient.id}, this.value)">
                                        <option value="">Sélectionner un médecin...</option>
                                        ${doctors.filter(d => d.status === 'available').map(d => 
                                            `<option value="${d.id}">Dr. ${d.firstName} ${d.lastName} (${d.specialty})</option>`
                                        ).join('')}
                                    </select>
                                </div>
                            `}
                            
                            <div class="patient-actions">
                                ${doctor ? `
                                    <button class="btn-success" onclick="moveToDoctor(${patient.id})">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="9 18 15 12 9 6"/>
                                        </svg>
                                        Entrer
                                    </button>
                                ` : ''}
                                <button class="btn-danger" onclick="removeFromQueue(${patient.id})">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="18" y1="6" x2="6" y2="18"/>
                                        <line x1="6" y1="6" x2="18" y2="18"/>
                                    </svg>
                                    Retirer
                                </button>
                            </div>
                        </div>
                    `;
                }).join('');
            }

            updateCounts();
        }

        function updateCounts() {
            const waiting = queuePatients.filter(p => p.status === 'waiting').length;
            const inCabinet = queuePatients.filter(p => p.status === 'incabinet').length;
            const completed = queuePatients.filter(p => p.status === 'completed').length;

            document.getElementById('waitingCount').textContent = waiting;
            document.getElementById('queueBadge').textContent = waiting + inCabinet;
        }

        function updateStats() {
            const waiting = queuePatients.filter(p => p.status === 'waiting').length;
            const inCabinet = queuePatients.filter(p => p.status === 'incabinet').length;
            const completed = queuePatients.filter(p => p.status === 'completed').length;

            document.getElementById('totalWaiting').textContent = waiting;
            document.getElementById('totalInCabinet').textContent = inCabinet;
            document.getElementById('totalCompleted').textContent = completed;
        }

        function assignDoctor(queueId, doctorId) {
            if (!doctorId) return;
            
            const patient = queuePatients.find(p => p.id === queueId);
            const doctor = doctors.find(d => d.id === parseInt(doctorId));
            
            if (patient && doctor) {
                patient.doctorId = doctor.id;
                renderQueue();
                renderDoctors();
                showNotification('Médecin Assigné', `${patient.name} a été assigné à Dr. ${doctor.firstName} ${doctor.lastName}`);
            }
        }

        function moveToDoctor(queueId) {
            const patient = queuePatients.find(p => p.id === queueId);
            
            if (!patient) return;
            
            if (!patient.doctorId) {
                showNotification('Erreur', 'Veuillez d\'abord assigner un médecin à ce patient.');
                return;
            }
            
            const doctor = doctors.find(d => d.id === patient.doctorId);
            
            if (doctor.status === 'busy') {
                showNotification('Médecin Occupé', `Dr. ${doctor.firstName} ${doctor.lastName} est déjà avec un patient.`);
                return;
            }
            
            patient.status = 'incabinet';
            patient.entryTime = getCurrentTime();
            
            doctor.status = 'busy';
            doctor.currentPatient = patient.name;
            doctor.patientsToday++;
            doctor.totalPatients++;
            
            renderQueue();
            renderDoctors();
            updateStats();
            showNotification('Patient Entré', `${patient.name} est maintenant avec Dr. ${doctor.firstName} ${doctor.lastName}`);
        }

        function completeVisit(doctorId) {
            const doctor = doctors.find(d => d.id === doctorId);
            const patient = queuePatients.find(p => p.doctorId === doctorId && p.status === 'incabinet');
            
            if (patient && doctor) {
                patient.status = 'completed';
                patient.exitTime = getCurrentTime();
                
                doctor.status = 'available';
                doctor.currentPatient = null;
                
                renderQueue();
                renderDoctors();
                updateStats();
                showNotification('Consultation Terminée', `${patient.name} a terminé sa consultation avec Dr. ${doctor.firstName} ${doctor.lastName}`);
                
                // Check for next patient
                const nextPatient = queuePatients.find(p => p.status === 'waiting' && p.doctorId === doctorId);
                if (nextPatient) {
                    setTimeout(() => {
                        showNotification('Patient Suivant', `${nextPatient.name} est le prochain patient pour Dr. ${doctor.firstName} ${doctor.lastName}`);
                    }, 2000);
                }
            }
        }

        function removeFromQueue(queueId) {
            if (confirm('Êtes-vous sûr de vouloir retirer ce patient de la file d\'attente?')) {
                queuePatients = queuePatients.filter(p => p.id !== queueId);
                renderQueue();
                updateStats();
                showNotification('Patient Retiré', 'Le patient a été retiré de la file d\'attente.');
            }
        }

        function addPatient(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            
            const newPatient = {
                id: nextPatientId++,
                firstName: formData.get('firstName'),
                lastName: formData.get('lastName'),
                phone: formData.get('phone'),
                email: formData.get('email') || 'N/A',
                dateOfBirth: formData.get('dateOfBirth'),
                gender: formData.get('gender'),
                bloodType: formData.get('bloodType') || 'N/A',
                address: formData.get('address') || 'N/A',
                status: 'waiting',
                registrationDate: new Date().toISOString().split('T')[0]
            };
            
            patients.push(newPatient);
            
            const doctorId = formData.get('doctorId');
            let assignedDoctorId = null;
            
            if (doctorId) {
                assignedDoctorId = parseInt(doctorId);
            } else {
                const availableDoctor = doctors.find(d => d.status === 'available');
                if (availableDoctor) {
                    assignedDoctorId = availableDoctor.id;
                }
            }
            
            const queueEntry = {
                id: nextQueueId++,
                patientId: newPatient.id,
                name: `${newPatient.firstName} ${newPatient.lastName}`,
                arrivalTime: getCurrentTime(),
                priority: formData.get('priority'),
                reason: formData.get('reason') || 'Consultation générale',
                status: 'waiting',
                doctorId: assignedDoctorId
            };
            
            queuePatients.push(queueEntry);
            
            form.reset();
            renderQueue();
            renderPatientsTable();
            renderDoctors();
            updateStats();
            
            const assignedDoctor = assignedDoctorId ? doctors.find(d => d.id === assignedDoctorId) : null;
            const message = assignedDoctor 
                ? `${queueEntry.name} a été ajouté et assigné à Dr. ${assignedDoctor.firstName} ${assignedDoctor.lastName}` 
                : `${queueEntry.name} a été ajouté à la file d'attente`;
            
            showNotification('Patient Ajouté', message);
            
            switchTab('queue');
            document.querySelectorAll('.tab-btn')[0].click();
        }

        function resetForm() {
            document.getElementById('addPatientForm').reset();
        }

        function renderPatientsTable() {
            const tbody = document.getElementById('patientsTable');
            
            if (patients.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 40px; color: #999;">Aucun patient enregistré</td></tr>';
                return;
            }
            
            tbody.innerHTML = patients.map(patient => {
                const queueStatus = queuePatients.find(q => q.patientId === patient.id);
                let statusBadge = '<span class="status-badge status-active">Enregistré</span>';
                
                if (queueStatus) {
                    if (queueStatus.status === 'waiting') {
                        statusBadge = '<span class="status-badge status-waiting">En Attente</span>';
                    } else if (queueStatus.status === 'incabinet') {
                        statusBadge = '<span class="status-badge status-incabinet">Chez le Médecin</span>';
                    }
                }
                
                return `
                    <tr>
                        <td><strong>${patient.firstName} ${patient.lastName}</strong></td>
                        <td>${patient.phone}</td>
                        <td>${patient.email}</td>
                        <td>${formatDate(patient.dateOfBirth)}</td>
                        <td>${statusBadge}</td>
                        <td>
                            <button class="btn-success" onclick="addToQueue(${patient.id})" ${queueStatus ? 'disabled' : ''}>
                                ${queueStatus ? 'Déjà en file' : 'Ajouter à la file'}
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function addToQueue(patientId) {
            const patient = patients.find(p => p.id === patientId);
            if (!patient) return;
            
            const availableDoctor = doctors.find(d => d.status === 'available');
            
            const queueEntry = {
                id: nextQueueId++,
                patientId: patient.id,
                name: `${patient.firstName} ${patient.lastName}`,
                arrivalTime: getCurrentTime(),
                priority: 'normal',
                reason: 'Consultation sans rendez-vous',
                status: 'waiting',
                doctorId: availableDoctor ? availableDoctor.id : null
            };
            
            queuePatients.push(queueEntry);
            
            renderQueue();
            renderPatientsTable();
            renderDoctors();
            updateStats();
            
            const message = availableDoctor 
                ? `${queueEntry.name} a été ajouté et assigné à Dr. ${availableDoctor.firstName} ${availableDoctor.lastName}` 
                : `${queueEntry.name} a été ajouté à la file d'attente`;
            
            showNotification('Ajouté à la File', message);
        }

        function filterPatients(searchTerm) {
            const tbody = document.getElementById('patientsTable');
            const term = searchTerm.toLowerCase();
            
            const filtered = patients.filter(patient => 
                `${patient.firstName} ${patient.lastName}`.toLowerCase().includes(term) ||
                patient.phone.includes(term) ||
                patient.email.toLowerCase().includes(term)
            );
            
            if (filtered.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 40px; color: #999;">Aucun patient trouvé</td></tr>';
                return;
            }
            
            tbody.innerHTML = filtered.map(patient => {
                const queueStatus = queuePatients.find(q => q.patientId === patient.id);
                let statusBadge = '<span class="status-badge status-active">Enregistré</span>';
                
                if (queueStatus) {
                    if (queueStatus.status === 'waiting') {
                        statusBadge = '<span class="status-badge status-waiting">En Attente</span>';
                    } else if (queueStatus.status === 'incabinet') {
                        statusBadge = '<span class="status-badge status-incabinet">Chez le Médecin</span>';
                    }
                }
                
                return `
                    <tr>
                        <td><strong>${patient.firstName} ${patient.lastName}</strong></td>
                        <td>${patient.phone}</td>
                        <td>${patient.email}</td>
                        <td>${formatDate(patient.dateOfBirth)}</td>
                        <td>${statusBadge}</td>
                        <td>
                            <button class="btn-success" onclick="addToQueue(${patient.id})" ${queueStatus ? 'disabled' : ''}>
                                ${queueStatus ? 'Déjà en file' : 'Ajouter à la file'}
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function updateDoctorSelect() {
            const select = document.getElementById('doctorSelect');
            const doctorOptions = doctors.map(d => 
                `<option value="${d.id}">Dr. ${d.firstName} ${d.lastName} (${d.specialty})</option>`
            ).join('');
            select.innerHTML = '<option value="">Auto-assigner au médecin disponible</option>' + doctorOptions;
        }

        function openAddDoctorModal() {
            document.getElementById('addDoctorModal').classList.add('active');
        }

        function closeAddDoctorModal() {
            document.getElementById('addDoctorModal').classList.remove('active');
            document.getElementById('addDoctorForm').reset();
        }

        function addDoctor(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            
            const newDoctor = {
                id: nextDoctorId++,
                firstName: formData.get('firstName'),
                lastName: formData.get('lastName'),
                specialty: formData.get('specialty'),
                phone: formData.get('phone') || 'N/A',
                status: 'available',
                currentPatient: null,
                patientsToday: 0,
                totalPatients: 0
            };
            
            doctors.push(newDoctor);
            
            renderDoctors();
            updateDoctorSelect();
            closeAddDoctorModal();
            
            showNotification('Médecin Ajouté', `Dr. ${newDoctor.firstName} ${newDoctor.lastName} a été ajouté avec succès`);
        }

        function showNotification(title, message) {
            const notification = document.getElementById('notification');
            document.getElementById('notifTitle').textContent = title;
            document.getElementById('notifMessage').textContent = message;
            
            notification.classList.add('active');
            
            setTimeout(() => {
                notification.classList.remove('active');
            }, 3000);
        }

        function getCurrentTime() {
            const now = new Date();
            return `${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}`;
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('fr-FR', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        function logout() {
            if (confirm('Êtes-vous sûr de vouloir vous déconnecter?')) {
                showNotification('Déconnexion', 'Vous avez été déconnecté avec succès');
                setTimeout(() => {
                    // In a real app, this would redirect to login page
                    alert('Redirection vers la page de connexion...');
                    // window.location.href = 'login.html';
                }, 1000);
            }
        }

        function refreshQueue() {
            renderQueue();
            renderDoctors();
            updateStats();
        }

        function initializeSampleQueue() {
            if (queuePatients.length === 0) {
                queuePatients.push({
                    id: nextQueueId++,
                    patientId: 1,
                    name: "Ahmed Benali",
                    arrivalTime: "08:30",
                    priority: "normal",
                    reason: "Contrôle annuel",
                    status: "waiting",
                    doctorId: null
                });
                
                queuePatients.push({
                    id: nextQueueId++,
                    patientId: 2,
                    name: "Fatima Cherif",
                    arrivalTime: "09:15",
                    priority: "urgent",
                    reason: "Douleur thoracique",
                    status: "waiting",
                    doctorId: 2
                });
                
                renderQueue();
                updateStats();
            }
        }

        // Add auto-refresh every 30 seconds
        setInterval(refreshQueue, 30000);

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + N: Add new patient
            if (e.ctrlKey && e.key === 'n') {
                e.preventDefault();
                switchTab('add');
                document.querySelectorAll('.tab-btn')[3].click();
            }
            
            // Ctrl + D: Doctors tab
            if (e.ctrlKey && e.key === 'd') {
                e.preventDefault();
                switchTab('doctors');
                document.querySelectorAll('.tab-btn')[1].click();
            }
            
            // Ctrl + Q: Queue tab
            if (e.ctrlKey && e.key === 'q') {
                e.preventDefault();
                switchTab('queue');
                document.querySelectorAll('.tab-btn')[0].click();
            }
            
            // Ctrl + P: Patients tab
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                switchTab('patients');
                document.querySelectorAll('.tab-btn')[2].click();
            }
            
            // Ctrl + S: Search focus
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                const searchInput = document.getElementById('searchInput');
                if (searchInput) {
                    searchInput.focus();
                }
            }
        });

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('addDoctorModal');
            if (e.target === modal) {
                closeAddDoctorModal();
            }
        });

        // Close notification when clicking on it
        document.getElementById('notification').addEventListener('click', function() {
            this.classList.remove('active');
        });
    </script>
</body>
</html>