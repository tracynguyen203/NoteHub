document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.nav-tabs .nav-link');
    const left = document.querySelector('.left');
    const right = document.querySelector('.right');
    const expandCollapseButton = document.querySelector('.expand-collapse-button');
    const expandIcon = document.querySelector('.expand-icon');
    const collapseIcon = document.querySelector('.collapse-icon');
    let isExpanded = window.innerWidth >= 768; 

    function updateLayout() {
        if (window.innerWidth < 768) {
            if (isExpanded) {
                left.classList.add('collapsed');
                right.classList.add('expanded');
                right.style.display = 'block';
                if (expandIcon && collapseIcon) {
                    expandIcon.style.display = 'none';
                    collapseIcon.style.display = 'inline-block'; 
                }
            } else {
                left.classList.remove('collapsed');
                right.classList.remove('expanded');
                right.style.display = 'none';
                if (expandIcon && collapseIcon) {
                    expandIcon.style.display = 'inline-block';
                    collapseIcon.style.display = 'none';
                }
            }
        } else {
            if (isExpanded) {
                left.classList.remove('collapsed');
                right.classList.remove('expanded');
                right.style.display = 'flex';
                if (expandIcon && collapseIcon) {
                    expandIcon.style.display = 'inline-block'; 
                    collapseIcon.style.display = 'none';
                }
            } else {
                left.classList.add('collapsed');
                right.classList.add('expanded');
                right.style.display = 'block';
                if (expandIcon && collapseIcon) {
                    expandIcon.style.display = 'none';
                    collapseIcon.style.display = 'inline-block'; 
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

    if (expandCollapseButton) {
        expandCollapseButton.addEventListener('click', toggleAside);
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

    if (window.innerWidth < 768 && expandIcon && collapseIcon) {
        expandIcon.style.display = 'none';
        collapseIcon.style.display = 'inline-block';
        isExpanded = false;
    } else if (window.innerWidth >= 768 && expandIcon && collapseIcon) {
        expandIcon.style.display = 'inline-block';
        collapseIcon.style.display = 'none';
        isExpanded = true;
    }

    const hash = window.location.hash;
    if (hash) {
        const targetTabLink = document.querySelector(`.nav-link[href="${hash}"]`);
        if (targetTabLink) {
            const tab = new bootstrap.Tab(targetTabLink);
            tab.show();

            setTimeout(() => {
                window.scrollTo({ top: 0, behavior: 'instant' });
            }, 10);
        }
    }
});

$(function() {
    // Avatar selection logic with animation
    $(document).on('click', '.avatar-choice', function() {
        $('.avatar-choice').removeClass('selected');
        $(this).addClass('selected');
        $('#selected-avatar').val($(this).data('avatar-url'));
    });

    // Change avatar submit
    $('#change-avatar-form').on('submit', function(e) {
        e.preventDefault();
        const avatarUrl = $('#selected-avatar').val();
        if (!avatarUrl) {
            alert('Please select an avatar.');
            return;
        }
        $.post('settings_actions.php', {
            action: 'change_avatar',
            avatar: avatarUrl
        }, function(res) {
            let data = JSON.parse(res);
            if (data.success) {
                $('#current-avatar').attr('src', avatarUrl);
                alert('Avatar changed!');
            } else {
                alert(data.error || 'Failed to change avatar.');
            }
        });
    });

    $(document).on('submit', '#font-color-form', function(e) {
        e.preventDefault();
        console.log("Handler attached");
        alert("Submitting...");
        const font = $(this).find('select[name="font"]').val();
        const color = $(this).find('select[name="color"]').val();
        console.log('Attempting to update font/color:', { font, color });

        $.ajax({
            url: 'settings_actions.php',
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'change_font_color',
                font: font,
                color: color
            },
            success: function(response) {
                console.log('AJAX response:', response);
                if (response.success) {
                    alert('Settings updated successfully! Reloading...');
                    location.reload();
                } else {
                    alert('Error: ' + response.error);
                }
            },
            error: function(xhr) {
                let error = 'Unknown error';
                try {
                    error = xhr.responseJSON ? xhr.responseJSON.error : xhr.responseText;
                } catch (e) {}
                alert('Error: ' + error);
            }
        });
    });
});