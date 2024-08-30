<?php $logo_img = session()->getFlashdata('logo_update') ? base_url() . session()->getFlashdata('logo_update') : session()->get('logo_update');
$logo_img = ($logo_img == '') ? 'images/logo-dashboard.png' : $logo_img;
?>
<nav class="sidebar d-none d-lg-block" id="sidebar-id ">

    <header class="">
        <div class="image-text">
            <div class="text logo-text">
                <?php $imageProperties = ['src' => $logo_img, 'class' => 'img-fluid', "style" => "height: 40px;", 'alt' => 'Hair Component'];
                echo img($imageProperties); ?>
            </div>
        </div>

        <i class='bx bx-chevron-right toggle'></i>
    </header>

    <div class="menu-bar">
        <div class="menu">

            <!-- <li class="menu-head ">
                    <h6>Customer Satisfaction
                        Survey </h6>
                </li> -->

            <li class="search-box">
                <i class='bx bx-search icon'></i>
                <input type="text" placeholder="Search...">
            </li>

            <ul class="menu-links" id="menu">
                <li class="nav-link">
                    <a href="<?php echo site_url('admin'); ?>?filter=no" onclick="setActive(0)">
                        <?php $imageProperties = ['src' => 'images/icons/8.png', 'class' => 'img-fluid'];
                        echo img($imageProperties); ?>
                        <span class="text nav-text">Dashboard</span>
                    </a>
                </li>
                <?php if (session()->get('tenant_id') == 1) { ?>
                    <li class="nav-link">
                        <a href="<?php echo site_url('createtenant'); ?>">
                            <?php $imageProperties = ['src' => 'images/icons/7.png', 'class' => 'img-fluid'];
                            echo img($imageProperties); ?>
                            <span class="text nav-text">Create Tenant</span>
                        </a>
                    </li>
                    <!-- <li class="nav-link">
                        <a  href="<?php echo site_url('questionList'); ?>">
                            <?php $imageProperties = ['src' => 'images/icons/6.png', 'class' => 'img-fluid'];
                            echo img($imageProperties); ?>
                            <span class="text nav-text">View Question</span>
                        </a>
                    </li> -->
                <?php } ?>
                <!-- <li class="nav-link">
                        <a href="<?php echo site_url('answerList'); ?>">
                        <?php $imageProperties = ['src' => 'images/icons/6.png', 'class' => 'img-fluid'];
                        echo img($imageProperties); ?>
                            <span class="text nav-text">View Answer</span>
                        </a>    
                    </li> -->
                <li class="nav-link">
                    <a href="<?php echo site_url('surveyList'); ?>" onclick="setActive(1)">
                        <?php $imageProperties = ['src' => 'images/icons/5.png', 'class' => 'img-fluid'];
                        echo img($imageProperties); ?>
                        <span class="text nav-text">Survey Management</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a href="<?php echo site_url('reports'); ?>" onclick="setActive(2)">
                        <?php $imageProperties = ['src' => 'images/icons/9.png', 'class' => 'img-fluid'];
                        echo img($imageProperties); ?>
                        <span class="text nav-text">Reports</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a href="<?php echo site_url('geomatrix'); ?>" onclick="setActive(3)">
                        <?php $imageProperties = ['src' => 'images/icons/4.png', 'class' => 'img-fluid'];
                        echo img($imageProperties); ?>
                        <span class="text nav-text">Geo-Matrix</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="<?php echo site_url('SurveyResponse'); ?>" onclick="setActive(4)">
                        <?php $imageProperties = ['src' => 'images/icons/3.png', 'class' => 'img-fluid'];
                        echo img($imageProperties); ?>
                        <span class="text nav-text">Survey Report</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a href="<?php echo site_url('emailtemplate'); ?>" onclick="setActive(5)">
                        <?php $imageProperties = ['src' => 'images/icons/2.png', 'class' => 'img-fluid'];
                        echo img($imageProperties); ?>
                        <span class="text nav-text">Send Campaign</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a href="<?php echo site_url('getCustomerData'); ?>" onclick="setActive(6)">
                        <?php $imageProperties = ['src' => 'images/icons/1.png', 'class' => 'img-fluid'];
                        echo img($imageProperties); ?>
                        <span class="text nav-text">Contact Details</span>
                    </a>
                </li>

                <!-- <li class="nav-link">
                    <a href="<?php echo site_url('getSegments'); ?>" onclick="setActive(7)">
                        <?php $imageProperties = ['src' => 'images/icons/1.png', 'class' => 'img-fluid'];
                        echo img($imageProperties); ?>
                        <span class="text nav-text">Tags and segments</span>
                    </a>
                </li> -->

            </ul>
        </div>

        <div class="bottom-content" id="menu">
            <hr>
            <li class="nav-link">
                <a href="#" onclick="setActive(7)">
                    <?php $imageProperties = ['src' => 'images/icons/user-headset.png', 'class' => 'img-fluid'];
                    echo img($imageProperties); ?>
                    <span class="text nav-text">Support</span>
                </a>
            </li>

            <li class="nav-link">
                <a href="<?php echo site_url('settingpage'); ?>" onclick="setActive(8)">
                    <?php $imageProperties = ['src' => 'images/icons/Settings.png', 'class' => 'img-fluid'];
                    echo img($imageProperties); ?>
                    <span class="text nav-text">Setting</span>
                </a>
            </li>
            <li class="Username">
                <a href="<?php echo site_url('userprofile'); ?>" onclick="setActive(9)">
                    <?php $imageProperties = ['src' => 'images/user-icon.png', 'class' => 'img-fluid'];
                    echo img($imageProperties); ?>
                    <span class="text nav-text">
                        <h6>
                            <?= session()->get('firstname') . " " . session()->get('lastname') ?>
                        </h6>
                        <h6>
                            <?= session()->get('email'); ?>
                        </h6>
                    </span>
                </a>
                <!-- <br/>
                        <a class="btn bx bx-log-out icon" href="<?php echo site_url('logout'); ?>"></a> -->

            </li>
            <li class="Username">
                <a href="<?php echo site_url('logout'); ?>">
                    <?php $imageProperties = ['src' => 'images/icons/10.png', 'class' => 'img-fluid'];
                    echo img($imageProperties); ?>
                    <span class="text nav-text">
                        <h6>Logout
                        </h6>
                    </span>
                </a>

            </li>

        </div>
    </div>

