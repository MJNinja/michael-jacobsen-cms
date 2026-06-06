    </div>
    <!-- END MAIN CONTENT-->

<div class="footer" align="center">
    	<?php echo $cms_name; ?> <?php echo $cms_version; ?> | Copyright &copy; <?php if(date('Y') == $cms_year){ echo $cms_year; }else{ echo $cms_year.' - '.date('Y'); }?>
    </div>

</div>

<?php require_once("javascript-inc.php"); ?>

</body>
</html>
