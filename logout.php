<?php
session_start();
session_destroy();
?>
<script type="text/javascript">
// Redirect to Homepage
    var homeLocation = window.location.protocol + "//" +
        window.location.hostname + "/kd_ranking/index.php";
    window.location = homeLocation;
</script>
