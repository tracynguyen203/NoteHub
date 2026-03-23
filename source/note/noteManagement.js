function pin_and_unpin(buttonElement, event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    const parentLi = buttonElement.closest('.nav-item, .grid-item');
    const tabElement = parentLi.querySelector('.nav-link');
    const pin_icon = buttonElement.querySelector('.fa-thumbtack');
    const unpin_icon = buttonElement.querySelector('.fa-thumbtack-slash');
    const noteId = buttonElement.getAttribute('data-id');
    let pinState;

    if (unpin_icon.style.display !== 'none') {
        // Pin
        pin_icon.style.display = 'inline-block';
        unpin_icon.style.display = 'none';
        tabElement.classList.add('active-top');
        pinState = 1;
    } else {
        // Unpin
        pin_icon.style.display = 'none';
        unpin_icon.style.display = 'inline-block';
        tabElement.classList.remove('active-top');
        pinState = 0;
    }

    // Update all pin buttons for this note in both views
    document.querySelectorAll(`.pin-btn[data-id="${noteId}"]`).forEach(btn => {
        btn.setAttribute('data-pinned', pinState);
        const pinIcon = btn.querySelector('.fa-thumbtack');
        const unpinIcon = btn.querySelector('.fa-thumbtack-slash');
        if (pinState) {
            pinIcon.style.display = 'inline-block';
            unpinIcon.style.display = 'none';
        } else {
            pinIcon.style.display = 'none';
            unpinIcon.style.display = 'inline-block';
        }
    });

    // Send AJAX to update pin state in DB
    fetch('update_pin.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `noteId=${encodeURIComponent(noteId)}&pinState=${encodeURIComponent(pinState)}`
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            alert('Failed to update pin state: ' + (data.error || 'Unknown error'));
        } else {
            movePinnedNotesToTop();
        }
    })
    .catch(() => {
        alert('Failed to update pin state.');
    });
}

function movePinnedNotesToTop() {
    const grid = document.getElementById('grid-note-items');
    if (!grid) return;
    const items = Array.from(grid.children);
    items.sort((a, b) => {
        const aPinned = a.querySelector('.pin-btn')?.getAttribute('data-pinned') == "1" ? 1 : 0;
        const bPinned = b.querySelector('.pin-btn')?.getAttribute('data-pinned') == "1" ? 1 : 0;
        return bPinned - aPinned;
    });
    items.forEach(item => grid.appendChild(item));
}

//expand and collapse button
document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.nav-tabs .nav-link');
    const left = document.querySelector('.left');
    const right = document.querySelector('.right');
    const expandCollapse = document.querySelector('.expand-collapse-button');
    const expand = document.querySelector('.expand-icon');
    const collapse = document.querySelector('.collapse-icon');
    let isExpanded = window.innerWidth >= 768; 

    function updateLayout() {
        if (window.innerWidth < 768) {
            if (isExpanded) {
                left.classList.add('collapsed');
                right.classList.add('expanded');
                right.style.display = 'block';
                if (expand && collapse) {
                    expand.style.display = 'none';
                    collapse.style.display = 'inline-block'; 
                }
            } else {
                left.classList.remove('collapsed');
                right.classList.remove('expanded');
                right.style.display = 'none';
                if (expand && collapse) {
                    expand.style.display = 'inline-block';
                    collapse.style.display = 'none';
                }
            }
        } else {
            if (isExpanded) {
                left.classList.remove('collapsed');
                right.classList.remove('expanded');
                right.style.display = 'flex';
                if (expand && collapse) {
                    expand.style.display = 'inline-block'; 
                    collapse.style.display = 'none';
                }
            } else {
                left.classList.add('collapsed');
                right.classList.add('expanded');
                right.style.display = 'block';
                if (expand && collapse) {
                    expand.style.display = 'none';
                    collapse.style.display = 'inline-block'; 
                }
            }
        }
    }

    function toggleAside() {
        isExpanded = !isExpanded;
        updateLayout();
    }

    updateLayout();

    window.addEventListener('resize', updateLayout);

    if (expandCollapse) {
        expandCollapse.addEventListener('click', toggleAside);
    }

    navLinks.forEach(navLink => {
        navLink.addEventListener('click', function(event) {
            const allTabLinks = document.querySelectorAll('.nav-link');
            allTabLinks.forEach(tab => {
                tab.classList.remove('active');
            });
            this.classList.add('active');

            if (window.innerWidth < 768) {
                if (!isExpanded) {
                    toggleAside();
                }
            }
        });
    });

    if (window.innerWidth < 768 && expand && collapse) {
        expand.style.display = 'none';
        collapse.style.display = 'inline-block';
        isExpanded = false;
    } else if (window.innerWidth >= 768 && expand && collapse) {
        expand.style.display = 'inline-block';
        collapse.style.display = 'none';
        isExpanded = true;
    }
});

// change icon when clicked
document.addEventListener('DOMContentLoaded', function() {
    const edit = document.getElementById('edit');
    const icon = document.querySelector('[data-bs-target="#edit"] i');

    edit.addEventListener('shown.bs.collapse', () => {
        icon.classList.replace('fa-angle-down', 'fa-angle-up');
    });

    edit.addEventListener('hidden.bs.collapse', () => {
        icon.classList.replace('fa-angle-up', 'fa-angle-down');
    });
});

