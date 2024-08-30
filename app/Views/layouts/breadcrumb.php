<ol class="breadcrumb">
            <li class="breadcrumb-item">
            <?php if (session()->get('isLoggedIn')) {  ?>
            <?php if (session()->get('role') == "admin") { ?>
              <a href="<?php echo base_url('admin'); ?>">admin</a>
              <?php } else { ?> 
                <a href="<?php echo base_url('user'); ?>">user</a>

                <?php } ?>
                <?php }  ?>

            </li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>