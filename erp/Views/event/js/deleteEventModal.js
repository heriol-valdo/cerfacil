// Apparition/disparition modal en fonction clic delete
$('#deleteButton').click(function () {
    $('#eventInfoModal').modal('hide');
    previousModal = $('#eventInfoModal');

    var recurrenceId = $(this).data('recurrenceId');
    if (recurrenceId) {
        $('#deleteMessage').hide();
        $('#recurrenceOptions').show();
        $('#confirmDelete').hide();
    } else {
        $('#deleteMessage').show();
        $('#recurrenceOptions').hide();
        $('#confirmDelete').show();
    }

    $('#deleteConfirmationModal').modal('show');
});

$('#deleteConfirmationModal .btn-secondary').click(function () {
    $('#deleteConfirmationModal').modal('hide');

    if (previousModal) {
        previousModal.modal('show');
    }
});

// Suppression event
$('#confirmDelete').click(async function () {
    var eventId = $('#deleteButton').data('eventId');
    const result = await deleteElement('deleteEvent', 'default', eventId, '');
    showToast(result.success, result.message, result.success ? "Suppression réussie" : "Erreur lors de la suppression");
    if (result.success) {
        setTimeout(() => {
            let queryParams = [];
            if (selectedUser) {
                queryParams.push(`selectedUser=${selectedUser}`);
            }
            if (selectedSession) {
                queryParams.push(`selectedSession=${selectedSession}`);
            }
            if (centreId) {
                queryParams.push(`centreId=${centreId}`);
            }

            const queryString = queryParams.length ? `?${queryParams.join('&')}` : '';

            window.location.href = window.location.pathname + queryString;
        }, 500);
    }
    $('#deleteConfirmationModal').modal('hide');
});

$('#deleteAll').click(async function () {
    var eventId = $('#deleteButton').data('eventId');
    var recurrenceId = $('#deleteButton').data('recurrenceId');
    const result = await deleteElement('deleteEvent', 'all', eventId, recurrenceId);
    showToast(result.success, result.message, result.success ? "Suppression réussie" : "Erreur lors de la suppression");
    if (result.success) {
        setTimeout(() => {
            let queryParams = [];
            if (selectedUser) {
                queryParams.push(`selectedUser=${selectedUser}`);
            }
            if (selectedSession) {
                queryParams.push(`selectedSession=${selectedSession}`);
            }
            if (centreId) {
                queryParams.push(`centreId=${centreId}`);
            }

            const queryString = queryParams.length ? `?${queryParams.join('&')}` : '';

            window.location.href = window.location.pathname + queryString;
        }, 500);
    }
    $('#deleteConfirmationModal').modal('hide');
});

$('#deleteAfter').click(async function () {
    var eventId = $('#deleteButton').data('eventId');
    var recurrenceId = $('#deleteButton').data('recurrenceId');
    const result = await deleteElement('deleteEvent', 'after', eventId, recurrenceId);
    showToast(result.success, result.message, result.success ? "Suppression réussie" : "Erreur lors de la suppression");
    if (result.success) {
        setTimeout(() => {
            let queryParams = [];
            if (selectedUser) {
                queryParams.push(`selectedUser=${selectedUser}`);
            }
            if (selectedSession) {
                queryParams.push(`selectedSession=${selectedSession}`);
            }
            if (centreId) {
                queryParams.push(`centreId=${centreId}`);
            }

            const queryString = queryParams.length ? `?${queryParams.join('&')}` : '';

            window.location.href = window.location.pathname + queryString;
        }, 500);
    }
    $('#deleteConfirmationModal').modal('hide');
});


$('#deleteDefault').click(async function () {
    var eventId = $('#deleteButton').data('eventId');
    const result = await deleteElement('deleteEvent', 'default', eventId, '');
    showToast(result.success, result.message, result.success ? "Suppression réussie" : "Erreur lors de la suppression");
    if (result.success) {
        setTimeout(() => {
            let queryParams = [];
            if (selectedUser) {
                queryParams.push(`selectedUser=${selectedUser}`);
            }
            if (selectedSession) {
                queryParams.push(`selectedSession=${selectedSession}`);
            }
            if (centreId) {
                queryParams.push(`centreId=${centreId}`);
            }

            const queryString = queryParams.length ? `?${queryParams.join('&')}` : '';

            window.location.href = window.location.pathname + queryString;
        }, 500);
    }
    $('#deleteConfirmationModal').modal('hide');
});


async function deleteElement(action, type, eventId, recurrenceId) {
    try {
        const response = await fetch("../../controller/Event/eventActionController.php", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: action,
                type: type,
                event_id: eventId,
                recurrence_id: recurrenceId
            }),
        });
        const result = await response.json();
        if (result.erreur) {
            return { success: false, message: result.erreur };
        } else if (result.valid) {
            return { success: true, message: result.valid };
        } else {
            return { success: false, message: 'An unknown error occurred.' };
        }
    } catch (error) {
        console.error("Network error: ", error);
        return { success: false, message: 'A network error occurred.' };
    }
}