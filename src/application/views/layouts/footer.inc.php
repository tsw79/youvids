<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 2/28/2019
 * Time: 09:03
 */
?>
    <!-- Footer -->
    <div class="footer">
      <div>Youvids.com</div>
    </div>

    <!-- Scripts -->
    <script type="text/javascript" src="<?= WEB_ROOT ?>/js/jquery/v3.1.1/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="<?= WEB_ROOT ?>/js/popper/v1.15/popper.min.js"></script>
    <script type="text/javascript" src="<?= WEB_ROOT ?>/js/bootstrap/v4.3.1/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= WEB_ROOT ?>/js/userActions.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <script>
      feather.replace();

      /* Sidenav show|hide control */
      var sidenav = $("#sideNavContainer");
      var main = $("#mainSectionContainer");
      var sidenavIsOpen = localStorage.getItem('sidenav.isOpen'); 

      if (sidenavIsOpen == null || sidenavIsOpen == 1) {
        sidenav.show();
        main.addClass("leftPadding");
        localStorage.setItem('sidenav.isOpen', 1);
      }
      else {
        sidenav.hide();
        main.removeClass("leftPadding");
        localStorage.setItem('sidenav.isOpen', 0);
      }
    </script>
    
    <script type="text/javascript" src="<?= WEB_ROOT ?>/js/common.js"></script>