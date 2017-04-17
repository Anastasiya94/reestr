<?php

    error_reporting(E_ALL & ~E_NOTICE);
    $_db_host = "localhost";
    $_db_user = "id1367301_root";
    $_db_pass = "12345";
    $_db_name = "id1367301_employees";
    $_mysqli =mysqli_connect($_db_host, $_db_user, $_db_pass,$_db_name );
    mysqli_query($_mysqli,"SET NAMES 'utf8'");
    mysqli_query($_mysqli,"SET CHARACTER SET 'utf8'");
    mysqli_query($_mysqli,"SET SESSION collation_connection = 'utf8_general_ci'");

    if (mysqli_connect_errno())
    {
            printf("Ошибка соединения: %s\n", mysqli_connect_error());
            exit();
    }
    if(isset($_GET["act"],$_POST,$_GET["page"]))
    {
         search($_POST,$_GET["page"]);
    }

    if(isset($_GET["del"],$_GET["id"]))
    {
         delete($_GET["id"]);
    }

    if(isset($_GET["add"],$_POST))
    {

         add($_POST,$_FILES);
    }

    if(isset($_GET["edit"],$_POST))
    {
         edit($_POST,$_FILES);
    }


    function search($_array,$_page)
    {
        global $_mysqli;

        $_limit_on_page = 5;
        $_page = $_page?$_page:1;

        $_query="SELECT * FROM employees_list WHERE
                (last_name LIKE '%".htmlspecialchars( strip_tags(trim($_array['search_text'])))."%'
                OR first_name LIKE '%".htmlspecialchars( strip_tags(trim($_array['search_text'])))."%'
                OR middle_name LIKE '%".htmlspecialchars( strip_tags(trim($_array['search_text'])))."%')";

        if (isset($_array['male']) && !isset($_array['female']))
        {
            $_query = $_query . " AND sex = 1 ";
        }
        if (!isset($_array['male']) && isset($_array['female']))
        {
            $_query = $_query . " AND sex = 2 ";
        }
        if ($_array['age_first'] != null)
        {
            $_query = $_query . " AND age >= ". htmlspecialchars( strip_tags(trim($_array['age_first'])));
        }
        if ($_array['age_last'] != null)
        {
            $_query = $_query . " AND age <= ".htmlspecialchars( strip_tags(trim($_array['age_last'])));
        }

        $_all_count=mysqli_num_rows(mysqli_query($_mysqli,$_query));
        $_next_status = ($_all_count-($_limit_on_page*($_page)))>0?1:0;
        $_previous_status = ($_limit_on_page-($_limit_on_page*$_page))<0?1:0;

        $_query = $_query." LIMIT ".$_limit_on_page." OFFSET ".($_limit_on_page*($_page-1));
        $_res = mysqli_query($_mysqli,$_query);

        if(mysqli_num_rows($_res))
        {
           echo "<table class='table table-bordered table-hover'>
                        <thead>
                        <tr>
                               <th width='38px'>№ id</th>
                               <th width='50px' >Фото</th>
                               <th>ФИО</th>
                               <th width='30px'>Возраст</th>
                               <th width='30px'>Пол</th>
                               <th width='84px'>Действия</th>
                        </tr>
                           </thead>
                           <tbody>";
            while($_row = mysqli_fetch_assoc($_res))
            {
                if($_row["sex"]== 2)
                   $_sex = "<em class='text-danger'>Жен</em>";
                else
                   $_sex = "<em class='text-primary'>Муж</em>";

                echo "<tr>
                          <td class='text-center'>".$_row["id"]."</td>
                          <td><img width='50' onclick ='zoom(this);' style ='cursor: url(./images/zoom.cur),auto;' height ='30px' src='data:image/jpeg;base64,".base64_encode($_row['photo'])."'/></td>
                          <td>".$_row["last_name"]." ".$_row["first_name"]." ".$_row["middle_name"]."</td>
                          <td class='text-center'>".$_row["age"]."</td>
                          <td>".$_sex."</td>
                          <td><button class='btn btn-warning' title='Редактировать' onclick='editForm(".$_row["id"].");'><i class='glyphicon glyphicon-pencil'></i></button> <button class='btn btn-danger' title='Удалить' onclick='remove(".$_row["id"].")'><i class='glyphicon glyphicon-trash'></i></button></td>
                          </tr>";
            }
            echo " </tbody>
                 </table>
                 <div class = 'btn-group btn-group-sm' style='float:right;'>";

          if($_previous_status==1)
          {
               echo "<button type = 'button' class = 'btn btn-default' onclick='search(".($_page-1).");'>".($_page-1)."</button>";
          }
          if( $_previous_status==1|| $_next_status==1)
          {
             echo "<button type = 'button' class = 'btn btn-default active ' onclick='search(".$_page.");'>".$_page."</button>";
          }

          if($_next_status==1)
          {
               echo "<button type = 'button' class = 'btn btn-default' onclick='search(".($_page+1).");'>".($_page+1)."</button>";
          }
          echo"</div>";

        }
    }

    function delete($_id)
    {
        global $_mysqli;
        $_res = mysqli_query($_mysqli,"DELETE FROM employees_list WHERE id=".$_id);
    }

    function add($_array,$_files)
    {
        global $_mysqli;

        if(!empty($_files))
        {
            $image_size=$_files['photo']['size'];

            if(!($image_size>200000)||$image_size!=0)
    	    {
                 if(substr($_files['photo']['type'], 0, 5)=='image')
    	         {
    	             $_image=file_get_contents($_FILES['photo']['tmp_name']);
    		         $_image=mysqli_escape_string($_mysqli,$_image);

    	         }
    	    }

        }

        $_age_date= strtotime($_array["date"]);
        $_age = date('Y') - date('Y', $_age_date);

        $_res = mysqli_query($_mysqli,"INSERT INTO employees_list(last_name,first_name,middle_name,age,sex,photo)
                                                        VALUES('".htmlspecialchars( strip_tags(trim($_array["last_name"])))."',
                                                               '".htmlspecialchars( strip_tags(trim($_array["first_name"])))."',
                                                               '".htmlspecialchars( strip_tags(trim($_array["middle_name"])))."',
                                                               ".$_age.",
                                                                ".$_array["sex"].",
                                                               '".$_image."')");


    }

    function edit($_array,$_files)
    {
        global $_mysqli;
        $_query = "UPDATE employees_list SET last_name ='".htmlspecialchars( strip_tags(trim($_array["last_name"])))."',
                                                                first_name = '".htmlspecialchars( strip_tags(trim($_array["first_name"])))."',
                                                                middle_name = '".htmlspecialchars( strip_tags(trim($_array["middle_name"])))."',
                                                                age = ".$_array["age"].",
                                                                sex = ".$_array["sex"]."";
        if(!empty($_files))
        {
            $image_size=$_files['photo']['size'];

            if(!($image_size>200000)||$image_size!=0)
    	    {
                 if(substr($_files['photo']['type'], 0, 5)=='image')
    	         {
    	             $_image=file_get_contents($_FILES['photo']['tmp_name']);
    		         $_image=mysqli_escape_string($_mysqli,$_image);

                     $_query = $_query.", photo = '".$_image."'";

    	         }
    	    }

        }

       $_query = $_query." WHERE id=".$_array["id"];

        $_res = mysqli_query($_mysqli,$_query);

    }

?>