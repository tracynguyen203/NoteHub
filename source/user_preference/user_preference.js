const avatarContainer = document.querySelector('.flex.flex-wrap.gap-2');
const selectedAvatarInput = document.getElementById('selected-avatar');
const avatarCount = 20; // Total number of default avatars
const avatarBaseURL = "https://n0tehub.me/user_preference/avatar/";

// generate avatar image elements
for (let i = 1; i <= avatarCount; i++) {
    const avatarDiv = document.createElement('div');
    avatarDiv.classList.add('avatar-item', 'rounded-md', 'overflow-hidden', 'cursor-pointer', 'border-2', 'border-transparent');
    avatarDiv.dataset.avatar = i;

    const img = document.createElement('img');
    img.src = `${avatarBaseURL}${i}.png`;
    img.alt = `Avatar ${i}`;
    img.classList.add('w-20', 'h-20', 'object-cover'); // set img size and opbject cover

    avatarDiv.appendChild(img);
    avatarDiv.addEventListener('click', () => {
        // remove selection from any previously selected avatar
        const selectedAvatar = document.querySelector('.avatar-item.selected');
        if (selectedAvatar) {
            selectedAvatar.classList.remove('selected', 'border-red-500', 'ring-2', 'ring-red-500');
        }

        // set css after click on a specific avatar
        avatarDiv.classList.add('selected', 'border-red-500', 'ring-2', 'ring-red-500');
        selectedAvatarInput.value = i; // update the hidden input with the selected avatar number
    });

    avatarContainer.appendChild(avatarDiv);
}

// handle submission
document.getElementById('update-profile-form').addEventListener('submit', async (event) => {
    event.preventDefault();
    
    const selectedAvatar = selectedAvatarInput.value;
    if (!selectedAvatar) {
        showMessage('Please select an avatar.', 'error');
        return;
    }

    try {
        console.log('Submitting to avatar_db/update_profile.php');
        const response = await fetch('avatar_db/update_profile.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ avatar: selectedAvatar })
        });
        
        const result = await response.json();
        showMessage(result.message, result.success ? 'success' : 'error');
        
        if (result.success) {
            location.href = "https://n0tehub.me/note/noteManagement.php";
        }
    } catch (error) {
        showMessage('Network error. Please try again.', 'error');
    }
});

// showMessage function
function showMessage(message, type = 'info') {
    const messageBox = document.getElementById('message-box');
    messageBox.textContent = message;
    messageBox.className = `show fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded shadow-md`;
    
    const colors = {
        success: 'bg-green-100 text-green-700 border-green-400',
        error: 'bg-red-100 text-red-700 border-red-400',
        info: 'bg-blue-100 text-blue-700 border-blue-400'
    };
    messageBox.classList.add(...colors[type].split(' '));
    
    setTimeout(() => messageBox.classList.remove('show'), 3000);
}