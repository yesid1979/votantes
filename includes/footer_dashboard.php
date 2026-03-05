            </div> <!-- End container-fluid -->
            
            <footer class="footer-dashboard">
                <div class="row align-items-center justify-content-between">
                    <div class="col-md-6 text-start">
                        <small>Copyright &copy; Sistema Votantes 2026</small>
                    </div>
                    <div class="col-md-6 text-end">
                        <small>Versión 3.0</small>
                    </div>
                </div>
            </footer>
            
        </div> <!-- End page-content-wrapper -->
    </div> <!-- End main-wrapper -->
</div> <!-- End dashboard-container -->

<!-- JQuery (Required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script> 

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>

<!-- SweetAlert2 (If used) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Toggle Sidebar Script -->
<script>
    var menuToggle = document.getElementById("menu-toggle");
    var overlay = document.getElementById("overlay");
    var body = document.body;

    if (menuToggle) {
        menuToggle.addEventListener("click", function(e) {
            e.preventDefault();
            body.classList.toggle("sb-sidenav-toggled");
        });
    }

    if (overlay) {
        overlay.addEventListener("click", function(e) {
            body.classList.remove("sb-sidenav-toggled");
        });
    }
</script>

</body>
</html>
