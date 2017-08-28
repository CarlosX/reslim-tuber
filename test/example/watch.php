<?php include 'backend/Core.php'; 
    //Validation url param
    $postid = filter_var((empty($_GET['movie'])?'':$_GET['movie']),FILTER_SANITIZE_STRING);
    //Data video
    $url = Core::getInstance()->api.'/video/post/data/read/'.$postid.'/?apikey='.Core::getInstance()->apikey;
    $data = json_decode(Core::execGetRequest($url));
    //Data adblock1 (sidebar)
    $urlsidebar = Core::getInstance()->api.'/ads/data/show/sidebar/?apikey='.Core::getInstance()->apikey;
    $datasidebar = json_decode(Core::execGetRequest($urlsidebar));
    //Data adblock2 (footer)
    $urlfooter = Core::getInstance()->api.'/ads/data/show/footer/?apikey='.Core::getInstance()->apikey;
    $datafooter = json_decode(Core::execGetRequest($urlfooter));
    //Data adblock3 (content)
    $urlcontent = Core::getInstance()->api.'/ads/data/show/content/?apikey='.Core::getInstance()->apikey;
    $datacontent = json_decode(Core::execGetRequest($urlcontent));

    //Data twitter
    if (!empty(Core::getInstance()->twitter)){
        $twittersite = Core::getInstance()->twitter;
        $twitterarray = explode('/',$twittersite);
        $twitterusername = end($twitterarray);
    }

    if (!empty($data)){
        if ($data->{'status'} == "success"){
            $title = $data->result[0]->{'Title'};
            $description = $data->result[0]->{'Description'};
            $keyword = Core::lang('watch_keyword').', '.$data->result[0]->{'Title'}.', '.$data->result[0]->{'Tags_inline'};
            $author = $data->result[0]->{'User'};
        } else {
            $title = '';
            $description = '';
            $keyword = '';
            $author = '';
            Core::goToPageFrontend('index.php');
        }
    } else {
        $title = '';
        $description = '';
        $keyword = '';
        $author = '';
        Core::goToPageFrontend('index.php');
    }
?>

<!DOCTYPE html>
<html lang="<?php echo Core::getInstance()->setlang?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="<?php echo $description;?>">
    <meta name="keyword" content="<?php echo $keyword;?>">
    <meta name="author" content="<?php echo $author;?>">
    <?php
        if (!empty($data)){
            if ($data->{'status'} == "success"){
                echo '<!-- Open Graphs -->
                    <link rel="author" href="'.((!empty(Core::getInstance()->gplus))?Core::getInstance()->gplus:'').'"/>
                    <link rel="publisher" href="'.((!empty(Core::getInstance()->gpub))?Core::getInstance()->gpub:'').'"/>
                    <meta itemprop="name" content="'.$data->result[0]->{'Title'}.'">
                    <meta itemprop="description" content="'.$data->result[0]->{'Description'}.'">
                    <meta itemprop="image" content="'.$data->result[0]->{'Image'}.'">
                	<meta name="twitter:card" content="summary_large_image" />
                	<meta name="twitter:title" content="'.$data->result[0]->{'Title'}.'" />
                	<meta name="twitter:description" content="'.$data->result[0]->{'Description'}.'" />
                    <meta name="twitter:image" content="'.$data->result[0]->{'Image'}.'" />
                    <meta name="twitter:image:alt" content="'.$data->result[0]->{'Title'}.'" />
                    <meta name="twitter:site" content="'.((!empty(Core::getInstance()->twitter))?'@'.$twitterusername:'').'">
                    <meta name="twitter:creator" content="'.((!empty(Core::getInstance()->twitter))?'@'.$twitterusername:'').'">
                    <meta property="og:title" content="'.$data->result[0]->{'Title'}.'" />
                    <meta property="og:description" content="'.$data->result[0]->{'Description'}.'" />
                    <meta property="og:url" content="'.((Core::isHttpsButtflare()) ? 'https' : 'http') . '://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" />
                    <meta property="og:image" content="'.$data->result[0]->{'Image'}.'" />
                	<meta property="og:site_name" content="'.Core::getInstance()->title.'" />
                    <meta property="og:type" content="video.movie" />
                	<meta property="og:video:release_date" content="'.date_format(date_create($data->result[0]->{'Created_at'}), 'Y-m-d').'" />';
                echo "\n";
                echo '<meta property="og:video:duration" content="'.Core::convertTimeToSeconds($data->result[0]->{'Duration'}).'" />';
                echo "\n";
                foreach ($data->result[0]->{'Director'} as $name => $valuedirector) {
                    echo '<meta property="og:video:director" content="'.$valuedirector.'" />';
                    echo "\n";
                }
                foreach ($data->result[0]->{'Stars'} as $name => $valuestars) {
                    echo '<meta property="og:video:actor" content="'.$valuestars.'" />';
                    echo "\n";
                }
                foreach ($data->result[0]->{'Tags'} as $name => $valuegenre) {
                    echo '<meta property="og:video:tag" content="'.$valuegenre.'" />';
                    echo "\n";
                }
            }
        }
    ?>

    <title><?php echo Core::lang('watch_header')?> <?php echo $title;?> | <?php echo Core::getInstance()->title;?></title>

    <?php include 'global-meta.php';?>
