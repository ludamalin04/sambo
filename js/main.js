import {apiClient} from './apiClient.js';

async function getContacts() {
    return await apiClient.getOne("contacts", 1);
}

const contactAddress = document.getElementById("contacts-address");
const contactMail = document.getElementById("contacts-mail");
const contactPhone = document.getElementById("contacts-phone");
const contactHours = document.getElementById("contacts-hours");


getContacts().then((response) => {
    if (response.data) {
        contactAddress.innerText = response.data.address;
        contactAddress.href = `https://www.google.com/maps/place/${response.data.address}`;
        contactMail.innerText = response.data.mail;
        contactMail.href = `mailto:${response.data.mail}`;
        contactPhone.innerText = response.data.phone;
        contactPhone.href = `tel:${response.data.phone}`;
        contactHours.innerText = response.data.hours;
    }
});

async function getSocial() {
    return await apiClient.getOne("social", 1);
}

const socialInstagram = document.querySelectorAll(".social-instagram");
const socialFacebook = document.querySelectorAll(".social-facebook");
const socialTiktok = document.querySelectorAll(".social-tiktok");

getSocial().then((response) => {
    console.log(socialInstagram);
    if (response.data) {
        socialInstagram[0].href = response.data.instagram;
        socialInstagram[1].href = response.data.instagram;
        socialInstagram[2].href = response.data.instagram;
        socialFacebook[0].href = response.data.facebook;
        socialFacebook[1].href = response.data.facebook;
        socialFacebook[2].href = response.data.facebook;
        socialTiktok[0].href = response.data.tiktok;
        socialTiktok[1].href = response.data.tiktok;
        socialTiktok[2].href = response.data.tiktok;
    }
})
//https://www.instagram.com/federation.sambo.kr
async function getClasses() {
    return await apiClient.custom("classes/with-trainers");
}


getClasses().then((response) => {
    if (response) {
        let group = groupBySchoolToArray(response);
        group.forEach(e => {
            const schoolItem = document.createElement("div");
            schoolItem.classList.add("schools-item");
            schoolContainer.appendChild(schoolItem);

            schoolItem.innerHTML = `<h2 class="schools-name">${e.school_name}</h2>
                                    <h3 class="schools-address">${e.school_address}</h3>`;

            const schoolTrainers = document.createElement("div");
            schoolTrainers.classList.add("schools-trainers");
            schoolItem.appendChild(schoolTrainers);

            e.trainers.forEach(trainer => {
                const schoolTrainer = document.createElement("div");
                schoolTrainer.classList.add("schools-trainer");
                schoolTrainers.appendChild(schoolTrainer);

                schoolTrainer.innerHTML = `<p class="schools-trainer-name">${trainer.name}</p>
                                            <p class="schools-trainer-number">${trainer.phone}</p>`;
            })
        })
    }
})


function groupBySchoolToArray(data) {
    if (!Array.isArray(data)) {
        console.error("Очікував масив, але отримав:", data);
        return [];
    }

    const groupedMap = new Map();

    data.forEach(item => {
        const key = item.school_name;

        if (!groupedMap.has(key)) {
            groupedMap.set(key, {
                school_name: item.school_name,
                school_address: item.school_address,
                trainers: []
            });
        }

        groupedMap.get(key).trainers.push({
            id_classes: item.id_classes,
            name: item.trainer_name,
            phone: item.trainer_phone
        });
    });

    return Array.from(groupedMap.values());
}

const schoolContainer = document.querySelector(".schools-container");




