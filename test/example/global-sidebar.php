<li class="<?php echo ((pathinfo(basename($_SERVER['REQUEST_URI']), PATHINFO_FILENAME) == "about")?'color-active':'')?>"><a href="about.php"><?php echo Core::lang('about_us')?></a></li>
<li class="<?php echo ((pathinfo(basename($_SERVER['REQUEST_URI']), PATHINFO_FILENAME) == "dmca")?'color-active':'')?>"><a href="dmca.php">DMCA</a></li>
<li class="<?php echo ((pathinfo(basename($_SERVER['REQUEST_URI']), PATHINFO_FILENAME) == "terms")?'color-active':'')?>"><a href="terms.php"><?php echo Core::lang('terms')?></a></li>
<li class="<?php echo ((pathinfo(basename($_SERVER['REQUEST_URI']), PATHINFO_FILENAME) == "privacy")?'color-active':'')?>"><a href="privacy.php"><?php echo Core::lang('privacy')?></a></li>