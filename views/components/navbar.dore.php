<?php 
    $adaUserMenu = !isset($adaUserMenu) ? true : $adaUserMenu;
    if(isset($navbarConf) && !empty($navbarConf)) 
        extract($navbarConf);
    $bgclass = null;
    $navStyles = '';

    // FIX LATTER: FORCE CHANGE NAVBAR BACKGROUND-COLOR
    $customBg = [
        'type' => 'script',
        'bg' => '#E0FBE2'
    ];
    if(isset($customBg) && !empty($customBg)){
        if($customBg['type'] == 'class')
            $bgclass = $customBg['bg'];
        else{
            $navStyles = 'background-color:' . $customBg['bg'] . '; color: white';
        }
        
    }
?>
<nav style="<?php echo $navStyles ?>" class="navbar fixed-top <?php echo !empty($bgclass) ? 'bg-' . $bgclass : null?>">
    <div class="d-flex align-items-center navbar-left">
    <?php if (isset($adaSidebar) && $adaSidebar): ?>
        <a href="#" class="menu-button d-none d-md-block">
            <svg class="main" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 17">
                <rect x="0.48" y="0.5" width="7" height="1" />
                <rect x="0.48" y="7.5" width="7" height="1" />
                <rect x="0.48" y="15.5" width="7" height="1" />
            </svg>
            <svg class="sub" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 17">
                <rect x="1.56" y="0.5" width="16" height="1" />
                <rect x="1.56" y="7.5" width="16" height="1" />
                <rect x="1.56" y="15.5" width="16" height="1" />
            </svg>
        </a>

        <a href="#" class="menu-button-mobile d-xs-block d-sm-block d-md-none">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 17">
                <rect x="0.5" y="0.5" width="25" height="1" />
                <rect x="0.5" y="7.5" width="25" height="1" />
                <rect x="0.5" y="15.5" width="25" height="1" />
            </svg>
        </a>
        <?php endif ?>
        <?php if(isset($pencarian) && $pencarian):?>
            <div class="search" data-search-path="Layouts.Search.html?q=">
            <input class="text-primary" placeholder="Search...">
            <span class="search-icon">
                <i class="text-primary simple-icon-magnifier"></i>
            </span>
        </div>
        <?php endif?>
    </div>
    <div class="navbar-center">
        <h2 class="text-primary"><a href="<?= base_url() ?>"><?= ucfirst(APPNAME) ?></a></h2>
    </div>
    <a style="background: url('<?php echo isset($navbarLogo) ? $navbarLogo : null ?>') no-repeat;  width: 50px; height: 50px" class="navbar-logo" href="<?php echo isset($homePath) ? $homePath : null ?>">
        <span class="logo d-none d-xs-block"></span>
        <span class="logo-mobile d-block d-xs-none"></span>
    </a>

    <div class="navbar-right">
        <div class="header-icons d-inline-block align-middle">
            <div class="position-relative d-none d-sm-inline-block">
                <?php if(isset($navbarApps) && is_array($navbarApps)): ?>
                <button class="header-icon btn btn-empty" type="button" id="iconMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="text-primary simple-icon-grid"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right mt-3  position-absolute" id="iconMenuDropdown">
                    <?php foreach($navbarApps as $k => $v): ?>
                        <a href="<?php echo $v['link']?>" class="icon-menu-item">
                        <i class="text-primary <?php echo isset($v['icon']) ? $v['icon'] : null ?>"></i>
                        <span class="text-primary"><?php echo $v['name'] ?></span>
                    </a>
                    <?php endforeach?>
                </div>
                <?php endif?>
            </div>
            
            <?php if(isset($adaNotif) && $adaNotif): ?>
            <div class="position-relative d-inline-block">
                <button class="header-icon btn btn-empty" type="button" id="notificationButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="text-primary simple-icon-bell"></i>
                    <span class="count"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-right mt-3 scroll position-absolute" id="notificationDropdown" style="overflow-y: scroll">
                </div>
            </div>
            <?php endif ?>
            <?php if(isset($bisaFullScreen) && $bisaFullScreen): ?>
            <button class="header-icon btn btn-empty d-none d-sm-inline-block" type="button" id="fullScreenButton">
                <i class="simple-icon-size-fullscreen"></i>
                <i class="simple-icon-size-actual"></i>
            </button>
            <?php endif ?>
        </div>
            
            <?php if($adaUserMenu): ?>
                <div class="user d-inline-block">
                    <button class="btn btn-empty p-0" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="name text-primary"><?php echo !empty(sessiondata('login', 'nama_lengkap')) ? sessiondata('login', 'nama_lengkap') : sessiondata('login', 'username') ?></span>
                        <span>
                            <img class="border border-primary" alt="Profile Picture" src="<?= staticUrl('img/profile/' . sessiondata('login', 'photo')) ?>" />
                        </span>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right mt-3">
                        <?php if(isset($userMenu) && is_array($userMenu)) :
                            foreach($userMenu as $menu): ?>
                                <p id="<?php echo $menu['id']?>" style="cursor: pointer;" class="dropdown-item" data-link="<?php echo $menu['link'] ?>" ><?php echo $menu['link']; ?></p>
                            <?php endforeach ?>
                        <?php else: ?>
                            <p id="akun" style="cursor: pointer;" class="dropdown-item" data-link="" >Akun saya</p>
                            <p id="logout" style="cursor: pointer;" class="dropdown-item" data-link="auth/logout" >Keluar</p>
                        <?php endif ?>
                    </div>
                </div>
            <?php endif?>
       
    </div>
</nav>
<script></script>