export async function getUserById(id) {
    const response = await fetch(`/messageapp/user/${id}`);

    if (!response.ok) {
        return null;
    }

    return await response.json();
}
