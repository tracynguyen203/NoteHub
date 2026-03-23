//Create function for list
const listContainer = document.querySelector(".nav-tabs"); // Sidebar list container
const noteContainer = document.querySelector(".note-items .tab-content"); // Main content area for notes
const create = document.querySelector(".createBtn"); // Create button
const gridContainer = document.querySelector("#grid-note-items"); // Get the grid container

// Function to create a new note
function createNote() {
    fetch("createNote.php", {
        method: "POST"
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            if (!data || !data.id || !data.title || !data.content) {
                console.error("Invalid note data received from server:", data);
                return;
            }

            // Create a new tab for the sidebar
            const newTab = document.createElement("li");
            const link = document.createElement("a");
            newTab.className = "nav-item";
            link.className = "nav-link active d-flex align-items-center";
            link.setAttribute("data-bs-toggle", "tab");
            link.href = "#" + data.id;

            link.innerHTML = `
                <div class="d-flex align-items-center flex-grow-1">
                    <button type="button"
                        class="btn btn-sm btn-secondary pin-btn me-2"
                        data-id="${data.id}"
                        data-pinned="0">
                        <i class="fa-solid fa-thumbtack pinned-icon" style="display:none"></i>
                        <i class="fa-solid fa-thumbtack-slash unpinned-icon" style="display:inline-block"></i>
                    </button>
                    <span>${data.title}</span>
                </div>
                <div class="ms-auto">
                    <button class="btn btn-sm rename-btn" style="background-color:#608BC1; color: white;" data-id="${data.id}" data-title="${data.title}">
                        <i class="fa-solid fa-marker"></i>
                    </button>
                    <button class="btn btn-sm btn-warning lock-btn" data-id="${data.id}" data-title="${data.title}">
                    <i class="fa-solid fa-lock"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="${data.id}" data-title="${data.title}">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                </div>
            `;

            newTab.appendChild(link);
            listContainer.appendChild(newTab);

            // Create a new editable content pane
            const notePad = document.createElement("div");
            notePad.className = "container tab-pane fade show active";
            notePad.id = data.id;
            notePad.setAttribute("contenteditable", "true");

            notePad.innerHTML = `
                ${data.content}
            `;
            noteContainer.appendChild(notePad);

            // --- Grid View ---
            const newGridItem = document.createElement("li");
            newGridItem.className = "grid-item nav-item";
            newGridItem.innerHTML = `
                <div class="grid-item-header">
                    <div class="d-flex align-items-center">
                        <button type="button"
                            class="btn btn-sm btn-secondary pin-btn me-2"
                            data-id="${data.id}"
                            data-pinned="0">
                            <i class="fa-solid fa-thumbtack" style="display:none"></i>
                            <i class="fa-solid fa-thumbtack-slash" style="display:inline-block"></i>
                        </button>
                        <span>${data.title}</span>
                    </div>
                    <div class="ms-auto">
                        <button class="btn btn-sm rename-btn" style="background-color:#608BC1; color: white;" data-id="${data.id}" data-title="${data.title}">
                            <i class="fa-solid fa-marker"></i>
                        </button>
                        <button class="btn btn-sm btn-warning lock-btn" data-id="${data.id}">
                            <i class="fa-solid fa-lock"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${data.id}" data-title="${data.title}">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </div>
                </div>
                <div class="grid-item-content noteArea" contenteditable="true"
                    style="font-family:${data.font_note ?? 'Arial'};
                            background:${data.note_color ?? '#FAF1E6'};">
                    ${data.content}
                </div>
                <div id="edit-${data.id}" class="edit grid-item-content noteArea">
                    <button class="btn" onclick="changeToItalic(this)"><i class="fa-solid fa-italic"></i></button>
                    <button class="btn" onclick="changeToBold(this)"><i class="fa-solid fa-bold"></i></button>
                    <button class="btn" onclick="changeToUnderline(this)"><i class="fa-solid fa-underline"></i></button>
                    <button class="btn" onclick="changeToCheckbox(this)"><i class="fa-solid fa-list-check"></i></button>
                </div>
            `;
            gridContainer.appendChild(newGridItem); // Append to grid container

            
            // Attach delete functionality to the new delete button
            const deleteButton = link.querySelector(".delete-btn");
            attachDeleteEvent(deleteButton);

            //Attach rename functionality to the new rename button
            const renameButton = link.querySelector(".rename-btn");
            attachRenameEvent(renameButton);

            // Attach pin functionality to the new pin button
            const pinButton = link.querySelector(".pin-btn");
            attachPinEvent(pinButton);

            //Deactivate any other active tabs in the sidebar
            listContainer.querySelectorAll('.nav-link.active').forEach(otherLink => {
                if (otherLink !== link) {
                    otherLink.classList.remove('active');
                }
            })

            // Deactivate all other active content panes
            noteContainer.querySelectorAll('.tab-pane.active').forEach(pane => {
                if (pane.id !== data.id) {
                    pane.classList.remove('active', 'show');
                }
            })
            
        })
        .catch(function (error) {
            console.error("Error creating note:", error);
        });
}