</head>

<body class="single-video dark">
<?php include 'global-header.php';?>

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-xs-12 col-sm-12">
                <?php
                    if (!empty($data)){
                        if ($data->{'status'} == "success"){
                            $totalvideo=0;
                            foreach ($data->result[0]->{'Embed'} as $name => $valuevideo) {
                                $totalvideo++;
                            }
                            if ($totalvideo>1) echo '<h1><a href="'.((Core::isHttpsButtflare()) ? 'https' : 'http') . '://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'">'.Core::lang('watch_header').' '.$title.'</a></h1>';
                            $datavideo = '';
                            $n=1;
                            foreach ($data->result[0]->{'Embed'} as $name => $valuevideo) {
                                if ($totalvideo>1){
                                    $datavideo .= '<button type="button" class="btn btn-danger btn-block" data-toggle="collapse" data-target="#eps'.$n.'">Episode '.$n.' <span class="caret"></span></button>
                                        <div id="eps'.$n.'" class="collapse'.(($n==1)?' in':'').'">
                                            <div class="sv-video">
                                                <div class="video-responsive">
                                                    <div>
                                                        '.$valuevideo.'
                                                        <!-- Remove Comment below to disable click popout google player -->
                                                        <!--<div style="width: 50px; height: 50px; position: absolute; opacity: 0; right: 10px; top: 10px;">&nbsp;</div>-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div><br>';
                                    $n++;
                                } else{
                                    $datavideo .= '<div class="sv-video">
                                            <div class="video-responsive">
                                                <div>
                                                    '.$valuevideo.'
                                                    <!-- Remove Comment below to disable click popout google player -->
                                                    <!--<div style="width: 50px; height: 50px; position: absolute; opacity: 0; right: 10px; top: 10px;">&nbsp;</div>-->
                                                </div>
                                            </div>
                                        </div><br>';
                                }
                            }
                            $datavideo = substr($datavideo, 0, -4);
                            echo $datavideo;

                            //author
                            if ($totalvideo==1) echo '<h1><a href="'.((Core::isHttpsButtflare()) ? 'https' : 'http') . '://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'">'.Core::lang('watch_header').' '.$title.'</a></h1>';
                            echo '<div class="author">
                                    <div class="sv-views">
                                        <div class="sv-views-count">
                                            '.number_format($data->result[0]->{'Viewer'}).''.Core::lang('views').'
                                        </div>
                                        <div class="sv-views-progress">
                                            <div class="sv-views-progress-bar"></div>
                                        </div>
                                        <div class="sv-views-stats text-right">
                                            <span id="totalliked" class="green"><i class="fa fa-thumbs-up iliked"></i> '.number_format($data->result[0]->{'Liked'}).'</span>
                                            <span id="totaldisliked" class="percent"><i class="fa fa-thumbs-down idisliked"></i> '.number_format($data->result[0]->{'Disliked'}).'</span>
                                            
                                        </div>
                                        <div class="sv-views-stats text-right">
                                            <span id="voteresult" class="green"></span>
                                        </div>
                                    </div>
                                    <div class="sv-name">
                                        <div class="adblock3">
                                            <div class="img">
                                                '.((!empty($datacontent) && ($datacontent->{'status'} == "success"))?$datacontent->result[0]->{'Embed'}:''.Core::lang('ads_here').' 468 x 60').'
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>';
                            
                            $datastars = '';
                            if (!empty($data->result[0]->{'Stars'})) {
                                $datastars .= '<h4>'.Core::lang('watch_stars').'</h4>';
                                $datastars .= '<p class="sv-tags">';
                                foreach ($data->result[0]->{'Stars'} as $name => $valuestars) {
                                    $datastars .= '<span><a href="'.Core::getInstance()->homepath.'/index.php?search='.$valuestars.'">'.$valuestars.'</a></span>';
                                }
                                $datastars .= '</p>';
                            }

                            $datacast = '';
                            if (!empty($data->result[0]->{'Cast'})) {
                                $datacast .= '<h4>'.Core::lang('watch_cast').'</h4>';
                                $datacast .= '<p class="sv-tags">';
                                foreach ($data->result[0]->{'Cast'} as $name => $valuecast) {
                                    $datacast .= '<span><a href="'.Core::getInstance()->homepath.'/index.php?search='.$valuecast.'">'.$valuecast.'</a></span>';
                                }
                                $datacast .= '</p>';
                            }

                            $datadirector = '';
                            if (!empty($data->result[0]->{'Director'})) {
                                $datadirector .= '<h4>'.Core::lang('watch_director').'</h4>';
                                $datadirector .= '<p class="sv-tags">';
                                foreach ($data->result[0]->{'Director'} as $name => $valuedirector) {
                                    $datadirector .= '<span><a href="'.Core::getInstance()->homepath.'/index.php?search='.$valuedirector.'">'.$valuedirector.'</a></span>';
                                }
                                $datadirector .= '</p>';
                            }

                            $datacountry = '';
                            if (!empty($data->result[0]->{'Country'})) {
                                $datacountry .= '<h4>'.Core::lang('watch_country').'</h4>';
                                $datacountry .= '<p class="sv-tags">';
                                foreach ($data->result[0]->{'Country'} as $name => $valuecountry) {
                                    $datacountry .= '<span><a href="'.Core::getInstance()->homepath.'/index.php?search='.$valuecountry.'">'.$valuecountry.'</a></span>';
                                }
                                $datacountry .= '</p>';
                            }

                            $datagenre = '';
                            if (!empty($data->result[0]->{'Tags'})) {
                                $datagenre .= '<h4>'.Core::lang('watch_genre').'</h4>';
                                $datagenre .= '<p class="sv-tags">';
                                foreach ($data->result[0]->{'Tags'} as $name => $valuegenre) {
                                    $datagenre .= '<span><a href="'.Core::getInstance()->homepath.'/index.php?search='.$valuegenre.'">'.$valuegenre.'</a></span>';
                                }
                                $datagenre .= '</p>';
                            }

                            //Info Video
                            echo '<div class="info">
                                    
                                    <h4>'.Core::lang('watch_about').'</h4>
                                    <p>'.$data->result[0]->{'Description'}.'</p>

                                    '.$datastars.'
                                    '.$datacast.'
                                    '.$datadirector.'
                                    '.$datacountry.'

                                    <h4>'.Core::lang('watch_release').'</h4>
                                    <p class="sv-tags"><span><a href="'.Core::getInstance()->homepath.'/index.php?search='.$data->result[0]->{'Released'}.'">'.$data->result[0]->{'Released'}.'</a><span></p>

                                    '.$datagenre.'
                                    <br>
                                    <div class="sharethis-inline-share-buttons"></div>
                                    <div class="adblock2">
                                        <div class="img">
                                            '.((!empty($datafooter) && ($datafooter->{'status'} == "success"))?$datafooter->result[0]->{'Embed'}:''.Core::lang('ads_here').'<br>728 x 90').'
                                        </div>
                                    </div>

                    <!-- similar videos -->
                    <div class="caption">
                        <div class="left">
                            <a href="#">'.Core::lang('watch_this_too').'</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="list similar-videos">
                        <div class="row">';
                            if (!empty($data) && ($data->{'status'} == "success")){
                                //Data random movies by it's released year
                                $urlrandomyear = Core::getInstance()->api.'/video/post/data/public/search/random/'.$data->result[0]->{'Released'}.'/1/4/?apikey='.Core::getInstance()->apikey.'&query=';
                                $datarandomyear = json_decode(Core::execGetRequest($urlrandomyear));
                                if (!empty($datarandomyear) && ($datarandomyear->{'status'} == "success")){
                                    $i=1;
                                    foreach ($datarandomyear->results as $name => $valuerandomyear) {
                                        echo '<div class="col-lg-3 col-xs-12 col-sm-6 videoitem">
                                                <div class="b-video">
                                                <div class="v-img">
                                                    <a href="'.Core::getInstance()->homepath.'/'.Core::lang('watch').'/'.$valuerandomyear->{'PostID'}.'/'.Core::convertToSlug($valuerandomyear->{'Title'}).'"><img src="'.$valuerandomyear->{'Image'}.'" class="top-cropped sm" alt="'.$valuerandomyear->{'Title'}.'"></a>
                                                    <div class="rating">'.$valuerandomyear->{'Rating'}.'</div>
                                                    <div class="time">'.$valuerandomyear->{'Duration'}.'</div>
                                                </div>
                                                <div class="v-desc">
                                                    <a href="'.Core::getInstance()->homepath.'/'.Core::lang('watch').'/'.$valuerandomyear->{'PostID'}.'/'.Core::convertToSlug($valuerandomyear->{'Title'}).'">'.Core::cutLongText($valuerandomyear->{'Title'},40).'</a>
                                                </div>
                                                <div class="v-views">';
                                                $datatag = "";
                                                foreach ($valuerandomyear->{'Tags'} as $namegenre => $valuegenre) {
                                                    $datatag .= '<a style="color:#6F6D6D" onMouseOut="this.style.color=\'#6F6D6D\'" onMouseOver="this.style.color=\'#ea2c5a\'" href="'.Core::getInstance()->homepath.'/index.php?search='.$valuegenre.'">'.$valuegenre.'</a>, ';
                                                }
                                                $datatag = substr($datatag, 0, -2);
                                                echo $datatag;
                                                echo '</div>
                                                <div class="v-views">
                                                    '.number_format($valuerandomyear->{'Viewer'}).''.Core::lang('views').'.
                                                </div>
                                            </div>
                                        </div>';
                                        if ($i%4==0){
						                    echo '<div class="clearfix visible-lg-block"></div>';
                    					}
					                    if ($i%2==0){
                    						echo '<div class="clearfix visible-md-block"></div>';
					                    }
                    					$i++;
                                    }
                                }
                            }

                        echo '</div>
                    </div>
                    <!-- END similar videos -->

                    <!-- comments -->
                    <div class="comments">
                        <div id="disqus_thread"></div>
                        <script>

                            /**
                            *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
                            *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/

                            var disqus_config = function () {
                                this.page.url = \''.((Core::isHttpsButtflare()) ? 'https' : 'http') . '://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'\';  // Replace PAGE_URL with your page\'s canonical URL variable
                                this.page.identifier = \''.$postid.'\'; // Replace PAGE_IDENTIFIER with your page\'s unique identifier variable
                            };

                            (function() { // DON\'T EDIT BELOW THIS LINE
                                var d = document, s = d.createElement(\'script\');
                                s.src = \'https://'.Core::getInstance()->disqus.'.disqus.com/embed.js\';
                                s.setAttribute(\'data-timestamp\', +new Date());
                                (d.head || d.body).appendChild(s);
                            })();
                        </script>
                        <script id="dsq-count-scr" src="//'.Core::getInstance()->disqus.'.disqus.com/count.js" async></script>
                        <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments.</a></noscript>
                    </div>
                    <!-- END comments -->
                </div>';
                        }
                    }
                ?>

            </div>

            <!-- right column -->
            <div class="col-lg-4 col-xs-12 col-sm-12">

                <div class="adblock">
                    <div class="img">
                        <?php echo ((!empty($datasidebar) && ($datasidebar->{'status'} == "success"))?$datasidebar->result[0]->{'Embed'}:''.Core::lang('ads_here').'<br>336 x 280');?>
                    </div>
                </div>

                <!-- Recomended Videos -->
                <div class="caption">
                    <div class="left">
                        <a href="#"><?php echo Core::lang('watch_recomended')?></a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="list">
                    <?php 
                        if (!empty($data) && ($data->{'status'} == "success")){
                            //Data random movies
                            $urlrandom = Core::getInstance()->api.'/video/post/data/public/search/random/1/10/?apikey='.Core::getInstance()->apikey.'&query=';
                            $datarandom = json_decode(Core::execGetRequest($urlrandom));
                            if (!empty($datarandom) && ($datarandom->{'status'} == "success")){
                                foreach ($datarandom->results as $name => $valuerandom) {
                                    echo '<div class="h-video row">
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="v-img">
                                                <a href="'.Core::getInstance()->homepath.'/'.Core::lang('watch').'/'.$valuerandom->{'PostID'}.'/'.Core::convertToSlug($valuerandom->{'Title'}).'"><img src="'.$valuerandom->{'Image'}.'" class="top-cropped sm" alt="'.$valuerandom->{'Title'}.'"></a>
                                                <div class="rating">'.$valuerandom->{'Rating'}.'</div>
                                                <div class="time">'.$valuerandom->{'Duration'}.'</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="v-desc">
                                                <a href="'.Core::getInstance()->homepath.'/'.Core::lang('watch').'/'.$valuerandom->{'PostID'}.'/'.Core::convertToSlug($valuerandom->{'Title'}).'">'.Core::cutLongText($valuerandom->{'Title'},40).'</a>
                                            </div>
                                            <div class="v-views">';
                                            $datatag = "";
                                            foreach ($valuerandom->{'Tags'} as $namegenre => $valuegenre) {
                                                $datatag .= '<a style="color:#6F6D6D" onMouseOut="this.style.color=\'#6F6D6D\'" onMouseOver="this.style.color=\'#ea2c5a\'" href="'.Core::getInstance()->homepath.'/index.php?search='.$valuegenre.'">'.$valuegenre.'</a>, ';
                                            }
                                            $datatag = substr($datatag, 0, -2);
                                            echo $datatag;
                                            echo '</div>
                                            <div class="v-views">
                                                '.number_format($valuerandom->{'Viewer'}).''.Core::lang('views').'.
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>';
                                }
                            }

                            
                        }
                    ?>
                </div>
                <!-- END Recomended Videos -->

                <!-- load more -->
                <div class="loadmore">
                    <a href="<?php echo Core::getInstance()->homepath?>/genre.php"><?php echo Core::lang('watch_all_genre')?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'global-footer.php';?>
<?php include 'global-js.php';?>

<?php
    if (!empty($data) && ($data->{'status'} == "success")){
        echo '<!-- Start Structured Data -->
        <script type="application/ld+json">
        {
          "@context": "http://schema.org",
          "@id": "'.Core::convertToSlug($data->result[0]->{'Title'}).'-'.$data->result[0]->{'PostID'}.'",
          "@type": "Movie",
          "dateCreated": "'.$data->result[0]->{'Created_at'}.'",
          "name": "'.$data->result[0]->{'Title'}.'",
          "url": "'.((Core::isHttpsButtflare()) ? 'https' : 'http') . '://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'",
          "description": "'.$data->result[0]->{'Description'}.'",
          "releasedEvent": {
            "@type": "PublicationEvent",
            "startDate": "'.$data->result[0]->{'Released'}.'",
            "location": {
              "@type": "Country",
              "name": '.json_encode($data->result[0]->{'Country'}).'
            }
          },
          "image": {
            "@type": "ImageObject",
            "url": "'.$data->result[0]->{'Image'}.'"
          },';
            if (!empty($data->result[0]->{'Stars'})) {
                echo "\n";
                echo '"actor": {
                    "@type": "Person",
                    "name": '.json_encode($data->result[0]->{'Stars'}).'
                },';
            }
            if (!empty($data->result[0]->{'Director'})) {
                echo "\n";
                echo '"director": {
                    "@type": "Person",
                    "name": '.json_encode($data->result[0]->{'Director'}).'
                  },';
            }
            echo "\n";
            echo '"duration": "'.$data->result[0]->{'Duration'}.'"
        }
        </script>
        <!-- End Structured Data -->';
    }
?>

<!-- Rating -->
<script type="text/javascript">
    $(function(){    
        $(".iliked").on("click",function(){
            $.ajax({
                type: "GET",
				url: "<?php echo Core::getInstance()->api?>/video/post/data/liked/<?php echo $postid?>/?apikey=<?php echo Core::getInstance()->apikey?>",
				dataType: 'json',
				success: function( data ) {
                    document.getElementById("voteresult").innerHTML='';
					if(data.status=='success'){
                        document.getElementById("totalliked").innerHTML='<i class="fa fa-thumbs-up iliked"></i> '+data.total;
                        document.getElementById("voteresult").innerHTML=data.message;
					} else {
						document.getElementById("voteresult").innerHTML=data.message;
					}
				},
				error: function( xhr, textStatus, error ) {
					console.log("XHR: " + xhr.statusText);
                    console.log("STATUS: "+textStatus);
                    console.log("ERROR: "+error);
                    console.log("TRACE: "+xhr.responseText);
				}
			}).done(function(res){ 
                
			});
        }),
        $(".idisliked").on("click",function(){
            $.ajax({
                type: "GET",
				url: "<?php echo Core::getInstance()->api?>/video/post/data/disliked/<?php echo $postid?>/?apikey=<?php echo Core::getInstance()->apikey?>",
				dataType: 'json',
				success: function( data ) {
                    document.getElementById("voteresult").innerHTML='';
					if(data.status=='success'){
                        document.getElementById("totaldisliked").innerHTML='<i class="fa fa-thumbs-down idisliked"></i> '+data.total;
                        document.getElementById("voteresult").innerHTML=data.message;
					} else {
						document.getElementById("voteresult").innerHTML=data.message;
					}
				},
				error: function( xhr, textStatus, error ) {
                    console.log("XHR: " + xhr.statusText);
                    console.log("STATUS: "+textStatus);
                    console.log("ERROR: "+error);
                    console.log("TRACE: "+xhr.responseText);
				}
			}).done(function(res){ 
                
			});
        });
    });
</script>
</body>
</html>
