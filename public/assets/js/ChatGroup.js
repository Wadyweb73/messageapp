export async function getUserGroupsById(userId) {
    const response = await fetch(`/messageapp/user/${userId}/groups`);

    if (!response.ok) {
        return null;
    }

    return await response.json();
}

export async function getGroupList() {
    const response = await fetch('/messageapp/groups');

    if (!response.ok) {
        return null;
    }

    return await response.json();
}

export async function getGroupById(groupId) {
    const response = await fetch(`/messageapp/group/${groupId}`);

    if (!response.ok) {
        return null;
    }

    return await response.json();
} 

export async function getGroupChatMessagesById(groupId) {
    const response = fetch(`/messageapp/group/${groupId}/messages`);

    if (!response.ok) {
        return null;
    }

    return await response.json()
}