//Delete function
const deleteModalElement = document.getElementById("deleteModal");
const deleteModal = new bootstrap.Modal(deleteModalElement);
const confirmDeleteBtn = document.getElementById("confirmDelete");
let noteIdToDelete = null;

console.log("deleteTitle:", document.getElementById("deleteTitle"));

// Attach to all delete buttons
function attachDeleteEvent(button) {
    button.addEventListener("click", function () {
        noteIdToDelete = button.getAttribute("data-id");
        const title = button.getAttribute("data-title");

        // Populate modal content
        document.getElementById("deleteOne").value = noteIdToDelete;
        document.getElementById("deleteTitle").textContent = title;

        // Show modal
        deleteModal.show();
    })
}

// Handle confirmation click
if (confirmDeleteBtn) {
    confirmDeleteBtn.addEventListener("click", function () {
        if (!noteIdToDelete) {
            return;
        }

        fetch("deleteNote.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ note_id: noteIdToDelete }),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.status === "success") {
                    document.querySelector(`.nav-link[href="#${noteIdToDelete}"]`)?.parentElement.remove();
                    document.getElementById(noteIdToDelete)?.remove();
                    deleteModal.hide();
                } else {
                    console.error("Failed to delete:", data.message);
                }
            })
            .catch((err) => console.error("Delete error:", err));
    })
}

// Attach event listener to the Create button for both list and grid view
if (create) {
    create.addEventListener("click", function () {
        createNote();
    })
} else {
    console.error('Create button not found!');
}

// Attach delete events to existing delete buttons
document.querySelectorAll(".delete-btn").forEach(attachDeleteEvent);

// Get the modal element by its ID
const renameModalElement = document.getElementById('renameModal');

// Initialize the Bootstrap modal
const renameModal = new bootstrap.Modal(renameModalElement);

// Function to attach rename functionality to a button
function attachRenameEvent(button) {
    button.addEventListener("click", function (e) {
        e.stopPropagation();
        const noteID = button.getAttribute("data-id");
        const title = button.getAttribute("data-title");

        $('#renameNoteID').val(noteID);
        $('#newTitle').val(title);
        renameModal.show();
    })
}

// Attach rename events to existing rename buttons on page load
document.querySelectorAll(".rename-btn").forEach(attachRenameEvent);

//Function to attach pin functionality to a button
function attachPinEvent(button) {
    button.addEventListener("click", function (e) {
        e.stopPropagation();

        //Select the pin
        const pinned = button.getAttribute("data-pinned") === "1";
        const noteID = button.getAttribute("data-id");

        // Select button
        const pinIcon = button.querySelector(".fa-thumbtack");
        const unpinIcon = button.querySelector(".fa-thumbtack-slash");

        if(pinned) {
            //Unpin
            button.setAttribute("data-pinned", "0"); 
            pinIcon.style.display = "none"; 
            unpinIcon.style.display = "inline-block"; 
        } else {
            //Pin
            button.setAttribute("data-pinned", "1"); 
            pinIcon.style.display = "inline-block"; 
            unpinIcon.style.display = "none"; 
        }
    })
}

// Attach pin/unpin events to existing pin buttons
document.querySelectorAll(".pin-btn").forEach(attachPinEvent);

// Text decoration functions
// Change text to Bold
function changeToBold() {
    document.execCommand("bold");
}

// Change text to Italic
function changeToItalic() {
    document.execCommand("italic");
}

// Change text to Underline
function changeToUnderline() {
    document.execCommand("underline");
}

// Change text to checkbox
function changeToCheckbox() {
    const select = window.getSelection();

    if (!select.rangeCount) {
        return;
    }

    const range = select.getRangeAt(0);
    if (range.collapsed) {
        return;
    }

    const selectedText = range.toString();
    if (!selectedText.trim()) return;

    // Create checkbox
    const checkBox = document.createElement("input");
    checkBox.type = "checkbox";

    // Create span to hold the selected text
    const span = document.createElement("span");
    span.textContent = " " + selectedText;

    // Create label to contain checkbox + span
    const label = document.createElement("label");
    label.appendChild(checkBox);
    label.appendChild(span);

    checkBox.addEventListener("change", () => {
        label.classList.toggle("checked-text", checkBox.checked);
    });

    // Replace the selected text with checkbox + text
    range.deleteContents();
    range.insertNode(label);
}