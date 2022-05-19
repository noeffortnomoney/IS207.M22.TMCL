
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3></h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-home"></i> Bộ Điều Khiển <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?=base."admin/showcategory"?>">Quản Lí Danh Mục Sản Phẩm</a></li>
                      <li><a href="<?=base."admin/showproduct"?>">Quản Lí Sản Phẩm</a></li>
                      <li><a href="<?=base?>admin/order">Quản Lí Đơn Hàng</a></li>
                      <li><a href="<?=base?>admin/useraccount">Quản Lí Khách Hàng</a></li>
                      <li><a href="<?=base."admin/showslider"?>">Quản Lí Slider</a></li>
                    </ul>
                  </li>
                  <li><a href="admin/ChangePass"><i class="fa fa-cog"></i>Đổi Mật Khẩu</a></li>
                  <li><a onclick="logout('<?=base.'logout/admin'?>')" class="dropdown-item" href="javascript:void(0)"><i class="fa fa-sign-out pull-left"></i>Đăng Xuất</a></li>
                </ul>
              </div>
            </div>