//Delete function
function deleteNote() {
    let selectedNote = null;
    let noteElement = null;

    document.addEventListener("click", function (e) {
        const deleteBtn = e.target.closest(".delete-btn");
        if (!deleteBtn) return;

        selectedNote = deleteBtn.getAttribute("data-id");
        noteElement = deleteBtn.closest("li");

        const title = deleteBtn.getAttribute("data-title");
        document.querySelector("#deleteModal .modal-body p").innerHTML = `
            Are you sure you want to delete <strong>${title}</strong>? `;

        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    });

    //When confirmed delete
    const confirmDelete = document.getElementById("confirmDelete");

    if (confirmDelete) {
        confirmDelete.addEventListener("click", function () {
            if (!selectedNote) return;

            fetch("deleteNote.php", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `note_id=${encodeURIComponent(selectedNote)}`
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    if (data.success) {
                        if (noteElement) {
                            noteElement.remove();

                            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                            modal.hide();
                        } else {
                            alert("Delete failed: " + (data.message || "Unknown error"));
                        }
                    }
                })
                .catch(function (error) {
                    console.log("Error:", error);
                });
        })
    }
}

deleteNote();


// Add click event to "view note" buttons
document.querySelectorAll('.view-note-btn').forEach(button => {
    button.addEventListener('click', function () {
        const noteId = this.getAttribute('data-id');
        fetchNote(noteId);
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const navLinks = document.querySelectorAll('.nav-tabs .nav-link');

    navLinks.forEach(navLink => {
        navLink.addEventListener('click', function (event) {
            event.preventDefault();

            const noteId = this.getAttribute('href').replace('#note-', '');
            const isPasswordProtected = this.getAttribute('data-password-protected') === 'true';

            if (isPasswordProtected) {
                // Show password modal
                const passwordModal = new bootstrap.Modal(document.getElementById('passwordModal'));
                document.getElementById('modalNoteId').value = noteId; // Pass note ID to the modal
                passwordModal.show();
            } else {
                // Fetch and display non-password-protected note
                fetchNoteContent(noteId);
            }
        });
    });
});

// Handle password form submission
document.getElementById('passwordForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const noteId = document.getElementById('modalNoteId').value;
    const notePassword = document.getElementById('notePassword').value;

    fetch('noteManagement.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=fetchNote&note_id=${encodeURIComponent(noteId)}&password=${encodeURIComponent(notePassword)}`,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Display the note content
                const noteContent = document.querySelector(`#note-${noteId} .noteArea`);
                noteContent.innerHTML = `<p>${data.note.content}</p>`;

                // Activate the tab
                const tabTrigger = document.querySelector(`a.nav-link[href="#note-${noteId}"]`);
                const tab = new bootstrap.Tab(tabTrigger);
                tab.show();

                // Hide the password modal
                const passwordModal = bootstrap.Modal.getInstance(document.getElementById('passwordModal'));
                passwordModal.hide();
            } else {
                alert(data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while accessing the note.');
        });
});


function fetchNoteContent(noteId) {
    fetch('noteManagement.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=fetchNote&note_id=${encodeURIComponent(noteId)}`,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const noteContent = document.querySelector(`#note-${noteId} .noteArea`);
                noteContent.innerHTML = `<p>${data.note.content}</p>`;

                // Activate the tab
                const tabTrigger = document.querySelector(`a.nav-link[href="#note-${noteId}"]`);
                const tab = new bootstrap.Tab(tabTrigger);
                tab.show();
            } else {
                alert(data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while fetching the note.');
        });
}

// grid list view function
function grid_list_view() {
    var list = document.getElementById("list-view");
    var grid = document.getElementById("grid-view");
    var items = document.getElementById("note-items");
    var gridIcon = document.querySelector('.grid-list-view .grid-icon');
    var listIcon = document.querySelector('.grid-list-view .list-icon');

    // Use computed style for robust detection
    var gridVisible = window.getComputedStyle(grid).display !== "none";

    if (gridVisible) {
        // Switch to list view
        grid.style.display = "none";
        list.style.display = "block";
        items.style.display = "block";
        gridIcon.style.display = "none";
        listIcon.style.display = "inline-block";
    } else {
        // Switch to grid view
        grid.style.display = "block";
        list.style.display = "none";
        items.style.display = "none";
        gridIcon.style.display = "inline-block";
        listIcon.style.display = "none";
    }
}

function switchNote() {
    var grid = document.getElementById("grid-view");
    var items = document.getElementById("note-items");
    if (grid.style.display === "block" && items.style.display === "none") {
      items.style.display = "block";
      grid.style.display = "none";
    } else {
      items.style.display = "none";
      grid.style.display = "block";
    }
}

// grid list view function

$(document).on('click', '.pin-btn', function (e) {
    e.preventDefault();
    e.stopPropagation();

    const btn = $(this);
    const noteId = btn.data("id");
    const currentlyPinned = btn.data("pinned") == 1 ? 1 : 0;
    const newPinState = currentlyPinned ? 0 : 1;

    console.log("Pin button clicked. Note ID:", noteId, "Current:", currentlyPinned, "New:", newPinState);

    $.ajax({
        url: "update_pin.php",
        method: "POST",
        dataType: "json",
        data: {
            noteId: noteId,
            pinState: newPinState
        },
        success: function(response) {
            console.log("Pin AJAX response:", response);
            if (response.success) {
                location.reload();
            } else {
                alert('Failed to update pin state: ' + (response.error || 'Unknown error'));
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX error:", error, xhr.responseText);
            alert('Failed to update pin state.');
        }
    });
});