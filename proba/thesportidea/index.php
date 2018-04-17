<!DOCTYPE html>
<html>
    <head>
        <title>TheSportIdea - home of sport</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="shortcut icon" type="image/png" href="img/favicon.png?<?php date('Y-m-d') ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="css/menu2.css" />
        <link rel="stylesheet" type="text/css" href="css/leaflet.css" />
        <link rel="stylesheet" type="text/css" href="css/MarkerCluster.css" />
        <link rel="stylesheet" type="text/css" href="css/MarkerCluster.Default.css" />
        <link rel="stylesheet" type="text/css" href="css/footer.css">
        
        <script type='text/javascript' src='js/jquery.min.js'></script>
        <script type='text/javascript' src='js/latlon.ip.js'></script>
        <!--<script type='text/javascript' src='js/right.click.js'></script>-->
        <script type='text/javascript' src='js/leaflet.js'></script>
        <script type='text/javascript' src='js/leaflet.markercluster.js'></script>

    </head>

    <body>
        <div class="header">
            <nav>
                <div class="navbar clear">
                    <a href="" class="logo">TheSportIdea</a>
                    <div class="menu-toggle"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></div>
                    <div class="menu">
                        <ul>
                            <li><a href="">menu 1</a></li>
                            <li><a href="">menu 2</a></li>
                            <li>
                                <span class="open-submenu">menu 3 <span class="arrow down"></span></span>
                                <ul>
                                    <li><a href="">almenupont 1</a></li>
                                    <li><a href="">almenupont 2</a></li>
                                    <li><a href="">almenupont 3</a></li>
                                    <li><a href="">almenupont 4</a></li>
                                </ul>
                            </li>
                            <li><a href="">menu 4</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <div id="map" class="wrapper" ></div>
        <div class="footer">
            <footer>
                <ul>
                    <li>
                        <div class="icon" data-icon="E"></div>
                        <div class="text">
                            <h4>About</h4>
                            <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin tristique justo eu sollicitudin pretium. Nam scelerisque arcu at dui porttitor, non viverra sapien pretium. Nunc nec dignissim nunc. Sed eget est purus. Sed convallis, metus in dictum feugiat, odio orci rhoncus metus. <a href="#">Read more</a></div>
                        </div>
                    </li>
                    <li>
                        <div class="icon" data-icon="a"></div>
                        <div class="text">
                            <h4>Archive</h4>
                            <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin tristique justo eu sollicitudin pretium. Nam scelerisque arcu at dui porttitor, non viverra sapien pretium. Nunc nec dignissim nunc. Sed eget est purus. Sed convallis, metus in dictum feugiat, odio orci rhoncus metus. <a href="#">Read more</a></div>
                        </div>
                    </li>
                    <li>
                        <div class="icon" data-icon="s"></div>
                        <div class="text">
                            <h4>Cloud</h4>
                            <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin tristique justo eu sollicitudin pretium. Nam scelerisque arcu at dui porttitor, non viverra sapien pretium. Nunc nec dignissim nunc. Sed eget est purus. Sed convallis, metus in dictum feugiat, odio orci rhoncus metus. <a href="#">Read more</a></div>
                        </div>
                    </li>
                </ul>

                <div class="bar">
                    <div class="bar-wrap">
                        <ul class="links">
                            <li><a href="#">Home</a></li>
                            <li><a href="#">License</a></li>
                            <li><a href="#">Contact Us</a></li>
                            <li><a href="#">Advertise</a></li>
                            <li><a href="#">About</a></li>
                        </ul>

                        <div class="social">
                            <a href="#" class="fb">
                                <span data-icon="f" class="icon"></span>
                                <span class="info">
                                    <span class="follow">Become a fan Facebook</span>
                                    <span class="num">9,999</span>
                                </span>
                            </a>

                            <a href="#" class="tw">
                                <span data-icon="T" class="icon"></span>
                                <span class="info">
                                    <span class="follow">Follow us Twitter</span>
                                    <span class="num">9,999</span>
                                </span>
                            </a>

                            <a href="#" class="rss">
                                <span data-icon="R" class="icon"></span>
                                <span class="info">
                                    <span class="follow">Subscribe RSS</span>
                                    <span class="num">9,999</span>
                                </span>
                            </a>
                        </div>
                        <div class="clear"></div>
                        <div class="copyright">&copy;  2014 All Rights Reserved</div>
                    </div>
                </div>
            </footer>
        </div>
        <script type='text/javascript' src='maps/markers2.js'></script>
        <script type='text/javascript' src='maps/leaf-demo.js'></script>
        <script type="text/javascript" src='js/menu2.js'></script>
    </body>
</html>
