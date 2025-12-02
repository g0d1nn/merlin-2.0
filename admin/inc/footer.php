        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="js/sweetalert2.min.js"></script>
        <script src="js/avisos.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const currentPage = window.location.pathname.split('/').pop();
                const menuLinks = document.querySelectorAll('header nav li a');
                
                menuLinks.forEach(link => {
                    const linkPage = link.getAttribute('href');
                    if (currentPage === linkPage || 
                        (currentPage === '' && linkPage === 'admin.php') ||
                        (currentPage.includes('gestaoUsuario') && linkPage.includes('gestaoUsuario')) ||
                        (currentPage.includes('gestaoVideos') && linkPage.includes('gestaoVideos')) ||
                        (currentPage.includes('adicionarUsuario') && linkPage.includes('gestaoUsuario')) ||
                        (currentPage.includes('editarUsuario') && linkPage.includes('gestaoUsuario')) ||
                        (currentPage.includes('adicionarVideo') && linkPage.includes('gestaoVideos')) ||
                        (currentPage.includes('editarVideo') && linkPage.includes('gestaoVideos'))) {
                        link.classList.add('active');
                    }
                });
            });
        </script>
        <footer>
            <p>&copy; 2025 Merlin. Todos os direitos reservados.</p>
        </footer>
    </body>
</html>