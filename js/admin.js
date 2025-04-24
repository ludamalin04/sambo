import {apiClient} from "./apiClient.js";

async function getContacts() {
    return await apiClient.getOne("contacts", 1);
}

const adminAddress = document.getElementById("admin-address");
const adminMail = document.getElementById("admin-mail");
const adminPhone = document.getElementById("admin-phone");
const adminHours = document.getElementById("admin-hours");

getContacts().then((response) => {
    if (response.data) {
        adminAddress.value = response.data.address;
        adminMail.value = response.data.mail;
        adminPhone.value = response.data.phone;
        adminHours.value = response.data.hours;
    }
})

async function getSocial() {
    return await apiClient.getOne("social", 1);
}

const adminInstagram = document.getElementById("admin-instagram");
const adminFacebook = document.getElementById("admin-facebook");
const adminTiktok = document.getElementById("admin-tiktok");

getSocial().then((response) => {
    if (response.data) {
        adminInstagram.value = response.data.instagram;
        adminFacebook.value = response.data.facebook;
        adminTiktok.value = response.data.tiktok;
    }
})


//клік на редагування форми

let editBtn = document.querySelectorAll("form .edit");
if (editBtn) {
    for (const editBtnElement of editBtn) {
        editBtnElement.addEventListener("click", function () {
            let form = editBtnElement.closest("form");
            let listInput = form.getElementsByTagName("input");
            for (const listInputElement of listInput) {
                listInputElement.disabled = false;
            }
        })
    }
}

//збереження інформації в формі контактів

let contactForm = document.getElementById("contact-form");
if (contactForm) {
    contactForm.addEventListener("submit", (e) => {
        e.preventDefault();

        const formData = new FormData(contactForm);
        const data = Object.fromEntries(formData.entries());

        updateContact(data).then(response => {
            if (response.success === true) {
                document.location.reload();
            } else {
                alert("Щось пішло не так!");
            }
        })
    })
}

async function updateContact(data) {
    return await apiClient.update('contacts', 1, data);
}

// збереження інформації в формі соц мереж
let socialForm = document.getElementById("social-form");
if (socialForm) {
    socialForm.addEventListener("submit", (e) => {
        e.preventDefault();

        const formData = new FormData(socialForm);
        const data = Object.fromEntries(formData.entries());

        updateSocial(data).then(response => {
            if (response.success === true) {
                document.location.reload();
            } else {
                alert("Щось пішло не так!");
            }
        })
    })
}

async function updateSocial(data) {
    return await apiClient.update('social', 1, data);
}
