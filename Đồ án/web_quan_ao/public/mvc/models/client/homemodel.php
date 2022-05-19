<?php
    class homemodel extends database{
        //Hiển thị danh mục sản phẩm
        function ShowCategory(){
            $sql = "SELECT * FROM category WHERE status = 'Hiển Thị'";
            $query = $this->conn->prepare($sql);
            $query->execute();
            $result =  $query->fetchAll(PDO::FETCH_ASSOC);
            return json_encode($result);
        }

        //Tìm kiểm sản phẩm
        function SearchProduct($content,$limit,$offset){
            $sql = "SELECT * FROM product WHERE name LIKE '%$content%' order by id limit $limit offset $offset";
            $query = $this->conn->prepare($sql);
            $query->execute();
            $result =  $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        //lấy số lượng sản phẩm tìm kiếm (dùng để phân trang)
        function GetNumberProductSearch($content){
            $sql = "SELECT * FROM product WHERE name LIKE '%$content%'";
            $query = $this->conn->prepare($sql);
            $query->execute();
            $result =  $query->rowCount();
            return $result;
        }
    }
?>