</nav>


<nav class="navbar sidebar navbar-expand-lg d-lg-none">
    <div class="container-fluid">
        <!-- <a class="navbar-brand" href="#">Navbar</a> -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <header>
                <div class="image-text">
                    <div class="text logo-text">
                        <?php $imageProperties = ['src' => $logo_img, 'class' => 'img-fluid', "style" => "height: 100%;", 'alt' => 'Hair Component'];
                        echo img($imageProperties); ?>
                    </div>
                </div>

                <!-- <i class='bx bx-chevron-right toggle'></i> -->
            </header>

            <div class="menu-bar">
                <div class="menu">

                    <!-- <li class="menu-head ">
                    <h6>Customer Satisfaction
                        Survey </h6>
                </li> -->

                    <li class="search-box">
                        <i class='bx bx-search icon'></i>
                        <input type="text" placeholder="Search...">
                    </li>

                    <ul class="menu-links" id="menu">
                        <li class="nav-link">
                            <a href="<?php echo site_url('admin'); ?>?filter=no" onclick="setActive(0)">
                                <?php $imageProperties = ['src' => 'images/icons/8.png', 'class' => 'img-fluid'];
                                echo img($imageProperties); ?>
                                <span class="text nav-text">Dashboard</span>
                            </a>
                        </li>
                        <?php if (session()->get('tenant_id') == 1) { ?>
                            <li class="nav-link">
                                <a href="<?php echo site_url('createtenant'); ?>">
                                    <?php $imageProperties = ['src' => 'images/icons/7.png', 'class' => 'img-fluid'];
                                    echo img($imageProperties); ?>
                                    <span class="text nav-text">Create Tenant</span>
                                </a>
                            </li>
                            <!-- <li class="nav-link">
                        <a  href="<?php echo site_url('questionList'); ?>">
                            <?php $imageProperties = ['src' => 'images/icons/6.png', 'class' => 'img-fluid'];
                            echo img($imageProperties); ?>
                            <span class="text nav-text">View Question</span>
                        </a>
                    </li> -->
                        <?php } ?>
                        <!-- <li class="nav-link">
                        <a href="<?php echo site_url('answerList'); ?>">
                        <?php $imageProperties = ['src' => 'images/icons/6.png', 'class' => 'img-fluid'];
                        echo img($imageProperties); ?>
                            <span class="text nav-text">View Answer</span>
                        </a>    
                    </li> -->
                        <li class="nav-link">
                            <a href="<?php echo site_url('surveyList'); ?>" onclick="setActive(1)">
                                <?php $imageProperties = ['src' => 'images/icons/5.png', 'class' => 'img-fluid'];
                                echo img($imageProperties); ?>
                                <span class="text nav-text">Survey Management</span>
                            </a>
                        </li>

                        <li class="nav-link">
                            <a href="<?php echo site_url('overallreport'); ?>" onclick="setActive(2)">
                                <?php $imageProperties = ['src' => 'images/icons/9.png', 'class' => 'img-fluid'];
                                echo img($imageProperties); ?>
                                <span class="text nav-text">Reports</span>
                            </a>
                        </li>

                        <li class="nav-link">
                            <a href="<?php echo site_url('geomatrix'); ?>" onclick="setActive(3)">
                                <?php $imageProperties = ['src' => 'images/icons/4.png', 'class' => 'img-fluid'];
                                echo img($imageProperties); ?>
                                <span class="text nav-text">Geo-Matrix</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a href="<?php echo site_url('SurveyResponse'); ?>" onclick="setActive(4)">
                                <?php $imageProperties = ['src' => 'images/icons/3.png', 'class' => 'img-fluid'];
                                echo img($imageProperties); ?>
                                <span class="text nav-text">Survey Report</span>
                            </a>
                        </li>

                        <li class="nav-link">
                            <a href="<?php echo site_url('emailtemplate'); ?>" onclick="setActive(5)">
                                <?php $imageProperties = ['src' => 'images/icons/2.png', 'class' => 'img-fluid'];
                                echo img($imageProperties); ?>
                                <span class="text nav-text">Send Campaign</span>
                            </a>
                        </li>

                        <li class="nav-link">
                            <a href="<?php echo site_url('getCustomerData'); ?>" onclick="setActive(6)">
                                <?php $imageProperties = ['src' => 'images/icons/1.png', 'class' => 'img-fluid'];
                                echo img($imageProperties); ?>
                                <span class="text nav-text">Contact Details</span>
                            </a>
                        </li>

                        <li class="nav-link">
                            <a href="<?php echo site_url('getSegments'); ?>" onclick="setActive(7)">
                                <?php $imageProperties = ['src' => 'images/icons/1.png', 'class' => 'img-fluid'];
                                echo img($imageProperties); ?>
                                <span class="text nav-text">Contact Details</span>
                            </a>
                        </li>

                    </ul>
                </div>

                <div class="bottom-content" id="menu">
                    <hr>
                    <li class="nav-link">
                        <a href="#" onclick="setActive(7)">
                            <?php $imageProperties = ['src' => 'images/icons/user-headset.png', 'class' => 'img-fluid'];
                            echo img($imageProperties); ?>
                            <span class="text nav-text">Support</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="<?php echo site_url('settingpage'); ?>" onclick="setActive(8)">
                            <?php $imageProperties = ['src' => 'images/icons/Settings.png', 'class' => 'img-fluid'];
                            echo img($imageProperties); ?>
                            <span class="text nav-text">Setting</span>
                        </a>
                    </li>
                    <li class="Username">
                        <a href="<?php echo site_url('userprofile'); ?>" onclick="setActive(9)">
                            <?php $imageProperties = ['src' => 'images/user-icon.png', 'class' => 'img-fluid'];
                            echo img($imageProperties); ?>
                            <span class="text nav-text">
                                <h6>
                                    <?= session()->get('firstname') . " " . session()->get('lastname') ?>
                                </h6>
                                <h6>
                                    <?= session()->get('email'); ?>
                                </h6>
                            </span>
                        </a>
                        <!-- <br/>
                        <a class="btn bx bx-log-out icon" href="<?php echo site_url('logout'); ?>"></a> -->

                    </li>
                    <li class="Username">
                        <a href="<?php echo site_url('logout'); ?>">
                            <?php $imageProperties = ['src' => 'images/icons/10.png', 'class' => 'img-fluid'];
                            echo img($imageProperties); ?>
                            <span class="text nav-text">
                                <h6>Logout
                                </h6>
                            </span>
                        </a>

                    </li>

                </div>
            </div>
        </div>
    </div>
</nav>




<script>
    // Function to set active state
    function setActive(index) {
        var menuItems = document.querySelectorAll('#menu a');

        // Remove active class from all menu items
        for (var i = 0; i < menuItems.length; i++) {
            menuItems[i].classList.remove('active');
        }

        // Add active class to the selected menu item
        menuItems[index].classList.add('active');

        // Store the active menu item index in localStorage
        localStorage.setItem('activeMenuItem', index);
    }

    // Function to retrieve and set active state on page load
    function setInitialActiveState() {
        var activeIndex = localStorage.getItem('activeMenuItem');
        if (activeIndex !== null) {
            setActive(parseInt(activeIndex));
        }
    }

    // Set the initial active state when the page loads
    window.onload = setInitialActiveState;
</script>