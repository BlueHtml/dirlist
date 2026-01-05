<?php
if(!defined('GEN_INIT'))exit();
?>
<footer class="footer card-footer mt-3" id="footer">
    <span> Copyright &copy;<?php echo date('Y')?> </span>
</footer>

<script src="<?php echo $cdnpublic?>jquery/3.6.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>popper.js/1.16.1/umd/popper.min.js"></script>
<script src="<?php echo $cdnpublic?>twitter-bootstrap/4.6.1/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic?>jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/3.1.1/layer.js"></script>
<script src="<?php echo $static_path; ?>js/clipBoard.min.js"></script>
<script src="<?php echo $static_path; ?>js/main.js?v=<?php echo time(); ?>"></script>
</body>
</html>
