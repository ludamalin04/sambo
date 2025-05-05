<!DOCTYPE html>
<html lang="ua">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>Admin panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/reset.css">
    <link rel="stylesheet" href="../styles/admin.css">
    <link rel="icon" type="image/x-icon" href="../imgs/logo.png">
</head>
<body>
<?php
include("checkSession.php");
if (!checkSession()):
    ?>
    <div class="wrapper">
        <section class="admin-login" id="loginForm">
            <form method="post" action="">
                <p>Логін</p>
                <input type="text" name="login" id="login">
                <p>Пароль</p>
                <input type="password" name="password" id="password">
                <button>вхід</button>
            </form>
        </section>
    </div>
    <script>
        document.getElementById("loginForm").addEventListener("submit", async function (e) {
            e.preventDefault();

            const login = document.getElementById("login").value;
            const password = document.getElementById("password").value;

            const res = await fetch("./auth.php", {
                method: "POST",
                headers: {"Content-Type": "application/json"},
                credentials: "include", // дозволяє зберігати куки
                body: JSON.stringify({login, password})
            });

            const data = await res.json();
            console.log(data);

            if (data.success) {
                //alert("Успішний вхід!");
                document.location.reload();
            } else {
                alert("Невірний логін або пароль");
            }
        });
    </script>
<?php
else:
?>
    <div class="wrapper">
        <section>
            <h2 class="title">Контакти</h2>
            <form class="container" id="contact-form">
                <div class="cell">
                    <h3 class="cell-name">Адреса</h3>
                    <input type="text" name="address" id="admin-address" class="cell-info" disabled>
                </div>
                <div class="cell mail">
                    <h3 class="cell-name">Електронна адреса</h3>
                    <input type="email" name="mail" id="admin-mail" class="cell-info" disabled>
                </div>
                <div class="cell phone">
                    <h3 class="cell-name">Номер телефону</h3>
                    <input type="text" name="phone" id="admin-phone" class="cell-info" disabled>
                </div>
                <div class="cell hours">
                    <h3 class="cell-name">Графік роботи</h3>
                    <input type="text" name="hours" id="admin-hours" class="cell-info" disabled>
                </div>
                <div class="button-container">
                    <button type="button" class="btn btn-outline-primary edit">редагувати</button>
                    <button type="submit" class="btn btn-primary">зберегти</button>
                </div>
            </form>
        </section>
        <section>
            <h2 class="title">Соцмережі</h2>
            <form class="container" id="social-form">
                <div class="cell">
                    <h3 class="cell-name">Instagram</h3>
                    <input type="text" name="instagram" id="admin-instagram" class="cell-info" disabled>
                </div>
                <div class="cell">
                    <h3 class="cell-name">Facebook</h3>
                    <input type="text" name="facebook" id="admin-facebook" class="cell-info" disabled>
                </div>
                <div class="cell">
                    <h3 class="cell-name">Tiktok</h3>
                    <input type="text" name="tiktok" id="admin-tiktok" class="cell-info" disabled>
                </div>
                <div class="button-container">
                    <button type="button" class="btn btn-outline-primary edit">редагувати</button>
                    <button type="submit" class="btn btn-primary">зберегти</button>
                </div>
            </form>
        </section>
        <!--    <section class="schools">-->
        <!--        <h2 class="title">Заняття</h2>-->
        <!--        <div class="container"></div>-->
        <!--    </section>-->
    </div>
