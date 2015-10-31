<!--sidebar start-->
<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <div class="leftside-navigation">
            <ul class="sidebar-menu" id="nav-accordion">
                <li <?php if(stripos($_SERVER['REQUEST_URI'],"dashboard.php")) echo 'class="active"'; ?>>
                    <a href="dashboard.php">
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                  <li class="sub-menu">
                    <a <?php if(stripos($_SERVER['REQUEST_URI'],"#")) echo 'class="active"'; ?> href="javascript:;">
                        <i class="fa fa-list-alt"></i>
                        <span>Grownups</span>
                    </a>
                    
                </li>
				
				  <li class="sub-menu">
                    <a <?php if(stripos($_SERVER['REQUEST_URI'],"#")) echo 'class="active"'; ?> href="javascript:;">
                        <i class="fa fa-users"></i>
                        <span>Kids</span>
                    </a>
                    
                </li>
                
                
               
            </ul>            
          </div>
          </div>
          </aside>
        
        <!-- sidebar menu end-->
   