</main>
<div id="address-restaurant">
    <iframe src="<?php echo getDatabaseSettingsDeliveryAddressAPI(1) ?>" width="90%" height="90%" style="
        margin: auto;
    position: relative;
    top: 15px;
    display: table; border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
<!-- Footer-->
<footer class="footer py-4">
    <div class="container">
        <div class="row align-items-center">
            <span>StatsHub Copyright 2024 &copy;</span>
            <div class="col-lg-4 my-3 my-lg-0">
                <?php
                if (isDatabaseSettingsSocialWhatsappEnabled(1)) {
                    ?>

                    <a href="https://api.whatsapp.com/send?phone=<?php echo getDatabaseSettingsSocialWhatsappInfo(1) ?>" aria-label="Whatsapp"><i class="fab fa-whatsapp"></i></a>
                    <?php
                }
                if (isDatabaseSettingsSocialFacebookEnabled(1)) {
                    ?>
                    <a href="https://www.facebook.com/<?php echo getDatabaseSettingsSocialFacebookInfo(1) ?>" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <?php
                }
                if (isDatabaseSettingsSocialInstagramEnabled(1)) {
                    ?>
                    <a href="https://instagram.com/<?php echo getDatabaseSettingsSocialInstagramInfo(1) ?>" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <?php
                }
                ?>
            </div>
            <div>
                <a href="#" target="_blank">Politica de
                    Privacidade</a>
                <a href="#" target="_blank">Termos de Uso</a>
            </div>
        </div>
    </div>
</footer>

</body>
<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>