import { getGroupById, getGroupList, getUserGroupsById } from "./ChatGroup.js";
import { getContactList, getUserList, getUserSession } from "./Contacts.js"
import { getChatMessages, getGroupChatMessages } from "./Message.js";
import { getUserById } from "./User.js";

export const BASE_URL = "http://localhost/messageapp/";

async function loadPageEvents() {
    if (document.querySelector('.js-start-first-chat-button')) {
        triggerStartConversationButtonEvent();
    }

    triggerSelectChatEvent();
    triggerSwitchToUserOrGroupDiscussionsEvent(); 
    triggerSendMessageButtonEvent();
}

function triggerSelectChatEvent() {
    const discussions = document.querySelectorAll('.js-discussion-floor'); 
    const chatUsername = document.querySelector('.js-chat-user-name');

    for (const discussion of discussions) {
        discussion.addEventListener('click', async () => {
            const userId = discussion.dataset.userChatId;  
            const selectedChat = document.querySelector(`.js-discussion-${userId}`);
            const chatUser = await getUserById(userId);  

            removeMessageActivation();

            if (selectedChat.classList.contains('message-active')) {
                selectedChat.classList.remove('message-active');
            }
            else {
                selectedChat.classList.add('message-active');
                chatUsername.innerHTML = chatUser.email;         
                await loadChosenUserChat(userId);
            }
        });
    }
}

function triggerSwitchToUserOrGroupDiscussionsEvent() {
    const switchDiscussionButtons = document.querySelectorAll('.js-switch-discussion-type');
    const chatMessagesContainer  = document.querySelector('.js-chat-messages-container');
    const headerUsernameContainer = document.querySelector('.header-chat-username-container')
    const addButton = document.querySelector('.add-user-or-group-button');

    for (const button of switchDiscussionButtons) {
        button.addEventListener('click', async () => {
            chatMessagesContainer.innerHTML = ``;

            removeChosenDiscussionTypeStyles();
            button.classList.add('chosen-discussion'); 

            if (button.classList.contains('user-discussions')) {
                await updateDiscussionPanel(); 
 
                const html = `
                    <i class="icon header-user-icon fa-solid fa-user"></i>
                    <p class="name js-chat-user-name" style="color: red;">O EMAIL DO EMAIL DO AMIGO SELECCIONADO VEM PARA AQUI!</p>
                `;
                
                headerUsernameContainer.innerHTML = html;

                addButton.innerHTML = 'Adicionar Novo Amigo';

                if (addButton.classList.contains('group')) {
                    addButton.classList.remove('group');
                    addButton.classList.add('user');
                }

                triggerSelectChatEvent();
            }
            else {
                const loggedUser = await getUserSession();
                const userGroups = await getUserGroupsById(loggedUser['user_id']);

                if (userGroups.length === 0) {
                    showInitialDiscussionPanelState();
                    alert('Voce ainda nao participa de nenhum grupo!');

                    triggerStartConversationButtonEvent();
                }

                addButton.innerHTML = 'Criar Novo Grupo';

                if (!addButton.classList.contains('group')) {
                    addButton.classList.remove('user');
                    addButton.classList.add('group');
                }

                const html = `
                    <i class="fa-solid fa-people-group header-group-icon"></i>
                    <p class="name js-chat-user-name" style="color: red;">O NOME DO GRUPO SELECCIONADO APARECE AQUI!</p>
                `;
                
                headerUsernameContainer.innerHTML = html;
                updateGroupChatDiscussionPanel();
            }

        });
    }
}

function triggerAddUserOrGroupEvent() {
    const addButton = document.querySelector('.add-user-or-group-button');

    addButton.addEventListener('click', () => {
        if (addButton.classList.contains('user')) {
            window.location.pathname = '/messageapp/user/contacts/saved';
        }
        else {
            showModal();
        }
    });
}

function triggerStartConversationButtonEvent() {
    const startChatButton = document.querySelector('.js-start-first-chat-button');
    const userDiscussionsButton = document.querySelector('.user-discussions'); 
    const modal = document.querySelector('.modal-create-chat-group');

    startChatButton.addEventListener('click', () => {
        if (userDiscussionsButton.classList.contains('chosen-discussion')) {
            window.location.pathname = '/messageapp/user/contacts/saved';
        }
        else {
            if (modal.classList.contains('show-modal')) {
                modal.classList.remove('show-modal'); 
            }
            else {
                showModal();
            }
        }
    })
}

