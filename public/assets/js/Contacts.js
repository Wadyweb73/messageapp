async function loadPageEvents() {
    const modal                = document.querySelector('.js-modal-add-contact')
    const addNewContactButton  = document.querySelector('.js-add-new-contact-button');
    const saveNumberButton     = document.querySelectorAll('.js-modal-add-user-button');
    const userInfoButton       = document.querySelectorAll('.js-user-info-button');

    addNewContactButton.addEventListener('click', () => {
        if (modal.classList.contains('show-modal')) {
            modal.classList.remove('show-modal');
        }
        else {
            modal.classList.add('show-modal');
        }
    });

    for (const button of saveNumberButton) {
        button.addEventListener('click', async () => {
            const response = await saveNewContact(button.dataset.userId);

            if (response['status'] !== undefined) {
                window.alert('New Contact Added!');
                window.location.reload();
            }
        });
    }

    for (const button of userInfoButton) {
        button.addEventListener('click', () => {
            const userId = button.dataset.userId;          
            window.location.pathname = `/messageapp/user/${userId}/profile`;
        });
    }
}

async function saveNewContact(userId) {
    const response = await fetch(`/messageapp/user/contact/${userId}/add`, {
        method: 'POST',
    });

    if (!response.ok) {
        return null;
    }

    return await response.json();
}

export async function getUserSession() {
    const response = await fetch('/messageapp/usersession');

    if (!response.ok) {
        return null;
    }

    return await response.json();
}

export async function getUserList() {
    const response = await fetch('/messageapp/users');

    return (!response.ok) ? null : await response.json();
}

export async function getContactList() {
    const response = await fetch('/messageapp/contacts');

    return (!response.ok) ? null : await response.json();
}

async function updateModalContent() {
    const modalBottomContainer = document.querySelector(('.modal-bottom-content'));

    const [loggedUser, users, contacts] = await Promise.all([
        getUserSession(),
        getUserList(),
        getContactList()
    ]);
    var htmlAccumulator = '';

    if (users == null) {
        return;
    }

    for (const user of users) {
        if (contacts) {
            var savedContact = contacts.find(ctt => ctt.contact_id == user.id);
        }

        if (user.id == loggedUser.user_id || savedContact != undefined) {
            continue; 
        }

        const html = `
            <div class="user-info-row">
                <div class="modal-left-side-info">
                    <i class="fa-regular fa-user modal-user-icon"></i>
                    <div class="modal-username-contanier js-modal-username-container">${user.full_name}</div>
                </div>
                
                <div class="modal-phonenumber-container modal-middle-info">
                    <i class="fa-solid fa-phone modal-phonenumber-icon"></i>
                    <div class="">(+258) ${user.phone_number}</div>
                </div>

                <div class="modal-action-buttons-container modal-right-side-info">
                    <button class="modal-profile-chat-button js-modal-profile-chat-button">
                        <i class="fa-solid fa-message modal-profile-chat-icon"></i>
                    </button>
                    <button class="modal-add-user-button js-modal-add-user-button" data-user-id="${user.id}">
                        <i class="fa-solid fa-user-plus modal-add-user-icon js-modal-add-user-icon"></i>
                    </button>
                </div>
            </div>
        `;

        htmlAccumulator += html;
    };

    modalBottomContainer.innerHTML = htmlAccumulator;
}

async function updateUserContactList() {
    const tableContainer = document.querySelector('.js-table-container');
    const [loggedUser, users, contacts] = await Promise.all([
        await getUserSession(),
        await getUserList(),
        await getContactList()
    ]);
    var htmlAccumulator = '';

    if (!contacts) {
        return;
    }

    for (const contact of contacts) {
        const currentContact = users.find(user => user.id == contact.contact_id);

        const html = `
            <div class="user-info-row">
                <div class="left-side-info">
                    <i class="fa-regular fa-user"></i>
                    <div>
                        <div class="username-container">${currentContact.full_name}</div>
                        <div class="phonenumber-container">(+258) ${currentContact.phone_number}</div>
                    </div>
                </div>
                
                <div class="email-container middle-info">
                    <i class="fa-solid fa-envelope email-icon"> </i>
                    ${currentContact.email}
                </div>

                <div class="action-buttons-container right-side-info">
                    <button class="view-profile-chat-button js-view-profile-chat-button" data-user-id="${currentContact.id}">
                        <i class="fa-solid fa-message view-profile-button"></i>
                    </button>

                    <button class="user-info-button js-user-info-button" data-user-id="${currentContact.id}">
                        <i class="fa-solid fa-circle-info user-info-icon"></i>
                    </button>
                </div>
            </div>
        `;

        htmlAccumulator += html;
    }

    tableContainer.innerHTML = htmlAccumulator;
}

async function main() {
    if (window.location.pathname == '/messageapp/user/contacts/saved') {
        await updateUserContactList();
        await updateModalContent();
        loadPageEvents();
    }
}

document.addEventListener('DOMContentLoaded', main());
