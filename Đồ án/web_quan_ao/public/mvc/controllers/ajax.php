<?php
    class ajax extends controller{
        var $commonmodel;
        var $homeclientmodel;
        var $checkoutmodel;
        var $ordermodel;
        function __construct()
        {
            $this->commonmodel = $this->ModelCommon("commonmodel");
            $this->homeclientmodel = $this->ModelClient("homemodel");
            $this->checkoutmodel = $this->ModelClient("checkoutmodel");
            $this->ordermodel = $this->ModelAdmin("ordermodel");

        }

        //kiểm tra xem tài khoản này có ai sử dụng hay chưa (lúc đăng kí tài khoản)
        function checkuser(){
            $email = $_POST["email"];
            if(CheckUnicode($email) == 0){
                $check = $this->commonmodel->checkemail($email);
                if($check >=1){
                    $mess = "<p style='color: red;'>Email này đã có người khác sử dụng</p>";
                }else{
                    $mess= "<p style='color: green;'>Email hợp lệ có thể sử dụng</p>";
                }
            }else {
                $mess ="<p style='color: red;'>Email và mật khẩu không được chứa kí tự đặc biệt</p>";
            }
            echo $mess;
        }

        //kiểm tra xem người dùng xác nhận mật khẩu khớp hay không (lúc đăng kí tài khoản)
        function checkpass(){
            $pass = $_POST["pass"];
            $pass_confirm = $_POST["pass_confirm"];
            
            if($pass != $pass_confirm){
                $mess = "<p style='color: red;'>Xác nhận mật khẩu không khớp</p>";
            }else {
                $mess = "<p style='color: green;'>xác nhận mật khẩu trùng khớp</p>";
            }
            echo $mess;
        }
        // hiển chi tiết sản phẩm khi người dùng bấm vào mua sản phẩm
        function details(){
            $id = $_POST["id"];
            $product = $this->commonmodel->GetProductById($id);
            echo '
                <div class="col-sm-12 padding-right">
                    <i style="font-size: 35px; color: orange;position: absolute;right: 0;z-index: 2;" class="fas fa-times-circle"></i>
                    <div class="product-details"><!--product-details-->
                        <div class="col-sm-4">
                            <div class="view-product">
                                <img style="object-fit: cover;" src="public/images/img_product/'.$product[0]["img_product"].'" alt="" />
                            </div>

                        </div>
                        <div class="col-sm-8">
                            <div class="product-information"><!--/product-information-->
                                <img src="public/client/images/product-details/new.jpg" class="newarrival" alt="" />
                                <h2 style="color: #FE980F;">'.$product[0]["name"].'</h2>
                                <p><b>Giá : '.number_format ($product[0]["price"] * (1-$product[0]["sale_product"]/100) , $decimals = 0 , $dec_point = "," , $thousands_sep = "." ).' Đồng</b></p>
                                <p><b>Số lượng đã bán:</b> '.$product[0]["pay"].'</p>
                                <p><b>Số Lượng còn:</b> '.$product[0]["quantity"].'</p>
                                <p><b>Nhà cung cấp:</b> '.$product[0]["production_company"].'</p>
                                <p><b>Mô tả: </b>'.$product[0]["descrip"].'</p>
                                <span>
                                    <a href="'.base.'cart/addcart&id='.$product[0]["id"].'" class="btn btn-fefault cart">
                                        <i class="fa fa-shopping-cart"></i>
                                        Thêm Vào Giỏ Hàng
                                    </a>
                                </span>
                            </div><!--/product-information-->
                        </div>
                    </div><!--/product-details-->
                </div>
            ';
        }
        //phân trang sản phẩm
        function paging(){
            if(isset($_POST["page"])){
                $current_page = $_POST["page"];
            }else $current_page = 1;
            if(isset($_POST["id"])){
                $id = $_POST["id"];
                if($id != 0){
                    $numberproduct = $this->commonmodel->NumberProductById($id);
                }else{
                    $numberproduct = $this->commonmodel->GetNumber("product");
                }
                $totalpage =ceil($numberproduct/6);
                for($i = 1; $i <= $totalpage;$i++){
                    if($current_page != $i){
                        if($current_page -5 < $i && $i < $current_page +5 ){
                            echo '
                            <a href="javascript:void(0)" class="paging nextpage" numPage ="'.$i.'">'.$i.'</a>
                            ';
                        }
                    }else {
                        echo '
                        <span class="paging paging-current">'.$i.'</span>
                        ';
                    }
                }
            }
        }

        // cập nhật lại tổng sổ tiền và số lượng sau khi bấm nút -
        function downquantity(){
            if(isset($_GET["id"])){
                $id = $_GET["id"];
                $_SESSION['cart'][$id]["quantity"]-=1;
                $_SESSION['cart'][$id]["total"] = $_SESSION['cart'][$id]["quantity"] * $_SESSION['cart'][$id]["price"];
                if($_SESSION['cart'][$id]["quantity"] == 0){
                    $_SESSION['cart'][$id]["quantity"]=1;
                    $_SESSION['cart'][$id]["total"] = $_SESSION['cart'][$id]["quantity"] * $_SESSION['cart'][$id]["price"];
                }
                header("location:".base."cart/showcart");
            }
        }

        // cập nhật lại tổng sổ tiền và số lượng sau khi bấm nút +
        function upquantity(){
            if(isset($_GET["id"])){
                $id = $_GET["id"];
                $_SESSION['cart'][$id]["quantity"]+=1;
                $quantity_product = $this->checkoutmodel->GetQuantityById($id);
                if($_SESSION['cart'][$id]["quantity"] <= $quantity_product[0]["quantity"]){
                    $_SESSION['cart'][$id]["total"] = $_SESSION['cart'][$id]["quantity"] * $_SESSION['cart'][$id]["price"];
                    header("location:".base."cart/showcart");
                }else{
                    $_SESSION['cart'][$id]["quantity"]-=1;
                    NotifiError("Xin lỗi số lượng trong kho không đủ","cart/showcart");
                }
            }
        }
        //cập nhật số lượng sản phẩm 
        function updatequantity(){
            if(isset($_POST["idproduct"])){
                $id = $_POST["idproduct"];
                //số lượng lấy từ người dùng nhập
                $quantity = $_POST["quantity"];
                //số lượng còn lại trong kho
                $quantity_product = $this->checkoutmodel->GetQuantityById($id);
                if($quantity <= $quantity_product[0]["quantity"]){
                    $_SESSION['cart'][$id]["quantity"]=$quantity;
                    if($_SESSION['cart'][$id]["quantity"] <= 0){
                        $_SESSION['cart'][$id]["quantity"]=1;
                        $_SESSION['cart'][$id]["total"] = $_SESSION['cart'][$id]["quantity"] * $_SESSION['cart'][$id]["price"];
                    }else{
                        $_SESSION['cart'][$id]["total"] = $_SESSION['cart'][$id]["quantity"] * $_SESSION['cart'][$id]["price"];
                    }
                    header("location:".base."cart/showcart");
                }else{
                    NotifiError("Xin lỗi số lượng trong kho không đủ","cart/showcart");
                }
            }
        }

        //xóa sản phẩm khỏi giỏ hàng
        function deleteproductcart(){
            if(isset($_GET["id"])){
                $id = $_GET["id"];
                unset($_SESSION['cart'][$id]);
                header("location:".base."cart/showcart");
            }
        }

        //Tìm kiếm sản phẩm
        function search(){
            if(isset($_POST["content"])){
                $current_page = $_POST["page"];
                $limit = 6;
                $offset = ($current_page-1)*6;
                $content = $_POST["content"];
                $product = $this->homeclientmodel->SearchProduct($content,$limit,$offset);
                echo '<h4 style="margin-left: 15px;">Kết quả tìm kiếm: '.$content.'</h4>';
                foreach($product as $key=>$values){ 
                    echo '
                        <div class="col-sm-4">
                        <div class="product-image-wrapper"style="max-height: 350px;">
                            <div class="single-products"style="max-height: 350px;">
                                <div class="productinfo text-center" style="max-height: 400px;">
                                    <img style="max-height: 200px;min-height: 200px;object-fit: cover;" src="public/images/img_product/'.$values["img_product"].'" alt="" />
                                    <h4 style="text-decoration: line-through;">'.number_format ($values["price"] , $decimals = 0 , $dec_point = "," , $thousands_sep = "." ).'đ</h4>
                                    <h2 style="margin:unset">'.number_format ($values["price"] * (1-$values["sale_product"]/100), $decimals = 0 , $dec_point = "," , $thousands_sep = "." ).'đ</h2>
                                    <p>'.$values["name"].'</p>
                                    <a href="javascript:void(0)" class="btn btn-default add-to-cart" idproduct ="'.$values["id"].'"><i class="fa fa-shopping-cart"></i>Mua Hàng</a>
                                </div>
                                <div class="product-overlay">
                                    <div class="overlay-content">
                                        <a href="javascript:void(0)" class="btn btn-default add-to-cart" idproduct ="'.$values["id"].'"><i class="fa fa-shopping-cart"></i>Mua Hàng</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    ';
                    }
            }
        }

        //phân trang cho các sản phẩm tìm kiếm
        function pagingsearch(){
            $content = $_POST["content"];
            if(isset($_POST["page"])){
                $current_page = $_POST["page"];
            }else $current_page = 1;
            $numberproduct = $this->homeclientmodel->GetNumberProductSearch($content);
            $totalpage =ceil($numberproduct/6);
                for($i = 1; $i <= $totalpage;$i++){
                    if($current_page != $i){
                        if($current_page -5 < $i && $i < $current_page +5 ){
                            echo '
                            <a href="javascript:void(0)" class="paging nextpagesearch" numPage ="'.$i.'">'.$i.'</a>
                            ';
                        }
                    }else {
                        echo '
                        <span class="paging paging-current">'.$i.'</span>
                        ';
                    }
                }
        }

        // chi tiết đơn hàng mà khách hàng đã mua
        function orderdetails(){
            $id_order = $_POST["id_order"];
            //lấy tất cả sản phẩm theo id_order
            $order_details = $this->ordermodel->GetOrderDetails($id_order);
            echo '
            <section id="cart_items">
                <div class="container">
                    <h3 style="color:FE980F;">Chi Tiết Đơn Hàng</h3>
                    <a href="'.base.'home/history" name="submit" class="btn btn-default" style="background-color:#FE980F; color:white;">Trở Về</a>
                    <div class="table-responsive cart_info">
                        <table class="table table-condensed">
                            <thead>
                                <tr class="cart_menu">
                                    <td class="image">Hình Ảnh</td>
                                    <td class="description">Tên Sản Phẩm</td>
                                    <td class="price">Giá</td>
                                    <td class="quantity">Số Lượng</td>
                                    <td class="total">Tổng Tiền</td>
                                </tr>
                            </thead>
                            <tbody>';
                            //dùng for để duyệt lấy từng id sản phẩm trong bảng order_details
                        foreach($order_details as $key=>$values){
                            //dùng id sản phẩm đó để lấy thông tin sản phẩm
                            $product = $this->commonmodel->GetProductById($values["product_id"]);
                            //dùng for để hiện sản phẩm
                            foreach($product as $key1=>$values1){
                                echo '
                                    <tr>
                                        <td class="cart_product">
                                            <img class="img-cart"src="public/images/img_product/'.$values1["img_product"].'">
                                        </td>
                                        <td class="cart_description">
                                            <h4 style="margin-bottom: 10px;">'.$values1["name"].'</h4>
                                        </td>
                                        <td class="cart_price" >
                                            <p style="margin-top: 10px;">'.number_format ($values1["price"] * (1-$values1["sale_product"]/100) , $decimals = 0 , $dec_point = "," , $thousands_sep = "." ).'đ</p>
                                        </td>
                                        <td class="cart_quantity">'.$values["quantity"].'</td>
                                        <td class="cart_total" id = "">
                                            <p class="cart_total_price">'.number_format ($values["unit_price"] , $decimals = 0 , $dec_point = "," , $thousands_sep = "." ).'đ</p>
                                        </td>
                                    </tr>
                                ';
                            }
                        }
            echo '
            </tbody>
                        </table>
                    </div>
                </div>
	        </section>';
        }
    }
?>