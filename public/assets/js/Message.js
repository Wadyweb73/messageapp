export async function getChatMessages(receiverId) {
    const response = await fetch(`/messageapp/chat/${receiverId}`); 

    if (!response.ok) {
        return null;
    }

    return await response.json();
}

export async function getGroupChatMessages(groupId) {
    const response = await fetch(`/messageapp/group/${groupId}/messages`);

    if (!response.ok) {
        return null;
    }

    return await response.json();
}