async function showModal() {
    const modal = document.querySelector('.modal-create-chat-group');

    modal.classList.add('show-modal');
    await updateModalContactList();

    document.addEventListener('keydown', (e) => {
        if (e.key == 'Escape') {
            modal.classList.remove('show-modal'); 
        }
    })
}

async function updateModalContactList() {
    const modalContactListContainer = document.querySelector('.js-contact-list');
    const [users, contacts] = await Promise.all([
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
            <div class="contact contact-info">
                <div>
                    <input type="checkbox" class="js-user-selected-checkbox" data-user-id=${currentContact.id}>
                    <label>${currentContact.full_name} => [${currentContact.email}]</label>
                </div>
                <i class="fa-solid fa-user-check"></i>
            </div>  
        `;

        htmlAccumulator += html;
    }

    modalContactListContainer.innerHTML = htmlAccumulator;
    triggerCreateGroupButtonEvent();
}

async function triggerCreateGroupButtonEvent() {
    const checkboxs = document.querySelectorAll('.js-user-selected-checkbox');
    const createGroupButton = document.querySelector('.js-create-group-button'); 
    const loggedUser = await getUserSession();
    let checkedUsers = [];

    checkedUsers.push(loggedUser['user_id']);

    createGroupButton.addEventListener('click', async () => {
        const groupName = document.querySelector('#group-name').value;
        const groupDescription = document.querySelector('#group-description').value;

        if (groupName.trim() == '') {
            alert('Nome do grupo nao pode ser vazio!');
            return;
        }

        for (const check of checkboxs) {
            const userId = Number(check.dataset.userId);

            if (!check.checked && checkedUsers.includes(userId)) {
                const index = checkedUsers.indexOf(userId);

                if (index !== -1) {
                    checkedUsers.splice(index, 1);
                }
            }
            else if (check.checked && !checkedUsers.includes(userId)){
                checkedUsers.push(userId);
            }
        }

        const data = {
            'users': checkedUsers,
            'group_name': groupName,
            'group_description': groupDescription
        }

        await createChatGroup(JSON.stringify(data));
    });
}

async function createChatGroup(data) {
    await fetch('/messageapp/group/create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: data
    })
    .then((response) => {
        if (!response.ok) {
            return null;
        }

        return response.json()
    })
    .then((res) => {
        console.log(res);
        updateGroupChatDiscussionPanel();
    })
    .catch((Error) => {
        console.log(`Erro: ${Error}`);
    });
}

async function updateGroupChatDiscussionPanel() {
    const discussionContainer = document.querySelector('.js-discussion-contact-container');
    const loggedUser = await getUserSession();
    const [userGroups, groupList] = await Promise.all([
        getUserGroupsById(loggedUser['user_id']),
        getGroupList()
    ]); 
    let htmlAccumulator = '';

    console.log(groupList, userGroups)
    if (groupList.length === 0) {
        showInitialDiscussionPanelState();
        return;
    }

    for (const group of groupList) {
        for (const userGroup of userGroups) {
            if (group.id !== userGroup.chat_group_id) {
                continue;
            }

            const html = `
                <div class="discussion js-discussion-${group.id}" data-group-chat-id=${group.id}>
                    <div class="photo">
                        <div class="user-photo-container">
                            <img src="${BASE_URL}/public/assets/imgs/group-circular.png">
                        </div>
                    </div>
                    <div class="desc-contact">
                        <p class="group-name js-group-chat-name">${group.group_name}</p>
                        <p class="message js-last-message">---</p>
                    </div>
                    <div class="timer js-user-discussion-timer">12 sec</div>
                    <div class="discussion-floor js-discussion-floor" data-group-chat-id=${group.id}></div>
                </div>
            `;

            htmlAccumulator += html;
        }
    }

    discussionContainer.innerHTML = htmlAccumulator;
    triggerSelectGroupChatEvent();
}

function triggerSelectGroupChatEvent() {
    const discussions = document.querySelectorAll('.js-discussion-floor')
    const chatName = document.querySelector('.js-chat-user-name');

    for (const discussion of discussions) {
        discussion.addEventListener('click', async () => {
            const groupId = discussion.dataset.groupChatId;  
            console.log(groupId);

            const selectedChat = document.querySelector(`.js-discussion-${groupId}`);
            const group = await getGroupById(groupId);  

            removeMessageActivation();

            selectedChat.classList.add('message-active');
            chatName.innerHTML = group.group_name;         
            await loadChosenGroupChat(groupId);
        });
    }
}

function showInitialDiscussionPanelState() {
    const discussionContainer = document.querySelector('.js-discussion-contact-container')

    discussionContainer.innerHTML = `
        <div class="no-chat-warning-container">
            <p class="no-chat-warning"><i>Suas conversas aparecerão aquí!</i></p>
            <button style="cursor: pointer;" class="start-first-chat-button js-start-first-chat-button">
                Começar Uma Conversa
                <i class="fa-regular fa-comment"></i>
            </button>
        </div>
    `;
}

function triggerSendMessageButtonEvent() {
    const sendMessageButton = document.querySelector('.js-send-message-button');

    sendMessageButton.addEventListener('click', async () => {
        const receiverId = await getChatReceiverId();
        const response   = await sendMessage();
        const receiverType = getReceiverType();

        if (response !== null) {
            if (receiverType == 'user') {
                await loadChosenUserChat(receiverId);
                return;
            }
            else {
                await loadChosenGroupChat(receiverId);
            }
        }
    })
}

function  removeChosenDiscussionTypeStyles() {
    const switchDiscussionButtons = document.querySelectorAll('.js-switch-discussion-type');

    for (const button of switchDiscussionButtons) {
        if (button.classList.contains('chosen-discussion')) {
            button.classList.remove('chosen-discussion');
        }
    }
}

function getReceiverType() {
    const switchDiscussionButtons = document.querySelectorAll('.js-switch-discussion-type');
    
    for (const button of switchDiscussionButtons) {
        if (button.classList.contains('chosen-discussion')) {
            return (button.classList.contains('user-discussions')) ? 'user' : 'group';
        }
    }
}

async function sendMessage() {
    const writeMessageInput = document.querySelector('.js-write-message-input');
    const content           = writeMessageInput.value;
    const receiverType      = getReceiverType();

    if (content.trim() === '') {
        alert("Escreva alguma coisa no campo de mensagem!");
        return;
    }

    const [receiverId, userSession] = await Promise.all([
        getChatReceiverId(),
        getUserSession()
    ]);

    if (receiverId === null) {
        alert('Seleccione um amigo para enviar mensagem!');
        return;
    }

    const data = {
        'sender': userSession['user_id'],
        'receiver': receiverId,
        'receiver_type': receiverType,
        'content': content
    }

    return await fetch(`/messageapp/message/send`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify( data )
    })
    .then((response) => {
        if (!response.ok) {
            return null;
        }

        resetMessageInput();
        return response.json()
    })
    .catch((Error) => {
        console.log(`Erro: ${Error}`);
    })
}

function resetMessageInput() {
    const writeMessageInput = document.querySelector('.js-write-message-input');

    writeMessageInput.value = '';
    writeMessageInput.focus();
}

async function getChatReceiverId() {
    const discussions = document.querySelectorAll('.js-discussion-floor'); 

    for (const discussion of discussions) {
        let receiverId = discussion.dataset.userChatId;  
        
        receiverId = (receiverId) ? receiverId : discussion.dataset.groupChatId;

        const selectedChat = document.querySelector(`.js-discussion-${receiverId}`);

        if (selectedChat.classList.contains('message-active')) {
            return receiverId;            
        }
    }

    return null;
}

async function loadChosenUserChat(receiverId) {
    const chatMessagesContainer  = document.querySelector('.js-chat-messages-container');
    const timer = document.querySelector('.js-user-discussion-timer')

    const [messages, loggedUser] = await Promise.all([
        getChatMessages(receiverId),
        getUserSession()
    ]);

    if (messages.data === null) {
        chatMessagesContainer.innerHTML = ``
        return;
    }

    let lastMessage     = null;
    let htmlAccumulator = '';

    for (const message of messages) {
        const isMine  = message.id_sender == loggedUser['user_id'];
        const content = message.content;
        const time    = getSendTime(message.send_date);

        htmlAccumulator += `
            <div class="message text-only">
                <div class="message-content ${isMine ? 'my-message' : 'response'}">
                    <p class="text">${content}</p>
                    <p class="time" style="${isMine ? 'text-align: right;' : ''}">${time}</p>
                </div>
            </div>
        `;

        lastMessage = {
            content: `${isMine ? 'Eu' : '👤'}: ${content}`,
            receiver: message.id_receiver == loggedUser.user_id ? message.id_sender : message.id_receiver,
            time: message.send_date
        };
    }

    chatMessagesContainer.innerHTML = htmlAccumulator;
    chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;

    const lastMessageElement     = document.querySelector(`.js-discussion-${lastMessage.receiver} .js-last-message`);
    lastMessageElement.innerHTML = lastMessage.content;
    timer.innerHTML = lastMessage.time;
}

async function loadChosenGroupChat(receiverId) {
    const chatMessagesContainer  = document.querySelector('.js-chat-messages-container');
    const [messages, users, loggedUser] = await Promise.all([
        getGroupChatMessages(receiverId),
        getUserList(),
        getUserSession()
    ]);

    if (messages.data === null) {
        chatMessagesContainer.innerHTML = ``
        return;
    }

    let lastMessage     = null;
    let htmlAccumulator = '';

    for (const message of messages) {
        const isMine  = message.id_sender == loggedUser['user_id'];
        const content = message.content;
        const time    = getSendTime(message.send_date);

        const currentUser = (!isMine) ? users.find(u => u.id == message.id_sender) : null;

        htmlAccumulator += `
            <div class="message text-only">
                <div class="message-content ${isMine ? 'my-message' : 'response'}">
                    ${(!isMine) ? '<p class="group-user-name">'+ currentUser.full_name +'</p>' : '--'}
                    <p class="text">${content}</p>
                    <p class="time" style="${isMine ? 'text-align: right;' : ''}">${time}</p>
                </div>
            </div>
        `;

        lastMessage = {
            content: `${isMine ? 'Eu' : '👤 [' + currentUser.full_name + ']'}: ${content}`,
            receiver: message.id_receiver == loggedUser.user_id ? message.id_sender : message.id_receiver,
            time: message.send_date
        };
    }

    chatMessagesContainer.innerHTML = htmlAccumulator;
    chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;

    const lastMessageElement     = document.querySelector(`.js-discussion-${lastMessage.receiver} .js-last-message`);
    lastMessageElement.innerHTML = lastMessage.content;
}

function getSendTime(timeInfo) {
    const date  = new Date(timeInfo); 
    const hours = String(date.getHours()).padStart(2, '0');
    const mins  = String(date.getMinutes()).padStart(2, '0');
    const time  = `${hours}:${mins}`;

    return time;
}

async function removeMessageActivation() {
    const discussionContainer = document.querySelectorAll('.discussion');

    for (const discussion of discussionContainer) {
        if (discussion.classList.contains('message-active')) {
            discussion.classList.remove('message-active');   
        }
    } 
}

async function updateDiscussionPanel() {
    const discussionContainer = document.querySelector('.js-discussion-contact-container')
    const [contacts, users, loggedUser] = await Promise.all([
        getContactList(),
        getUserList(),
        await getUserSession()
    ]);
    var htmlAccumulator = '';

    if (!contacts) {
        showInitialDiscussionPanelState();
        return;
    }

    var savedContacts = contacts.filter(ctt => ctt.user_id == loggedUser.user_id)

    for (const contact of savedContacts) {
        for (const user of users) {
             if (contact.contact_id == user.id) {
                const html = `
                    <div class="discussion js-discussion-${user.id}" data-user-chat-id=${user.id}>
                        <div class="photo">
                            <div class="user-photo-container">
                                <img src="${BASE_URL}/public/assets/imgs/user-icon.png">
                            </div>
                            <div class="online"></div>
                        </div>
                        <div class="desc-contact">
                            <p class="name">${user.full_name}</p>
                            <p class="message js-last-message">---</p>
                        </div>
                        <div class="timer">12 sec</div>
                        <div class="discussion-floor js-discussion-floor" data-user-chat-id=${user.id}></div>
                    </div>
                `;

                htmlAccumulator += html;
            }
        }
    }

    discussionContainer.innerHTML = htmlAccumulator;
}

async function main() {
    const chatMessagesContainer = document.querySelector('.js-chat-messages-container');
    await updateDiscussionPanel();
    await loadPageEvents();
    triggerAddUserOrGroupEvent();

    chatMessagesContainer.innerHTML = ``;
}

document.addEventListener('DOMContentLoaded', main());