<!--    -->
<!--    -->
<!--    -->
    <div class="container">
        <h1 class="mb-4 text-center">Управління школами, класами і тренерами</h1>

        <!-- Toast notifications -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="toast-container"></div>
        </div>

        <!-- School Section -->
        <div class="mb-5">
            <h2>Школи</h2>
            <form id="schoolForm" class="row g-2 mb-3">
                <input type="hidden" id="schoolId">
                <div class="col-md-4">
                    <input type="text" class="form-control" id="schoolName" placeholder="Назва школи" required>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" id="schoolAddress" placeholder="Адреса" required>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary" type="submit">Зберегти</button>
                    <button class="btn btn-secondary" type="reset">Очистити</button>
                </div>
            </form>
            <ul class="list-group" id="schoolList"></ul>
        </div>

        <!-- Trainers Section -->
        <div class="mb-5">
            <h2>Тренери</h2>
            <form id="trainerForm" class="row g-2 mb-3">
                <input type="hidden" id="trainerId">
                <div class="col-md-3">
                    <input type="text" class="form-control" id="trainerName" placeholder="Ім'я" required>
                </div>
                <div class="col-md-3">
                    <input type="tel" class="form-control" id="trainerPhone" placeholder="Телефон" required pattern="[0-9\-+() ]+">
                </div>
                <div class="col-md-3">
                    <input type="hidden" class="form-control" id="trainerDescription" placeholder="Опис">
                </div>
                <div class="col-md-3">
                    <input type="hidden" class="form-control" id="trainerImage" placeholder="Посилання на зображення">
                </div>
                <div class="col-md-12">
                    <button class="btn btn-primary" type="submit">Зберегти</button>
                    <button class="btn btn-secondary" type="reset">Очистити</button>
                </div>
            </form>
            <ul class="list-group" id="trainerList"></ul>
        </div>

        <!-- Classes Section -->
        <div>
            <h2>Класи (згруповані по школах)</h2>
            <form id="classForm" class="row g-2 mb-3">
                <input type="hidden" id="classId">
                <div class="col-md-5">
                    <select id="classSchool" class="form-select" required></select>
                </div>
                <div class="col-md-5">
                    <select id="classTrainer" class="form-select" required></select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" type="submit">Зберегти</button>
                </div>
            </form>
            <div id="classList"></div>
        </div>
    </div>

    <script type="module">
        // uiHandlers.js
        import { apiClient } from '../js/apiClient.js';

        // DOM Elements
        const toastContainer = document.getElementById('toast-container');

        // === Toasts ===
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white bg-${type} border-0 show mb-2`;
            toast.role = 'alert';
            toast.innerHTML = `
    <div class="d-flex">
      <div class="toast-body">${message}</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Закрити"></button>
    </div>
  `;
            toastContainer.appendChild(toast);
            setTimeout(() => toast.remove(), 4000);
        }

        // === Fetch & Render ===
        async function fetchAll() {
            const [schools, trainers, classes] = await Promise.all([
                apiClient.getAll('school'),
                apiClient.getAll('trainers'),
                apiClient.getAll('classes')
            ]);

            renderSchools(schools);
            renderTrainers(trainers);
            renderClassFormOptions(schools, trainers);
            renderClassesGrouped(classes, schools, trainers);
        }

        function renderSchools(schools) {
            const list = document.getElementById('schoolList');
            list.innerHTML = '';
            schools.data.forEach(s => {
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between';
                li.innerHTML = `
      <div><strong>${s.name}</strong> - ${s.address}</div>
      <div>
        <button class="btn btn-sm btn-outline-primary me-2" onclick='editSchool(${JSON.stringify(s)})'>Редагувати</button>
        <button class="btn btn-sm btn-outline-danger" onclick='deleteSchool(${s.id_school})'>Видалити</button>
      </div>
    `;
                list.appendChild(li);
            });
        }

        function renderTrainers(trainers) {
            const list = document.getElementById('trainerList');
            list.innerHTML = '';
            trainers.data.forEach(t => {
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between';
                li.innerHTML = `
      <div><strong>${t.name}</strong> (${t.phone}) - ${t.description}</div>
      <div>
        <button class="btn btn-sm btn-outline-primary me-2" onclick='editTrainer(${JSON.stringify(t)})'>Редагувати</button>
        <button class="btn btn-sm btn-outline-danger" onclick='deleteTrainer(${t.id_trainers})'>Видалити</button>
      </div>
    `;
                list.appendChild(li);
            });
        }

        function renderClassFormOptions(schools, trainers) {
            const schoolSelect = document.getElementById('classSchool');
            const trainerSelect = document.getElementById('classTrainer');
            schoolSelect.innerHTML = trainerSelect.innerHTML = '';

            schools.data.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id_school;
                opt.textContent = s.name;
                schoolSelect.appendChild(opt);
            });

            trainers.data.forEach(t => {
                const opt = document.createElement('option');
                opt.value = t.id_trainers;
                opt.textContent = t.name;
                trainerSelect.appendChild(opt);
            });
        }

        function renderClassesGrouped(classes, schools, trainers) {
            const grouped = {};
            schools.data.forEach(s => grouped[s.id_school] = { school: s.name, items: [] });
            classes.data.forEach(c => grouped[c.id_school]?.items.push(c));

            const container = document.getElementById('classList');
            container.innerHTML = '';
            for (const id in grouped) {
                const group = grouped[id];
                const section = document.createElement('div');
                section.className = 'mb-3';
                const ul = document.createElement('ul');
                ul.className = 'list-group';

                group.items.forEach(c => {
                    const trainer = trainers.data.find(t => t.id_trainers === c.id_trainers);
                    const li = document.createElement('li');
                    li.className = 'list-group-item d-flex justify-content-between';
                    li.innerHTML = `
        <div><strong>Тренер:</strong> ${trainer?.name || 'Невідомо'}</div>
        <div>
          <button class="btn btn-sm btn-outline-danger" onclick='deleteClass(${c.id_classes})'>Видалити</button>
        </div>
      `;
                    ul.appendChild(li);
                });

                section.innerHTML = `<h5>${group.school}</h5>`;
                section.appendChild(ul);
                container.appendChild(section);
            }
        }

        // === Actions ===
        window.editSchool = (s) => {
            document.getElementById('schoolId').value = s.id_school;
            document.getElementById('schoolName').value = s.name;
            document.getElementById('schoolAddress').value = s.address;
        };

        window.deleteSchool = async (id) => {
            await apiClient.remove('school', id);
            showToast('Школу видалено');
            fetchAll();
        };

        window.editTrainer = (t) => {
            document.getElementById('trainerId').value = t.id_trainers;
            document.getElementById('trainerName').value = t.name;
            document.getElementById('trainerPhone').value = t.phone;
            document.getElementById('trainerDescription').value = t.description;
            document.getElementById('trainerImage').value = t.image;
        };

        window.deleteTrainer = async (id) => {
            await apiClient.remove('trainers', id);
            showToast('Тренера видалено');
            fetchAll();
        };

        window.deleteClass = async (id) => {
            await apiClient.remove('classes', id);
            showToast('Клас видалено');
            fetchAll();
        };

        // === Forms ===
        document.getElementById('schoolForm').onsubmit = async e => {
            e.preventDefault();
            const id = document.getElementById('schoolId').value;
            const name = document.getElementById('schoolName').value;
            const address = document.getElementById('schoolAddress').value;
            if (id) await apiClient.update('school', id, { name, address });
            else await apiClient.create('school', { name, address });
            showToast('Школу збережено');
            e.target.reset();
            fetchAll();
        };

        document.getElementById('trainerForm').onsubmit = async e => {
            e.preventDefault();
            const id = document.getElementById('trainerId').value;
            const name = document.getElementById('trainerName').value;
            const phone = document.getElementById('trainerPhone').value;
            const description = document.getElementById('trainerDescription').value;
            const image = document.getElementById('trainerImage').value;
            if (id) await apiClient.update('trainers', id, { name, phone, description, image });
            else await apiClient.create('trainers', { name, phone, description, image });
            showToast('Тренера збережено');
            e.target.reset();
            fetchAll();
        };

        document.getElementById('classForm').onsubmit = async e => {
            e.preventDefault();
            const schoolId = document.getElementById('classSchool').value;
            const trainerId = document.getElementById('classTrainer').value;
            await apiClient.create('classes', { id_school: schoolId, id_trainers: trainerId });
            showToast('Клас додано');
            e.target.reset();
            fetchAll();
        };

        fetchAll();

    </script>

<!--    -->
<!--    -->
<!--    -->
    <script src="../js/admin.js" type="module"></script>
<?php
endif;
?>
</body>
</html>