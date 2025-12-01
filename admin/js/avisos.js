

function avisoExcluirUsuario(id) {
    Swal.fire({
        title: 'Tem certeza que quer excluir?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não',
        scrollbarPadding: false,
        heightAuto: false,
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'excluirUsuario.php?id_usuario=' + id;
        }
    })
}

function avisoExcluirVideo(id) {
    Swal.fire({
        title: 'Tem certeza que quer excluir?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não',
        scrollbarPadding: false,
        heightAuto: false,
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'excluirVideo.php?id_videoaula=' + id;
        }
    })
}