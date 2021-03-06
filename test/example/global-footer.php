<!-- footer -->
<footer>
    <div class="container-fluid">
        <div class="row">
            <div class="container padding-def">
                <div class="col-lg-1  col-sm-2 col-xs-12 footer-logo">
                    <a class="navbar-brand" id="gotop" href="#" title="Go Top"><img src="<?php echo Core::getInstance()->homepath?>/images/logo.svg" alt="<?php echo Core::lang('search_desc_1').' '.Core::getInstance()->title;?>" class="logo" /></a>
                </div>
                <div class="col-lg-7  col-sm-7 col-xs-12">
                    <div class="f-copy">
                        <ul class="list-inline">
                            <li><a href="<?php echo Core::getInstance()->homepath?>" title="<?php echo Core::getInstance()->title?>"><?php echo Core::getInstance()->title.'</a> | '.Core::getInstance()->description.''?></li>
                        </ul>
                    </div>
                    <div class="delimiter"></div>
                    <div class="f-copy">
                        <ul class="list-inline">
                            <li><a href="<?php echo Core::getInstance()->homepath?>/about.php" title="<?php echo Core::lang('about')?>"><?php echo Core::lang('about')?></a></li>
                            <li><a href="<?php echo Core::getInstance()->homepath?>/contact.php" title="<?php echo Core::lang('contact')?>"><?php echo Core::lang('contact')?></a></li>
                            <li>Copyrights @ <?php echo Date('Y');?>, <a href="<?php echo Core::getInstance()->homepath;?>" title="<?php echo $_SERVER['SERVER_NAME'];?>"><?php echo $_SERVER['SERVER_NAME'];?></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-offset-1 col-lg-3 col-sm-3 col-xs-12 hidden-xs">
                    <div class="f-icon pull-left">
                        <a href="<?php echo (empty(Core::getInstance()->facebook)?'#':Core::getInstance()->facebook);?>" title="Facebook Page"><i class="fa fa-facebook-square"></i></a>
                        <a href="<?php echo (empty(Core::getInstance()->twitter)?'#':Core::getInstance()->twitter);?>" title="Twitter Page"><i class="fa fa-twitter"></i></a>
                        <a href="<?php echo (empty(Core::getInstance()->gplus)?'#':Core::getInstance()->gplus);?>" title="Google Plus Page"><i class="fa fa-google-plus"></i></a>
                    </div>
                    <div class="f-lang pull-right">
                       <h6 class="color-active"><div id="totalvideo"></div></h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- /footer -->