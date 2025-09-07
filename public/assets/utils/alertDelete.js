function confirmDeleteAttr(element) {
        const id = element.getAttribute('data-id');
        const dialogTitle = element.getAttribute('data-dialogTitle');
        const dialogMessage = element.getAttribute('data-dialogMessage');
        const dialogUriToProcess = element.getAttribute('data-dialogUriToProcess');
        const dialogUriToRedirect = element.getAttribute('data-dialogUriToRedirect');
        const dialogConfirmButton = element.getAttribute('data-dialogConfirmButton');
        const dialogCancelButton = element.getAttribute('data-dialogCancelButton');
        const dialogErrorMessage = element.getAttribute('data-dialogErrorMessage');
        const dialogErrorTitle = element.getAttribute('data-dialogErrorTitle');
        const dialogCancelTitle = element.getAttribute('data-dialogCancelTitle');
        const dialogCancelMessage = element.getAttribute('data-dialogCancelMessage');
        const dialogSuccessTitle = element.getAttribute('data-dialogSuccessTitle');

        const dialogProcessTitle = element.getAttribute('data-dialogProcessTitle');
        const dialogProcessMessage = element.getAttribute('data-dialogProcessMessage');

        confirmDelete(id, dialogTitle, dialogMessage, dialogUriToProcess, dialogUriToRedirect, dialogConfirmButton, dialogCancelButton, dialogErrorMessage, dialogErrorTitle, dialogCancelTitle, dialogCancelMessage, dialogSuccessTitle, dialogProcessTitle, dialogProcessMessage);
    }
    function confirmDelete(id, dialogTitle, dialogMessage, dialogUriToProcess, dialogUriToRedirect, dialogConfirmButton, dialogCancelButton, dialogErrorMessage, dialogErrorTitle, dialogCancelTitle, dialogCancelMessage, dialogSuccessTitle, dialogProcessTitle, dialogProcessMessage) {
        Swal.fire({
            title: dialogTitle,
            text: dialogMessage,
            icon: 'warning',
            showDenyButton: true,
            confirmButtonText: dialogConfirmButton,
            denyButtonText: dialogCancelButton,
            confirmButtonColor: " #39afd1",
            denyButtonColor: " #fa5c7c",
            background: "#ffffffff",
            color: "#3b3b3bff",
            width: '420px',
            customClass: {
                title: 'swal-title',
                content: 'swal-content',
                confirmButton: 'swal-confirm-btn',
                denyButton: 'swal-deny-btn',
                htmlContainer: 'swal-text'
            }
        }).then((result) => {
            if (result.isConfirmed) {

                // Mostrar carregando antes do AJAX
                Swal.fire({
                    title: dialogProcessTitle,
                    text: dialogProcessMessage,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    background: "#ffffffff",
                    color: "#3b3b3bff",
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: dialogUriToProcess,
                    type: "POST",
                    data: { id: id },
                    dataType: "json", 
                    success: function (jsonResponse) {
                        if (jsonResponse.success) {
                            Swal.fire({
                                title: dialogSuccessTitle,
                                text: jsonResponse.message,
                                icon: 'success',
                                width: '420px',
                                confirmButtonColor: "#39afd1",
                                background: "#ffffffff",
                                color: "#3b3b3bff",
                                customClass: {
                                    title: 'swal-title',
                                    content: 'swal-content',
                                    htmlContainer: 'swal-text',
                                    confirmButton: 'swal-confirm-btn'
                                }
                            }).then(() => {
                                window.location.href = dialogUriToRedirect;
                            });
                        } else {
                            Swal.fire({
                                title: dialogErrorTitle,
                                text: jsonResponse.message || dialogErrorMessage,
                                icon: 'error',
                                width: '420px',
                                confirmButtonColor: "#39afd1",
                                background: "#ffffffff",
                                color: "#3b3b3bff",
                                customClass: {
                                    title: 'swal-title',
                                    content: 'swal-content',
                                    htmlContainer: 'swal-text',
                                    confirmButton: 'swal-confirm-btn'
                                }
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            title: dialogErrorTitle,
                            text: dialogErrorMessage,
                            icon: 'error',
                            width: '420px',
                            confirmButtonColor: "#39afd1",
                            background: "#ffffffff",
                            color: "#3b3b3bff",
                            customClass: {
                                title: 'swal-title',
                                content: 'swal-content',
                                htmlContainer: 'swal-text',
                                confirmButton: 'swal-confirm-btn'
                            }
                        });
                    }
                });
            } else if (result.isDenied) {
                Swal.fire({
                    title: dialogCancelTitle,
                    text: dialogCancelMessage,
                    icon: 'info',
                    width: '420px',
                    confirmButtonColor: "#39afd1",
                    background: "#ffffffff",
                    color: "#3b3b3bff"
                });
            }
        });
    }