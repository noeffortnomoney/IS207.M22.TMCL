<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<?php
//mã màu #3085d6
function notification($icon,$title,$content,$btntext,$showbtn,$colorbtn){
    echo '<script>
    $(document).ready(function(){
        Swal.fire({
            position: "center",
            icon: "'.$icon.'",
            title: "'.$title.'",
            text: "'.$content.'",
            confirmButtonColor: "'.$colorbtn.'",
            confirmButtonText: "'.$btntext.'",
            showConfirmButton: '.$showbtn.',
          })
    });
</script>';
}
function NotifiSiginSuccess(){
    echo '<script>
    $(document).ready(function(){
        Swal.fire({
            title: "Đăng Kí Tài Khoản Thành Công",
            icon: "success",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Đăng Nhập Ngay"
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href= "'.base.'login/login";
            }
          });
    });
</script>';
}
function NotifiOrder(){
    echo '<script>
    $(document).ready(function(){
        Swal.fire({
            title: "Xử Lý Đơn Hàng Thành Công",
            icon: "success",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "OK"
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href= "'.base.'admin/order";
            }
          });
    });
</script>';
}

function NotifiError($text,$url){
  echo '<script>
  $(document).ready(function(){
      Swal.fire({
          title: "'.$text.'",
          icon: "error",
          showCancelButton: false,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Thử Lại"
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href= "'.base.$url.'";
          }
        });
  });
</script>';
}
//thay đổi thành công
function notifichanger($text){
  echo "
  <script>
  $(document).ready(function(){
    Swal.fire({
      position: 'center',
      icon: 'success',
      title: '".$text."',
      showConfirmButton: false,
      timer: 1000
    })
  });
</script>
  ";
}
?>
