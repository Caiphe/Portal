/*
*Create team/company js
*/

document.addEventListener('DOMContentLoaded', () => {
    const emailInput = document.getElementById('email-input');
    const emailContainer = document.getElementById('email-input-container');
    const tagsContainer = document.getElementById('tags-container');
    const inviteButton = document.getElementById('invite-button');
    const teamForm = document.getElementById('create-team');
    const emailListInput = document.getElementById('email-list');
    const teamOwnerInput = document.getElementById('team-owner');
    let isFirstEmailSet = false;

    // Initially disable the team owner input and set background to gray
    teamOwnerInput.disabled = true;
    teamOwnerInput.style.backgroundColor = '#f2f2f2';

    emailInput.addEventListener('keyup', function (event) {
        if (event.key === 'Enter' || event.key === ',') {
            const emails = this.value.split(',');
            emails.forEach(email => {
                if (validateEmail(email.trim())) {
                    createEmailTag(email.trim());
                }
            });
            this.value = '';
        }
    });

    emailContainer.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-tag')) {
            const tag = event.target.parentElement;
            removeEmailTag(tag);
        }
    });

    tagsContainer.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-tag')) {
            const tag = event.target.parentElement;
            removeEmailTag(tag);
        }
    });

    inviteButton.addEventListener('click', function () {
        const emails = emailInput.value.split(',');
        emails.forEach(email => {
            if (validateEmail(email.trim())) {
                createEmailTag(email.trim());
                if (!isFirstEmailSet) {
                    setTeamOwner(email.trim());
                    isFirstEmailSet = true;
                }
            }
        });
        emailInput.value = '';
        updateHiddenInput();
    });

    teamForm.addEventListener('submit', function (event) {
        event.preventDefault();
        const errors = validateForm(this); // Use 'this' or 'teamForm' to refer to the form element
        if (errors.length === 0) {
            updateHiddenInput();
            submitForm();
        } else {
            handleFormErrors(errors);
        }
    });

    function validateForm(form) {
        const errors = [];
        const teamName = form['name'].value;
        const urlValue = form['url'].value;
        const contactNumber = form['contact'].value;
        const phoneRegex = /^\+|0(?:[0-9] ?){6,14}[0-9]$/;
        const teamOwner = form['team_owner'].value;

        if (teamName === '') errors.push("Team name required");
        if (urlValue === '') errors.push("Team URL required");
        if (urlValue !== '' && !/https?:\/\/.*\..*/.test(urlValue)) errors.push('Please add a valid team URL. Eg. https://url.com');
        if (contactNumber === '') errors.push("Contact number required");
        if (contactNumber !== '' && !phoneRegex.test(contactNumber)) errors.push("Valid phone number required");
        if (form['country'].value === '') errors.push("Country required");
        if (teamOwner === '') errors.push("Team owner is required");

        return errors;
    }

    function createEmailTag(email) {
        if (!isEmailTagExists(email)) {
            const tag = document.createElement('div');
            tag.classList.add('email-tag');
            tag.innerHTML = `<span>${email}</span><span class="remove-tag">×</span>`;
            tagsContainer.appendChild(tag);
            updateHiddenInput();

            // Enable team owner input after the first email tag is created
            if (!isFirstEmailSet) {
                teamOwnerInput.disabled = false;
            }
        } else {
            addAlert('warning', [`Email ${email} already exists.`]);
        }
    }

    function isEmailTagExists(email) {
        const tags = document.querySelectorAll('.email-tag span:first-child');
        return Array.from(tags).some(tag => tag.textContent === email);
    }

    function removeEmailTag(tag) {
        const email = tag.querySelector('span:first-child').textContent;
        tag.remove();
        if (teamOwnerInput.value === email) {
            teamOwnerInput.value = '';
            teamOwnerInput.readOnly = false;
            teamOwnerInput.style.backgroundColor = '#f2f2f2';
            isFirstEmailSet = false;
            updateTeamOwner();
        }
        updateHiddenInput();
    }

    function updateHiddenInput() {
        const tags = document.querySelectorAll('.email-tag span:first-child');
        const emails = Array.from(tags).map(tag => tag.textContent);
        emailListInput.value = emails.join(',');
    }

    function setTeamOwner(email) {
        teamOwnerInput.value = email;
        teamOwnerInput.readOnly = true;
        teamOwnerInput.style.backgroundColor = '#f2f2f2';
    }

    function updateTeamOwner() {
        const tags = document.querySelectorAll('.email-tag span:first-child');
        if (tags.length > 0) {
            const firstEmail = tags[0].textContent;
            setTeamOwner(firstEmail);
            isFirstEmailSet = true;
        }
    }

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function submitForm() {
        const formData = new FormData(teamForm);
        const xhr = new XMLHttpRequest();

        xhr.open('POST', teamForm.action, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('input[name="_token"]').value);

        xhr.onload = function () {
            removeLoading();

            if (xhr.status === 200) {
                addAlert('success', [`${formData.get('name')} has been successfully created. You will be redirected to your teams page shortly.`], function () {
                    window.location.href = "/teams";
                });
            } else if (xhr.status === 429) {
                addAlert('warning', ["You are not allowed to create more than 2 teams per day."], function () {
                    window.location.href = "/teams";
                });
            } else if (xhr.status === 413) {
                addAlert('warning', ["The logo dimensions are too large, please make sure the width and height are less than 2000."]);
            } else {
                const result = xhr.responseText ? JSON.parse(xhr.responseText) : null;
                const messages = result && result.errors ? Object.values(result.errors).flat() : ['Sorry, there was a problem creating your team. Please try again.'];
                addAlert('error', messages);
            }
        };

        xhr.onerror = function () {
            removeLoading();
            addAlert('error', ['Sorry, there was a problem creating your team. Please try again.']);
        };

        addLoading('Creating a new team...');
        xhr.send(formData);
    }

    function handleFormErrors(errors) {
        const errorMessages = errors.map(error => `<p>${error}</p>`).join('');
        addAlert('error', [errorMessages]);
    }
});
